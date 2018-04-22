<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 16:01
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\Starbase;
use Seat\Upgrader\Services\MappingCollection;

class CorporationStarbase extends Starbase implements ICoreUpgrade
{
    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_starbases' => [
                'corporationID' => 'corporation_id',
                'itemID'        => 'starbase_id',
                'typeID'        => 'type_id',
                'locationID'    => 'system_id',
                'moonID'        => 'moon_id',
                'state'         => 'state',
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
