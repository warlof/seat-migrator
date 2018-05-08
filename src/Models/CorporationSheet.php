<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:52
 */

namespace Warlof\Seat\Migrator\Models;


use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CorporationSheet extends \Seat\Eveapi\Models\Corporation\CorporationSheet implements ICoreUpgrade
{

    public function getTaxRateAttribute($value)
    {
        return $value / 100;
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_infos' => [
                'corporationID'   => 'corporation_id',
                'corporationName' => 'name',
                'ticker'          => 'ticker',
                'ceoID'           => 'ceo_id',
                'stationID'       => 'home_station_id',
                'description'     => 'description',
                'url'             => 'url',
                'allianceID'      => 'alliance_id',
                'factionID'       => 'faction_id',
                'taxRate'         => 'tax_rate',
                'memberCount'     => 'member_count',
                'shares'          => 'shares',
                'created_at'      => 'created_at',
                'updated_at'      => 'updated_at',
            ],
            'corporation_member_limits' => [
                'corporationID' => 'corporation_id',
                'memberLimit'   => 'limit',
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
