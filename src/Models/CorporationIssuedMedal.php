<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 15:23
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\Medal;
use Seat\Eveapi\Models\Corporation\MemberMedal;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CorporationIssuedMedal extends MemberMedal implements ICoreUpgrade
{

    public function getTitleAttribute()
    {
        $medal = Medal::find($this->medalID);

        if (! is_null($medal))
            return $medal->title;

        return '';
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
            'character_medals' => [
                'characterID'   => 'character_id',
                'medalID'       => 'medal_id',
                'title'         => 'title',
                'reason'        => 'reason',
                'corporationID' => 'corporation_id',
                'issuerID'      => 'issuer_id',
                'issued'        => 'date',
                'status'        => 'status',
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
