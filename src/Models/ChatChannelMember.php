<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 20:56
 */

namespace Seat\Upgrader\Models;


use Seat\Upgrader\Services\MappingCollection;

class ChatChannelMember extends \Seat\Eveapi\Models\Character\ChatChannelMember implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_chat_channel_members' => [
                'id'         => 'id',
                'channelID'  => ['channel_id', 'channel_info_id'],
                'accessorID' => 'accessor_id',
                'role'       => 'role',
                'reason'     => 'reason',
                'untilWhen'  => 'end_at',
                'created_at' => 'created_at',
                'updated_at' => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
