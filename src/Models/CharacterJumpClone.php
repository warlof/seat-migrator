<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Seat\Upgrader\Models;

use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\CharacterSheetJumpClone;
use Seat\Upgrader\Services\MappingCollection;

class CharacterJumpClone extends CharacterSheetJumpClone implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_jump_clones (character_id, jump_clone_id, name, location_id, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->jumpCloneID,
            $this->cloneName,
            $this->locationID,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_jump_clones' => [
                'characterID' => 'character_id',
                'jumpCloneID' => 'jump_clone_id',
                'cloneName'   => 'name',
                'locationID'  => 'location_id',
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
