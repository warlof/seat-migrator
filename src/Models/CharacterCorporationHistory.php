<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 17:56
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Eve\CharacterInfoEmploymentHistory;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class CharacterCorporationHistory extends CharacterInfoEmploymentHistory implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'recordID'];

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
