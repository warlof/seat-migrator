<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 14:20
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\AccountBalance;
use Seat\Upgrader\Services\MappingCollection;

class CorporationWalletBalance extends AccountBalance implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
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
