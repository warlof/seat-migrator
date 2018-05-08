<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\Bookmark;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CharacterBookmark extends Bookmark implements ICoreUpgrade
{

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }

    public function getUpgradeMapping() : array
    {
        return [
            'character_bookmarks' => [
                'characterID' => 'character_id',
                'bookmarkID'  => 'bookmark_id',
                'folderID'    => 'folder_id',
                'created'     => 'created',
                'creatorID'   => 'creator_id',
                'itemID'      => 'item_id',
                'memo'        => 'label',
                'locationID'  => 'location_id',
                'note'        => 'notes',
                'typeID'      => 'type_id',
                'x'           => 'x',
                'y'           => 'y',
                'z'           => 'z',
                'mapID'       => 'map_id',
                'mapName'     => 'map_name',
                'created_at'  => 'created_at',
                'updated_at'  => 'updated_at',
            ],
            'character_bookmark_folders' => [
                'characterID' => 'character_id',
                'folderID'    => 'folder_id',
                'folderName'  => 'name',
                'created_at'  => 'created_at',
                'updated_at'  => 'updated_at',
            ],
        ];
    }

}
