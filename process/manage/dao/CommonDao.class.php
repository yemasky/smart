<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 16:56
 */
namespace member;
class CommonDao extends \BaseDao {
    private static $objDao = null;

    public static function instance() {
        if(is_object(self::$objDao)) {
            return self::$objDao;
        }
        self::$objDao = new CommonDao();
        return self::$objDao;
    }

    public function getDsnRead() {//default
		// TODO: Implement getDsnRead() method.
		return DbConfig::instance()->dsnRead();
    }

    public function getDsnWrite() {//default
		// TODO: Implement getDsnWrite() method.
		return DbConfig::instance()->dsnWrite();
    }

    public function where($where = '') {
		// TODO: Implement where() method.
		$this->where = $where;
		return $this;
	}
	public function limit($limit = '') {
		// TODO: Implement limit() method.
		$this->limit = $limit;
		return $this;
	}

	public function group($group = '') {
		// TODO: Implement group() method.
		$this->group = $group;
		return $this;
	}

	public function order($order = '', $sort = '') {
		// TODO: Implement order() method.
        if(!empty($sort)) $sort = ' ' . $sort;
		$this->order = $order . $sort;;
		return $this;
	}

	public function joinTable($arrayJoinTable = array()) {
		// TODO: Implement joinTable() method.
		$this->arrayJoinTable = $arrayJoinTable;
		return $this;
	}

    public function queryCondition($arrayCondition = array()) {
        //$arrayCondition = empty($arrayCondition) ? DbConfig::$db_query_conditions : $arrayCondition;
        $this->where          = $arrayCondition['where'];
        $this->limit          = $arrayCondition['limit'];
        $this->order          = $arrayCondition['order'];
        $this->group          = $arrayCondition['group'];
        $this->arrayJoinTable = $arrayCondition['joinTable'];
        return $this;
    }
    //Db 事务


	//other Db 事务
	public function DbstartTransaction() {

	}

	public function Dbcommit() {

	}

	public function Dbrollback() {

	}
}