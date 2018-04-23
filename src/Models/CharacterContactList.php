<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 19:01
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\ContactList;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class CharacterContactList extends ContactList implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'contactID'];

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
            'character_contacts' => [
                'characterID'   => 'character_id',
                'contactID'     => 'contact_id',
                'standing'      => 'standing',
                'contactTypeID' => 'contact_type',
                'labelMask'     => 'label_id',
                'inWatchlist'   => 'is_watched',
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
