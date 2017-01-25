<?php

/**
 *  MySQL数据库的驱动支持 
 */
class SQLException extends Exception{
}
class mysqliDriver {
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
		$this->conn = @mysqli_connect($dbConfig['host'], $dbConfig['login'], $dbConfig['password'], $dbConfig['database'], $dbConfig['port']);
		if(mysqli_connect_errno()) {
			throw new SQLException("数据库链接错误: " . mysqli_connect_errno());
		}
		//$this->execute('SET NAMES UTF8;');
	}

	public function setCharacter($character) {
        $this->execute('SET NAMES ' . $character);
    }

	public function selectDB($databases){
		if(mysqli_select_db($this->conn, $databases)) {
		} else {
			throw new SQLException("无法找到数据库，请确认数据库名称正确！");
		}
	}

	/**
	 * 按SQL语句获取记录结果，返回数组
	 *
	 * @param $hashOrIdKey hash key 或者 IdKey，$childrenKey key 或者 只取children
	 *        	sql 执行的SQL语句
	 */
	public function getQueryArrayResult($sql, $hashOrIdKey = null, $multiple = false, $fatherKey = '', $childrenKey = ''){
		$result = $this->execute($sql);
		$rows = array ();
        if(empty($hashOrIdKey)) {
            while($rows[] = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            }
            array_pop($rows);
        } elseif(!$multiple) {
            if(empty($fatherKey)) {//hash $multiple单重
                if(!empty($childrenKey)) {
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $rows[$row[$hashOrIdKey]][$row[$childrenKey]] = $row;
                    }
                } else {
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $rows[$row[$hashOrIdKey]] = $row;
                    }
                }
            } else {
                if(!empty($childrenKey)) {//hash $multiple多重 $fatherKey 不变  $childrenKey  = 主键
                    $key = $childrenKey;
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        if($row[$fatherKey] == $row[$key]) {//father
                            $children = isset($rows[$row[$hashOrIdKey]][$row[$fatherKey]]['children']) ? $rows[$row[$hashOrIdKey]][$row[$fatherKey]]['children'] : '';
                            $rows[$row[$hashOrIdKey]][$row[$fatherKey]] = $row;
                            $rows[$row[$hashOrIdKey]][$row[$fatherKey]]['children'] = $children;
                        } else {
                            $rows[$row[$hashOrIdKey]][$row[$fatherKey]]['children'][] = $row;
                        }
                    }
                } else {
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $rows[$row[$hashOrIdKey]][$row[$fatherKey]][] = $row;
                    }
                }
            }
        } elseif(!empty($fatherKey)) {//$multiple多重
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                if($row[$hashOrIdKey] == $row[$fatherKey]) {//father
                    $children = isset($rows[$row[$fatherKey]]['children']) ? $rows[$row[$fatherKey]]['children'] : '';
                    $rows[$row[$fatherKey]] = $row;
                    $rows[$row[$fatherKey]]['children'] = $children;
                } else {
                    if(!empty($childrenKey)) {
                        $rows[$row[$fatherKey]]['children'][$row[$childrenKey]] = $row;
                    } else {
                        $rows[$row[$fatherKey]]['children'][] = $row;
                    }
                }
            }
        } else {//$multiple多重
            if(!empty($childrenKey)) {
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $rows[$row[$hashOrIdKey]][$row[$childrenKey]][] = $row;
                }
            } else {
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $rows[$row[$hashOrIdKey]][] = $row;
                }
            }
        }
		mysqli_free_result($result);
		return $rows;
	}

	public function getQueryRowResult($sql){
		$result = $this->execute($sql);
		$row = mysqli_fetch_assoc($result);
		mysqli_free_result($result);
		return $row;
	}

	public function getQueryOneResult($sql){
		$result = $this->execute($sql);
		$row = mysqli_fetch_row($result);
		mysqli_free_result($result);
		return $row[0];
	}

	/**
	 * 返回当前插入记录的主键ID
	 */
	public function getInsertid(){
		return mysqli_insert_id($this->conn);
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
		if($result = mysqli_query($this->conn, $sql)) {
			return $result;
		} else {
			// print_r( mysql_error());
			throw new SQLException("{$sql}\r\n<br />执行错误:" . mysqli_error($this->conn));
		}
	}

	/**
	 * 返回影响行数
	 */
	public function affected_rows(){
		return mysqli_affected_rows($this->conn);
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
		mysqli_close($this->conn);
	}
}

