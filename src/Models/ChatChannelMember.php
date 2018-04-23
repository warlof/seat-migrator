<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 20:56
 */

namespace Warlof\Seat\Migrator\Models;


use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class ChatChannelMember extends \Seat\Eveapi\Models\Character\ChatChannelMember implements ICoreUpgrade
{
    public static $alliances;

    public function getAccessorTypeAttribute()
    {
        if (is_null(self::$alliances))
            self::$alliances = include __DIR__ . '/../Config/alliances.php';

        if ((1000000 <= $this->accessorID && $this->accessorID <= 2000000) ||
            (98000000 <= $this->accessorID && $this->accessorID <= 99000000))
            return 'corporation';

        if (in_array($this->accessorID, self::$alliances) || (99000000 <= $this->accessorID && $this->accessorID <= 100000000))
            return 'alliance';

        return 'character';
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_chat_channel_members' => [
                'id'           => 'id',
                'channelID'    => ['channel_id', 'channel_info_id'],
                'accessorID'   => 'accessor_id',
                'accessorType' => 'accessor_type',
                'role'         => 'role',
                'reason'       => 'reason',
                'untilWhen'    => 'end_at',
                'created_at'   => 'created_at',
                'updated_at'   => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
