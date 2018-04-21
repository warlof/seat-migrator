<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Seat\Upgrader\Models;

use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\AccountBalance;

class CharacterAccountBalance extends AccountBalance implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_wallet_balances (character_id, balance, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->balance,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

}
