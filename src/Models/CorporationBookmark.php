<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 14:32
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\Bookmark;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CorporationBookmark extends Bookmark implements ICoreUpgrade
{
    public function getUpgradeMapping(): array
    {
        return [
            'corporation_bookmarks' => [
                'corporationID' => 'corporation_id',
                'folderID'      => 'folder_id',
                'bookmarkID'    => 'bookmark_id',
                'creatorID'     => 'creator_id',
                'created'       => 'created',
                'itemID'        => 'item_id',
                'typeID'        => 'type_id',
                'locationID'    => 'location_id',
                'x'             => 'x',
                'y'             => 'y',
                'z'             => 'z',
                'mapName'       => 'map_name',
                'mapID'         => 'map_id',
                'memo'          => 'label',
                'note'          => 'notes',
                'created_at'    => 'created_at',
                'updated_at'    => 'updated_at',
            ],
            'corporation_bookmark_folders' => [
                'corporationID' => 'corporation_id',
                'folderID'      => 'folder_id',
                'folderName'    => 'name',
                'creatorID'     => 'creator_id',
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
