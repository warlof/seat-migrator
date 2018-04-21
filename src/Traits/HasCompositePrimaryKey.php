<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 20/04/2018
 * Time: 18:52
 */

namespace Seat\Upgrader\Traits;


use Illuminate\Database\Eloquent\Builder;

trait HasCompositePrimaryKey
{

    /**
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    protected function setKeysForSaveQuery(Builder $query)
    {
        if (is_array($this->getKeyName())) {
            foreach ((array) $this->getKeyName() as $keyField)
                $query->where($keyField, '=', $this->original[$keyField]);

            return $query;
        }

        return parent::setKeysForSaveQuery($query);
    }

}
