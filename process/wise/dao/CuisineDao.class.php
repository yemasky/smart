<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */

namespace wise;
class CuisineDao extends CommonDao {
	private static $objDao = null;

	public static function instance() {
		if(is_object(self::$objDao)) {
			return self::$objDao;
		}
		self::$objDao = new CuisineDao();

		return self::$objDao;
	}
    //Cuisine
    public function getCuisine(\WhereCriteria $whereCriteria, $field = null) {
        return $this->setDsnRead($this->getDsnRead())->setTable('channel_cuisine')->getList($whereCriteria, $field);
    }

    public function saveCuisine($arrayData, $insert_type = 'INSERT') {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_cuisine')->insert($arrayData, $insert_type);
    }

    public function updateCuisine(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return $this->setDsnWrite($this->getDsnWrite())->setTable('channel_cuisine')->update($whereCriteria, $arrayUpdateData);
    }
    //

}