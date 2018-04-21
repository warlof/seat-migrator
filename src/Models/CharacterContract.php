<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 19:30
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\Contract;

class CharacterContract extends Contract implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO contract_details (contract_id, issuer_id, issuer_corporation_id, assignee_id, acceptor_id, " .
               "start_location_id, end_location_id, `type`, status, title, for_corporation, availability, date_issued, " .
               "date_expired, date_accepted, days_to_complete, date_completed, price, reward, collateral, buyout, volume, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->contractID,
            $this->issuerID,
            $this->issuerCorpID,
            $this->assigneeID,
            $this->acceptorID,
            $this->startStationID,
            $this->endStationID,
            $this->type,
            $this->status,
            $this->title,
            $this->forCorp,
            $this->availability,
            $this->dateIssued,
            $this->dateExpired,
            $this->dateAccepted,
            $this->numDays,
            $this->dateCompleted,
            $this->price,
            $this->reward,
            $this->collateral,
            $this->buyout,
            $this->volume,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

}
