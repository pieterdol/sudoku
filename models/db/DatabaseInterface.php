<?php
namespace Db;

interface DatabaseInterface
{
    public function post($table, $values);
    public function get($select, $from, $where, $values);
    public function put($table, $where, $values, $id);
    public function delete($table, $where, $values, $id);
}