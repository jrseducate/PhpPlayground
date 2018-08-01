<?php

return [
    'dashboard_index'   => buildArray()->url('/dashboard')->call('DashboardController@index')->get(),
    'test_index'        => buildArray()->url('/test')->call('TestController@test')->get(),
    'index'             => buildArray()->url('/*')->call('TestController@index')->get(),
];