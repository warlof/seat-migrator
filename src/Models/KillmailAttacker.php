<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 19:31
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\KillMail\Attacker;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;
use Warlof\Seat\Migrator\Traits\HasCompositePrimaryKey;

class KillmailAttacker extends Attacker implements ICoreUpgrade
{
    use HasCompositePrimaryKey;

    protected $primaryKey = ['killID', 'characterID'];

    public function getUpgradeMapping(): array
    {
        return [
            'killmail_attackers' => [
                'killID'         => 'killmail_id',
                'characterID'    => 'character_id',
                'corporationID'  => 'corporation_id',
                'allianceID'     => 'alliance_id',
                'factionID'      => 'faction_id',
                'securityStatus' => 'security_status',
                'finalBlow'      => 'final_blow',
                'damageDone'     => 'damage_done',
                'shipTypeID'     => 'ship_type_id',
                'weaponTypeID'   => 'weapon_type_id',
                'created_at'     => 'created_at',
                'updated_at'     => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
