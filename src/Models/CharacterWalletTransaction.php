<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 13:42
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\WalletTransaction;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterWalletTransaction extends WalletTransaction implements ICoreUpgrade
{

    public $incrementing = false;

    public function getTransactionTypeAttribute($value)
    {
        return ($value == 'buy');
    }

    public function getTransactionForAttribute($value)
    {
        return ($value == 'personal');
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_wallet_transactions' => [
                'characterID'          => 'character_id',
                'transactionID'        => 'transaction_id',
                'transactionDateTime'  => 'date',
                'typeID'               => 'type_id',
                'stationID'            => 'location_id',
                'price'                => 'unit_price',
                'quantity'             => 'quantity',
                'clientID'             => 'client_id',
                'transactionType'      => 'is_buy',
                'transactionFor'       => 'is_personal',
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
