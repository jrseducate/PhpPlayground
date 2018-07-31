<?php
    if(!function_exists('dump'))
    {
        /**
         * Dump
         *
         * Formats and Renders the values provided
         *
         * @param array ...$values
         */
        function dump(...$values)
        {
            foreach($values as $value)
            {
                echo '<pre>' , var_dump($value) , '</pre>';
            }
        }
    }

    if(!function_exists('dd'))
    {
        /**
         * Dump Die
         *
         * Formats and Renders the values provided
         *
         * @param array ...$values
         */
        function dd(...$values)
        {
            foreach($values as $value)
            {
                echo '<pre>' , var_dump($value) , '</pre>';
            }

            die();
        }
    }

    if(!function_exists('try_get'))
    {
        /**
         * Try Get
         *
         * Used to get the $attribute by name from the $data, or returns $default if not found
         *
         * @param object|array $data
         * @param string $attribute
         * @param null|mixed $default
         * @return mixed|null
         */
        function try_get($data, $attribute, $default = null)
        {
            if(is_null($attribute))
            {
                return $data;
            }

            if(strpos($attribute, '.') === false)
            {
                if(is_array($data))
                {
                    return isset($data[$attribute]) ? $data[$attribute] : $default;
                }
                else if(is_object($data))
                {
                    return isset($data->$attribute) ? $data->$attribute : $default;
                }

                return $default;
            }

            $result     = $data;
            $attributes = explode('.', $attribute);

            foreach($attributes as $attribute)
            {
                $result = try_get($result, $attribute, $default);

                if($result === $default)
                {
                    break;
                }
            }

            return $result;
        }
    }

    if(!function_exists('array_first'))
    {
        /**
         * Array First
         *
         * Gets the first value in the $array passed
         *
         * @param $array
         * @return null
         */
        function array_first($array)
        {
            if(!empty($array) && is_array($array))
            {
                return array_values($array)[0];
            }

            return null;
        }
    }

    if(!function_exists('array_to_table'))
    {
        /**
         * Array to Table
         *
         * Returns the $array formatted as an html table
         *
         * @param $array
         * @return string
         */
        function array_to_table($array)
        {
            $result = '';
            $result .= "
            <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                text-align: left;
                padding: 8px;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            table, td, th {
                border: 1px solid #ddd;
                text-align: left;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                padding: 15px;
            }
            </style>";
            $result .= "<table>";
            $result .= "<tr>";
            foreach(array_keys(array_first($array)) as $key)
            {
                $result .= "<th>$key</th>";
            }
            $result .= "</tr>";
            foreach($array as $row)
            {
                $result .= "<tr>";
                foreach($row as $value)
                {
                    $result .= "<td>$value</td>";
                }
                $result .= "</tr>";
            }
            $result .= "</table>";

            return $result;
        }
    }

    if(!function_exists('coalesce'))
    {
        /**
         * Coalesce
         *
         * Returns the first non-null value
         *
         * @param array ...$values
         * @return mixed|null
         */
        function coalesce(...$values)
        {
            foreach($values as $value)
            {
                if(isset($value))
                {
                    return $value;
                }
            }

            return null;
        }
    }

    if(!function_exists('queryHelper'))
    {
        /**
         * Query Helper
         *
         * A shorthand function for getting a QueryHelper
         *
         * @param null|string $connectionName
         * @return null|QueryHelper
         */
        function queryHelper($connectionName = null)
        {
            return QueryHelper::connection($connectionName);
        }
    }

    if(!function_exists('queryHelperCustom'))
    {
        /**
         * Query Helper Custom
         *
         * A shorthand function for getting a QueryHelper with a custom connection
         *
         * @param null|string $url
         * @param null|string $username
         * @param null|string $password
         * @return QueryHelper
         */
        function queryHelperCustom($url = null, $username = null, $password = null)
        {
            return QueryHelper::customConnection($url, $username, $password);
        }
    }

    if(!function_exists('config'))
    {
        /**
         * Config
         *
         * Returns the current config value based on the path
         * config('database.connections') => {PROJECT_FOLDER}/config/database.php[connections]
         *
         * @param $path
         * @return mixed|null
         */
        function config($path = '')
        {
            return app()->config($path);
        }
    }

    if(!function_exists('env'))
    {
        /**
         * Config
         *
         * Returns the current config value based on the path
         * config('database.connections') => {PROJECT_FOLDER}/config/database.php[connections]
         *
         * @param string $path
         * @return mixed|null
         */
        function env($path = '')
        {
            return app()->env($path);
        }
    }

    if(!function_exists('view'))
    {
        /**
         * View
         *
         * Returns the view from the views folder
         *
         * @param string $name
         * @param array $with
         * @return mixed|null
         */
        function view($name, $with = [])
        {
            return app()->view($name, $with);
        }
    }

?>
