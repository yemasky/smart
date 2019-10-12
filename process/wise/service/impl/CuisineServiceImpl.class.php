<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class CuisineServiceImpl extends \BaseServiceImpl implements \BaseService {
    private static $objService = null;

    public static function instance() {
        if (is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new CuisineServiceImpl();

        return self::$objService;
    }

    //Cuisine
    public function getCuisine(\WhereCriteria $whereCriteria, $field = '') {
        return CuisineDao::instance()->getCuisine($whereCriteria);
    }

    public function saveCuisine($arrayData, $insert_type = 'INSERT') {
        return CuisineDao::instance()->saveCuisine($arrayData, $insert_type);
    }

    public function updateCuisine(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return CuisineDao::instance()->updateCuisine($whereCriteria, $arrayUpdateData);
    }

}