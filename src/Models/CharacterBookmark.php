<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 10:35
 */

namespace Seat\Upgrader\Models;

use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\Bookmark;
use Seat\Upgrader\Services\MappingCollection;

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
                'created_at'  => 'created_at',
                'updated_at'  => 'updated_at',
            ],
        ];
    }

    public function upgrade(string $target)
    {
        $sql = $this->getUpgradeQuery();

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->bookmarkID,
            $this->folderID,
            $this->created,
            $this->creatorID,
            $this->itemID,
            $this->memo,
            $this->locationID,
            $this->note,
            $this->typeID,
            $this->x,
            $this->y,
            $this->z,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();

    }

}
