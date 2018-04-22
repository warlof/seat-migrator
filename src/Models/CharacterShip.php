<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 22:43
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Eve\CharacterInfo;
use Seat\Upgrader\Services\MappingCollection;

class CharacterShip extends CharacterInfo implements ICoreUpgrade
{
    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_ships' => [
                'characterID' => 'character_id',
                'shipTypeID'  => 'ship_item_id',
                'shipName'    => 'ship_name',
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
