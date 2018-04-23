<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 22:17
 */

namespace Warlof\Seat\Migrator\Models;


use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\MailMessage;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class MailHeader extends MailMessage implements ICoreUpgrade
{

    private static $alliance_ids = [];

    public function upgrade(string $target)
    {
        if (self::$alliance_ids == [])
            self::$alliance_ids = include __DIR__ . '/../Config/alliances.php';

        $sql = "INSERT IGNORE INTO mail_headers (character_id, mail_id, subject, `from`, `timestamp`, is_read, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        DB::connection($target)->insert($sql, [
            $this->characterID,
            $this->messageID,
            $this->title,
            $this->senderID,
            $this->sentDate,
            true,
            $this->created_at,
            $this->updated_at,
        ]);

        $sql = "INSERT IGNORE INTO mail_recipients (mail_id, recipient_id, recipient_type, created_at, updated_at) " .
               "VALUES (?, ?, ?, ?, ?)";

        if (! is_null($this->toCorpOrAllianceID)) {

            $type = 'corporation';

            if ((99000000 <= $this->toCorpOrAllianceID && $this->toCorpOrAllianceID <= 100000000) ||
                (in_array($this->toCorpOrAllianceID, self::$alliance_ids)))
                $type = 'alliance';

            DB::connection($target)->insert($sql, [
                $this->messageID,
                $this->toCorpOrAllianceID,
                $type,
                $this->created_at,
                $this->updated_at,
            ]);
        }

        if (! is_null($this->toListID))
            DB::connection($target)->insert($sql, [
                $this->messageID,
                $this->toListID,
                'mailing_list',
                $this->created_at,
                $this->updated_at,
            ]);

        if (! is_null($this->toCharacterIDs))
            collect(explode(',', $this->toCharacterIDs))->each(function($character_id) use ($sql, $target) {
                DB::connection($target)->insert($sql, [
                    $this->messageID,
                    $character_id,
                    'character',
                    $this->created_at,
                    $this->updated_at,
                ]);
            });

        $this->upgraded = true;
        $this->save();
    }

    public function getUpgradeMapping(): array
    {
        return [
            'mail_headers' => [
                'characterID' => 'character_id',
                'messageID'   => 'mail_id',
                'title'       => 'subject',
                'senderID'    => 'from',
                'sentDate'    => 'timestamp',
                'created_at'  => 'created_at',
                'updated_at'  => 'updated_at',
            ],
            // TODO : implement closure in magic mapper
            //'mail_recipients' => [
            //
            //],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
