<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 11:04
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\PlanetaryColony;

class CharacterPlanet extends PlanetaryColony implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_planets (character_id, solar_system_id, planet_id, upgrade_level, " .
               "num_pins, last_update, planet_type, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

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

        DB::connection($target)->insert($sql, [
            $this->ownerID,
            $this->solarSystemID,
            $this->planetID,
            $this->upgradeLevel,
            $this->numberOfPins,
            $this->lastUpdate,
            $types[$this->planetTypeID],
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

}
