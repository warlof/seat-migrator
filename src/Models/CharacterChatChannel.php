<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Seat\Upgrader\Models;

use Seat\Eveapi\Models\Character\ChatChannelMember;
use Seat\Upgrader\Services\MappingCollection;

class CharacterChatChannel extends ChatChannelMember implements ICoreUpgrade
{
    public $maps = [
        'characterID' => 'character_id',
        'channelID'   => 'channel_id',
        'created_at'  => 'created_at',
        'updated_at'  => 'updated_at',
    ];

    public function newCollection(array $models = [])
    {
        return new MappingCollection($this->maps, $models);
    }

}
