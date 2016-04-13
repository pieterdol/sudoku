<?php
namespace db;

use PDO;

interface Database
{
    public function post($table, $values);
    public function get($select, $from, $where, $values);
    public function put($table, $where, $values, $id);
    public function delete($table, $where, $values, $id);
}

class dbConnection implements Database 
{
    public $dbConnection;

    public function __construct() 
    {
        $db_host = "localhost";
        $db_name = "sudoku";
        $db_user = "root";
        $db_pass = "";
        $this->dbConnection = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_user, $db_pass);
    }

    public function post($table, $values) 
    {
    }

    public function get($select, $from, $where, $values)
    {
        if(!empty($where)){
            $where = " WHERE " . $where;
        }
        $stmt = $this->dbConnection->prepare("SELECT $select FROM $from $where");
        $stmt->execute($values);
        return $stmt->fetch();
    }

    public function put($table, $where, $values, $id)
    {

    }

    public function delete($table, $where, $values, $id)
    {

    }
}