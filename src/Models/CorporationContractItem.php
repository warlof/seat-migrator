<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 14:44
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\ContractItem;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class CorporationContractItem extends ContractItem implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['corporationID', 'contractID', 'recordID'];

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
