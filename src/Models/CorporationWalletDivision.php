<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:46
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\CorporationSheetWalletDivision;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CorporationWalletDivision extends CorporationSheetWalletDivision implements ICoreUpgrade
{

    public function getTypeAttribute()
    {
        return 'wallet';
    }

    public function getAccountKeyAttribute($value)
    {
        return $value - 999;
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_divisions' => [
                'corporationID' => 'corporation_id',
                'accountKey'    => 'division',
                'type'          => 'type',
                'description'   => 'name',
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
