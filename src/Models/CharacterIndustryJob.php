<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 22:01
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\IndustryJob;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterIndustryJob extends IndustryJob implements ICoreUpgrade
{

    public function getStatusAttribute($value)
    {
        if ($value == 1)
            return 'active';
        if ($value == 2)
            return 'paused';
        if ($value == 3)
            return 'ready';
        if ($value == 101)
            return 'delivered';
        if ($value == 102)
            return 'cancelled';

        return 'reverted';
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_industry_jobs' => [
                'characterID'          => 'character_id',
                'jobID'                => 'job_id',
                'installerID'          => 'installer_id',
                'facilityID'           => 'facility_id',
                'stationID'            => 'station_id',
                'activityID'           => 'activity_id',
                'blueprintID'          => 'blueprint_id',
                'blueprintTypeID'      => 'blueprint_type_id',
                'blueprintLocationID'  => 'blueprint_location_id',
                'outputLocationID'     => 'output_location_id',
                'runs'                 => 'runs',
                'cost'                 => 'cost',
                'licensedRuns'         => 'licensed_runs',
                'probability'          => 'probability',
                'productTypeID'        => 'product_type_id',
                'status'               => 'status',
                'timeInSeconds'        => 'duration',
                'startDate'            => 'start_date',
                'endDate'              => 'end_date',
                'pauseDate'            => 'pause_date',
                'completedDate'        => 'completed_date',
                'completedCharacterID' => 'completed_character_id',
                'successfulRuns'       => 'successful_runs',
                'created_at'           => 'created_at',
                'updated_at'           => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
