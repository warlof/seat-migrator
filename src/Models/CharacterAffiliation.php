<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Warlof\Seat\Migrator\Models;

use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterAffiliation extends \Seat\Eveapi\Models\Eve\CharacterAffiliation implements ICoreUpgrade
{

    public function getUpgradeMapping(): array
    {
        return [
            'character_affiliations' => [
                'characterID'   => 'character_id',
                'corporationID' => 'corporation_id',
                'allianceID'    => 'alliance_id',
                'factionID'     => 'faction_id',
                'created_at'    => 'created_at',
                'updated_at'    => 'updated_at',
            ]
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
