<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 22:13
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Character\MailMessageBody;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class MailBody extends MailMessageBody implements ICoreUpgrade
{

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
