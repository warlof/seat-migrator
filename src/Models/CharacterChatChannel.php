<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Warlof\Seat\Migrator\Models;

use Seat\Eveapi\Models\Character\ChatChannel;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class CharacterChatChannel extends ChatChannel implements ICoreUpgrade
{
    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'channelID'];

    public function getUpgradeMapping(): array
    {
        return [
            'character_chat_channels' => [
                'characterID' => 'character_id',
                'channelID'   => ['channel_id', 'channel_info_id'],
                'created_at'  => 'created_at',
                'updated_at'  => 'updated_at',
            ]
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
