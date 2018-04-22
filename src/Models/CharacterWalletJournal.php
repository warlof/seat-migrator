<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 13:33
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\WalletJournal;
use Seat\Upgrader\Services\MappingCollection;

class CharacterWalletJournal extends WalletJournal implements ICoreUpgrade
{

    public $incrementing = false;

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_wallet_journals (character_id, id, `date`, ref_type, first_party_id, " .
               "second_party_id, amount, balance, reason, tax_receiver_id, tax, context_id, ".
               "created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->refID,
            $this->date,
            $this->refTypeID,
            $this->ownerID1,
            $this->ownerID2,
            $this->amount,
            $this->balance,
            $this->reason,
            $this->taxReceiverID,
            $this->taxAmount,
            $this->argID1,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_wallet_journals' => [
                'characterID'   => 'character_id',
                'refID'         => 'id',
                'date'          => 'date',
                'refTypeID'     => 'ref_type',
                'ownerID1'      => 'first_party_id',
                'ownerID2'      => 'second_party_id',
                'amount'        => 'amount',
                'balance'       => 'balance',
                'reason'        => 'reason',
                'taxReceiverID' => 'tax_receiver_id',
                'taxAmount'     => 'tax',
                'argID1'        => 'context_id',
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
