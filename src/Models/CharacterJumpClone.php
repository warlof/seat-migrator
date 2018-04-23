<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Warlof\Seat\Migrator\Models;

use Seat\Eveapi\Models\Character\CharacterSheetJumpClone;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterJumpClone extends CharacterSheetJumpClone implements ICoreUpgrade
{

    public function getLocationTypeAttribute()
    {
        if ((60000000 <= $this->locationID && $this->locationID <= 64000000) ||
            (68000000 <= $this->locationID && $this->locationID <= 70000000))
            return 'station';

        return 'structure';
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_jump_clones' => [
                'characterID'  => 'character_id',
                'jumpCloneID'  => 'jump_clone_id',
                'cloneName'    => 'name',
                'locationID'   => 'location_id',
                'locationType' => 'location_type',
                'created_at'   => 'created_at',
                'updated_at'   => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
