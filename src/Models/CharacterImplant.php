<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Warlof\Seat\Migrator\Models;

use Seat\Eveapi\Models\Character\CharacterSheetImplants;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterImplant extends CharacterSheetImplants implements ICoreUpgrade
{

    public function getUpgradeMapping(): array
    {
        return [
            'character_implants' => [
                'characterID' => 'character_id',
                'typeID'      => 'type_id',
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
