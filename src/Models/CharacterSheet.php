<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 11:26
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;

class CharacterSheet extends \Seat\Eveapi\Models\Character\CharacterSheet implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_infos (character_id, `name`, corporation_id, alliance_id, birthday, " .
               "gender, race_id, bloodline_id, ancenstry_id, faction_id, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->name,
            $this->corporationID,
            $this->allianceID,
            $this->DoB,
            $this->gender,
            $this->race,
            $this->bloodLineID,
            $this->ancestryID,
            $this->factionID,
            $this->created_at,
            $this->updated_at,
        ]);

        $sql = "INSERT IGNORE INTO character_attributes (character_id, charisma, intelligence, memory, perception, " .
               "willpower, bonus_remaps, last_remap_date, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->charisma,
            $this->intelligence,
            $this->memory,
            $this->perception,
            $this->willpower,
            $this->freeRespecs,
            $this->lastRespecDate,
            $this->created_at,
            $this->updated_at,
        ]);

        $sql = "INSERT IGNORE INTO character_clones (character_id, home_location_id, home_location_type, " .
               "last_station_change_date, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->homeStationID,
            ((60000000 >= $this->homeStationID && $this->homeStationID <= 64000000) ||
             (68000000 >= $this->homeStationID && $this->homeStationID <= 70000000)) ? 'station' : 'structure',
            $this->remoteStationDate,
            $this->created_at,
            $this->updated_at,
        ]);

        $sql = "INSERT IGNORE INTO character_info_skills (character_id, total_sp, unallocated_sp, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->freeSkillPoints,
            $this->cloneSkillPoints,
            $this->created_at,
            $this->updated_at,
        ]);

        $sql = "INSERT IGNORE INTO character_fatigues (character_id, last_jump_date, jump_fatigue_expire_date, " .
               "last_update_date, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->cloneJumpDate,
            $this->jumpFatigue,
            $this->jumpLastUpdate,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

}
