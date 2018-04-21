<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 22:01
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\IndustryJob;
use Seat\Upgrader\Traits\HasCompositePrimaryKey;

class CharacterIndustryJob extends IndustryJob implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'jobID'];

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_industry_jobs (character_id, job_id, installer_id, facility_id, station_id, " .
               "activity_id, blueprint_id, blueprint_type_id, blueprint_location_id, output_location_id, runs, cost, " .
               "licensed_runs, probability, product_type_id, status, duration, start_date, end_date, pause_date, " .
               "completed_date, completed_character_id, successful_runs, created_at, updated_at)" .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->jobID,
            $this->installerID,
            $this->facilityID,
            $this->stationID,
            $this->activityID,
            $this->blueprintID,
            $this->blueprintTypeID,
            $this->blueprintLocationID,
            $this->outputLocationID,
            $this->runs,
            $this->cost,
            $this->licensedRuns,
            $this->probability,
            $this->productTypeID,
            $this->status,
            $this->timeInSeconds,
            $this->startDate,
            $this->endDate,
            $this->pauseDate,
            $this->completedDate,
            $this->completedCharacterID,
            $this->successfulRuns,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

}
