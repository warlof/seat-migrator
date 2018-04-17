<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 16/04/2018
 * Time: 17:11
 */

namespace Seat\Upgrader;


use Illuminate\Support\ServiceProvider;
use Seat\Upgrader\Commands\SchemaUpgrade;

class UpgraderServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->addCommands();
    }

    private function addCommands()
    {
        $this->commands([
            SchemaUpgrade::class,
        ]);
    }

}