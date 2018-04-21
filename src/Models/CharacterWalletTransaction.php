<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 13:42
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\WalletTransaction;
use Seat\Upgrader\Services\MappingCollection;

class CharacterWalletTransaction extends WalletTransaction implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_wallet_transactions (character_id, transaction_id, `date`, type_id, " .
               "location_id, unit_price, quantity, client_id, is_buy, is_personal, journal_ref_id, created_at, updated_at)" .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $type = false;
        if ($this->transactionType == 'buy')
            $type = true;

        $personal = false;
        if ($this->transactionFor == 'personal')
            $personal = true;

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->transactionID,
            $this->transactionDateTime,
            $this->typeID,
            $this->stationID,
            $this->price,
            $this->quantity,
            $this->clientID,
            $type,
            $personal,
            $this->journalTransactionID,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
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
