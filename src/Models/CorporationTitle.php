<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 16:08
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\Title;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CorporationTitle extends Title implements ICoreUpgrade
{
    public function getUpgradeMapping(): array
    {
        return [
            'corporation_titles' => [
                'corporationID' => 'corporation_id',
                'titleID'       => 'title_id',
                'titleName'     => 'name',
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
