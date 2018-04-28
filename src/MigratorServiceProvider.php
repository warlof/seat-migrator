<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 16/04/2018
 * Time: 17:11
 */

namespace Warlof\Seat\Migrator;

use Illuminate\Support\ServiceProvider;
use Warlof\Seat\Migrator\Commands\Upgrade;
use Warlof\Seat\Migrator\Commands\Version;

class MigratorServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->addCommands();
        $this->addPublications();
    }

    private function addCommands()
    {
        $this->commands([
            Upgrade::class,
            Version::class,
        ]);
    }

    private function addPublications()
    {
        $this->publishes([
            __DIR__ . '/Database/migrations/' => database_path('migrations'),
        ]);
    }
}
