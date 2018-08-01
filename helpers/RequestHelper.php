<?php
/**
 * Created by PhpStorm.
 * User: Jeremy
 * Date: 7/31/2018
 * Time: 9:44 PM
 */

class RequestHelper
{
    /**
     * Request Data
     *
     * @var array
     */
    protected $requestData;

    public function __construct()
    {
        $this->requestData = $_REQUEST;
    }

    public function get($attribute, $default = null)
    {
        return try_get($this->requestData, $attribute, $default);
    }

    public function all()
    {
        return $this->requestData;
    }
}