<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 16:18
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\WalletTransaction;
use Seat\Upgrader\Services\MappingCollection;

class CorporationWalletTransaction extends WalletTransaction implements ICoreUpgrade
{

    public $incrementing = false;

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_wallet_transactions' => [
                'corporationID'        => 'corporation_id',
                'accountKey'           => 'division',
                'transactionDateTime'  => 'date',
                'transactionID'        => 'transaction_id',
                'quantity'             => 'quantity',
                'typeID'               => 'type_id',
                'price'                => 'unit_price',
                'clientID'             => 'client_id',
                'stationID'            => 'location_id',
                'transactionType'      => 'is_buy',
                'journalTransactionID' => 'journal_ref_id',
                'created_at'           => 'created_at',
                'updated_at'           => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
