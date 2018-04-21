<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 18:56
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\ContactListLabel;
use Seat\Upgrader\Services\MappingCollection;
use Seat\Upgrader\Traits\HasCompositePrimaryKey;

class CharacterContactListLabel extends ContactListLabel implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'labelID'];

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO character_contact_labels (character_id, label_id, label_name, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->labelID,
            $this->name,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();

    }

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
