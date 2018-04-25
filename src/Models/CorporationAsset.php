<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 14:24
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\AssetList;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class CorporationAsset extends AssetList implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    private static $location_flags;

    protected $primaryKey = ['corporationID', 'itemID'];

    public function getLocationTypeAttribute()
    {
        if ((60000000 <= $this->locationID && $this->locationID <= 64000000) ||
            (68000000 <= $this->locationID && $this->locationID <= 70000000))
            return 'station';

        if (30000000 <= $this->locationID && $this->locationID <= 32000000)
            return 'solar_system';

        return 'other';
    }

    public function getFlagAttribute($value)
    {
        if (is_null(self::$location_flags))
            self::$location_flags = include __DIR__ . '/../Config/corporation_location_flags.php';

        return self::$location_flags[$value];
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_assets' => [
                'corporationID' => 'corporation_id',
                'itemID'        => 'item_id',
                'locationID'    => 'location_id',
                'locationType'  => 'location_type',
                'typeID'        => 'type_id',
                'quantity'      => 'quantity',
                'flag'          => 'location_flag',
                'singleton'     => 'is_singleton',
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
