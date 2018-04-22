<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:10
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\MarketOrder;
use Seat\Upgrader\Services\MappingCollection;

class CorporationOrder extends MarketOrder implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_orders' => [
                'orderID'       => 'order_id',
                'corporationID' => 'corporation_id',
                'stationID'     => 'location_id',
                'volEntered'    => 'volume_total',
                'volRemaining'  => 'volume_remain',
                'minVolume'     => 'min_volume',
                'typeID'        => 'type_id',
                'range'         => 'range',
                'accountKey'    => 'wallet_division',
                'duration'      => 'duration',
                'escrow'        => 'escrow',
                'price'         => 'price',
                'bid'           => 'is_buy_order',
                'issued'        => 'issued',
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
