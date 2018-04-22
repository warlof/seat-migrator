<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 20:04
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Character\UpcomingCalendarEvent;
use Seat\Upgrader\Services\MappingCollection;

class CharacterCalendarEventDetail extends UpcomingCalendarEvent implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
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
