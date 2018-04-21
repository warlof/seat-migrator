<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 16/04/2018
 * Time: 17:13
 */

namespace Seat\Upgrader\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\CharacterSheetJumpCloneImplants;
use Seat\Eveapi\Models\Character\MarketOrder;
use Seat\Eveapi\Models\Character\UpcomingCalendarEvent;
// Upgrader
use Seat\Upgrader\Models\CharacterAccountBalance;
use Seat\Upgrader\Models\CharacterAffiliation;
use Seat\Upgrader\Models\CharacterBookmark;
use Seat\Upgrader\Models\CharacterContactList;
use Seat\Upgrader\Models\CharacterContactListLabel;
use Seat\Upgrader\Models\CharacterContract;
use Seat\Upgrader\Models\CharacterContractItem;
use Seat\Upgrader\Models\CharacterImplant;
use Seat\Upgrader\Models\CharacterIndustryJob;
use Seat\Upgrader\Models\CharacterJumpClone;
use Seat\Upgrader\Models\CharacterNotification;
use Seat\Upgrader\Models\CharacterNotificationText;
use Seat\Upgrader\Models\CharacterPlanet;
use Seat\Upgrader\Models\CharacterPlanetLink;
use Seat\Upgrader\Models\CharacterPlanetPin;
use Seat\Upgrader\Models\CharacterPlanetRoute;
use Seat\Upgrader\Models\CharacterResearch;
use Seat\Upgrader\Models\CharacterSheet;
use Seat\Upgrader\Models\CharacterSkill;
use Seat\Upgrader\Models\CharacterSkillQueue;
use Seat\Upgrader\Models\CharacterStanding;
use Seat\Upgrader\Models\CharacterTitle;
use Seat\Upgrader\Models\CharacterWalletJournal;
use Seat\Upgrader\Models\CharacterWalletTransaction;
use Seat\Upgrader\Models\ChatChannelInfo;
use Seat\Upgrader\Models\ContactListCorporate;
use Seat\Upgrader\Models\MailBody;
use Seat\Upgrader\Models\MailHeader;
use Seat\Upgrader\Models\MailingList;
use Spatie\DbDumper\Databases\MySql;

class SchemaUpgrade extends Command
{

    const CHUNK_SIZE = 100;

    const TARGETED_BASE = 'target';

    protected $signature = 'seat:schema:upgrade ' .
                           '{--force : Start an upgrade and reset all flag from upgraded stuff}';

    protected $description = 'Perform an upgrade between the installed SeAT 2.0.0 to a new SeAT 3.0.0';

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
        //$this->backups();

        //
        // proceed to upgrade
        //
        $this->comment('The process will now moving data between current installation to the new one.');
        $this->comment('Once a record is migrated, it will be flag. So, you can run the process more than once.');
        $this->info('Please be patient - the process can take a long time depending on the records amount in your database.');

        //

        $count = CharacterAccountBalance::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_account_balances to character_wallet_balances (%d)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterAccountBalance::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterAffiliation::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_affiliations to character_affiliations (%d)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterAffiliation::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterBookmark::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_bookmarks to character_bookmarks (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterBookmark::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterTitle::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_character_sheet_corporation_titles to character_titles (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterTitle::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterImplant::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_character_sheet_implants to character_implants (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterImplant::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterJumpClone::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_character_sheet_jump_clones to character_jump_clones (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterJumpClone::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        $records = CharacterSheetJumpCloneImplants::select(
                    DB::raw('characterID as character_id'),
                    DB::raw('jumpCloneID as jump_clone_id'),
                    DB::raw("CONCAT('[', GROUP_CONCAT(typeID), ']') as implants"))
                ->where('upgraded', false)
                ->groupBy('characterID', 'jumpCloneID')
                ->get();

        $this->comment(sprintf('Proceed upgrade from character_character_sheet_jump_clone_implants to character_jump_clones (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            $chunk->each(function($record){

                DB::connection(self::TARGETED_BASE)
                    ->insert("UPDATE character_jump_clones SET implants = ? WHERE character_id = ? AND jump_clone_id = ?", [
                        $record->implants,
                        $record->character_id,
                        $record->jump_clone_id,
                    ]);

            });
        });

        CharacterSheetJumpCloneImplants::where('upgraded', false)->update(['upgraded' => true]);

        //

        $count = CharacterSkill::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_character_sheet_skills to character_skills (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterSkill::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterSheet::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_character_sheets to character_infos, character_attributes, character_clones, character_info_skills, character_fatigues (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterSheet::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = ChatChannelInfo::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_chat_channel_infos to character_chat_channel_infos (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = ChatChannelInfo::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        // TODO
/*
        $records = CharacterChatChannel::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_chat_channel_members to character_chat_channel_members (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_chat_channel_members')->insert($chunk->only('id', 'channel_id',
                'accessorID', 'role', 'untilWhen', 'reason', 'created_at', 'updated_at')->all());

            DB::table('character_chat_channel_members')
                ->whereIn('id', $chunk->only('id')->all())
                ->update(['upgraded' => true]);

        });

        //

        $records = ChatChannel::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_chat_channels to character_chat_channels (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_chat_channels')->insert($chunk->toArray());

        });

        ChatChannel::where('upgraded', false)->update(['upgraded' => true]);

        //
*/
/*
        $records = ContactListCorporate::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_contact_list_corporates to corporation_contacts (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(SELF::TARGETED_BASE);

        });
*/
        $count = ContactListCorporate::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_contact_list_corporates to corporation_contacts (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = ContactListCorporate::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterContactListLabel::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_contact_list_labels to character_contact_labels (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterContactListLabel::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterContactList::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_contact_lists to character_contacts (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterContactList::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        // TODO
/*
        $records = ContactNotifications::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_contact_notifications to character_notifications (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            // TODO : implement sender_type and type fields

            DB::connection('target')->table('character_notifications')->insert($chunk->pluck('characterID',
                'notificationID', 'senderID', 'sentDate', 'messageData', 'created_at', 'updated_at'));

            DB::table('character_notifications')
                ->whereIn('notificationID', $chunk->pluck('notificationID'))
                ->update('upgraded', true);

        });
*/
        //

        $count = CharacterContract::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_contracts to contract_details (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterContract::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterContractItem::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_contract_items to contract_items (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterContractItem::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterIndustryJob::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_industry_jobs to character_industry_jobs (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterIndustryJob::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = MailHeader::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_mail_messages to mail_headers, mail_recipients (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = MailHeader::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = MailBody::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_mail_message_bodies to mail_bodies (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = MailBody::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = MailingList::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_mailing_lists, character_mailing_list_infos to mail_mailing_lists (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = MailingList::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = MarketOrder::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_market_orders to character_orders (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_orders')->insert($chunk->pluck('orderID', 'charID', 'stationID',
                'volEntered', 'volRemaining', 'minVolume', 'orderState', 'typeID', 'range', 'accountKey', 'duration',
                'escrow', 'price', 'bid', 'issued', 'created_at', 'updated_at'));

            DB::table('character_market_orders')->whereIn('id', $chunk->pluck('id'))->update('upgraded', true);

        });

        $bar->finish();
        $this->line('');

        //

        $count = CharacterNotification::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_notifications to character_notifications (%s)',
            $records->count()));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterNotification::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        $count = CharacterNotificationText::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_notification_texts to character_notifications (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterNotificationText::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterPlanet::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_planetary_colonies to character_planets (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterPlanet::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterPlanetLink::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_planetary_links to character_planet_links (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterPlanetLink::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterPlanetPin::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_planetary_pins to character_planet_pins (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterPlanetPin::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterPlanetRoute::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_planetary_routes to character_planet_routes, character_planet_route_waypoints (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterPlanetRoute::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterResearch::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_researches to character_agent_researches (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterResearch::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterSkillQueue::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_skill_queues to character_skill_queues (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterSkillQueue::where('upgraded', false)->take(self::TARGETED_BASE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        //

        $count = CharacterStanding::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_standings to character_standings (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterStanding::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        // TODO
/*
        $records = UpcomingCalendarEvent::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_upcoming_calendar_events to character_calendar_events (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_calendar_events')->insert($chunk->pluck('characterID', 'eventID',
                'eventDate', 'eventTitle', 'importance', 'response', 'created_at', 'updated_at'));

            DB::connection('target')->table('character_calendar_event_details')->insert($chunk->pluck('eventID',
                'ownerID', 'ownerName', 'duration', 'eventText', 'ownerTypeID', 'created_at', 'updated_at'));

        });

        UpcomingCalendarEvent::where('upgraded', false)->update(['upgraded' => true]);
*/
        //

        // TODO : fix type conversion on date field
        /*
        $count = CharacterWalletJournal::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_wallet_journals to character_wallet_journals (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterWalletJournal::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');
*/
        //

        // TODO : fix type conversion on date field
        $count = CharacterWalletTransaction::where('upgraded', false)->count();
        $this->comment(sprintf('Proceed upgrade from character_wallet_transactions to character_wallet_transactions (%s)',
            $count));
        $bar = $this->output->createProgressBar($count);

        while (($records = CharacterWalletTransaction::where('upgraded', false)->take(self::CHUNK_SIZE)->get())->count() > 0) {
            $records->upgrade(self::TARGETED_BASE);
            $bar->advance($records->count());
        }

        $bar->finish();
        $this->line('');

        // ** CORPORATION START HERE ** //

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
        $this->db['password'] = $this->output->askHidden('What is the password attached to the provided username ?');
        $this->db['database'] = $this->ask('What is the name of the database where SeAT 3.0.0 has been installed ? ' .
            '(default is seat)', 'seat-dev');

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
