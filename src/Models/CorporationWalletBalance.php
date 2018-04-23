<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 14:20
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\AccountBalance;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CorporationWalletBalance extends AccountBalance implements ICoreUpgrade
{
    public function getAccountKeyAttribute($value)
    {
        return $value - 999;
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_wallet_balances' => [
                'corporationID' => 'corporation_id',
                'accountKey'    => 'division',
                'balance'       => 'balance',
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
