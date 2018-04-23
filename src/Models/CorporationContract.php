<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 14:49
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\Contract;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CorporationContract extends Contract implements ICoreUpgrade
{

    public function getUpgradeMapping(): array
    {
        return [
            'contract_details' => [
                'contractID'     => 'contract_id',
                'issuerID'       => 'issuer_id',
                'issuerCorpID'   => 'issuer_corporation_id',
                'assigneeID'     => 'assignee_id',
                'acceptorID'     => 'acceptor_id',
                'startStationID' => 'start_location_id',
                'endStationID'   => 'end_location_id',
                'type'           => 'type',
                'status'         => 'status',
                'title'          => 'title',
                'forCorp'        => 'for_corporation',
                'availability'   => 'availability',
                'dateIssued'     => 'date_issued',
                'dateExpired'    => 'date_expired',
                'dateAccepted'   => 'date_accepted',
                'numDays'        => 'days_to_complete',
                'dateCompleted'  => 'date_completed',
                'price'          => 'price',
                'reward'         => 'reward',
                'collateral'     => 'collateral',
                'buyout'         => 'buyout',
                'volume'         => 'volume',
                'created_at'     => 'created_at',
                'updated_at'     => 'updated_at',
            ],
            'corporation_contracts' => [
                'corporationID' => 'corporation_id',
                'contractID'    => 'contract_id',
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
