<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 23:55
 */

namespace wise;
class EmployeeService extends \BaseService {
	private static $objService = null;

	public static function instance() {
		if(is_object(self::$objService)) {
		} else {
			self::$objService = new EmployeeService();
		}
		
		return self::$objService;
	}

	//--------Employee//-----------//
	public function getEmployee(\WhereCriteria $whereCriteria, $field = null) {
		return EmployeeDao::instance()->getEmployee($field, $whereCriteria);
	}

	public function saveEmployee($arrayData, $insert_type = 'INSERT') {
		return EmployeeDao::instance()->setTable('employee')->insert($arrayData, $insert_type);
	}

	public function updateEmployee(\WhereCriteria $whereCriteria, $arrayUpdateData) {
		return EmployeeDao::instance()->setTable('employee')->update($arrayUpdateData, $whereCriteria);
	}
	//--------Employee//-----------//

	//employee_sector
	public function getEmployeeSector(\WhereCriteria $whereCriteria, $field = null) {
		return EmployeeDao::instance()->setTable('employee_sector')->getList($field, $whereCriteria);
	}

	public function saveEmployeeSector($arrayData, $insert_type = 'INSERT') {
		return EmployeeDao::instance()->setTable('employee_sector')->insert($arrayData, $insert_type);
	}

	public function updateEmployeeSector(\WhereCriteria $whereCriteria, $arrayUpdateData) {
		return EmployeeDao::instance()->setTable('employee_sector')->update($arrayUpdateData, $whereCriteria);
	}
}