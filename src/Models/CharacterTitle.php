<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Seat\Upgrader\Models;

use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\CharacterSheetCorporationTitles;

class CharacterTitle extends CharacterSheetCorporationTitles implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_titles (character_id, title_id, `name`, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->titleID,
            $this->titleName,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

}
