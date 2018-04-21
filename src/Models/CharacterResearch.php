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
use Seat\Upgrader\Services\MappingCollection;

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

    public function getUpgradeMapping(): array
    {
        return [
            'character_agent_researches' => [
                'characterID'       => 'character_id',
                'agentID'           => 'agent_id',
                'skillTypeID'       => 'skill_type_id',
                'researchStartDate' => 'started_at',
                'pointsPerDay'      => 'points_per_day',
                'remainderPoints'   => 'remainder_points',
                'created_at'        => 'created_at',
                'updated_at'        => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
