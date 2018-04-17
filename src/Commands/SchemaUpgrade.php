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
use Seat\Eveapi\Models\Character\AccountBalance;
use Seat\Eveapi\Models\Character\Bookmark;
use Seat\Eveapi\Models\Character\CharacterSheet;
use Seat\Eveapi\Models\Character\CharacterSheetCorporationTitles;
use Seat\Eveapi\Models\Character\CharacterSheetImplants;
use Seat\Eveapi\Models\Character\CharacterSheetJumpClone;
use Seat\Eveapi\Models\Character\CharacterSheetJumpCloneImplants;
use Seat\Eveapi\Models\Character\CharacterSheetSkills;
use Seat\Eveapi\Models\Character\ChatChannel;
use Seat\Eveapi\Models\Character\ChatChannelInfo;
use Seat\Eveapi\Models\Character\ChatChannelMember;
use Seat\Eveapi\Models\Character\ContactList;
use Seat\Eveapi\Models\Character\ContactListCorporate;
use Seat\Eveapi\Models\Character\ContactListLabel;
use Seat\Eveapi\Models\Character\ContactNotifications;
use Seat\Eveapi\Models\Character\Contract;
use Seat\Eveapi\Models\Character\ContractItems;
use Seat\Eveapi\Models\Character\IndustryJob;
use Seat\Eveapi\Models\Character\MailingListInfo;
use Seat\Eveapi\Models\Character\MailMessage;
use Seat\Eveapi\Models\Character\MailMessageBody;
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
use Seat\Eveapi\Models\Eve\CharacterAffiliation;
use Seat\Notifications\Models\Notification;
use Spatie\DbDumper\Databases\MySql;

class SchemaUpgrade extends Command
{

    const CHUNK_SIZE = 100;

    protected $signature = 'seat:schema:upgrade ' .
                           '{--continue : Continue an already started upgrade}' .
                           '{--force : Start an upgrade and reset all flag from upgraded stuff}';

    protected $description = 'Perform an upgrade between the installed SeAT 2.0.0 to a new SeAT 3.0.0';

    /**
     * @var array The remote DB setup
     */
    private $db = [
        'driver'   => 'mysql',
        'host'     => null,
        'port'     => null,
        'username' => null,
        'password' => null,
        'database' => null,
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->promptSetup();

        //
        // proceed to upgrade
        //
        $this->comment('The process will now moving data between current installation to the new one.');
        $this->comment('Once a record is migrated, it will be flag. So, you can run the process more than once.');
        $this->info('Please be patient - the process can take a long time depending on the records amount in your database.');

        //

        $records = AccountBalance::where('upgraded', false)
                                 ->select('characterID', 'balance', 'created_at', 'updated_at')
                                 ->get();
        $this->comment(sprintf('Proceed upgrade from character_account_balances to character_wallet_balances' .
                               '(%d)', $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {
            DB::connection('target')->table('character_wallet_balances')->insert($chunk->toArray());

            DB::table('character_account_balances')
                ->whereIn('id', $chunk->pluck('id'))
                ->update('upgraded', true);
        });

        //

        $records = CharacterAffiliation::where('upgraded', false)->get();
        $this->comment(sprintf('Proceed upgrade from character_affiliations to character_affiliations (%d)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {
            DB::connection('target')->table('character_affiliations')->insert($chunk->toArray());

            DB::table('character_affiliations')
              ->whereIn('characterID', $chunk->pluck('characterID'))
              ->update('upgraded', true);
        });

        //

        $records = Bookmark::where('upgraded', false)->get();
        $this->comment(sprintf('Proceed upgrade from character_bookmarks to character_bookmarks (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {
            DB::connection('target')->table('character_bookmarks')->insert($chunk->toArray());

            DB::table('character_bookmarks')
              ->whereIn('id', $chunk->pluck('id'))
              ->update('upgraded', true);
        });

        //

        $records = CharacterSheetCorporationTitles::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_character_sheet_corporation_titles to character_titles (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_titles')->insert($chunk->toArray());

            DB::table('character_character_sheet_corporation_titles')
                ->whereIn('id', $chunk->pluck('id'))
                ->update('upgraded', true);

        });

        //

        $records = CharacterSheetImplants::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_character_sheet_implants to character_implants (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_implants')->insert($chunk->toArray());

            DB::table('character_character_sheet_implants')
                ->whereIn('id', $chunk->pluck('id'))
                ->update('upgraded', true);

        });

        // TODO : use group_concat function to build a JSON array

        $records = CharacterSheetJumpCloneImplants::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_character_sheet_jump_clone_implants to character_jump_clones (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_jump_clones')->insert($chunk->toArray());

            DB::table('character_character_sheet_jump_clone_implants')
                ->whereIn('id', $chunk->pluck('id'))
                ->update('upgraded', true);

        });

        //

        $records = CharacterSheetJumpClone::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_character_sheet_jump_clones to character_jump_clones (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_jump_clones')->insert($chunk->toArray());

            DB::table('character_character_sheet_jump_clones')
                ->whereIn('jumpCloneID', $chunk->pluck('jumpCloneID'))
                ->update('upgraded', true);

        });

        //

        $records = CharacterSheetSkills::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_character_sheet_skills to character_skills (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_skills')->insert($chunk->toArray());

            DB::table('character_character_sheet_skills')
                ->whereIn('id', $chunk->pluck('id'))
                ->update('upgraded', true);

        });

        //

        $records = CharacterSheet::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_character_sheets to character_infos, character_attributes, character_clones, character_info_skills, character_fatigues (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_infos')->insert($chunk->pluck('characterID', 'name', 'DoB',
                'race', 'bloodLineID', 'ancestryID', 'gender', 'corporationID', 'allianceID', 'factionID', 'created_at',
                'updated_at'));

            DB::connection('target')->table('character_attributes')->insert($chunk->pluck('characterID', 'freeRespecs',
                'lastRespecDate', 'intelligence', 'memory', 'charisma', 'perception', 'willpower', 'created_at',
                'updated_at'));

            // TODO : implement home_location_type to diff station from structure
            DB::connection('target')->table('character_clones')->insert($chunk->pluck('characterID', 'homeStationID',
                'remoteStationDate', 'created_at', 'updated_at'));

            DB::connection('target')->table('character_info_skills')->insert($chunk->pluck('characterID',
                'freeSkillPoints', 'created_at', 'updated_at'));

            DB::connection('target')->table('character_fatigues')->insert($chunk->pluck('characterID', 'cloneJumpDate',
                'jumpFatigue', 'jumpLastUpdate', 'created_at', 'updated_at'));

            DB::table('character_character_sheets')
                ->whereIn('characterID', $chunk->pluck('characterID'))
                ->update('upgraded', true);

        });

        //

        $records = ChatChannelInfo::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_chat_channel_infos to character_chat_channel_infos (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_chat_channel_infos')->insert($chunk->pluck('channelID',
                'ownerID', 'displayName', 'comparisonKey', 'hasPassword', 'motd', 'created_at', 'updated_at'));

            DB::table('character_chat_channel_infos')
                ->whereIn('channelID', $chunk->pluck('channelID'))
                ->update('upgraded', true);

        });

        //

        $records = ChatChannelMember::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_chat_channel_members to character_chat_channel_members (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_chat_channel_members')->insert($chunk->pluck('id', 'channelID',
                'accessorID', 'role', 'untilWhen', 'reason', 'created_at', 'updated_at'));

            DB::table('character_chat_channel_members')
                ->whereIn('id', $chunk->pluck('id'))
                ->update('upgraded', true);

        });

        //

        $records = ChatChannel::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_chat_channels to character_chat_channels (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_chat_channels')->insert($chunk->toArray());

        });

        ChatChannel::where('upgraded', false)->update('upgraded', true);

        //

        $records = ContactListCorporate::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_contact_list_corporates to corporation_contacts (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('corporation_contacts')->insert($chunk->pluck('corporationID',
                'contactID', 'standing', 'contactTypeID', 'labelMask', 'created_at', 'updated_at'));

            DB::table('character_contact_list_corporates')
                ->whereIn('contactID', $chunk->pluck('contactID'))
                ->update('upgraded', true);

        });

        //

        $records = ContactListLabel::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_contact_list_labels to character_contact_labels (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_contact_labels')->insert($chunk->toArray());

            DB::table('character_contact_list_labels')
                ->whereIn('labelID', $chunk->pluck('labelID'))
                ->update('upgraded', true);

        });

        //

        $records = ContactList::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_contact_lists to character_contacts (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_contacts')->insert($chunk->pluck('characterID', 'contactID',
                'standing', 'contactTypeID', 'labelMask', 'inWatchlist', 'created_at', 'updated_at'));

            DB::table('character_contact_lists')
                ->whereIn('contactID', $chunk->pluck('contactID'))
                ->update('upgraded', true);

        });

        //

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

        //

        $records = ContractItems::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_contract_items to contract_items (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('contract_items')->insert($chunk->pluck('contractID', 'recordID', 'typeID',
                'quantity', 'rawQuantity', 'singleton', 'included', 'created_at', 'updated_at'));

        });

        ContractItems::where('upgraded', false)->update('upgraded', true);

        //

        $records = Contract::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_contracts to contract_details (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('contract_details')->insert($chunk->pluck('contractID', 'issuerID',
                'issuerCorpID', 'assigneeID', 'acceptorID', 'startStationID', 'endStationID', 'type', 'status', 'title',
                'forCorp', 'availability', 'dateIssued', 'dateExpired', 'dateAccepted', 'numDays', 'dateCompleted',
                'price', 'reward', 'collateral', 'buyout', 'volume', 'created_at', 'updated_at'));

            DB::connection('target')->table('character_contract')->insert($chunk->pluck('characterID', 'contractID',
                'created_at', 'updated_at'));

        });

        Contract::where('upgraded', false)->update('upgraded', true);

        //

        $records = IndustryJob::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_industry_jobs to character_industry_jobs (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_industry_jobs')->insert($chunk->pluck('characterID', 'jobID',
                'installerID', 'facilityID', 'stationID', 'activityID', 'blueprintID', 'blueprintTypeID',
                'blueprintLocationID', 'outputLocationID', 'runs', 'cost', 'licensedRuns', 'probability',
                'productTypeID', 'status', 'timeInSeconds', 'startDate', 'endDate', 'pauseDate', 'completedDate',
                'completedCharacterID', 'successfulRuns', 'created_at', 'updated_at'));

            DB::table('character_industry_jobs')->whereIn('jobID', $chunk->pluck('jobID'))->update('upgraded', true);

        });

        //

        $records = MailMessageBody::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_mail_message_bodies to mail_bodies (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('mail_bodies')->insert($chunk->pluck('messageID', 'body',
                'created_at', 'updated_at'));

            DB::table('character_mail_message_bodies')
                ->whereIn('messageID', $chunk->pluck('messageID'))
                ->update('upgraded', true);

        });

        //

        $records = MailMessage::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_mail_messages to mail_headers (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('mail_headers')->insert($chunk->pluck('characterID', 'messageID',
                'senderID', 'sentDate', 'title', 'created_at', 'updated_at'));

            $alliances = collect();

            $corporations = collect();

            $characters = collect();

            $lists = collect();

            $chunk->each(function($record) use ($alliances, $corporations, $characters, $lists) {

                // todo : use map in order to tranform response and insert into mail_recipients
                // https://gist.github.com/a-tal/5ff5199fdbeb745b77cb633b7f4400bb

                if (!is_null($record->toListID))
                    $lists->push($record);

                if (!is_null($record->toCharacterIDs))
                    $characters->push($record);

                if (!is_null($record->toCorpOrAllianceID)) {
                    if ($record->toCorpOrAllianceID >= 1000000 && $record->toCorpOrAllianceID <= 2000000)
                        $corporations->push($record);
                    if ($record->toCorpOrAllianceID >= 98000000 && $record->toCorpOrAllianceID < 99000000)
                        $corporations->push($record);
                    if ($record->toCorpOrAllianceID >= 99000000 && $record->toCorpOrAllianceID < 100000000)
                        $alliances->push($record);
                    if ($record->toCorpOrAllianceID >= 100000000 && $record->toCorpOrAllianceID < 2100000000);
                    // random type :(
                }

            });

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

        $records = Notification::where('upgraded', false)->get();

        $this->comment(sprintf('Proceed upgrade from character_notifications to character_notifications, character_notification_texts (%s)',
            $records->count()));

        $records->chunk(self::CHUNK_SIZE)->each(function($chunk) {

            DB::connection('target')->table('character_notifications')->insert($chunk->pluck('characterID',
                'notificationID', 'typeID', 'senderID', 'sentDate', 'read', 'created_at', 'updated_at'));

            // todo : create a join between character_notifications and character_notification_texts
            // todo : use mapping

            DB::table('character_notifications')
                ->whereIn('id', $chunk->pluck('id'))
                ->update('upgraded', true);

            DB::table('character_notification_texts')
                ->whereIn('notificationID', $chunk->pluck('notificationID'))
                ->update('upgraded', true);

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

        PlanetaryLink::where('upgraded', false)->update('upgraded', true);

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

        SkillQueue::where('upgraded', false)->update('upgraded', true);

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

        UpcomingCalendarEvent::where('upgraded', false)->update('upgraded', true);

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
            '(ie: example.com or 192.168.3.3) ?');
        $this->db['port']     = $this->ask('What is the database port where SeAT 3.0.0 has been installed ' .
            '(default is 3306)', 3306);
        $this->db['username'] = $this->ask('What is the database username which have to be use for the migration ' .
            '(the user must be usable from the current server ? (default is seat)', 'seat');
        $this->db['password'] = $this->ask('What is the password attached to the provided username ?' .
            '(warning - it will be showed in the prompt');
        $this->db['database'] = $this->ask('What is the name of the database where SeAT 3.0.0 has been installed ? ' .
            '(default is seat)', 'seat');

        // check connection status
        Config::set('database.connections.target', $this->db);
        DB::purge('target');
        DB::reconnect('target');

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
