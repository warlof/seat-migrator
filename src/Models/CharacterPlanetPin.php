<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 12:37
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\PlanetaryPin;
use Seat\Upgrader\Services\MappingCollection;

class CharacterPlanetPin extends PlanetaryPin implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_planet_pins (character_id, planet_id, pin_id, type_id, schematic_id, " .
               "latitude, longitude, install_time, expiry_time, last_cycle_start, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->ownerID,
            $this->planetID,
            $this->pinID,
            $this->typeID,
            $this->schematicID,
            $this->latitude,
            $this->longitude,
            $this->installTime,
            $this->expiryTime,
            $this->lastLaunchTime,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

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
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
