<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\CharacterSheetCorporationTitles;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterTitle extends CharacterSheetCorporationTitles implements ICoreUpgrade
{

    public function getUpgradeMapping(): array
    {
        return [
            'character_titles' => [
                'characterID' => 'character_id',
                'titleID'     => 'title_id',
                'titleName'   => 'name',
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
