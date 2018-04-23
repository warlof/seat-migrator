<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 12:59
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\Research;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterResearch extends Research implements ICoreUpgrade
{

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
