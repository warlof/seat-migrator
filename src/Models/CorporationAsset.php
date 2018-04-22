<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 14:24
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\AssetList;
use Seat\Upgrader\Services\MappingCollection;
use Seat\Upgrader\Traits\HasCompositePrimaryKey;

class CorporationAsset extends AssetList implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['corporationID', 'itemID'];

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

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
