<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 19:10
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\Notifications;
use Seat\Upgrader\Services\MappingCollection;

class CharacterNotification extends Notifications implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_notifications (character_id, notification_id, `type`, sender_id, " .
               "sender_type, `timestamp`, is_read, text, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->notificationID,
            $this->typeID, // TODO : convert
            $this->senderID,
            function(){
                if ((3000000 >= $this->senderID && $this->senderID <= 4000000) ||
                    (90000000 >= $this->senderID && $this->senderID <= 98000000))
                    return 'character';
                if ((1000000 >= $this->senderID && $this->senderID <= 2000000) ||
                    (98000000 >= $this->senderID && $this->senderID <= 99000000))
                    return 'corporation';
                if (99000000 >= $this->senderID && $this->senderID <= 100000000)
                    return 'alliance';
                if (500000 >= $this->senderID && $this->senderID <= 1000000)
                    return 'faction';
                return 'other';
            },
            $this->sentDate,
            $this->read,
            '',
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_notifications' => [
                'characterID'    => 'character_id',
                'notificationID' => 'notification_id',
                'typeID'         => 'type',
                'senderID'       => 'sender_id',
                'sentDate'       => 'timestamp',
                'read'           => 'is_read',
                'created_at'     => 'created_at',
                'updated_at'     => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
