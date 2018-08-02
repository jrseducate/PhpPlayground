<?php
/**
 * User: Jeremy
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