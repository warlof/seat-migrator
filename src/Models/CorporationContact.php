<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 14:39
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\ContactList;
use Seat\Upgrader\Services\MappingCollection;
use Seat\Upgrader\Traits\HasCompositePrimaryKey;

class CorporationContact extends ContactList implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['corporationID', 'contactID'];

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_contacts' => [
                'corporationID' => 'corporation_id',
                'contactID'     => 'contact_id',
                'standing'      => 'standing',
                'contactTypeID' => 'contact_type',
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
