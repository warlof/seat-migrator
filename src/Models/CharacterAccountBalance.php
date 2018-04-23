<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Warlof\Seat\Migrator\Models;

use Seat\Eveapi\Models\Character\AccountBalance;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterAccountBalance extends AccountBalance implements ICoreUpgrade
{

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
