<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:20
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\Medal;
use Seat\Upgrader\Services\MappingCollection;

class CorporationMedal extends Medal implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_medals' => [
                'medalID'       => 'medal_id',
                'corporationID' => 'corporation_id',
                'title'         => 'title',
                'description'   => 'description',
                'creator_id'    => 'creator_id',
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
