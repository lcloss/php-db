<?php

namespace LCloss\DB;

class SQL 
{
    protected $table = "";
    protected $id_column = NULL;
    protected $pairs = [];
    protected $wheres = [];
    protected $whereRaw = "";
    protected $sql = "";
    protected $limit = 0;
    protected $offset = 0;

    public function __construct()
    {
    }

    public function setTable( $table )
    {
        $this->table = $table;
    }

    public function setIdColumn( $id_column )
    {
        $this->id_column = $id_column;
    }

    public function setPairs( $pairs )
    {
        $this->pairs = $pairs;
    }

    public function get()
    {
        return $this->sql;
    }
    
    public function update()
    {
        $sets = [];
        $newPairs = $this->convertPairs( $this->pairs );
        foreach( $newPairs as $key => $value ) {
            if ( $key !== $this->id_column ) {
                $sets[] = "{$key} = {$value}";
            } else {
                $this->where( $key, $value );
            }
        }
        $this->sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " " . $this->getWhere() . ";";
        return $this;
    }

    public function insert()
    {
        $newPairs = $this->convertPairs( $this->pairs );
        $this->sql = "INSERT INTO {$this->table} (" . implode(', ', array_keys( $newPairs )) . ") VALUE (" . implode(', ', array_values( $newPairs )) . ");";
        return $this;
    }

    public function select()
    {
        $this->sql = "SELECT * FROM {$this->table} " . $this->getWhere();
        $this->sql .= ( $this->limit > 0 ) ? " LIMIT {$this->limit}" : "";
        $this->sql .= ( $this->offset > 0 ) ? " OFFSET {$this->offset}" : "";
        $this->sql .= ";";

        return $this;
    }

    public function delete()
    {
        $this->sql = "DELETE FROM {$this->table} " . $this->getWhere() . ";";
        return $this;
    }
    
    public function save()
    {
        if ( array_key_exists( $this->id_column, $this->pairs )) {
            return $this->update()->get();
        } else {
            return $this->insert()->get();
        }
    }

    public function count( string $column_name = '*' )
    {
        $this->sql = "SELECT COUNT( $column_name ) AS num_rows FROM {$this->table} " . $this->getWhere();

        return $this;
    }

    public function getWhere()
    {
        $where = "";
        if ( count( $this->wheres ) == 1 ) {
            $this->wheres[0]['open'] = "";
            $this->wheres[0]['close'] = "";
        }

        foreach( $this->wheres as $where_cond ) {
            $where .= " " . trim($where_cond['oper'] . " " . $where_cond['open'] . $where_cond['left'] . " " . $where_cond['comp'] . " " . $where_cond['right'] . $where_cond['close']);
        }
        if ( !empty($this->whereRaw) )
        {
            $where .= " " . $this->whereRaw;
        }
        if ( !empty($where) ) {
            $where = "WHERE " . trim($where);
        }

        return $where;
    }

    public function where($left, $right, $open = "(", $oper = "AND", $comp = "=", $close = ")")
    {
        $where = [];
        $where['open'] = $open;
        $where['close'] = $close;
        if ( count($this->wheres) > 0 ) {
            $where['oper'] = $oper;
        } else {
            $where['oper'] = "";
        }
        $where['left'] = $left;
        $where['comp'] = $comp;
        $where['right'] = $right;
        $this->wheres[] = $where;
        return $this;
    }

    public function whereRaw( $where_clause )
    {
        $this->whereRaw = $where_clause;
    }

    public function whereAnd($left, $right)
    {
        $this->where($left, $right, "AND", "=");
        return $this;
    }

    public function whereOr($left, $right)
    {
        $this->where($left, $right, "OR", "=");
        return $this;
    }

    public function whereLike($left, $right, $oper = "AND")
    {
        $this->where($left, $right, $oper, "LIKE");
        return $this;
    }

    public function limit( $limit )
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset( $offset )
    {
        $this->offset = $offset;
        return $this;
    }

    private function format( $value )
    {
        if (is_string($value) && !empty($value)) {
            return "'" . addslashes($value) . "'";

        } else if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';

        } else if ($value !== '') {
            return $value;

        } else {
            return "NULL";

        }
    }

    private function convertPairs( $sets )
    {
        $newPairs = [];
        foreach ( $sets as $key => $value ) {
            if (is_scalar($value)) {
                $newPairs[$key] = $this->format($value);
            }
        }
        return $newPairs;
    }
}