<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:43
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\CorporationSheetDivision;
use Seat\Upgrader\Services\MappingCollection;

class CorporationHangarDivision extends CorporationSheetDivision implements ICoreUpgrade
{

    public $type = 'hangar';

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_divisions' => [
                'corporationID' => 'corporation_id',
                'accountKey'    => 'division',
                'type'          => 'type',
                'description'   => 'name',
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
