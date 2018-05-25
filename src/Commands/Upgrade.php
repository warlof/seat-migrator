<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 16/04/2018
 * Time: 17:13
 */

namespace Warlof\Seat\Migrator\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\CharacterSheetJumpCloneImplants;
use Seat\Eveapi\Models\Character\MailMessage;
use Spatie\DbDumper\Databases\MySql;

class Upgrade extends Command
{

    const TARGETED_BASE = 'target';

    protected $signature = 'seat:migrator:upgrade {--force}';

    protected $description = 'Perform an upgrade between the installed SeAT 2.0.0 to a new SeAT 3.0.0';

    private $chunk_size = 100;

    /**
     * @var array The remote DB setup
     */
    private $db = [
        'driver'    => 'mysql',
        'collation' => 'latin1_swedish_ci',
        'host'      => null,
        'port'      => null,
        'username'  => null,
        'password'  => null,
        'database'  => null,
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->promptSetup();
        $this->backups();

        //
        // proceed to upgrade
        //
        $this->comment('The process will now moving data between current installation to the new one.');
        $this->comment('Once a record is migrated, it will be flag. So, you can run the process more than once.');
        $this->info('Please be patient - the process can take a long time depending on the records amount in your database.');

        //
        $classes = include __DIR__ . '/../Config/upgrades.php';

        if ($this->option('force')) {
            $this->comment('Reset flag has been detected - resetting all upgrade flag from data source');

            foreach ($classes as $class)
                $class::where('upgraded', true)->update(['upgraded' => false]);
        }

        foreach ($classes as $class) {
            $model = new $class;
            $targeted_tables = implode(', ', array_keys($model->getUpgradeMapping()));

            $count = $class::where('upgraded', false)->count();
            $this->comment(sprintf('Proceed upgrade from %s to %s (%d)', $model->getTable(),
                $targeted_tables, $count));
            $bar = $this->output->createProgressBar($count);

            while (($records = $class::where('upgraded', false)->take($this->chunk_size)->get())->count() > 0) {
                $records->upgrade(self::TARGETED_BASE);
                $bar->advance($records->count());
            }

            $bar->finish();
            $this->line('');
        }

        //

        $records = CharacterSheetJumpCloneImplants::select(
                    DB::raw('characterID as character_id'),
                    DB::raw('jumpCloneID as jump_clone_id'),
                    DB::raw("CONCAT('[', GROUP_CONCAT(typeID), ']') as implants"))
                ->groupBy('characterID', 'jumpCloneID')
                ->get();

        $this->comment(sprintf('Proceed upgrade from character_character_sheet_jump_clone_implants to character_jump_clones (%s)',
            $records->count()));
        $bar = $this->output->createProgressBar($records->count());

        $records->chunk($this->chunk_size)->each(function($chunk) use ($bar) {

            $chunk->each(function($record) use ($bar) {

                DB::connection(self::TARGETED_BASE)
                    ->insert("UPDATE character_jump_clones SET implants = ? WHERE character_id = ? AND jump_clone_id = ?", [
                        $record->implants,
                        $record->character_id,
                        $record->jump_clone_id,
                    ]);

                $bar->advance(1);

            });
        });

        CharacterSheetJumpCloneImplants::where('upgraded', false)->update(['upgraded' => true]);
        $bar->finish();
        $this->line('');

        // Mail recipients
        $allianceIDs = include __DIR__ . '/../Config/alliances.php';

        $sql = "INSERT IGNORE INTO mail_recipients (mail_id, recipient_id, recipient_type, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?)";

        $mails = MailMessage::select('messageID', 'toCorpOrAllianceID', 'toCharacterIDs', 'toListID', 'created_at', 'updated_at')
            ->distinct();

        $this->comment(sprintf('Proceed upgrade from character_mail_messages to mail_recipients (%s)',
            $records->count()));
        $bar = $this->output->createProgressBar($mails->count());

        $mails->each(function($record) use ($allianceIDs, $bar, $sql) {
            if (! is_null($record->toCorpOrAllianceID) && $record->toCorpOrAllianceID > 0) {
                $type = 'corporation';

                if ($record->toCorpOrAllianceID >= 99000000 && $record->toCorpOrAllianceID <= 100000000)
                    $type = 'alliance';

                if (in_array($record->toCorpOrAllianceID, $allianceIDs))
                    $type = 'alliance';

                DB::connection(self::TARGETED_BASE)
                    ->insert($sql, [
                        $record->messageID,
                        $record->toCorpOrAllianceID,
                        $type,
                        $record->created_at,
                        $record->updated_at,
                    ]);
            }

            if (! is_null($record->toListID) && $record->toListID > 0)
                DB::connection(self::TARGETED_BASE)
                    ->insert($sql, [
                        $record->messageID,
                        $record->toListID,
                        'mailing_list',
                        $record->created_at,
                        $record->updated_at,
                    ]);

            if (! is_null($record->toCharacterIDs) && ! empty($record->toCharacterIDs)) {
                $characters = explode(',', $record->toCharacterIDs);

                foreach ($characters as $character_id)
                    DB::connection(self::TARGETED_BASE)
                        ->insert($sql, [
                            $record->messageID,
                            $character_id,
                            'character',
                            $record->created_at,
                            $record->updated_at,
                        ]);

            }

            $bar->advance();
        });

        $bar->finish();
        $this->line('');

        // pruning deprecated mercenary division
        DB::connection(self::TARGETED_BASE)->delete('DELETE FROM corporation_divisions WHERE division = ?', [9001]);

        $this->info('The process has successfully ended. Your new SeAT 3.0.0 is now ready for update and usage.');

    }

    private function promptSetup()
    {
        $this->warn('This command will perform a data migration between existing SeAT 2.0.0 version to a new ' .
            'installed SeAT 3.0.0');
        $this->warn('Be sure to backup database before continue - it could be useful in case of crash.');

        $disclaimer = $this->confirm('Are you ready ?', false);

        if (! $disclaimer) {
            $this->comment('Process has been aborted by user input');
            return;
        }

        $this->comment('We will now ask you a few questions in order to be ready to handle the database migration');
        $this->comment('Ensure the database on which SeAT 3.0.0 is installed is up and reachable by the current server');
        $this->comment('');

        $this->db['host']     = $this->ask('What is the IP address or hostname from SeAT 3.0.0 database ' .
            '(ie: example.com or 192.168.3.3) ?', 'seat-mariadb');
        $this->db['port']     = $this->ask('What is the database port where SeAT 3.0.0 has been installed ' .
            '(default is 3306)', 3306);
        $this->db['username'] = $this->ask('What is the database username which have to be use for the migration ' .
            '(the user must be usable from the current server ? (default is seat)', 'seat');
        $this->db['password'] = $this->secret('What is the password attached to the provided username ?');
        $this->db['database'] = $this->ask('What is the name of the database where SeAT 3.0.0 has been installed ? ' .
            '(default is seat)', 'seat-dev');

        $this->chunk_size     = $this->ask('This package will use chunk in order to avoid to overload your system. '.
            'You can keep the default value as it or specifying a custom one.', $this->chunk_size);

        // check connection status
        Config::set('database.connections.target', $this->db);
        DB::purge('target');
        DB::reconnect('target');
    }

    private function backups()
    {
        // ensure backup folder exists
        if (! is_dir(storage_path('backup')))
            mkdir(storage_path('backup', 644, false));

        // never trust user action
        // backup the database to which we're coupled
        $backup_path = storage_path(sprintf('backup/seat200_%s.sql', carbon()->format('Ymd_His')));

        $this->comment('Generating backup for SeAT 2.0.0 (current installation)');

        MySql::create()
            ->setDbName(env('DB_DATABASE'))
            ->setUserName(env('DB_USERNAME'))
            ->setPassword(env('DB_PASSWORD'))
            ->setHost(env('DB_HOST'))
            ->setPort(env('DB_PORT'))
            ->dumpToFile($backup_path);

        $this->info(sprintf('Backup for SeAT 2.0.0 has been successfully generated. (%s)', $backup_path));

        // backup the target database
        $backup_path = storage_path(sprintf('backup/seat300_%s.sql', carbon()->format('Ymd_His')));

        $this->comment('Generating backup for SeAT 3.0.0 (new installation)');

        MySql::create()
            ->setDbName($this->db['database'])
            ->setUserName($this->db['username'])
            ->setPassword($this->db['password'])
            ->setHost($this->db['host'])
            ->setPort($this->db['port'])
            ->dumpToFile($backup_path);

        $this->info(sprintf('Backup for SeAT 3.0.0 has been successfully generated. (%s)', $backup_path));
    }

}
