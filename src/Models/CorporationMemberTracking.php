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
    public function getOnlineAttribute()
    {
        return carbon($this->logoffDateTime)->lessThan(carbon($this->logonDateTime));
    }

    public function getStationIDAttribute()
    {
        if ($this->isStation())
            return $this->locationID;

        return null;
    }

    public function getStructureIDAttribute()
    {
        if (! $this->isStructure())
            return null;

        if ($this->locationID > 0)
            return $this->locationID;

        // hotfix int32 overflow from xAPI
        return $this->locationID + 2147483647;
    }

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
            'corporation_members' => [
                'corporationID' => 'corporation_id',
                'characterID'   => 'character_id',
                'created_at'    => 'created_at',
                'updated_at'    => 'updated_at',
            ],
            'character_onlines' => [
                'characterID'    => 'character_id',
                'online'         => 'online', // TODO : add attribute
                'logonDateTime'  => 'last_login',
                'logoffDateTime' => 'last_logout',
                'created_at'     => 'created_at',
                'updated_at'     => 'updated_at',
            ],
            'character_locations' => [
                'characterID' => 'character_id',
                'stationID'   => 'station_id',
                'structureID' => 'structure_id',
                'created_at'  => 'created_at',
                'updated_at'  => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }

    private function isStation()
    {
        return ((60000000 <= $this->locationID && $this->locationID <= 64000000) ||
                (68000000 <= $this->locationID && $this->locationID <= 70000000));
    }

    private function isSystem()
    {
        return ((0 <= $this->locationID && $this->locationID <= 10000) ||
                (30000000 <= $this->locationID && $this->locationID <= 32000000));
    }

    private function isCelestial()
    {
        return (40000000 <= $this->locationID && $this->locationID <= 50000000);
    }

    private function isAsteroid()
    {
        return (70000000 <= $this->locationID && $this->locationID <= 80000000);
    }

    private function isStargate()
    {
        return (50000000 <= $this->locationID && $this->locationID <= 60000000);
    }

    private function isStructure()
    {
        return (!$this->isStation() && !$this->isSystem() && !$this->isCelestial() && !$this->isAsteroid() &&
                !$this->isStargate());
    }
}
