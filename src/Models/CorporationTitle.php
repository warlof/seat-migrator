<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 16:08
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\Title;
use Seat\Upgrader\Services\MappingCollection;

class CorporationTitle extends Title implements ICoreUpgrade
{
    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

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
