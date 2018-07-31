<?php

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