<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Seat\Upgrader\Models;

use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\CharacterSheetJumpClone;

class CharacterJumpClone extends CharacterSheetJumpClone implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_jump_clones (character_id, jump_clone_id, name, location_id, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->jumpCloneID,
            $this->cloneName,
            $this->locationID,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

}
