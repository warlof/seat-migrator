<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\CharacterSheetSkills;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterSkill extends CharacterSheetSkills implements ICoreUpgrade
{

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
