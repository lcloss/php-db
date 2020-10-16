<?php
namespace LCloss\DB;

use LCloss\DB\Connection;

class Database
{
    private static $instance = NULL;

    private function __construct() {}

    public static function getInstance( string $env_file ) {
        if ( is_null(self::$instance) ) {
            self::$instance = new Database();
        }
        self::$instance->conn = Connection::getInstance( $env_file );

        if ( !is_a( self::$instance->conn, 'PDO' )) {
            throw new \Exception('Connection to database failed.');
        }

        return self::$instance;
    }

    public static function exec( $sql )
    {
        $conn = Connection::getInstance();
        if ( $conn ) {
            xdebug_var_dump($sql);
            return $conn->exec( $sql );
        } else {
            throw new \Exception('Error on connecting to database.');
        }
    }
    
    public static function query( $sql )
    {
        $conn = Connection::getInstance();
        if ( $conn ) {
            return $conn->query( $sql );
        } else {
            throw new \Exception('Error on connecting to database.');
        }
    }
}