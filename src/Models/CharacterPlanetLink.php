<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 12:09
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\PlanetaryLink;
use Seat\Upgrader\Services\MappingCollection;
use Seat\Upgrader\Traits\HasCompositePrimaryKey;

class CharacterPlanetLink extends PlanetaryLink implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['ownerID', 'planetID', 'sourcePinID', 'destinationPinID'];

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_planet_links (character_id, planet_id, source_pin_id, destination_pin_id, " .
               "link_level, created_at, updated_at)" .
               "VALUES (?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->ownerID,
            $this->planetID,
            $this->sourcePinID,
            $this->destinationPinID,
            $this->linkLevel,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_planet_links' => [
                'ownerID'          => 'character_id',
                'planetID'         => 'planet_id',
                'sourcePinID'      => 'source_pin_id',
                'destinationPinID' => 'destination_pin_id',
                'linkLevel'        => 'link_level',
                'created_at'       => 'created_at',
                'updated_at'       => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
