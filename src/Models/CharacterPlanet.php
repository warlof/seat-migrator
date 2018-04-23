<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 11:04
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\PlanetaryColony;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterPlanet extends PlanetaryColony implements ICoreUpgrade
{

    public function getPlanetTypeAttribute()
    {
        $types = [
            11    => 'temperate',
            12    => 'ice',
            13    => 'gas',
            2014  => 'oceanic',
            2015  => 'lava',
            2016  => 'barren',
            2017  => 'storm',
            2063  => 'plasma',
            30889 => 'shattered',
        ];

        return $types[$this->planetTypeID];
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_planets' => [
                'ownerID'       => 'character_id',
                'solarSystemID' => 'solar_system_id',
                'planetID'      => 'planet_id',
                'upgradeLevel'  => 'upgrade_level',
                'numberOfPins'  => 'num_pins',
                'lastUpdate'    => 'last_update',
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
