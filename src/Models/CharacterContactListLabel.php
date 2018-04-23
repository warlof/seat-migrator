<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 18:56
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\ContactListLabel;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class CharacterContactListLabel extends ContactListLabel implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'labelID'];

    public function getUpgradeMapping(): array
    {
        return [
            'character_contact_labels' => [
                'characterID' => 'character_id',
                'labelID'     => 'label_id',
                'name'        => 'label_name',
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
