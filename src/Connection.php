<?php
namespace LCloss\DB;

use LCloss\Env\Environment;
use PDO;

final Class Connection
{
    private static $connection;

    /**
     * Singleton
     */
    private function __construct() {}

    private function __clone() {}

    private function __wakeup() {}

    /**
     * Make a connection string and generate PDO object
     */
    private static function make( array $data )
    {
        $driver     = $data['driver'] ?? "mysql";
        $server     = $data['server'] ?? "localhost";
        $port       = $data['port'] ?? NULL;
        $user       = $data['user'] ?? NULL;
        $pass       = $data['password'] ?? NULL;
        $dbname     = $data['db'] ?? NULL;

        if ( !is_null($driver) ) {
            switch( strtoupper($driver) ) {
                case 'MYSQL':
                    $port = $port ?? 3306;
                    $dsn = "mysql:host={$server};port={$port};dbname={$dbname}";
                    return new PDO($dsn, $user, $pass);
                    break;

                case 'MSSQL':
                    $port = $port ?? 1433;
                    $dsn = "mssql:host={$server},{$port};dbname={$dbname}";
                    return new PDO($dsn, $user, $pass);
                    break;

                case 'PGSQL':
                    $port = $port ?? 5432;
                    $dsn = "pgsql:dbname={$dbname}; user={$user}; password={$pass}, host={$server};port={$port}";
                    return new PDO($dsn);
                    break;

                case 'SQLITE':
                    $dsn = "sqlite:{$dbname}";
                    return new PDO($dsn);
                    break;

                case 'OCI8':
                    $dsn = "oci:dbname={$dbname}";
                    return new PDO($dsn, $user, $pass);
                    break;

                case 'FIREBIRD':
                    $dsn = "firebird:dbname={$dbname}";
                    return new PDO($dsn, $user, $pass);
                    break;

                default:
                    throw new \Exception(sprintf('Database driver %s is not supported.', $driver));
                    break;
            }
        } else {
            throw new \Exception('Database environment data is missed.');
        }
    }

    public static function getInstance(): PDO
    {
        $path = implode( DIRECTORY_SEPARATOR, array_slice( explode( DIRECTORY_SEPARATOR, __DIR__ ), 0, -4 )) . DIRECTORY_SEPARATOR;

        $env = Environment::getInstance('.env', $path);

        $database_settings = $env->database;

        if ( is_null(self::$connection) ) {
            self::$connection = self::make( $database_settings );
            self::$connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            self::$connection->exec("set names utf8");
        }
        return self::$connection;
    }

}