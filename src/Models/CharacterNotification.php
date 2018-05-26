<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 19:10
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\Notifications;
use Seat\Eveapi\Models\Character\NotificationsText;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterNotification extends Notifications implements ICoreUpgrade
{

    private static $notification_types;

    public function getTypeIDAttribute($value)
    {
        if (is_null(self::$notification_types))
            self::$notification_types = include __DIR__ . '/../Config/notification_types.php';

        return self::$notification_types[$value];
    }

    public function getSenderTypeAttribute()
    {
        if ((3000000 <= $this->senderID && $this->senderID <= 4000000) ||
            (90000000 <= $this->senderID && $this->senderID <= 98000000))
            return 'character';

        if ((1000000 <= $this->senderID && $this->senderID <= 2000000) ||
            (98000000 <= $this->senderID && $this->senderID <= 99000000))
            return 'corporation';

        if (99000000 <= $this->senderID && $this->senderID <= 100000000)
            return 'alliance';

        if (500000 <= $this->senderID && $this->senderID <= 1000000)
            return 'faction';

        return 'other';
    }

    public function getTextAttribute()
    {
        $notification = NotificationsText::find($this->notificationID);

        if (is_null($notification))
            return '';

        $notification->upgraded = true;
        $notification->save();
        return $notification->text;
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_notifications' => [
                'characterID'    => 'character_id',
                'notificationID' => 'notification_id',
                'typeID'         => 'type',
                'senderID'       => 'sender_id',
                'senderType'     => 'sender_type',
                'sentDate'       => 'timestamp',
                'text'           => 'text',
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
