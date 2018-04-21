<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 13:08
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\SkillQueue;
use Seat\Upgrader\Traits\HasCompositePrimaryKey;

class CharacterSkillQueue extends SkillQueue implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'queuePosition'];

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_skill_queues (character_id, skill_id, finish_date, start_date, " .
               "finished_level, queue_position, training_start_sp, level_end_sp, level_start_sp, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->typeID,
            $this->endTime,
            $this->startTime,
            $this->level,
            $this->queuePosition,
            $this->startSP,
            $this->endSP,
            $this->startSP,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

}
