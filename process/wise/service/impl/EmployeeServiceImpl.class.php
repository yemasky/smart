<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class EmployeeServiceImpl extends \BaseServiceImpl implements EmployeeService  {
    private static $objService = null;

    public static function instance() {
        if (is_object(self::$objService)) {
            return self::$objService;
        }
        self::$objService = new EmployeeServiceImpl();

        return self::$objService;
    }

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

    public function getEmployeeChannel($company_id, $employee_id) {
        $arrayEmployeeChannel = null;
        $arrayEmployeeSector  = $this->getEmployeeSector($company_id, $employee_id);
        if (!empty($arrayEmployeeSector)) {
            $defaultMember  = $arrayEmployeeSector[0]['channel_father_id'];
            $arrayChannelId = array_flip(array_column($arrayEmployeeSector, 'channel_father_id'));
            $arrayChannel   = ChannelServiceImpl::instance()->getCompanyChannelCache($company_id);
            foreach ($arrayChannel as $channel_id => $channel) {
                if ($channel['valid'] == 0) continue;//无效排除
                if (isset($arrayChannelId[$channel_id]) || isset($arrayChannelId[$channel['channel_father_id']])) {
                    $arrayEmployeeChannel[$channel_id]['default_id'] = $defaultMember;
                    if ($defaultMember == $channel_id) {
                        $arrayEmployeeChannel[$channel_id]['default'] = 1;
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

}