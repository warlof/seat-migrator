<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 26/04/2018
 * Time: 21:01
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\AssetListContents;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class CorporationAssetListContent extends AssetListContents implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['corporationID', 'itemID'];

    private static $location_flag;

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
        if (is_null(self::$location_flag))
            self::$location_flag = include __DIR__ . '/../Config/corporation_location_flags.php';

        return self::$location_flag[$value];
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_assets' => [
                'corporationID' => 'corporation_id',
                'itemID'        => 'item_id',
                'typeID'        => 'type_id',
                'quantity'      => 'quantity',
                'locationID'    => 'location_id',
                'locationType'  => 'location_type',
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
