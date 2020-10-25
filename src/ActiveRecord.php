<?php
namespace LCloss\DB;

use LCloss\DB\Sql;
use LCloss\DB\Connection;

use PDO;

class ActiveRecord 
{
    private static $connection;

    private $content = [];
    protected $table = NULL;
    protected $id_column = NULL;
    protected $sql = NULL;
    protected $log_timestamp;

    public function __construct()
    {
        $this->sql = new Sql();

        if ( !is_bool( $this->log_timestamp )) {
            $this->log_timestamp = true;
        }

        // $class = get_called_class();
        // xdebug_var_dump($class);
        // $table = strtolower( (new $class())->table );

        if ( is_null( $this->table ) ) {
            $class = explode('\\', get_class($this));
            $this->table = strtolower( array_pop($class) );
            $this->sql->setTable( $this->table );
        }

        if ( is_null( $this->id_column ) ) {
            $this->id_column = 'id';
            $this->sql->setIdColumn( $this->id_column );
        }
    }

    /**
     * Set PDO connection
     * @param PDO $connection
     * @return void
     */
    public static function setConnection( PDO $connection )
    {
        self::$connection = $connection;
    }

    public function __set( $param, $value ) 
    {
        $this->content[$param] = $value;
    }

    public function __get( $param )
    {
        return $this->content[$param];
    }

    public function __isset( $param )
    {
        return isset( $this->content['param'] );
    }

    public function __unset( $param ) 
    {
        if ( isset( $param ) ) {
            unset( $this->content[$param] );
            return true;
        }
        return false;
    }
    private function ___clone()
    {
        if ( isset($this->content[ $this->id_column ])) {
            unset($this->content[ $this->id_column ]);
        }
    }

    public function toArray()
    {
        return $this->content;
    }

    public function fromArray( array $array ) 
    {
        $this->content = $array;
    }

    public function toJson()
    {
        return json_encode( $this->content );
    }

    public function fromJson( string $json )
    {
        $this->content = json_decode( $json );
    }

    public function save()
    {
        if ( $this->log_timestamp === true ) {
            $this->updated_at = date('Y-m-d H:i:s');

            if ( !array_key_exists( $this->id_column, $this->content ) ) {
                $this->created_at = date('Y-m-d H:i:s');
            }
        }
        $this->sql->setPairs( $this->content );
        return self::exec( $this->sql->save() );
    }

    public function delete()
    {
        if ( isset( $this->content[ $this->id_column ] ) ) {
            $this->sql = $this->sql->where($this->id_column, $this->content[ $this->id_column ]);
            return self::exec( $this->sql->delete()->get() );
        }
    }

    public static function find( $id )
    {
        $sql = new Sql();

        $class = get_called_class();
        $id_column = (new $class())->id_column;
        $table = (new $class())->table;

        $sql->setTable( $table );
        $sql->setIdColumn( $id_column );

        $select_sql = $sql->where($id_column, $id)->select()->get();
        return self::fetchObject( $select_sql );
    }

    public static function findFirst( string $filter = '' )
    {
        return self::all( $filter, 1 );
    }

    public function where( $column, $value ) 
    {
        $select_sql = $this->sql->where( $column, $value );
        return $this;
    }

    public function get()
    {
        $select_sql = $this->sql->select()->get();
        return self::fetchAll( $select_sql );
    }

    public static function all( string $filter = '', int $limit = 0, int $offset = 0)
    {
        $sql = new Sql();

        $class = get_called_class();
        $table = (new $class())->table;

        $sql->setTable( $table );

        if ( !empty($filter) ) {
            $sql->whereRaw( $filter );
        }
        $select_sql = $sql->limit( $limit )->offset( $offset )->select()->get();
        return self::fetchAll( $select_sql );
    }

    public static function count( string $column_name = '*', string $filter = '' )
    {
        $sql = new Sql();

        $class = get_called_class();
        $table = (new $class())->table;

        $sql->setTable( $table );

        if ( !empty($filter) ) {
            $sql->whereRaw( $filter );
        }
        $select_sql = $sql->count()->get();
        $res = self::fetch( $select_sql );
        return (int) $res['num_rows'];
    }

    public static function fetchObject( $sql )
    {
        $res = self::query( $sql );

        if ( $res ) {
            $obj = $res->fetchObject( get_called_class() );
        }

        return $obj;   
    }

    public static function fetchAll( $sql )
    {
        $res = self::query( $sql );

        if ( $res ) {
            $obj = $res->fetchAll( PDO::FETCH_CLASS, get_called_class() );
        }

        return $obj;   
    }

    public static function exec( $sql )
    {
        $conn = Connection::getInstance();
        if ( $conn ) {
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

    public static function fetch( $sql )
    {
        $conn = Connection::getInstance();
        if ( $conn ) {
            $p = $conn->prepare( $sql );
            $p->execute();
            
            $q = $p->fetch( PDO::FETCH_ASSOC );
            return $q;
        }
    }
}