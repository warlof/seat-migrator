<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 11:32
 */

namespace Warlof\Seat\Migrator\Database\Eloquent;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Warlof\Seat\Migrator\Database\Query\Grammars\CustomMySqlGrammar;

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
     * Transfer all date from the collection to the targeted Database according to the model mapping
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

        $model = $this->first();
        $this->getKeyFilter(DB::table($model->getTable()))->update(['upgraded' => true]);
    }

    /**
     * Provide the whereFilter for on a model primary key
     *
     * @param Builder $query
     * @return Builder
     */
    private function getKeyFilter(Builder $query)
    {
        // getting the first element in the collection in order to extract its model structure attributes
        $model = $this->first();

        // determine if the model is using a surrogate key
        // if not, return the filter on the primary key itself
        if (! is_array($model->getKeyName()))
            return $query->whereIn($model->getKeyName(), $this->pluck($model->getKeyName()));

        // otherwise, compute a hash for each model in the collection on all fields composing the surrogate key
        $ids = $this->map(function ($item, $key) use ($model) {
            $hash = [];

            foreach ($model->getKeyName() as $column)
                $hash[] = $item->{$column};

            return md5(implode('', $hash));
        });

        $binding_str = trim(str_repeat('?,', $ids->count()), ',');

        // returning the filter on the surrogate key
        return $query->whereRaw('MD5(CONCAT(' . implode(', ', $model->getKeyName()) . ')) IN (' . $binding_str . ')',
            $ids->toArray());
    }

}
