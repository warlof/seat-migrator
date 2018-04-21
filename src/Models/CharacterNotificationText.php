<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 19:26
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\NotificationsText;
use Seat\Upgrader\Services\MappingCollection;

class CharacterNotificationText extends NotificationsText implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "UPDATE character_notifications SET text = ? WHERE notification_id = ?";

        DB::connection($target)->update($sql, [
            $this->text,
            $this->notificationID
        ]);

        $this->upgraded = true;
        $this->save();
    }

    /**
     * TODO : implement UPDATE magic mapper
     *
     * @return array
     */
    public function getUpgradeMapping(): array
    {
        return [
            'character_notifications' => [
                'notificationID' => 'notification_id',
                'text'           => 'text',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
