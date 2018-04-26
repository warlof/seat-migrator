<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 26/04/2018
 * Time: 21:32
 */

namespace Warlof\Seat\Migrator\Commands;


use Illuminate\Console\Command;

class Version extends Command
{

    protected $signature = 'seat:migrator:version';

    protected $description = 'Display the package version';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $config = include __DIR__ . '/../Config/migrator.config.php';

        $this->info($config['version']);
    }

}
