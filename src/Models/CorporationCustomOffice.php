<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:00
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\CustomsOffice;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CorporationCustomOffice extends CustomsOffice implements ICoreUpgrade
{

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
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
