<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 19:30
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\Contract;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterContract extends Contract implements ICoreUpgrade
{

    public function getTypeAttribute($value)
    {
        if ($value == 'ItemExchange')
            return 'item_exchange';

        if ($value == 'Courier')
            return 'courier';

        if ($value == 'Auction')
            return 'auction';

        return 'unknown';
    }

    public function getStatusAttribute($value)
    {
        if ($value == 'Completed')
            return 'finished';

        if ($value == 'Failed')
            return 'failed';

        if ($value == 'Deleted')
            return 'deleted';

        if ($value == 'Outstanding')
            return 'outstanding';

        if ($value == 'InProgress')
            return 'in_progress';

        if ($value == 'CompletedByContractor')
            return 'finished_contractor';

        if ($value == 'Rejected')
            return 'rejected';

        return 'reversed';
    }

    public function getAvailabilityAttribute($value)
    {
        if ($value == 'Private')
            return 'personal';

        if ($value == 'Public')
            return 'public';

        return 'corporation';
    }

    public function getUpgradeMapping(): array
    {
        return [
            'contract_details' => [
                'contractID'      => 'contract_id',
                'issuerID'        => 'issuer_id',
                'issuerCorpID'    => 'issuer_corporation_id',
                'assigneeID'      => 'assignee_id',
                'acceptorID'      => 'acceptor_id',
                'startStationID'  => 'start_location_id',
                'endStationID'    => 'end_location_id',
                'type'            => 'type',
                'status'          => 'status',
                'title'           => 'title',
                'forCorp'         => 'for_corporation',
                'availability'    => 'availability',
                'dateIssued'      => 'date_issued',
                'dateExpired'     => 'date_expired',
                'dateAccepted'    => 'date_accepted',
                'numDays'         => 'days_to_complete',
                'dateCompleted'   => 'date_completed',
                'price'           => 'price',
                'reward'          => 'reward',
                'collateral'      => 'collateral',
                'buyout'          => 'buyout',
                'volume'          => 'volume',
                'created_at'      => 'created_at',
                'updated_at'      => 'updated_at',
            ],
            'character_contracts' => [
                'characterID' => 'character_id',
                'contractID'  => 'contract_id',
                'created_at'  => 'created_at',
                'updated_at'  => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
