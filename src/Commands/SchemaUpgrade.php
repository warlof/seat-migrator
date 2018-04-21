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
use Seat\Eveapi\Models\Character\MailingListInfo;
use Seat\Eveapi\Models\Character\MailMessage;
use Seat\Eveapi\Models\Character\MarketOrder;
use Seat\Eveapi\Models\Character\PlanetaryColony;
use Seat\Eveapi\Models\Character\PlanetaryLink;
use Seat\Eveapi\Models\Character\PlanetaryPin;
use Seat\Eveapi\Models\Character\PlanetaryRoute;
use Seat\Eveapi\Models\Character\Research;
use Seat\Eveapi\Models\Character\SkillQueue;
use Seat\Eveapi\Models\Character\Standing;
use Seat\Eveapi\Models\Character\UpcomingCalendarEvent;
use Seat\Eveapi\Models\Character\WalletJournal;
use Seat\Eveapi\Models\Character\WalletTransaction;
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
use Seat\Upgrader\Models\CharacterSheet;
use Seat\Upgrader\Models\CharacterSkill;
use Seat\Upgrader\Models\CharacterTitle;
use Seat\Upgrader\Models\ChatChannelInfo;
use Seat\Upgrader\Models\ContactListCorporate;
use Seat\Upgrader\Models\MailBody;
use Seat\Upgrader\Models\MailHeader;
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

        $records = CharacterAccountBalance::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_account_balances to character_wallet_balances' .
                               '(%d)', $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

        //

        $records = CharacterAffiliation::where('upgraded', false)->get();
        $this->comment(sprintf('Proceed upgrade from character_affiliations to character_affiliations (%d)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

        //

        $records = CharacterBookmark::where('upgraded', false)->get();
        $this->comment(sprintf('Proceed upgrade from character_bookmarks to character_bookmarks (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

        //

        $records = CharacterTitle::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_character_sheet_corporation_titles to character_titles (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

        //

        $records = CharacterImplant::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_character_sheet_implants to character_implants (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

        //

        $records = CharacterJumpClone::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_character_sheet_jump_clones to character_jump_clones (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

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

        $records = CharacterSkill::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_character_sheet_skills to character_skills (%s)',
            $records->count()));

        $records->each(function ($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

        //

        $records = CharacterSheet::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_character_sheets to character_infos, character_attributes, character_clones, character_info_skills, character_fatigues (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

        //

        $records = ChatChannelInfo::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_chat_channel_infos to character_chat_channel_infos (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

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
        $records = ContactListCorporate::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_contact_list_corporates to corporation_contacts (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(SELF::TARGETED_BASE);

        });

        //

        $records = CharacterContactListLabel::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_contact_list_labels to character_contact_labels (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

        //

        $records = CharacterContactList::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_contact_lists to character_contacts (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

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

        $records = CharacterContract::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_contracts to contract_details (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

        //

        $records = CharacterContractItem::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_contract_items to contract_items (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

        //

        $records = CharacterIndustryJob::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_industry_jobs to character_industry_jobs (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

        //

        $records = MailHeader::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_mail_messages to mail_headers, mail_recipients (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

        //

        $records = MailBody::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_mail_message_bodies to mail_bodies (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

        //

        $records = MailingListInfo::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_mailing_list_infos to mail_mailing_lists (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('mail_mailing_lists')->insert($chunk->toArray());

            DB::table('character_mailing_list_infos')
                ->whereIn('listID', $chunk->pluck('listID'))
                ->update('upgraded', true);

        });

        //

        $records = MarketOrder::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_market_orders to character_orders (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_orders')->insert($chunk->pluck('orderID', 'charID', 'stationID',
                'volEntered', 'volRemaining', 'minVolume', 'orderState', 'typeID', 'range', 'accountKey', 'duration',
                'escrow', 'price', 'bid', 'issued', 'created_at', 'updated_at'));

            DB::table('character_market_orders')->whereIn('id', $chunk->pluck('id'))->update('upgraded', true);

        });

        //

        $records = CharacterNotification::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_notifications to character_notifications (%s)',
            $records->count()));

        $records->each(function($record) {

            $record->upgrade(self::TARGETED_BASE);

        });

        $records = CharacterNotificationText::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_notification_texts to character_notifications (%s)',
            $records->count()));

        $records->each(function($record){

            $record->upgrade(self::TARGETED_BASE);

        });

        //

        $records = PlanetaryColony::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_planetary_colonies to character_planets (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_planets')->insert($chunk->pluck('solarSystemID', 'planetID',
                'planetTypeID', 'ownerID', 'upgradeLevel', 'numberOfPins', 'created_at', 'updated_at'));

            DB::table('character_planetary_colonies')->whereIn('id', $chunk->pluck('id'))->update('upgraded', true);

        });

        //

        $records = PlanetaryLink::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_planetary_links to character_planet_links (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_planet_links')->insert($chunk->toArray());

        });

        PlanetaryLink::where('upgraded', false)->update(['upgraded' => true]);

        //

        $records = PlanetaryPin::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_planetary_pins to character_planet_pins (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_planet_pins')->insert($chunk->pluck('pinID', 'ownerID',
                'planetID', 'typeID', 'schematicID', 'lastLaunchTime', 'installTime', 'expiryTime', 'longitude',
                'latitude', 'created_at', 'updated_at'));

            DB::table('character_planetary_pins')->whereIn('id', $chunk->pluck('id'))->update('upgraded', true);

        });

        //

        $records = PlanetaryRoute::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_planetary_routes to character_planet_routes (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_planet_routes')->insert($chunk->pluck('routeID', 'ownerID',
                'planetID', 'sourcePinID', 'destinationPinID', 'contentTypeID', 'quantity', 'created_at', 'updated_at'));

            DB::connection('target')->table('character_planet_route_waypoints')->insert($chunk->pluck('routeID', 'ownerID', 'planetID', 'waypoint1', 'created_at', 'updated_at'));
            DB::connection('target')->table('character_planet_route_waypoints')->insert($chunk->pluck('routeID', 'ownerID', 'planetID', 'waypoint2', 'created_at', 'updated_at'));
            DB::connection('target')->table('character_planet_route_waypoints')->insert($chunk->pluck('routeID', 'ownerID', 'planetID', 'waypoint3', 'created_at', 'updated_at'));
            DB::connection('target')->table('character_planet_route_waypoints')->insert($chunk->pluck('routeID', 'ownerID', 'planetID', 'waypoint4', 'created_at', 'updated_at'));
            DB::connection('target')->table('character_planet_route_waypoints')->insert($chunk->pluck('routeID', 'ownerID', 'planetID', 'waypoint5', 'created_at', 'updated_at'));

            DB::table('character_planetary_routes')->whereIn('id', $chunk->pluck('id'))->update('upgraded', true);

        });

        //

        $records = Research::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_researches (%s)', $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_agent_researches')->insert($chunk->pluck('characterID',
                'agentID', 'skillTypeID', 'researchStartDate', 'pointsPerDay', 'remainderPoints', 'created_at',
                'updated_at'));

            DB::table('character_researches')->whereIn('id', $chunk->pluck('id'))->update('upgraded', true);

        });

        //

        $records = SkillQueue::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_skill_queues to character_skill_queues (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_skill_queues')->insert($chunk);

        });

        SkillQueue::where('upgraded', false)->update(['upgraded' => true]);

        //

        $records = Standing::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_standings to character_standings (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_standings')->insert($chunk->pluck('characterID', 'type',
                'fromID', 'standing', 'created_at', 'updated_at'));

            DB::table('character_standings')->whereIn('id', $chunk->pluck('id'))->update('upgraded', true);

        });

        //

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

        //

        $records = WalletJournal::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_wallet_journals to character_wallet_journals (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_wallet_journals')->insert($chunk->pluck('characterID', 'refID',
                'date', 'refTypeID', 'ownerID1', 'ownerID2', 'amount', 'balance', 'reason', 'taxReceiverID',
                'taxAmount', 'owner1TypeID', 'owner2TypeID', 'created_at', 'updated_at'));

            DB::table('character_wallet_journals')->whereIn('hash', $chunk->pluck('hash'))->update('upgraded', true);

        });

        //

        $records = WalletTransaction::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_wallet_transactions to character_wallet_transactions (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_wallet_transactions')->insert($chunk->pluck('characterID',
                'transactionID', 'transactionDateTime', 'quantity', 'typeID', 'price', 'clientID', 'stationID',
                'journalTransactionID', 'created_at', 'updated_at'));

            DB::table('character_wallet_transactions')->whereIn('hash', $chunk->pluck('hash'))->update('upgraded', true);

        });

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
        $this->db['password'] = $this->ask('What is the password attached to the provided username ?' .
            '(warning - it will be showed in the prompt', 'seatseat');
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