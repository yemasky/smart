<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */
namespace wise;
class EmployeeDao extends CommonDao {
    private static $objDao = null;

    public static function instance() {
        if(is_object(self::$objDao)) {
            return self::$objDao;
        }
        self::$objDao = new EmployeeDao();
        return self::$objDao;
    }

    public function getEmployee($field = null, \WhereCriteria $whereCriteria){
        return $this->setDsnRead($this->getDsnRead())->setTable('employee')->getList($field, $whereCriteria);
    }

}