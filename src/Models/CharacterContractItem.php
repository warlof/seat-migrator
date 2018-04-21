<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 19:58
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\ContractItems;
use Seat\Upgrader\Services\MappingCollection;
use Seat\Upgrader\Traits\HasCompositePrimaryKey;

class CharacterContractItem extends ContractItems implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['contractID', 'recordID'];

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO contract_items (contract_id, record_id, type_id, quantity, raw_quantity, " .
               "is_singleton, is_included, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->contractID,
            $this->recordID,
            $this->typeID,
            $this->quantity,
            $this->rawQuantity,
            $this->singleton,
            $this->included,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

    public function getUpgradeMapping(): array
    {
        return [
            'contract_items' => [
                'contractID'  => 'contract_id',
                'recordID'    => 'record_id',
                'typeID'      => 'type_id',
                'quantity'    => 'quantity',
                'rawQuantity' => 'raw_quantity',
                'singleton'   => 'is_singleton',
                'included'    => 'is_included',
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
