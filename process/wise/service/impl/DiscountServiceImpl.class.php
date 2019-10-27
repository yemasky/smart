<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class DiscountServiceImpl extends \BaseServiceImpl implements \BaseService {
    private static $objService = null;

    public static function instance() {
        if (is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new DiscountServiceImpl();

        return self::$objService;
    }

    public function getDiscount(\WhereCriteria $whereCriteria, $field = '') {
        return DiscountDao::instance()->getDiscount($whereCriteria, $field);
    }

    public function saveDiscount($arrayData, $insert_type = 'INSERT') {
        return DiscountDao::instance()->saveDiscount($arrayData, $insert_type);
    }

    public function updateDiscount(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return DiscountDao::instance()->updateDiscount($whereCriteria, $arrayUpdateData);
    }

    //table
    public function getChannelDiscountPage(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $tableState = $objRequest->tableState;
        if (empty($tableState)) $tableState = array();
        $company_id      = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id      = decode($objRequest->c_id, getDay());
        $tableStateModel = new TableStateModel($tableState);
        $objPagination   = $tableStateModel->getPagination();
        $objSearch       = $tableStateModel->getSearch();
        $objSort         = $tableStateModel->getSort();

        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id);
        if (!empty($searchValue = $objSearch->getPredicateObject())) {
            //$whereCriteria->MATCH('receivable_name', $searchValue['$']);
            $whereCriteria->LIKE('discount_name', '%' . $searchValue['$'] . '%');
        }
        $discountCount = DiscountDao::instance()->getChannelDiscountCount($whereCriteria);
        $objPagination->setTotalItemCount($discountCount);
        $number        = $objPagination->getNumber();
        $numberOfPages = ceil($discountCount / $number);
        $objPagination->setNumberOfPages($numberOfPages);
        if (!empty($predicate = $objSort->getPredicate())) {
            $reverse = $objSort->isReverse() ? 'DESC' : 'ASC';
            $whereCriteria->ORDER($predicate, $reverse);
        }
        $start = $objPagination->getStart();
        $whereCriteria->LIMIT($start, $number);
        $arrayData = $this->getDiscount($whereCriteria);
        if (!empty($arrayData)) {
            foreach ($arrayData as $i => $data) {
                $arrayData[$i]['cd_id'] = encode($data['discount_id']);

            }
        }
        $tableStateModel->setItemData($arrayData);
        return ['numberOfPages' => $numberOfPages, 'data' => $arrayData];
    }
}