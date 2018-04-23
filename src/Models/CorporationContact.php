<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 14:39
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\ContactList;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class CorporationContact extends ContactList implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['corporationID', 'contactID'];

    public function getContactTypeIDAttribute($value)
    {
        if ($value == 2)
            return 'corporation';

        if ($value == 16159)
            return 'alliance';

        if ($value == 30)
            return 'faction';

        return 'character';
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
