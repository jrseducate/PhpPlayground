<?php
/**
 * User: Jeremy
 */

class TestController
{
    function index()
    {
        echo view('index', ['true' => 0]);
    }
    function test()
    {
        dd('HERE!');
    }
}