<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 16:11
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\WalletJournal;
use Seat\Upgrader\Services\MappingCollection;

class CorporationWalletJournal extends WalletJournal implements ICoreUpgrade
{

    public $incrementing = false;

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_wallet_journals' => [
                'corporationID' => 'corporation_id',
                'accountKey'    => 'division',
                'refID'         => 'id',
                'date'          => 'date',
                'refTypeID'     => 'ref_type',
                'ownerID1'      => 'first_party_id',
                'ownerID2'      => 'second_party_id',
                'amount'        => 'amount',
                'balance'       => 'balance',
                'reason'        => 'reason',
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
