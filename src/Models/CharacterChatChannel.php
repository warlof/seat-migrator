<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Seat\Upgrader\Models;

use Seat\Eveapi\Models\Character\ChatChannel;
use Seat\Upgrader\Services\MappingCollection;
use Seat\Upgrader\Traits\HasCompositePrimaryKey;

class CharacterChatChannel extends ChatChannel implements ICoreUpgrade
{
    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'channelID'];

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

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
