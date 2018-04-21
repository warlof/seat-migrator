<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Seat\Upgrader\Models;

use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\CharacterSheetImplants;

class CharacterImplant extends CharacterSheetImplants implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_implants (character_id, type_id, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->typeID,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

}
