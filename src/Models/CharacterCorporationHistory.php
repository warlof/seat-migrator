<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 17:56
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Eve\CharacterInfoEmploymentHistory;
use Seat\Upgrader\Services\MappingCollection;
use Seat\Upgrader\Traits\HasCompositePrimaryKey;

class CharacterCorporationHistory extends CharacterInfoEmploymentHistory implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'recordID'];

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_corporation_histories' => [
                'characterID'   => 'character_id',
                'recordID'      => 'record_id',
                'corporationID' => 'corporation_id',
                'startDate'     => 'start_date',
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
