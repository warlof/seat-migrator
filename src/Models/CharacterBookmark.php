<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Seat\Upgrader\Models;

use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\Bookmark;

class CharacterBookmark extends Bookmark implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_bookmarks (character_id, bookmark_id, folder_id, created, creator_id, " .
               "item_id, label, location_id, notes, type_id, x, y, z, updated_at, created_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->bookmarkID,
            $this->folderID,
            $this->created,
            $this->creatorID,
            $this->itemID,
            $this->memo,
            $this->locationID,
            $this->note,
            $this->typeID,
            $this->x,
            $this->y,
            $this->z,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();

    }

}
