<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 14:24
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\AssetList;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class CorporationAsset extends AssetList implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['corporationID', 'itemID'];

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_assets' => [
                'corporationID' => 'corporation_id',
                'itemID'        => 'item_id',
                'locationID'    => 'location_id',
                'typeID'        => 'type_id',
                'quantity'      => 'quantity',
                'flag'          => 'location_flag',
                'singleton'     => 'is_singleton',
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
