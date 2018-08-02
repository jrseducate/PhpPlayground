<?php
/**
 * User: Jeremy
 */

class BuildArrayHelper
{
    protected $array = [];

    public function __construct()
    {
    }

    public function __call($name, $arguments)
    {
        $this->array[$name] = count($arguments) > 1 ? $arguments : array_first($arguments);

        return $this;
    }

    public function get()
    {
        return $this->array;
    }
}