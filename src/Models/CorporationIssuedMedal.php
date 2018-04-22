<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:23
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\MemberMedal;
use Seat\Upgrader\Services\MappingCollection;

class CorporationIssuedMedal extends MemberMedal implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_issued_medals' => [
                'corporationID' => 'corporation_id',
                'medalID'       => 'medal_id',
                'characterID'   => 'character_id',
                'reason'        => 'reason',
                'status'        => 'status',
                'issuerID'      => 'issuer_id',
                'issued'        => 'issued_at',
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
