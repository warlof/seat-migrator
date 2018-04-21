<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Seat\Upgrader\Models;

use Illuminate\Support\Facades\DB;
use Seat\Upgrader\Services\MappingCollection;

class CharacterAffiliation extends \Seat\Eveapi\Models\Eve\CharacterAffiliation implements ICoreUpgrade
{
    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_affiliations (character_id, corporation_id, alliance_id, faction_id, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->corporationID,
            $this->allianceID,
            $this->factionID,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

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
