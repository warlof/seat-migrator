<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 09:30
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\MailingListInfo;
use Seat\Upgrader\Traits\HasCompositePrimaryKey;

class MailingList extends \Seat\Eveapi\Models\Character\MailingList implements ICoreUpgrade
{

    use HasCompositePrimaryKey;

    protected $primaryKey = ['characterID', 'listID'];

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO mail_mailing_lists (character_id, mailing_list_id, `name`, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?)";

        $info = MailingListInfo::find($this->listID);

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->listID,
            $info->displayName,
            $this->created_at,
            $this->updated_at,
        ]);

        $info->upgraded = true;
        $info->save();

        $this->upgraded = true;
        $this->save();
    }

}
