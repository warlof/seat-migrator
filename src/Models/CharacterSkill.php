<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Seat\Upgrader\Models;

use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\CharacterSheetSkills;
use Seat\Upgrader\Services\MappingCollection;

class CharacterSkill extends CharacterSheetSkills implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_skills " .
               "(character_id, skill_id, skillpoints_in_skill, trained_skill_level, active_skill_level, " .
               "created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->typeID,
            $this->skillpoints,
            $this->level,
            $this->level,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_skills' => [
                'characterID' => 'character_id',
                'typeID'      => 'skill_id',
                'skillpoints' => 'skillpoints_in_skill',
                'level'       => 'trained_skill_level',
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
