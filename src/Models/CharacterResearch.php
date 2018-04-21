<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 12:59
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\Research;

class CharacterResearch extends Research implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_agent_researches (character_id, agent_id, skill_type_id, started_at, " .
               "points_per_day, remainder_points, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->agentID,
            $this->skillTypeID,
            $this->researchStartDate,
            $this->pointsPerDay,
            $this->remainderPoints,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

}
