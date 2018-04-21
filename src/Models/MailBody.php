<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 22:13
 */

namespace Seat\Upgrader\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\MailMessageBody;
use Seat\Upgrader\Services\MappingCollection;

class MailBody extends MailMessageBody implements ICoreUpgrade
{

    public function upgrade(string $target)
    {
        $sql = "INSERT IGNORE INTO mail_bodies (mail_id, body, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->messageID,
            $this->body,
            $this->created_at,
            $this->updated_at,
        ]);

        $this->upgraded = true;
        $this->save();
    }

    public function getUpgradeMapping(): array
    {
        return [
            'mail_bodies' => [
                'messageID'  => 'mail_id',
                'body'       => 'body',
                'created_at' => 'created_at',
                'updated_at' => 'updated_at'
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
