<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 11:32
 */

namespace Seat\Upgrader\Services;


use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MappingCollection extends Collection
{

    //private $mapping = [];

    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    public function upgrade(string $target)
    {
        if ($this->count() < 1)
            return;

        $artifact = $this->first();

        $mapping = call_user_func([$artifact, 'getUpgradeMapping']);

        DB::connection($target)->setQueryGrammar(new CustomMySqlGrammar());

        foreach ($mapping as $table => $columns) {
            DB::connection($target)->table($table)->insert($this->map(function($item, $key) use ($columns) {
                $new = [];

                foreach ($columns as $src_column => $dst_column)
                    $new[$dst_column] = $item->{$src_column};

                return $new;
            })->toArray());
        }

        if (is_array($artifact->getKeyName())) {
            DB::table($artifact->getTable())
                ->whereRaw('MD5(CONCAT(' . implode(', ', $artifact->getKeyName()) . ')) IN (?)',
                    $this->map(function($item, $key) use ($artifact) {
                        $hash = [];

                        foreach ($artifact->getKeyName() as $column)
                            $hash[] = $item->{$column};

                        return md5(implode('', $hash));
                    })->implode('", "'))
                ->update(['upgraded' => true]);
        } else {
            DB::table($artifact->getTable())
                ->whereIn($artifact->getKeyName(), $this->pluck($artifact->getKeyName()))
                ->update(['upgraded' => true]);
        }
    }

}
