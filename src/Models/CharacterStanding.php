<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 13:26
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\Standing;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterStanding extends Standing implements ICoreUpgrade
{

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
