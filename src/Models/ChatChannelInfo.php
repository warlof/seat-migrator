<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Seat\Upgrader\Models;

use Illuminate\Support\Facades\DB;
use Seat\Upgrader\Services\MappingCollection;

class ChatChannelInfo extends \Seat\Eveapi\Models\Character\ChatChannelInfo implements ICoreUpgrade
{
    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_chat_channel_infos (channel_id, owner_id, `name`, comparison_key, " .
               "has_password, motd, created_at, updated_at)" .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->channelID,
            $this->ownerID,
            $this->displayName,
            $this->comparisonKey,
            $this->hasPassword,
            $this->motd,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

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
