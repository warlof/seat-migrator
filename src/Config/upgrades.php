<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 11:48
 */

return [
    \Seat\Upgrader\Models\Killmail::class                     => 'Proceed upgrade from kill_mail_details to killmail_details, killmail_victims, character_killmails (%s)',
    \Seat\Upgrader\Models\KillmailAttacker::class             => 'Proceed upgrade from kill_mail_attackers to killmail_attackers (%s)',
    \Seat\Upgrader\Models\KillmailItem::class                 => 'Proceed upgrade from kill_mail_items to killmail_victim_items (%s)',
    \Seat\Upgrader\Models\CharacterCalendarEventDetail::class => 'Proceed upgrade from character_upcoming_calendar_events to character_calendar_event_details (%s)',
    \Seat\Upgrader\Models\CharacterAccountBalance::class      => 'Proceed upgrade from character_account_balances to character_wallet_balances (%d)',
    \Seat\Upgrader\Models\CharacterAffiliation::class         => 'Proceed upgrade from character_affiliations to character_affiliations (%d)',
    \Seat\Upgrader\Models\CharacterBookmark::class            => 'Proceed upgrade from character_bookmarks to character_bookmarks (%s)',
    \Seat\Upgrader\Models\CharacterTitle::class               => 'Proceed upgrade from character_character_sheet_corporation_titles to character_titles (%s)',
    \Seat\Upgrader\Models\CharacterImplant::class             => 'Proceed upgrade from character_character_sheet_implants to character_implants (%s)',
    \Seat\Upgrader\Models\CharacterJumpClone::class           => 'Proceed upgrade from character_character_sheet_jump_clones to character_jump_clones (%s)',
    \Seat\Upgrader\Models\CharacterSheet::class               => 'Proceed upgrade from character_character_sheets to character_infos, character_attributes, character_clones, character_info_skills, character_fatigues (%s)',
    \Seat\Upgrader\Models\CharacterShip::class                => 'Proceed upgrade from eve_character_infos to charaacter_ships (%s)',
    \Seat\Upgrader\Models\CharacterSkill::class               => 'Proceed upgrade from character_character_sheet_skills to character_skills (%s)',
    \Seat\Upgrader\Models\CharacterCorporationHistory::class  => 'Proceed upgrade from eve_character_info_employment_histories to character_corporation_histories (%s)',
    \Seat\Upgrader\Models\ChatChannelInfo::class              => 'Proceed upgrade from character_chat_channel_infos to character_chat_channel_infos (%s)',
    \Seat\Upgrader\Models\CharacterChatChannel::class         => 'Proceed upgrade from character_chat_channels to character_chat_channels (%s)',
    \Seat\Upgrader\Models\ChatChannelMember::class            => 'Proceed upgrade from character_chat_channel_members to character_chat_channel_member (%s)',
    \Seat\Upgrader\Models\ContactListCorporate::class         => 'Proceed upgrade from character_contact_list_corporates to corporation_contacts (%s)',
    \Seat\Upgrader\Models\CharacterContactListLabel::class    => 'Proceed upgrade from character_contact_list_labels to character_contact_labels (%s)',
    \Seat\Upgrader\Models\CharacterContactList::class         => 'Proceed upgrade from character_contact_lists to character_contacts (%s)',
    \Seat\Upgrader\Models\CharacterContract::class            => 'Proceed upgrade from character_contracts to contract_details, character_contracts (%s)',
    \Seat\Upgrader\Models\CharacterContractItem::class        => 'Proceed upgrade from character_contract_items to contract_items (%s)',
    \Seat\Upgrader\Models\CharacterIndustryJob::class         => 'Proceed upgrade from character_industry_jobs to character_industry_jobs (%s)',
    \Seat\Upgrader\Models\MailHeader::class                   => 'Proceed upgrade from character_mail_messages to mail_headers, mail_recipients (%s)',
    \Seat\Upgrader\Models\MailBody::class                     => 'Proceed upgrade from character_mail_message_bodies to mail_bodies (%s)',
    \Seat\Upgrader\Models\MailingList::class                  => 'Proceed upgrade from character_mailing_lists, character_mailing_list_infos to mail_mailing_lists (%s)',
    \Seat\Upgrader\Models\CharacterOrder::class               => 'Proceed upgrade from character_market_orders to character_orders (%s)',
    \Seat\Upgrader\Models\CharacterNotification::class        => 'Proceed upgrade from character_notifications to character_notifications (%s)',
    \Seat\Upgrader\Models\CharacterNotificationText::class    => 'Proceed upgrade from character_notification_texts to character_notifications (%s)',
    \Seat\Upgrader\Models\CharacterPlanet::class              => 'Proceed upgrade from character_planetary_colonies to character_planets (%s)',
    \Seat\Upgrader\Models\CharacterPlanetLink::class          => 'Proceed upgrade from character_planetary_links to character_planet_links (%s)',
    \Seat\Upgrader\Models\CharacterPlanetPin::class           => 'Proceed upgrade from character_planetary_pins to character_planet_pins (%s)',
    \Seat\Upgrader\Models\CharacterPlanetRoute::class         => 'Proceed upgrade from character_planetary_routes to character_planet_routes, character_planet_route_waypoints (%s)',
    \Seat\Upgrader\Models\CharacterResearch::class            => 'Proceed upgrade from character_researches to character_agent_researches (%s)',
    \Seat\Upgrader\Models\CharacterSkillQueue::class          => 'Proceed upgrade from character_skill_queues to character_skill_queues (%s)',
    \Seat\Upgrader\Models\CharacterStanding::class            => 'Proceed upgrade from character_standings to character_standings (%s)',
    \Seat\Upgrader\Models\CharacterWalletJournal::class       => 'Proceed upgrade from character_wallet_journals to character_wallet_journals (%s)',
    \Seat\Upgrader\Models\CharacterWalletTransaction::class   => 'Proceed upgrade from character_wallet_transactions to character_wallet_transactions (%s)',
    // Corporation
    \Seat\Upgrader\Models\CorporationWalletBalance::class     => 'Proceed upgrade from corporation_account_balances to corporation_wallet_balances (%s)',
    \Seat\Upgrader\Models\CorporationAsset::class             => 'Proceed upgrade from corporation_asset_lists to corporation_assets (%s)',
    \Seat\Upgrader\Models\CorporationBookmark::class          => 'Proceed upgrade from corporation_bookmarks to corporation_bookmarks, corporation_bookmark_folders (%s)',
    \Seat\Upgrader\Models\CorporationContact::class           => 'Proceed upgrade from corporation_contact_lists to corporation_contacts (%s)',
    \Seat\Upgrader\Models\CorporationContract::class          => 'Proceed upgrade from corporation_contracts to contract_details (%s)',
    \Seat\Upgrader\Models\CorporationContractItem::class      => 'Proceed upgrade from corporation_contract_items to contract_items (%s)',
    \Seat\Upgrader\Models\CorporationCustomOffice::class      => 'Proceed upgrade from corporation_customs_offices to corporation_customs_offices (%s)',
    \Seat\Upgrader\Models\CorporationIndustryJob::class       => 'Proceed upgrade from corporation_industry_jobs to corporation_industry_jobs (%s)',
    \Seat\Upgrader\Models\CorporationOrder::class             => 'Proceed upgrade from corporation_market_orders to corporation_orders (%s)',
    \Seat\Upgrader\Models\CorporationMedal::class             => 'Proceed upgrade from corporation_medals to corporation_medals (%s)',
    \Seat\Upgrader\Models\CorporationIssuedMedal::class       => 'Proceed upgrade from corporation_member_medals to corporation_issued_medals, character_medals (%s)',
    \Seat\Upgrader\Models\CorporationMemberTracking::class    => 'Proceed upgrade from corporation_member_trackings to corporation_member_trackings, corporation_members, character_onlines, character_locations (%s)',
    \Seat\Upgrader\Models\CorporationShareholder::class       => 'Proceed upgrade from corporation_shareholders to corporation_shareholders (%s)',
    \Seat\Upgrader\Models\CorporationHangarDivision::class    => 'Proceed upgrade from corporation_sheet_divisions to corporation_division (%s)',
    \Seat\Upgrader\Models\CorporationWalletDivision::class    => 'Proceed upgrade from corporation_sheet_wallet_divisions to corporation_divisions (%s)',
    \Seat\Upgrader\Models\CorporationSheet::class             => 'Proceed upgrade from corporation_sheets to corporation_infos, corporation_member_limits (%s)',
    \Seat\Upgrader\Models\CorporationStanding::class          => 'Proceed upgrade from corporation_standings to corporation_standings (%s)',
    \Seat\Upgrader\Models\CorporationStarbase::class          => 'Proceed upgrade from corporation_starbases to corporation_starbases (%s)',
    \Seat\Upgrader\Models\CorporationStarbaseDetail::class    => 'Proceed upgrade from corporation_starbase_details to corporation_starbase_detais (%s)',
    \Seat\Upgrader\Models\CorporationTitle::class             => 'Proceed upgrade from corporation_titles to corporation_titles (%s)',
    \Seat\Upgrader\Models\CorporationWalletJournal::class     => 'Proceed upgrade from corporation_wallet_journals to corporation_wallet_journals (%s)',
    \Seat\Upgrader\Models\CorporationWalletTransaction::class => 'Proceed upgrade from corporation_wallet_transactions to corporation_wallet_transactions (%s)',
];
