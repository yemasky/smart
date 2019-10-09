<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */

namespace wise;
class SalesTargetDao extends CommonDao {
	private static $objDao = null;

	public static function instance() {
		if(is_object(self::$objDao)) {
			return self::$objDao;
		}
		self::$objDao = new SalesTargetDao();

		return self::$objDao;
	}
    //SalesTarget
    public function getSalesTarget(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('sales_target')->getList($whereCriteria, $field);
    }

    public function saveSalesTarget($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('sales_target')->insert($arrayData, $insert_type);
    }

    public function batchInsertSalesTarget($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('sales_target')->batchInsert($arrayData, $insert_type);
    }

    public function updateSalesTarget(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('sales_target')->update($whereCriteria, $arrayUpdateData);
    }

    public function deleteSalesTarget(\WhereCriteria $whereCriteria) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('sales_target')->delete($whereCriteria);
    }
}