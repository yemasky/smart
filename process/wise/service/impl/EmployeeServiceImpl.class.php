<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class EmployeeServiceImpl extends \BaseServiceImpl implements EmployeeService {
    private static $objService = null;

    public static function instance() {
        if (is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new EmployeeServiceImpl();

        return self::$objService;
    }

    //employee
    public function getEmployee(\WhereCriteria $whereCriteria, $field = null) {
        return EmployeeDao::instance()->getEmployee($whereCriteria, $field);
    }

    public function saveEmployee($arrayData, $insert_type = 'INSERT') {
        return EmployeeDao::instance()->saveEmployee($arrayData, $insert_type);
    }

    public function updateEmployee(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return EmployeeDao::instance()->updateEmployee($whereCriteria, $arrayUpdateData);
    }

    public function getEmployeeReceivablePage(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $tableState = $objRequest->tableState;
        if (empty($tableState)) $tableState = array();
        $company_id      = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();
        $channel_id      = $objRequest->channel_id;
        $tableStateModel = new TableStateModel($tableState);
        $objPagination   = $tableStateModel->getPagination();
        $objSearch       = $tableStateModel->getSearch();
        $objSort         = $tableStateModel->getSort();
        //
        $sector_id        = $objRequest->sector_id;
        $sector_father_id = $objRequest->sector_father_id;
        $sector_type      = $objRequest->sector_type;
        $sectorChildren   = $objRequest->sectorChildren;
        //
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id)->EQ('channel_id', $channel_id);
        if (!empty($searchValue = $objSearch->getPredicateObject())) {
            //$whereCriteria->MATCH('employee_name', $searchValue['$']);
            $whereCriteria->LIKE('employee_name', '%' . $searchValue['$'] . '%');
        }
        if (!empty($sector_id)) {
            if ($sector_type == 'position') $whereCriteria->EQ('sector_id', $sector_id);
            if ($sector_type == 'sector') {
                if ($sector_father_id != $sector_id) {
                    $whereCriteria->EQ('sector_father_id', $sector_id);
                } else {
                    if (!empty($sectorChildren)) {
                        $whereCriteria->ArrayIN('sector_father_id', json_decode(stripslashes($sectorChildren), true));
                    }
                }
            }
        }
        $employeeSectorCount = EmployeeDao::instance()->getEmployeeSectorCount($whereCriteria, 'employee_id');
        $objPagination->setTotalItemCount($employeeSectorCount);
        $number        = $objPagination->getNumber();
        $numberOfPages = ceil($employeeSectorCount / $number);
        $objPagination->setNumberOfPages($numberOfPages);
        if (!empty($predicate = $objSort->getPredicate())) {
            $reverse = $objSort->isReverse() ? 'DESC' : 'ASC';
            $whereCriteria->ORDER($predicate, $reverse);
        }
        $start = $objPagination->getStart();
        $whereCriteria->LIMIT($start, $number);
        $fileid    = 'employee_id,employee_name,sex,birthday,photo,mobile,email,weixin';
        $arrayData = EmployeeDao::instance()->getEmployeeSector($whereCriteria, $fileid);
        if (!empty($arrayData)) {
            foreach ($arrayData as $i => $data) {
                $arrayData[$i]['e_id'] = encode($data['employee_id']);
            }
        }
        $tableStateModel->setItemData($arrayData);
        return ['numberOfPages' => $numberOfPages, 'data' => $arrayData];
    }

    //employee_sector
    public function getEmployeeSector($company_id, $employee_id = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('company_id', $company_id);

        if (!empty($employee_id) && $employee_id > 0) $whereCriteria->EQ('employee_id', $employee_id);
        $field = 'channel_father_id, sector_id, sector_father_id, is_default';
        $whereCriteria->ORDER('is_default');

        return EmployeeDao::instance()->getEmployeeSector($whereCriteria, $field);
    }

    public function getEmployeeCompanySector($company_id, $employee_id) {
        $arrayEmployeeSector = $this->getEmployeeSector($company_id, $employee_id);
        if (!empty($arrayEmployeeSector)) {
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id);
            $arraySectorId = array_column($arrayEmployeeSector, 'sector_id');
            if (!empty($arraySectorId)) {
                $whereCriteria->ArrayIN('sector_id', $arraySectorId);
                $field = 'sector_id, channel_father_id, sector_father_id, sector_name, sector_order, sector_type';

                return CompanyDao::instance()->getCompanySector($whereCriteria, $field);
            }

            return null;
        }

        return null;
    }

    public function getEmployeeChannelSector($employee_id, $channel_id = '', $field = '') {
        $whereCriteria = new \WhereCriteria();
        $whereCriteria->EQ('employee_id', $employee_id);
        if(!empty($channel_id)) $whereCriteria->EQ('channel_id', $channel_id);
        if(empty($field)) $field = 'channel_father_id, channel_id, sector_id, sector_father_id, is_default';

        return EmployeeDao::instance()->getEmployeeSector($whereCriteria, $field);
    }

    public function getEmployeeChannel($company_id, $employee_id) {
        //取得用户的全部channel 按company统计
        $arrayEmployeeChannel = null;
        $arrayEmployeeSector  = $this->getEmployeeChannelSector($employee_id);
        if (!empty($arrayEmployeeSector)) {
            $defaultMember  = $arrayEmployeeSector[0]['channel_father_id'];
            $arrayChannelId = array_flip(array_column($arrayEmployeeSector, 'channel_father_id'));
            $arrayChannel   = ChannelServiceImpl::instance()->getCompanyChannelCache($company_id);
            foreach ($arrayChannel as $channel_id => $channel) {
                if ($channel['valid'] == 0) continue;//无效排除
                if (isset($arrayChannelId[$channel_id]) || isset($arrayChannelId[$channel['channel_father_id']])) {
                    $arrayEmployeeChannel[$channel_id]['default_id'] = $defaultMember;
                    if ($defaultMember == $channel_id) {
                        $arrayEmployeeChannel[$channel_id]['default'] = 1;//默认只有1个
                    } else {
                        $arrayEmployeeChannel[$channel_id]['default'] = 0;
                    }
                    $arrayEmployeeChannel[$channel_id]['channel_id']        = $channel['channel_id'];
                    $arrayEmployeeChannel[$channel_id]['id']                = encode($channel['channel_id'], getDay());
                    $arrayEmployeeChannel[$channel_id]['channel']           = $channel['channel'];
                    $arrayEmployeeChannel[$channel_id]['channel_father_id'] = $channel['channel_father_id'];
                    $arrayEmployeeChannel[$channel_id]['channel_name']      = $channel['channel_name'];
                    $arrayEmployeeChannel[$channel_id]['channel_en_name']   = $channel['channel_en_name'];
                    $arrayEmployeeChannel[$channel_id]['company_chairman']  = $channel['company_chairman'];
                    $arrayEmployeeChannel[$channel_id]['business_day']      = $channel['business_day'];//是否是夜審制度
                    $arrayEmployeeChannel[$channel_id]['member_of']         = 0;
                    if ($channel['channel_father_id'] > 0) $arrayEmployeeChannel[$channel_id]['member_of'] = 1;
                }
            }
        }

        return $arrayEmployeeChannel;
    }

    //company_channel_sector position 职位，sector 部门
    public function getChannelSector(\WhereCriteria $whereCriteria, $field = null) {
        return EmployeeDao::instance()->getChannelSector($whereCriteria, $field);
    }

    public function saveChannelSector($arrayData, $insert_type = 'INSERT') {
        return EmployeeDao::instance()->saveChannelSector($arrayData, $insert_type);
    }

    public function updateChannelSector(\WhereCriteria $whereCriteria, $arrayUpdateData) {
        return EmployeeDao::instance()->updateChannelSector($whereCriteria, $arrayUpdateData);
    }


}