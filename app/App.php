<?php
/**
 * Created by PhpStorm.
 * User: Jeremy
 * Date: 7/30/2018
 * Time: 8:45 PM
 */

class App
{
    public function __construct()
    {
    }

    public function init()
    {
        $this->loadHelpersFile();
        $this->loadConfig('config');
        $this->loadConfig('database');
        $this->loadConfig('helpers');
        $this->loadConfigHelpers();
        $this->loadEnv();
    }

    public function config($path)
    {
        return try_get($this->config, $path);
    }

    public function env($path)
    {
        return try_get($this->env, $path);
    }

    protected $env = [];

    public function loadEnv()
    {
        $envFile = BASE_DIR . DS . '.env';
        $envText = read_file($envFile);
        if(empty($envText))
        {
            return;
        }
        $env     = [];
        foreach(explode("\n", $envText) as $line => $envLine)
        {
            $equalCount = substr_count($envLine, '=');
            if($equalCount > 1)
            {
                error('.env file has more than one \'=\' on line ' . $line);
            }
            if($equalCount < 1)
            {
                error('.env file has more no \'=\' on line ' . $line);
            }
            $key = null;
            $value = null;
            list($key, $value) = explode('=', $envLine);
            if(!$key)
            {
                error('.env file has an invalid key (key=value) on line ' . $line);
            }
            $env[$key] = $value;
        }

        $this->env = array_replace($this->env, $env);
    }

    public function loadHelpersFile()
    {
        $helperFile = HELPER_DIR . DS . 'helpers.php';
        include_file($helperFile);
    }

    public function loadConfigHelpers()
    {
        $helperFiles = config('helpers.include');
        foreach($helperFiles as $helperFile)
        {
            $helperFile = HELPER_DIR . DS . $helperFile . '.php';
            include_file($helperFile);
        }
    }

    private $config = [];

    public function loadConfig($name)
    {
        $configPath = CONFIG_DIR . DS . $name . '.php';
        if(file_exists($configPath))
        {
            $config = require_file($configPath);
            if(!empty($config))
            {
                $config = [$name => $config];
                $this->config = array_replace($this->config, $config);
            }
        }
    }
}