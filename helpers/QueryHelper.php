<?php

if(!class_exists('QueryHelper'))
{
    include '../helpers/helpers.php';
    class QueryHelper
    {
        /**
         * ID Increment
         *
         * Used to generate a new ID for each unique connection made
         *
         * @var int
         */
        protected static $idInc = 0;

        /**
         * Connections
         *
         * Used to connect to already configured connections by name
         *
         * @var array
         */
        private static $connections = null;

        public static function initConnections()
        {
            self::$connections = config('database.connections');
        }

        /**
         * Active Connections
         *
         * All connections currently active
         *
         * @var array
         */
        protected static $activeConnections = [];

        /**
         * Generate Key
         *
         * Generated a unique key based on parameters passed
         *
         * @param array ...$params
         * @return string
         */
        protected static function generateKey(...$params)
        {
            return md5(implode('_', $params));
        }

        /**
         * ID
         *
         * ID of the connection
         *
         * @var int|null
         */
        protected $id = null;

        /**
         * URL
         *
         * URL of the connection
         *
         * @var string|null
         */
        protected $url = null;

        /**
         * Username
         *
         * Username of the connection
         *
         * @var string|null
         */
        protected $username = null;

        /**
         * Password
         *
         * Password of the connection
         *
         * @var string|null
         */
        protected $password = null;

        /**
         * Connection
         *
         * The underlying connection object used to query
         *
         * @var mysqli|null
         */
        protected $connection = null;

        /**
         * QueryHelper Constructor
         *
         * @param null|string $url
         * @param null|string $username
         * @param null|string $password
         */
        protected function __construct($url = null, $username = null, $password = null)
        {
            $this->id       = self::$idInc++;
            $this->url      = coalesce($url, 'localhost');
            $this->username = coalesce($username, 'root');
            $this->password = coalesce($password, '');
            $this->connect();

            $key = self::generateKey($url, $username, $password);
            self::$activeConnections[$key] = $this;

            return $this;
        }

        /**
         * Connect
         *
         * Initializes connection using $url, $username and $password properties
         */
        protected function connect()
        {
            $this->connection = new mysqli($this->url, $this->username, $this->password);

            if ($this->connection->connect_error)
            {
                die("Connection failed: " . $this->connection->connect_error);
            }
        }

        /**
         * Connection
         *
         * Gets or Initializes connection by $connectionName parameter using the already configured $connections
         *
         * @param null|string $connectionName
         * @return null|QueryHelper
         */
        public static function connection($connectionName = null)
        {
            $connectionName = coalesce($connectionName, 'mysql');
            $connection     = try_get(self::$connections, $connectionName);

            if($connection)
            {
                $key = self::generateKey(
                    try_get($connection, 'url'),
                    try_get($connection, 'username'),
                    try_get($connection, 'password')
                );

                if(isset(self::$activeConnections[$key]))
                {
                    return self::$activeConnections[$key];
                }

                $connection = new QueryHelper(
                    try_get($connection, 'url'),
                    try_get($connection, 'username'),
                    try_get($connection, 'password')
                );
            }
            else
            {
                die("Connection '$connectionName' is not defined!");
            }

            return $connection;
        }

        /**
         * Custom Connection
         *
         * Gets or Initializes connection by $url, $username, and $password parameters
         *
         * @param null|string $url
         * @param null|string $username
         * @param null|string $password
         * @return QueryHelper
         */
        public static function customConnection($url = null, $username = null, $password = null)
        {
            $key = self::generateKey($url, $username, $password);

            if(isset(self::$activeConnections[$key]))
            {
                return self::$activeConnections[$key];
            }

            $connection = new QueryHelper($url, $username, $password);

            return $connection;
        }

        /**
         * Query
         *
         * Executes the provided query on the connection, then returns the results as an array
         *
         * @param string $queryString
         * @return array
         */
        public function query($queryString)
        {
            $results    = [];
            $result     = $this->connection->query($queryString);

            if($result === false)
            {
                die("Query failed: " . $this->connection->error);
            }

            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    $results[] = $row;
                }
            }

            return $results;
        }
    }

    QueryHelper::initConnections();
}
