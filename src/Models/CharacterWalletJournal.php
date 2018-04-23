<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 13:33
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\WalletJournal;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterWalletJournal extends WalletJournal implements ICoreUpgrade
{

    public $incrementing = false;

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
