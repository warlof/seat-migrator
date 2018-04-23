<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:20
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\Medal;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CorporationMedal extends Medal implements ICoreUpgrade
{

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_medals' => [
                'medalID'       => 'medal_id',
                'corporationID' => 'corporation_id',
                'title'         => 'title',
                'description'   => 'description',
                'creatorID'     => 'creator_id',
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
