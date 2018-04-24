<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:00
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\CustomsOffice;
use Seat\Eveapi\Models\Corporation\CustomsOfficeLocation;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CorporationCustomOffice extends CustomsOffice implements ICoreUpgrade
{

    public function getMapIDAttribute()
    {
        $location = CustomsOfficeLocation::where('itemID', $this->itemID)->first();
        if (is_null($location))
            return null;

        return $location->mapID;
    }

    public function getXAttribute()
    {
        $location = CustomsOfficeLocation::where('itemID', $this->itemID)->first();
        if (is_null($location))
            return null;

        return $location->x;
    }

    public function getYAttribute()
    {
        $location = CustomsOfficeLocation::where('itemID', $this->itemID)->first();
        if (is_null($location))
            return null;

        return $location->y;
    }

    public function getZAttribute()
    {
        $location = CustomsOfficeLocation::where('itemID', $this->itemID)->first();
        if (is_null($location))
            return null;

        return $location->z;
    }

    public function getStandingLevelAttribute($value)
    {
        if ($value == -10)
            return 'terrible';

        if ($value == -5)
            return 'bad';

        if ($value == 5)
            return 'good';

        if ($value == 10)
            return 'excellent';

        return 'neutral';
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_customs_offices' => [
                'corporationID'           => 'corporation_id',
                'itemID'                  => 'office_id',
                'solarSystemID'           => 'system_id',
                'reinforceHour'           => 'reinforce_exit_start',
                'allowAlliance'           => 'allow_alliance_access',
                'allowStandings'          => 'allow_access_with_standings',
                'standingLevel'           => 'standing_level',
                'taxRateAlliance'         => 'alliance_tax_rate',
                'taxRateCorp'             => 'corporation_tax_rate',
                'taxRateStandingHigh'     => 'excellent_standing_tax_rate',
                'taxRateStandingGood'     => 'good_standing_tax_rate',
                'taxRateStandingNeutral'  => 'neutral_standing_tax_rate',
                'taxRateStandingBad'      => 'bad_standing_tax_rate',
                'taxRateStandingHorrible' => 'terrible_standing_tax_rate',
                'mapID'                   => 'location_id',
                'x'                       => 'x',
                'y'                       => 'y',
                'z'                       => 'z',
                'created_at'              => 'created_at',
                'updated_at'              => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
