<?php

/**
 * Created by PhpStorm.
 * User: CooC
 * Date: 2015/12/9
 * Time: 18:04
 */
abstract class BaseDao{
    protected $table = '';
    protected $dsn_read = '';
    protected $dsn_write = '';
    protected $table_key = '*';
    protected static $objBase = null;

    abstract public static function instance();

    abstract public function getDsnRead();

    abstract public function getDsnWrite();

    public function setTable($table) {
        $this->table = $table;
        return $this;
    }

    public function __call($name, $args){
        $objCallName = new $name($args);
        $objCallName->setCallObj($this, $args);
        return $objCallName;
    }

    public function setKey($table_key) {
        $this->table_key = $table_key;
        return $this;
    }

    public function setDsnRead($dsn_read) {
        $this->dsn_read = $dsn_read;
        return $this;
    }

    public function setDsnWrite($dsn_write) {
        $this->dsn_write = $dsn_write;
        return $this;
    }

    public function whereRead($where) {
        DBQuery::instance($this->getDsnRead())->where($where);
        return $this;
    }

    public function getList($conditions, $fields = NULL, $hashKey = NULL, $multiple = false, $fatherKey = '', $childrenKey = '') {
        if(empty($fields)) {
            $fields = '*';
        }
        return DBQuery::instance($this->getDsnRead())->setTable($this->table)->setKey($this->table_key)->group($conditions['group'])->order($conditions['order'])->limit($conditions['limit'])->getList($conditions['where'], $fields, $hashKey, $multiple, $fatherKey, $childrenKey);
    }

    public function getRow($conditions_where, $fields = NULL) {
        if(empty($fields)) {
            $fields = '*';
        }
        return DBQuery::instance($this->getDsnRead())->setTable($this->table)->getRow($conditions_where, $fields);

    }

    public function getCount($conditions_where, $fields = NULL) {
        if(empty($fields)) {
            $fields = 'COUNT('. $this->table_key .') count_num';
        } else {
            $fields = 'COUNT('. $fields .') count_num ';
        }
        return DBQuery::instance($this->getDsnRead())->setTable($this->table)->getOne($conditions_where, $fields);
    }

    public function insert($arrayData, $insert_type = 'INSERT') {
        return DBQuery::instance($this->getDsnWrite())->setTable($this->table)->insert($arrayData, $insert_type)->getInsertId();
    }

    public function update($where, $row) {
        return DBQuery::instance($this->getDsnWrite())->setTable($this->table)->update($where, $row);
    }

    public function delete($where) {
        return DBQuery::instance($this->getDsnWrite())->setTable($this->table)->delete($where);
    }

    public function batchInsert($arrayValues, $insert_type = 'INSERT') {
        return DBQuery::instance($this->getDsnWrite())->setTable($this->table)->batchInsert($arrayValues, $insert_type);
    }

    //事务
    public function startTransaction() {
        DBQuery::instance($this->getDsnWrite())->startTransaction();
        return $this;
    }
    public function enableAutocommit() {
        DBQuery::instance($this->getDsnWrite())->enableAutocommit();
        return $this;
    }
    public function disableAutocommit() {
        DBQuery::instance($this->getDsnWrite())->disableAutocommit();
        return $this;
    }
    public function commit() {
        DBQuery::instance($this->getDsnWrite())->commit();
        return $this;
    }
    public function rollback() {
        DBQuery::instance($this->getDsnWrite())->rollback();
        return $this;
    }
}