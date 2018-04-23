<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 19:49
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\KillMail\Item;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class KillmailItem extends Item implements ICoreUpgrade
{
    use HasCompositePrimaryKey;

    protected $primaryKey = ['killID', 'typeID'];

    public function getUpgradeMapping(): array
    {
        return [
            'killmail_victim_items' => [
                'killID'       => 'killmail_id',
                'typeID'       => 'item_type_id',
                'qtyDestroyed' => 'quantity_destroyed',
                'qtyDropped'   => 'quantity_dropped',
                'singleton'    => 'singleton',
                'flag'         => 'flag',
                'created_at'   => 'created_at',
                'updated_at'   => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
