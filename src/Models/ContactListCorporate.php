<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 12:09
 */

namespace Warlof\Seat\Migrator\Models;


use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class ContactListCorporate extends \Seat\Eveapi\Models\Character\ContactListCorporate implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'corporationID', 'contactID'];

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
                'labelMask'     => 'label_id',
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
