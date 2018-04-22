<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 14:32
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\Corporation\Bookmark;
use Seat\Upgrader\Services\MappingCollection;

class CorporationBookmark extends Bookmark implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'corporation_bookmarks' => [
                'corporationID' => 'corporation_id',
                'folderID'      => 'folder_id',
                'bookmarkID'    => 'bookmark_id',
                'creatorID'     => 'creator_id',
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
                'foolderID'     => 'folder_id',
                'folderName'    => 'name',
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
