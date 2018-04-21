<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 16/04/2018
 * Time: 17:11
 */

namespace Seat\Upgrader;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Seat\Upgrader\Commands\SchemaUpgrade;

class UpgraderServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->addCommands();
        $this->addPublications();

        //$this->debug();
    }

    private function addCommands()
    {
        $this->commands([
            SchemaUpgrade::class,
        ]);
    }

    private function addPublications()
    {
        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations'),
        ]);
    }

    private function debug()
    {
        DB::listen(function($q){
            $positional = 0;
            $full_query = '';

            foreach (str_split($q->sql) as $char)
                if ($char === '?') {
                    $value = $q->bindings[$positional];

                    if (is_scalar($value))
                        $full_query = $full_query . '"' . $value . '"';
                    else
                        $full_query = $full_query . '[' . gettype($value) . ']';

                    $positional++;
                } else
                    $full_query = $full_query . $char;

            logger()->debug(' ---> QUERY DEBUG:' . $full_query . ' <----');
        });
    }

}
