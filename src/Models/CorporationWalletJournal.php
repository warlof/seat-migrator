<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 16:11
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\WalletJournal;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CorporationWalletJournal extends WalletJournal implements ICoreUpgrade
{

    private static $ref_types;

    public $incrementing = false;

    public function getAccountKeyAttribute($value)
    {
        return $value - 999;
    }

    public function getRefTypeIDAttribute($value)
    {
        if (is_null(self::$ref_types))
            self::$ref_types = include __DIR__ . '/../Config/reference_types.php';

        return self::$ref_types[$value];
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
