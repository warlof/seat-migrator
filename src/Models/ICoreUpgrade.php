<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 12:10
 */

namespace Warlof\Seat\Migrator\Models;


interface ICoreUpgrade
{

    public function getUpgradeMapping() : array;

}
