<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 22/04/2018
 * Time: 16:04
 */

namespace Warlof\Seat\Migrator\Models;


use Seat\Eveapi\Models\Corporation\StarbaseDetail;
use Warlof\Seat\Migrator\Database\Eloquent\MappingCollection;

class CorporationStarbaseDetail extends StarbaseDetail implements ICoreUpgrade
{
    public function getUpgradeMapping(): array
    {
        return [
            'corporation_starbase_details' => [
                'corporationID'           => 'corporation_id',
                'itemID'                  => 'starbase_id',
                'onlineTimestamp'         => 'online',
                'allowCorporationMembers' => 'allow_corporation_members',
                'allowAllianceMembers'    => 'allow_alliance_members',
                'useStandingsFrom'        => 'use_alliance_standings',
                'onStandingDrop'          => 'attack_standing_threshold',
                'onStatusDropEnabled'     => 'attack_security_status_threshold',
                'onAggression'            => 'attack_if_other_security_status_dropping',
                'onCorporationWar'        => 'attack_if_at_war',
                'created_at'              => 'created_at',
                'updated_at'              => 'updated_at',
            ],
        ];
    }

    public function newCollection(array $models = [])
    {
        return new MappingCollection($models);
    }
}
