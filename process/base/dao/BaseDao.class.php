<?php

/**
 * User: CooC
 * Date: 2015/12/9
 * Time: 18:04
 */
abstract class BaseDao {
    protected $table = '';
    protected $entity_class = '';
    protected $dsn_read = '';
    protected $dsn_write = '';
    protected $table_key = '*';
    protected $cache_id = '';

    abstract public static function instance();

    abstract public function getDsnRead();

    abstract public function getDsnWrite();

    public function __call($name, $args) {
        $objCallName = new $name($args);
        $objCallName->setCallObj($this, $args);

        return $objCallName;
    }

    public function setDsnRead($dsn_read) {
        $this->dsn_read = $dsn_read;

        return $this;
    }

    public function setDsnWrite($dsn_write) {
        $this->dsn_write = $dsn_write;

        return $this;
    }

    //事务//default
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

    public function setTable($table) {
        $this->table = $table;

        return $this;
    }

    public function setEntity($entity_class) {
        $this->entity_class = $entity_class;

        return $this;
    }

    public function setKey($table_key) {
        $this->table_key = $table_key;

        return $this;
    }

    public function setCacheId($cache_id) {
        $this->cache_id = $cache_id;

        return $this;
    }

    public function getList(\WhereCriteria $whereCriteria, $field = '*'): array {
        if (empty($field)) $field = '*';
        return DBQuery::instance($this->getDsnRead())->setTable($this->table)->setKey($this->table_key)->getList($field, $whereCriteria);
    }

    public function getEntity(\WhereCriteria $whereCriteria, $field = '*') {
        if (empty($field)) $field = '*';
        return DBQuery::instance($this->getDsnRead())->setEntityClass($this->entity_class)->setKey($this->table_key)->getEntity($field, $whereCriteria);
    }

    public function getEntityList(\WhereCriteria $whereCriteria, $field = '*'): array {
        return DBQuery::instance($this->getDsnRead())->setEntityClass($this->entity_class)->setKey($this->table_key)->getEntityList($field, $whereCriteria);
    }

    public function getCount(\WhereCriteria $whereCriteria, $field = null) {
        return DBQuery::instance($this->getDsnRead())->setTable($this->table)->getCount($field, $whereCriteria);
    }

    public function insert($arrayData, $insert_type = 'INSERT') {
        return DBQuery::instance($this->getDsnWrite())->setTable($this->table)->insert($arrayData, $insert_type)->getInsertId();
    }

    public function batchInsert($arrayValues, $insert_type = 'INSERT') {
        return DBQuery::instance($this->getDsnWrite())->setTable($this->table)->batchInsert($arrayValues, $insert_type);
    }

    public function insertEntity($objEntity, $insert_type = 'INSERT') {
        return DBQuery::instance($this->getDsnWrite())->insertEntity($objEntity, $insert_type)->getInsertId();
    }

    public function batchInsertEntity($arrayObjEntity, $field_key = '', $insert_type = 'INSERT') : array {
        return DBQuery::instance($this->getDsnWrite())->batchInsertEntity($arrayObjEntity, $field_key, $insert_type);
    }

    public function update(\WhereCriteria $whereCriteria, $row, $update_type = '') {//IGNORE
        return DBQuery::instance($this->getDsnWrite())->setTable($this->table)->update($whereCriteria, $row, $update_type);
    }

    public function batchUpdateByKey(\WhereCriteria $whereCriteria, $arrayUpdate, $update_type = '') {
        return DBQuery::instance($this->getDsnWrite())->setTable($this->table)->batchUpdateByKey($whereCriteria, $arrayUpdate, $update_type);
    }

    public function delete(\WhereCriteria $whereCriteria) {
        return DBQuery::instance($this->getDsnWrite())->setTable($this->table)->delete($whereCriteria);
    }

    public function affectedRows() {
        return DBQuery::instance($this->getDsnWrite())->affectedRows();
    }
}