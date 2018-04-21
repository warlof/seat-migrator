<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 12:42
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\PlanetaryRoute;

class CharacterPlanetRoute extends PlanetaryRoute implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_planet_routes (character_id, planet_id, route_id, source_pin_id, " .
               "destination_pin_id, content_type_id, quantity, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->ownerID,
            $this->planetID,
            $this->routeID,
            $this->sourcePinID,
            $this->destinationPinID,
            $this->contentTypeID,
            $this->quantity,
            $this->created_at,
            $this->updated_at,
        ]);

        $sql = "INSERT IGNORE INTO character_planet_route_waypoints (character_id, planet_id, route_id, pin_id, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?)";

        for ($i = 1; $i < 6; $i++)
            DB::connection($target)->insert($sql, [
                $this->ownerID,
                $this->planetID,
                $this->routeID,
                $this->waypoint{$i},
                $this->created_at,
                $this->updated_at,
            ]);

        $this->upgraded = true;
        $this->save();
    }

}
