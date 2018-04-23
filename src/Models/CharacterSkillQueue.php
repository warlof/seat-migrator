<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 13:08
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\SkillQueue;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class CharacterSkillQueue extends SkillQueue implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'queuePosition'];

    public function getUpgradeMapping(): array
    {
        return [
            'character_skill_queues' => [
                'characterID'   => 'character_id',
                'typeID'        => 'skill_id',
                'endTime'       => 'finish_date',
                'startTime'     => 'start_date',
                'level'         => 'finished_level',
                'queuePosition' => 'queue_position',
                'startSP'       => 'training_start_sp',
                'endSP'         => 'level_end_sp',
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
