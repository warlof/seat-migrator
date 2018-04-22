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
    /**
     * MappingCollection constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    /**
     * Transfer all date from the collection to the targeted database according to the model mapping
     *
     * @param string $target
     */
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

                foreach ($columns as $src_column => $dst_column) {
                    if (is_array($dst_column)) {
                        foreach ($dst_column as $column)
                            $new[$column] = $item->{$src_column};
                    } else {
                        $new[$dst_column] = $item->{$src_column};
                    }
                }

                return $new;
            })->toArray());
        }

        $this->flagSource();
    }

    /**
     * Update all source records based on model definition
     */
    private function flagSource()
    {
        if ($this->count() < 1)
            return;

        $artifact = $this->first();

        if (is_array($artifact->getKeyName())) {
            $ids = $this->map(function ($item, $key) use ($artifact) {
                $hash = [];

                foreach ($artifact->getKeyName() as $column)
                    $hash[] = $item->{$column};

                return md5(implode('', $hash));
            });

            $binding_str = trim(str_repeat('?,', $ids->count()), ',');

            DB::table($artifact->getTable())
                ->whereRaw('MD5(CONCAT(' . implode(', ', $artifact->getKeyName()) . ')) IN (' . $binding_str . ')',
                    $ids->toArray())
                ->update(['upgraded' => true]);

            return;
        }

        DB::table($artifact->getTable())
            ->whereIn($artifact->getKeyName(), $this->pluck($artifact->getKeyName()))
            ->update(['upgraded' => true]);
    }

}
