<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 11:26
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Eve\CharacterInfo;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterSheet extends \Seat\Eveapi\Models\Character\CharacterSheet implements ICoreUpgrade
{

    public function getRaceAttribute($value)
    {
        if ($value == 'Gallente')
            return 8;

        if ($value == 'Minmatar')
            return 2;

        if ($value == 'Amarr')
            return 4;

        if ($value == 'Caldari')
            return 1;

        if ($value == 'Jove')
            return 16;

        if ($value == 'Pirate')
            return 32;

        if ($value == 'Sleepers')
            return 64;

        if ($value == 'ORE')
            return 128;

        return 0;
    }

    public function getHomeLocationTypeAttribute()
    {
        if ((60000000 <= $this->homeStationID && $this->homeStationID <= 64000000) ||
            (68000000 <= $this->homeStationID && $this->homeStationID <= 70000000))
            return 'station';

        return 'structure';
    }

    public function getCloneSkillPointsAttribute($value)
    {
        $character = CharacterInfo::find($this->characterID);
        if (is_null($character))
            return null;

        return $character->skillPoints;
    }

    public function getSecurityStatusAttribute()
    {
        $character = CharacterInfo::find($this->characterID);
        if (is_null($character))
            return null;

        return $character->securityStatus;
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_infos'       => [
                'characterID'    => 'character_id',
                'name'           => 'name',
                'corporationID'  => 'corporation_id',
                'allianceID'     => 'alliance_id',
                'DoB'            => 'birthday',
                'gender'         => 'gender',
                'race'           => 'race_id',
                'securityStatus' => 'security_status',
                'bloodLineID'    => 'bloodline_id',
                'ancestryID'     => 'ancenstry_id',
                'factionID'      => 'faction_id',
                'created_at'     => 'created_at',
                'updated_at'     => 'updated_at',
            ],
            'character_attributes'  => [
                'characterID'    => 'character_id',
                'charisma'       => 'charisma',
                'intelligence'   => 'intelligence',
                'memory'         => 'memory',
                'perception'     => 'perception',
                'willpower'      => 'willpower',
                'freeRespecs'    => 'bonus_remaps',
                'lastRespecDate' => 'last_remap_date',
                'created_at'     => 'created_at',
                'updated_at'     => 'updated_at',
            ],
            'character_clones'      => [
                'characterID'       => 'character_id',
                'cloneJumpDate'     => 'last_clone_jump_date',
                'homeStationID'     => 'home_location_id',
                'homeLocationType'  => 'home_location_type',
                'remoteStationDate' => 'last_station_change_date',
                'created_at'        => 'created_at',
                'updated_at'        => 'updated_at',
            ],
            'character_info_skills' => [
                'characterID'      => 'character_id',
                'freeSkillPoints'  => 'unallocated_sp',
                'cloneSkillPoints' => 'total_sp',
                'created_at'       => 'created_at',
                'updated_at'       => 'updated_at',
            ],
            'character_fatigues'    => [
                'characterID'    => 'character_id',
                'cloneJumpDate'  => 'last_jump_date',
                'jumpFatigue'    => 'jump_fatigue_expire_date',
                'jumpLastUpdate' => 'last_update_date',
                'created_at'     => 'created_at',
                'updated_at'     => 'updated_at',
            ]
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
