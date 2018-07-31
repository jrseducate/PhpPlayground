<?php

class TestController
{
    function index()
    {
        echo view('index', ['true' => 0]);
    }
}