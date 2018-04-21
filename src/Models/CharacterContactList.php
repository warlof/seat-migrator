<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 19:01
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\ContactList;
use Seat\Upgrader\Services\MappingCollection;
use Seat\Upgrader\Traits\HasCompositePrimaryKey;

class CharacterContactList extends ContactList implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'contactID'];

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_contacts (character_id, contact_id, standing, contact_type, label_id, is_watched, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->contactID,
            $this->standing,
            $this->contactTypeID,
            $this->labelMask,
            $this->inWatchList,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
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
                'inWatchList'   => 'is_watched',
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
