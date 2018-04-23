<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 09:30
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\MailingListInfo;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class MailingList extends \Seat\Eveapi\Models\Character\MailingList implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'listID'];

    public function getDisplayNameAttribute()
    {
        $mailing_list = MailingListInfo::find($this->listID);

        if (is_null($mailing_list))
            return '';

        $mailing_list->upgraded = true;
        $mailing_list->save();
        return $mailing_list->displayName;
    }

    public function getUpgradeMapping(): array
    {
        return [
            'mail_mailing_lists' => [
                'characterID' => 'character_id',
                'listID'      => 'mailing_list_id',
                'displayName' => 'name',
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
