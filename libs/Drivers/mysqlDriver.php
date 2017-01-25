<?php

/**
 *  MySQL数据库的驱动支持 
 */
class SQLException extends Exception{
}
class mysqlDriver{
	/**
	 * 数据库链接句柄
	 */
	private $conn;

	/**
	 * 构造函数
	 *
	 * @param
	 *        	dbConfig 数据库配置
	 */
	public function __construct($dbConfig){
		$this->conn = mysql_connect($dbConfig['host'], $dbConfig['login'], $dbConfig['password']);
		if($this->conn == false) {
			throw new SQLException("数据库链接错误: " . mysql_error());
		}
		if(mysql_select_db($dbConfig['database'], $this->conn)) {
		} else {
			throw new SQLException("无法找到数据库，请确认数据库名称正确！");
		} // $this -> query();
		//$this->execute('SET NAMES UTF8;');
	}

    public function setCharacter($character) {
        $this->execute('SET NAMES ' . $character);
    }

	public function selectDB($databases){
		if(mysql_select_db($databases, $this->conn)) {
		} else {
			throw new SQLException("无法找到数据库，请确认数据库名称正确！");
		}
	}

	/**
	 * 按SQL语句获取记录结果，返回数组
	 *
	 * @param
	 *        	sql 执行的SQL语句
	 */
	public function getQueryArrayResult($sql, $hashOrIdKey = null, $multiple = false){
		$result = $this->execute($sql);
		$rows = array ();
        if(empty($hashOrIdKey)) {
            while($rows[] = mysql_fetch_array($result, MYSQL_ASSOC)) {
            }
            array_pop($rows);
        } elseif(!$multiple) {//$multiple单重
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $rows[$row[$hashOrIdKey]] = $row;
            }
        } elseif(!empty($fatherKey)) {
            while($row = mysql_fetch_array($result, MYSQLI_ASSOC)) {
                if($row[$hashOrIdKey] == $row[$fatherKey]) {//father
                    $children = isset($rows[$row[$fatherKey]]['children']) ? $rows[$row[$fatherKey]]['children'] : '';
                    $rows[$row[$fatherKey]] = $row;
                    $rows[$row[$fatherKey]]['children'] = $children;
                } else {
                    $rows[$row[$fatherKey]]['children'][] = $row;
                }
            }
        }else {//$multiple多重
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $rows[$row[$hashOrIdKey]] = $row;
            }
        }

		mysql_free_result($result);
		return $rows;
	}

	public function getQueryRowResult($sql){
		$result = $this->execute($sql);
		$row = mysql_fetch_assoc($result);
		mysql_free_result($result);
		return $row;
	}

	public function getQueryOneResult($sql){
		$result = $this->execute($sql);
		$row = mysql_fetch_row($result);
		mysql_free_result($result);
		return $row[0];
	}
	/**
	 * 返回当前插入记录的主键ID
	 */
	public function getInsertid(){
		return mysql_insert_id($this->conn);
	}

	/**
	 * 格式化带limit的SQL语句
	 */
	public function setlimit($sql, $limit){
		return $sql . " LIMIT {$limit}";
	}

	/**
	 * 执行一个SQL语句
	 *
	 * @param
	 *        	sql 需要执行的SQL语句
	 */
	public function execute($sql){
		sqlDebugLog($sql);
		if($result = mysql_query($sql, $this->conn)) {
			return $result;
		} else {
			// print_r( mysql_error());
			throw new SQLException("{$sql}<br />执行错误:" . mysql_error());
		}
	}

	/**
	 * 返回影响行数
	 */
	public function affected_rows(){
		return mysql_affected_rows($this->conn);
	}

	/**
	 * 获取数据表结构
	 *
	 * @param
	 *        	tbl_name 表名称
	 */
	public function getTableDescribe($tbl_name){
		return $this->getQueryArrayResult("DESCRIBE {$tbl_name}");
	}
	/*
     * 事务
     */
	public function startTransaction() {
		return $this->execute('START TRANSACTION;');
	}
	public function enableAutocommit() {
		return $this->execute('SET AUTOCOMMIT=1;');
	}
	public function disableAutocommit() {
		return $this->execute('SET AUTOCOMMIT=0;');
	}
	public function commit() {
		return $this->execute('COMMIT;');
	}
	public function rollback() {
		return $this->execute('ROLLBACK;');
	}
	/**
	 * 析构函数 __destruct
	 */
	public function close(){
		mysql_close($this->conn);
	}
}

