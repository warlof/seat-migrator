<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 13:26
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\Standing;
use Seat\Upgrader\Services\MappingCollection;

class CharacterStanding extends Standing implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_standings (id, character_id, from_id, from_type, standing, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->id,
            $this->characterID,
            $this->fromID,
            $this->type,
            $this->standing,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_standings' => [
                'id'          => 'id',
                'characterID' => 'character_id',
                'fromID'      => 'from_id',
                'type'        => 'from_type',
                'standing'    => 'standing',
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
