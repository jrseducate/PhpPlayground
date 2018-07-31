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
        $this->loadConfig('routes');
        $this->loadConfigHelpers();
        $this->loadEnv();
        return $this->directTraffic();
    }

    public function config($path)
    {
        return try_get($this->config, $path);
    }

    public function env($path)
    {
        return try_get($this->env, $path);
    }

    public function view($name, $with = [])
    {
        $html = read_file(VIEW_DIR . DS . $name . '.blade.php');
        $html = $this->parseView($name, $html, $with);
        $html = $this->parseView($name, $html, $with, false, '<?php', '?>');

        return $html;
    }

    public function parseView($name, $html, $with = [], $expectReturn = true, $tagStart = '{!!', $tagEnd = '!!}')
    {
        foreach($with as $key => $value)
        {
            $$key = $value;
        }

        $count = 0;

        $tagStartIndex = 0;
        $tagEndIndex = 0;
        do
        {
//            if($count > 5)
//            {
//                dump($tagStartIndex);
//                dump($tagEndIndex);
//                dd($html);
//            }

            $tagStartIndex  = strpos($html, $tagStart, min($tagStartIndex, strlen($html)));
            $tagEndIndex    = strpos($html, $tagEnd, min($tagEndIndex, strlen($html)));
            $validTags      = $tagStartIndex !== false && $tagEndIndex !== false;
            if($validTags)
            {
                $tagStartIndex += strlen($tagStart);
                $code = trim(substr($html, $tagStartIndex, $tagEndIndex - $tagStartIndex), " \t\n\r \v;");
                $code = $expectReturn ? "return ($code);" : $code . ';';
                try
                {
                    $result = eval($code);
                }
                catch(\Error $exception)
                {
                    error("Failed to eval view '$name' char indexes $tagStartIndex - $tagEndIndex");
                }
                $result = $expectReturn ? $result : '';
                if(is_string($result))
                {
                    $html = substr($html, 0, $tagStartIndex - strlen($tagStart)) . $result . substr($html, $tagEndIndex + strlen($tagEnd));
                }
            }
//            $tagStartIndex  -= strlen($tagStart);
//            $tagEndIndex    -= strlen($tagStart) + strlen($tagEnd);
            $count++;
        } while($validTags);

        return $html;
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

    public function directTraffic()
    {
        $url            = $_SERVER['REQUEST_URI'];
        $routes         = config('routes');
        $routeChosen    = null;
        $routeAction    = null;

        if(!empty($url))
        {
            $questionMark = strpos($url, '?');
            if($questionMark !== false)
            {
                $url = substr($url, 0, $questionMark);
            }
        }

        $url = explode('/', trim($url, '/'));

        foreach($routes as $route => $action)
        {
            $validRoute = true;
            $route      = explode('/', trim($route, '/'));
            foreach($route as $index => $routePart)
            {
                $urlPart    = try_get($url, $index, '');
                $validRoute &= $urlPart == $routePart || $routePart == '*';
            }

            if($validRoute)
            {
                $routeChosen = implode('/', $route);
                $routeAction = $action;
                break;
            }
        }

        $url = implode('/', $url);

        if($routeChosen && $routeAction)
        {
            if(is_string($routeAction))
            {
                list($controllerName, $function) = explode('@', $routeAction);
                include_file(CONTROLLERS_DIR . DS . $controllerName . '.php');
                $callback = function() use($controllerName, $function)
                {
                    if(!class_exists($controllerName))
                    {
                        error("Failed to instantiate controller '$controllerName'");
                    }
                    $controller = new $controllerName();
                    if(!method_exists($controller, $function))
                    {
                        error("Function missing in controller '$controllerName::$function()'");
                    }
                    return $controller->$function();
                };
            }
            else
            {
                $callback = $routeAction;
            }

            return $callback();
        }
        else
        {
            error("Route '$url' not found");
        }
    }
}