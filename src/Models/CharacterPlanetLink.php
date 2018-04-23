<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 12:09
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\PlanetaryLink;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class CharacterPlanetLink extends PlanetaryLink implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['ownerID', 'planetID', 'sourcePinID', 'destinationPinID'];

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
