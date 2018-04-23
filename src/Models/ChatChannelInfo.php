<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Warlof\Seat\Migrator\Models;


use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class ChatChannelInfo extends \Seat\Eveapi\Models\Character\ChatChannelInfo implements ICoreUpgrade
{
    public function getUpgradeMapping(): array
    {
        return [
            'character_chat_channel_infos' => [
                'channelID'     => 'channel_id',
                'ownerID'       => 'owner_id',
                'displayName'   => 'name',
                'comparisonKey' => 'comparison_key',
                'hasPassword'   => 'has_password',
                'motd'          => 'motd',
                'created_at'    => 'created_at',
                'updated_at'    => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }

}
