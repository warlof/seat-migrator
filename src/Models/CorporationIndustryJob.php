<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:05
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\IndustryJob;
use Seat\Upgrader\Services\MappingCollection;

class CorporationIndustryJob extends IndustryJob implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_industry_jobs' => [
                'corporationID'       => 'corporation_id',
                'jobID'               => 'job_id',
                'installerID'         => 'installer_id',
                'facilityID'          => 'facility_id',
                'stationID'           => 'location_id',
                'activityID'          => 'activity_id',
                'blueprintID'         => 'blueprint_id',
                'blueprintTypeID'     => 'blueprint_type_id',
                'blueprintLocationID' => 'blueprint_location_id',
                'outputLocationID'    => 'output_location_id',
                'runs'                => 'runs',
                'cost'                => 'cost',
                'licensedRuns'        => 'licensed_runs',
                'probability'         => 'probability',
                'productTypeID'       => 'product_type_id',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
