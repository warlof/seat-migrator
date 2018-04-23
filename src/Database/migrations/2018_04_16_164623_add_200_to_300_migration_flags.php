<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 16/04/2018
 * Time: 16:46
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Add200To300MigrationFlags extends Migration
{

    private $core_tables = [];

    private $ignored_tables = [
        // users
        // sde
        // acl
    ];

    public function up()
    {
        // retrieve all installed table
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

        // looping over fetched list and append an extra column called "upgraded" which determine if data inside a table
        // has been successfully upgraded to the new format
        foreach ($tables as $table) {
            if (in_array($table, $this->ignored_tables))
                continue;

            Schema::table($table, function(Blueprint $blueprint) {

                echo sprintf("Preparing %s for upgrade...\r\n", $blueprint->getTable());

                $blueprint->boolean('upgraded')->default(false);

                // add an index tied to the new column in order to improve upgrading scale on the flow
                $blueprint->index('upgraded');

            });
        }
    }

    public function down()
    {

        // retrieve all installed table
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

        // looping over fetched list and remove the extra column called "upgraded" which determine if data inside a table
        // has been successfully upgraded to the new format
        foreach ($this->core_tables as $table) {
            if (in_array($table, $this->ignored_tables))
                continue;

            Schema::table($table, function(Blueprint $blueprint) use ($table) {

                $blueprint->dropIndex(sprintf('%s_upgraded', $table));
                $blueprint->dropColumn('upgraded');

            });
        }
    }

}
