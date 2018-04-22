<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:40
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\Shareholder;
use Seat\Upgrader\Services\MappingCollection;

class CorporationShareholder extends Shareholder implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_shareholders' => [
                'corporationID'   => 'corporation_id',
                'shareholderType' => 'shareholder_type',
                'shareholderID'   => 'shareholder_id',
                'shares'          => 'share_count',
                'created_at'      => 'created_at',
                'updated_at'      => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
