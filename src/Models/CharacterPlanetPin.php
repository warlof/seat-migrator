<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 12:37
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\PlanetaryPin;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterPlanetPin extends PlanetaryPin implements ICoreUpgrade
{

    public function getUpgradeMapping(): array
    {
        return [
            'character_planet_pins' => [
                'ownerID'        => 'character_id',
                'planetID'       => 'planet_id',
                'pinID'          => 'pin_id',
                'typeID'         => 'type_id',
                'schematicID'    => 'schematic_id',
                'latitude'       => 'latitude',
                'longitude'      => 'longitude',
                'installTime'    => 'install_time',
                'expiryTime'     => 'expiry_time',
                'lastLaunchTime' => 'last_cycle_start',
                'created_at'     => 'created_at',
                'updated_at'     => 'updated_at',
            ],
            'character_planet_contents' => [
                'ownerID'         => 'character_id',
                'planetID'        => 'planet_id',
                'pinID'           => 'pin_id',
                'contentTypeID'   => 'type_id',
                'contentQuantity' => 'amount',
                'created_at'      => 'created_at',
                'updated_at'      => 'updated_at',
            ],
            'character_planet_extractors' => [
                'ownerID'          => 'character_id',
                'planetID'         => 'planet_id',
                'pinID'            => 'pin_id',
                'cycleTime'        => 'cycle_time',
                'quantityPerCycle' => 'qty_per_cycle',
                'created_at'       => 'created_at',
                'updated_at'       => 'updated_at',
            ],
            'character_planet_factories' => [
                'ownerID'     => 'character_id',
                'planetID'    => 'planet_id',
                'pinID'       => 'pin_id',
                'schematicID' => 'schematic_id',
                'created_at'  => 'created_at',
                'updated_at'  => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
