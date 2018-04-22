<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 18:16
 */

namespace Seat\Upgrader\Models;


use Seat\Eveapi\Models\KillMail\Attacker;
use Seat\Eveapi\Models\KillMail\Detail;
use Seat\Upgrader\Services\MappingCollection;

class Killmail extends Detail implements ICoreUpgrade
{

    /**
     * Compute killmail hash required by ESI
     *
     * Special thanks to Kali Izia https://forums-archive.eveonline.com/message/4900335/#post4900335
     *
     * @return string
     */
    public function getHashAttribute()
    {
        $final_blow = $this->attackers->where('finalBlow', true)->first();

        return sha1(implode('', [
            ($this->characterID == 0) ? 'None' : $this->characterID,
            ($final_blow->characterID == 0) ? 'None' : $final_blow->characterID,
            $this->shipTypeID,
            (carbon($this->killTime)->timestamp * 10000000) + 116444736000000000,
        ]));
    }

    public function attackers()
    {
        return $this->hasMany(Attacker::class, 'killID', 'killID');
    }

    public function upgrade(string $target)
    {
        // TODO: Implement upgrade() method.
    }

    public function getUpgradeMapping(): array
    {
        return [
            'character_killmails' => [
                'characterID' => 'character_id',
                'killID'      => 'killmail_id',
                'hash'        => 'killmail_hash',
                'created_at'  => 'created_at',
                'updated_at'  => 'updated_at',
            ],
            'corporation_killmails' => [
                'corporationID' => 'corporation_id',
                'killID'        => 'killmail_id',
                'hash'          => 'killmail_hash',
                'created_at'    => 'created_at',
                'updated_at'    => 'updated_at',
            ],
            'killmail_details' => [
                'killID'        => 'killmail_id',
                'killTime'      => 'killmail_time',
                'solarSystemID' => 'solar_system_id',
                'moonID'        => 'moon_id',
                'created_at'    => 'created_at',
                'updated_at'    => 'updated_at',
            ],
            'killmail_victims' => [
                'killID'        => 'killmail_id',
                'characterID'   => 'character_id',
                'corporationID' => 'corporation_id',
                'allianceID'    => 'alliance_id',
                'factionID'     => 'faction_id',
                'damageTaken'   => 'damage_taken',
                'shipTypeID'    => 'ship_type_id',
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
