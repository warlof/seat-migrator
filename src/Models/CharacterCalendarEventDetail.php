<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 20:04
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\UpcomingCalendarEvent;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterCalendarEventDetail extends UpcomingCalendarEvent implements ICoreUpgrade
{

    public function getOwnerTypeIDAttribute($value)
    {
        if ($value == 0)
            return 'eve_server';

        if ($value == 2)
            return 'corporation';

        if ($value == 16159)
            return 'alliance';

        if ($value == 30)
            return 'faction';

        return 'character';
    }

    public function getResponseAttribute($value)
    {
        if ($value == 'Undecided')
            return 'not_responded';

        if ($value == 'Accepted')
            return 'accepted';

        if ($value == 'Declined')
            return 'declined';

        return 'tentative';
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_calendar_event_details' => [
                'eventID'     => 'event_id',
                'ownerID'     => 'owner_id',
                'ownerName'   => 'owner_name',
                'duration'    => 'duration',
                'eventText'   => 'text',
                'ownerTypeID' => 'owner_type',
                'created_at'  => 'created_at',
                'updated_at'  => 'updated_at',
            ],
            'character_calendar_events' => [
                'characterID' => 'character_id',
                'eventID'     => 'event_id',
                'eventDate'   => 'event_date',
                'eventTitle'  => 'title',
                'importance'  => 'importance',
                'response'    => 'event_response',
                'created_at'  => 'created_at',
                'updated_at'  => 'updated_at',
            ],
            'character_calendar_attendees' => [
                'eventID'     => 'event_id',
                'characterID' => 'character_id',
                'response'    => 'event_response',
                'created_at'  => 'created_at',
                'updated_at'  => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
