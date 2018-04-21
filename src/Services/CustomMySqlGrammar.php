<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 21/04/2018
 * Time: 16:54
 */

namespace Seat\Upgrader\Services;


use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\MySqlGrammar;

class CustomMySqlGrammar extends MySqlGrammar
{

    public function compileInsert(Builder $query, array $values)
    {
        $table = $this->wrapTable($query->from);

        if (! is_array(reset($values)))
            $values = [$values];

        $columns = $this->columnize(array_keys(reset($values)));

        $parameters = collect($values)->map(function($record) {

            return '(' . $this->parameterize($record) . ')';

        })->implode(', ');

        return 'INSERT IGNORE INTO ' . $table . '(' . $columns . ') VALUES ' . $parameters;
    }

}
