<?php
/**
 * Created by PhpStorm.
 * User: Warlof Tutsimo
 * Date: 19/04/2018
 * Time: 11:32
 */

namespace Seat\Upgrader\Services;


//use Illuminate\Support\Collection;
use \Illuminate\Database\Eloquent\Collection;

class MappingCollection extends Collection
{

    private $mapping = [];

    public function __construct($mapping, $items = [])
    {
        $this->mapping = $mapping;

        parent::__construct($items);
    }

    public function chunk($size)
    {
        if ($size <= 0) {
            return new static($this->mapping, $this->items);
        }

        $chunks = [];

        foreach (array_chunk($this->items, $size, true) as $chunk) {
            $chunks[] = new static($this->mapping, $chunk);
        }

        return new static($this->mapping, $chunks);
    }

    public function toArray()
    {

        $array = [];

        foreach ($this as $model) {

            $element = [];

            foreach ($this->mapping as $from => $to) {

                $element[$to] = $model->{$from};

            }

            $array[] = $element;

        }

        return $array;

    }

}
