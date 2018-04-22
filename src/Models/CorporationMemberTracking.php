<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:27
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\MemberTracking;
use Seat\Upgrader\Services\MappingCollection;

class CorporationMemberTracking extends MemberTracking implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_member_trackings' => [
                'corporationID'  => 'corporation_id',
                'characterID'    => 'character_id',
                'startDateTime'  => 'start_date',
                'baseID'         => 'base_id',
                'logonDateTime'  => 'logon_date',
                'logoffDateTime' => 'logoff_date',
                'locationID'     => 'location_id',
                'shipTypeID'     => 'ship_type_id',
                'created_at'     => 'created_at',
                'updated_at'     => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
