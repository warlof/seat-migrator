<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:57
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\Standing;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CorporationStanding extends Standing implements ICoreUpgrade
{
    public function getUpgradeMapping(): array
    {
        return [
            'corporation_standings' => [
                'corporationID' => 'corporation_id',
                'type'          => 'from_type',
                'fromID'        => 'from_id',
                'standing'      => 'standing',
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
