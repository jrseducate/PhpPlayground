<?php
    function error($error)
    {
        throw new \Exception($error);
    }
    function fixPath($path)
    {
        return str_replace(array('/', '\\'), DS, $path);
    }
    function splitPath($path)
    {
        return explode(DS, fixPath($path));
    }
    function include_file($file)
    {
        if(!file_exists($file))
        {
            error("Included file '$file' does not exist.");
        }

        include $file;
    }
    function require_file($file)
    {
        if(!file_exists($file))
        {
            error("Required file '$file' does not exist.");
        }

        return require $file;
    }
    function read_file($file)
    {
        if(!file_exists($file))
        {
            error("Read file '$file' does not exist.");
        }

        return file_get_contents($file);
    }

    /**
     * App
     *
     * @return App
     */
    function app()
    {
        $app = isset($GLOBALS['app']) ? $GLOBALS['app'] : null;

        if(!$app)
        {
            $app = $GLOBALS['app'] = new App();
        }

        return $app;
    }

    define('DS', DIRECTORY_SEPARATOR);
    $splitPath  = splitPath(isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : null);
    $wwwIndex   = array_search('www', $splitPath);
    $basePath   = implode(DS, array_slice($splitPath, 0, $wwwIndex + 2));
    define('BASE_DIR', $basePath);
    define('APP_DIR', $basePath . DS . 'app');
    define('CONFIG_DIR', $basePath . DS . 'config');
    define('HELPER_DIR', $basePath . DS . 'helpers');
    define('CONTROLLERS_DIR', $basePath . DS . 'controllers');
    define('VIEW_DIR', $basePath . DS . 'views');

    $appFile = APP_DIR . DS . 'App.php';
    include_file($appFile);

    app()->init();

//    $connection = queryHelperCustom();
//    $results    = $connection->query('SELECT * FROM information_schema.tables');
//    echo array_to_table($results);
?>
