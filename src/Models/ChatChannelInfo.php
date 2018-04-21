<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Seat\Upgrader\Models;

use Illuminate\Support\Facades\DB;

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
}
