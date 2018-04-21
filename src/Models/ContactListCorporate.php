<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 12:09
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Upgrader\Services\MappingCollection;
use Seat\Upgrader\Traits\HasCompositePrimaryKey;

class ContactListCorporate extends \Seat\Eveapi\Models\Character\ContactListCorporate implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'corporationID', 'contactID'];

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO corporation_contacts (corporation_id, contact_id, standing, contact_type, label_id, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->corporationID,
            $this->contactID,
            $this->standing,
            $this->contactTypeID,
            $this->labelMask,
            $this->created_at,
            $this->updated_at
        ]);

        $this->upgraded = true;
        $this->save();
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
