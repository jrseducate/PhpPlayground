<?php
/**
 * User: Jeremy
 */

/**
 * Error
 *
 * @param string $error
 * @throws Exception
 */
function error($error)
{
    throw new \Exception($error);
}

/**
 * Fix Path
 *
 * @param string $path
 * @return mixed
 */
function fixPath($path)
{
    return str_replace(array('/', '\\'), DS, $path);
}

/**
 * Split Path
 *
 * @param string $path
 * @return array
 */
function splitPath($path)
{
    return explode(DS, fixPath($path));
}

/**
 * Include File
 *
 * @param string $file
 * @throws Exception
 */
function include_file($file)
{
    if(!file_exists($file))
    {
        error("Included file '$file' does not exist.");
    }

    include $file;
}

/**
 * Require File
 *
 * @param string $file
 * @return mixed
 * @throws Exception
 */
function require_file($file)
{
    if(!file_exists($file))
    {
        error("Required file '$file' does not exist.");
    }

    return require $file;
}

/**
 * @param string $file
 * @return bool|string
 * @throws Exception
 */
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
define('APP_DIR', BASE_DIR . DS . 'app');
define('CONFIG_DIR', BASE_DIR . DS . 'config');
define('HELPER_DIR', BASE_DIR . DS . 'helpers');
define('CONTROLLERS_DIR', BASE_DIR . DS . 'controllers');
define('VIEW_DIR', BASE_DIR . DS . 'views');

$appFile = APP_DIR . DS . 'App.php';

include_file($appFile);

app()->init();

//    $connection = queryHelperCustom();
//    $results    = $connection->query('SELECT * FROM information_schema.tables');
//    echo array_to_table($results);
?>
