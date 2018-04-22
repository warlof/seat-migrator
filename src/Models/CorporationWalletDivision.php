<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:46
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\CorporationSheetWalletDivision;
use Seat\Upgrader\Services\MappingCollection;

class CorporationWalletDivision extends CorporationSheetWalletDivision implements ICoreUpgrade
{

    public $type = 'wallet';

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
