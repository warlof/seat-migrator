<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 12:10
 */

namespace Seat\Upgrader\Models;


interface ICoreUpgrade
{

    public function upgrade(string $target);

}
