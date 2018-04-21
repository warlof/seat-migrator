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
use Seat\Upgrader\Services\MappingCollection;

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

    public function getUpgradeMapping(): array
    {
        return [
            'character_wallet_balances' => [
                'characterID' => 'character_id',
                'balance'     => 'balance',
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
