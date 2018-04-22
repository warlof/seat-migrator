<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 11:48
 */

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
use Seat\Upgrader\Models\CharacterOrder;
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

return [
    CharacterAccountBalance::class    => 'Proceed upgrade from character_account_balances to character_wallet_balances (%d)',
    CharacterAffiliation::class       => 'Proceed upgrade from character_affiliations to character_affiliations (%d)',
    CharacterBookmark::class          => 'Proceed upgrade from character_bookmarks to character_bookmarks (%s)',
    CharacterTitle::class             => 'Proceed upgrade from character_character_sheet_corporation_titles to character_titles (%s)',
    CharacterImplant::class           => 'Proceed upgrade from character_character_sheet_implants to character_implants (%s)',
    CharacterJumpClone::class         => 'Proceed upgrade from character_character_sheet_jump_clones to character_jump_clones (%s)',
    CharacterSkill::class             => 'Proceed upgrade from character_character_sheet_skills to character_skills (%s)',
    CharacterSheet::class             => 'Proceed upgrade from character_character_sheets to character_infos, character_attributes, character_clones, character_info_skills, character_fatigues (%s)',
    ChatChannelInfo::class            => 'Proceed upgrade from character_chat_channel_infos to character_chat_channel_infos (%s)',
    ContactListCorporate::class       => 'Proceed upgrade from character_contact_list_corporates to corporation_contacts (%s)',
    CharacterContactListLabel::class  => 'Proceed upgrade from character_contact_list_labels to character_contact_labels (%s)',
    CharacterContactList::class       => 'Proceed upgrade from character_contact_lists to character_contacts (%s)',
    CharacterContract::class          => 'Proceed upgrade from character_contracts to contract_details (%s)',
    CharacterContractItem::class      => 'Proceed upgrade from character_contract_items to contract_items (%s)',
    CharacterIndustryJob::class       => 'Proceed upgrade from character_industry_jobs to character_industry_jobs (%s)',
    MailHeader::class                 => 'Proceed upgrade from character_mail_messages to mail_headers, mail_recipients (%s)',
    MailBody::class                   => 'Proceed upgrade from character_mail_message_bodies to mail_bodies (%s)',
    MailingList::class                => 'Proceed upgrade from character_mailing_lists, character_mailing_list_infos to mail_mailing_lists (%s)',
    CharacterOrder::class             => 'Proceed upgrade from character_market_orders to character_orders (%s)',
    CharacterNotification::class      => 'Proceed upgrade from character_notifications to character_notifications (%s)',
    CharacterNotificationText::class  => 'Proceed upgrade from character_notification_texts to character_notifications (%s)',
    CharacterPlanet::class            => 'Proceed upgrade from character_planetary_colonies to character_planets (%s)',
    CharacterPlanetLink::class        => 'Proceed upgrade from character_planetary_links to character_planet_links (%s)',
    CharacterPlanetPin::class         => 'Proceed upgrade from character_planetary_pins to character_planet_pins (%s)',
    CharacterPlanetRoute::class       => 'Proceed upgrade from character_planetary_routes to character_planet_routes, character_planet_route_waypoints (%s)',
    CharacterResearch::class          => 'Proceed upgrade from character_researches to character_agent_researches (%s)',
    CharacterSkillQueue::class        => 'Proceed upgrade from character_skill_queues to character_skill_queues (%s)',
    CharacterStanding::class          => 'Proceed upgrade from character_standings to character_standings (%s)',
    CharacterWalletJournal::class     => 'Proceed upgrade from character_wallet_journals to character_wallet_journals (%s)',
    CharacterWalletTransaction::class => 'Proceed upgrade from character_wallet_transactions to character_wallet_transactions (%s)',
];
