<?php

/**
 *  MySQL数据库的驱动支持
 */
class SQLException extends Exception {
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
     *            dbConfig 数据库配置
     */
    public function __construct($dbConfig) {
        $this->conn = @mysqli_connect($dbConfig['host'], $dbConfig['login'], $dbConfig['password'], $dbConfig['database'], $dbConfig['port']);
        if (mysqli_connect_errno()) {
            throw new SQLException("数据库链接错误: " . mysqli_connect_errno());
        }
        //$this->execute('SET NAMES UTF8;');
    }

    public function setCharacter($character) {
        $this->execute('SET NAMES ' . $character);
    }

    public function selectDB($databases) {
        if (mysqli_select_db($this->conn, $databases)) {
        } else {
            throw new SQLException("无法找到数据库，请确认数据库名称正确！");
        }
    }

    /**
     * 按SQL语句获取记录结果，返回数组
     *
     * @param $hashOrIdKey hash key 或者 IdKey，$childrenKey key 或者 只取children
     *            sql 执行的SQL语句
     */
    public function getQueryArrayResult($sql, WhereCriteria $whereCriteria): array {
        //$hashKey = '',  = false,  = '',  = ''
        //$result       = $this->execute($sql);
        $result = $this->selectPrepareStatement($sql, $whereCriteria->getStatement(), $whereCriteria->getParam());

        $rows         = array();
        $hashKey      = $whereCriteria->getHashKey();
        $toHashArray  = $whereCriteria->isMultiple();
        $fatherHash   = $whereCriteria->getFatherKey();
        $childrenHash = $whereCriteria->getChildrenKey();
        if (empty($hashKey) && $toHashArray == false) {//empty($hashKey) && $toHashArray == true
            while ($rows[] = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            }
            //if $fatherHash != '', $childrenHash == '' ??
            //if $fatherHash == '', $childrenHash == ''
            //if $fatherHash == '', $childrenHash != '' ??
            array_pop($rows);
        } elseif (!empty($hashKey) && $toHashArray == true) {//!empty($hashKey) && $toHashArray == true hash 数组 []形式
            if (empty($fatherHash)) {
                if (empty($childrenHash)) {
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $rows[$row[$hashKey]][] = $row;
                    }
                } else {
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $rows[$row[$hashKey]][$row[$childrenHash]][] = $row;
                    }
                }
            } else {
                if (empty($childrenHash)) {// 父类子类循环
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        if ($row[$hashKey] == $row[$fatherHash] || $row[$fatherHash] == '0') {//father 父 0父
                            $children                         = isset($rows[$row[$fatherHash]]['children']) ? $rows[$row[$fatherHash]]['children'] : [];
                            $rows[$row[$hashKey]]             = $row;
                            $rows[$row[$hashKey]]['children'] = $children;
                        } else {
                            $rows[$row[$fatherHash]]['children'][] = $row;//children 子
                        }
                    }
                } else {
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $rows[$row[$hashKey]][$row[$fatherHash]][$row[$childrenHash]][] = $row;
                    }
                }
            }
        } elseif ($toHashArray == false) {//!empty($hashKey) 无[]
            //if(!empty($hashKey))
            if (empty($fatherHash)) {
                if (empty($childrenHash)) {
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $rows[$row[$hashKey]] = $row;
                    }
                } else {
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $rows[$row[$hashKey]][$row[$childrenHash]] = $row;
                    }
                }
            } else {
                if (empty($childrenHash)) {//
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        if ($row[$hashKey] == $row[$fatherHash] || $row[$fatherHash] == '0') {//father 父 0父
                            $children                         = isset($rows[$row[$fatherHash]]['children']) ? $rows[$row[$fatherHash]]['children'] : [];
                            $rows[$row[$hashKey]]             = $row;
                            $rows[$row[$hashKey]]['children'] = $children;
                        } else {
                            $rows[$row[$fatherHash]]['children'][$row[$hashKey]] = $row;
                            //children 子
                        }
                    }
                } else {
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $rows[$row[$hashKey]][$row[$fatherHash]][$row[$childrenHash]] = $row;
                    }
                }
            }
            //if(empty($hashKey))
        } else {//$toHashArray != true && $toHashArray != false
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                if ($row[$fatherHash] == $row[$childrenHash]) {//father
                    $children                                            = isset($rows[$row[$hashKey]][$row[$fatherHash]]['children']) ? $rows[$row[$hashKey]][$row[$fatherHash]]['children'] : [];
                    $rows[$row[$hashKey]][$row[$fatherHash]]             = $row;
                    $rows[$row[$hashKey]][$row[$fatherHash]]['children'] = $children;
                } else {
                    $rows[$row[$hashKey]][$row[$fatherHash]]['children'][] = $row;
                }
            }
        }
        mysqli_free_result($result);

        return $rows;
    }

    public function getStatementArrayResult($sql, WhereCriteria $whereCriteria): array {
        $objStatement = $this->prepareStatement($sql, $whereCriteria->getStatement(), $whereCriteria->getParam());
        $objStatement->execute();
        $objStatement->store_result();
        // get column names
        $metadata = $objStatement->result_metadata();
        $fields   = $metadata->fetch_fields();

        $results     = [];
        $ref_results = [];
        foreach ($fields as $field) {
            $results[$field->name] = null;
            $ref_results[]         =& $results[$field->name];
        }
        call_user_func_array(array($objStatement, 'bind_result'), $ref_results);

        $data = [];
        while ($objStatement->fetch()) {
            $data[] = $results;
        }
        $objStatement->free_result();

        return $data;
    }

    public function getEntityArrayResult(string $sql, string $modelEntityClass, WhereCriteria $whereCriteria): array {
        $result       = $this->selectPrepareStatement($sql, $whereCriteria->getStatement(), $whereCriteria->getParam());
        $arrayObject  = array();
        $hashKey      = $whereCriteria->getHashKey();
        $toHashArray  = $whereCriteria->isMultiple();
        $fatherHash   = $whereCriteria->getFatherKey();
        $childrenHash = $whereCriteria->getChildrenKey();
        if (empty($hashKey) && $toHashArray == false) {//empty($hashKey) && $toHashArray == true
            while ($object = $result->fetch_object($modelEntityClass)) {
                $arrayObject[] = $object;
            }
            //array_pop($arrayObject);
        } elseif (!empty($hashKey) && $toHashArray == true) {//!empty($hashKey) && $toHashArray == true hash 数组 []形式
            if (empty($fatherHash)) {
                if (empty($childrenHash)) {
                    while ($object = $result->fetch_object($modelEntityClass)) {
                        $arrayObject[$object->getPrototype($hashKey)][] = $object;
                    }
                } else {
                    while ($object = $result->fetch_object($modelEntityClass)) {
                        $arrayObject[$object->getPrototype($hashKey)][$object->getPrototype($childrenHash)][] = $object;
                    }
                }
            } else {
                if (empty($childrenHash)) {// 父类子类循环
                    while ($object = $result->fetch_object($modelEntityClass)) {
                        if ($object->getPrototype($hashKey) == $object->getPrototype($fatherHash) || $object->getPrototype($fatherHash) == '0') {//father 父 0父
                            $children                                                 = isset($arrayObject[$object->getPrototype($fatherHash)]['children']) ? $arrayObject[$object->getPrototype($fatherHash)]['children'] : null;
                            $arrayObject[$object->getPrototype($hashKey)]             = $object;
                            $arrayObject[$object->getPrototype($hashKey)]['children'] = $children;
                        } else {
                            $arrayObject[$object->getPrototype($fatherHash)]['children'][] = $object;//children 子
                        }
                    }
                } else {
                    while ($object = $result->fetch_object($modelEntityClass)) {
                        $arrayObject[$object->getPrototype($hashKey)][$object->getPrototype($fatherHash)][$object->getPrototype($childrenHash)][] = $object;
                    }
                }
            }
        } elseif ($toHashArray == false) {//!empty($hashKey) 无[]
            //if(!empty($hashKey))
            if (empty($fatherHash)) {
                if (empty($childrenHash)) {
                    while ($object = $result->fetch_object($modelEntityClass)) {
                        $arrayObject[$object->getPrototype($hashKey)] = $object;
                    }
                } else {
                    while ($object = $result->fetch_object($modelEntityClass)) {
                        $arrayObject[$object->getPrototype($hashKey)][$object->getPrototype($childrenHash)] = $object;
                    }
                }
            } else {
                if (empty($childrenHash)) {//
                    while ($object = $result->fetch_object($modelEntityClass)) {
                        if ($object->getPrototype($hashKey) == $object->getPrototype($fatherHash) || $object->getPrototype($fatherHash) == '0') {//father 父 0父
                            $children                                                 = isset($arrayObject[$object->getPrototype($fatherHash)]['children']) ? $arrayObject[$object->getPrototype($fatherHash)]['children'] : null;
                            $arrayObject[$object->getPrototype($hashKey)]             = $object;
                            $arrayObject[$object->getPrototype($hashKey)]['children'] = $children;
                        } else {
                            $arrayObject[$object->getPrototype($fatherHash)]['children'][$object->getPrototype($hashKey)] = $object;//children 子
                        }
                    }
                } else {
                    while ($object = $result->fetch_object($modelEntityClass)) {
                        $arrayObject[$object->getPrototype($hashKey)][$object->getPrototype($fatherHash)][$object->getPrototype($childrenHash)] = $object;
                    }
                }
            }
            //if(empty($hashKey))
        } else {//$toHashArray != true && $toHashArray != false
            while ($object = $result->fetch_object($modelEntityClass)) {
                if ($object->getPrototype($fatherHash) == $object->getPrototype($childrenHash)) {//father
                    $children                                                                                     = isset($arrayObject[$object->getPrototype($hashKey)][$object->getPrototype($fatherHash)]['children']) ?
                        $arrayObject[$object->getPrototype($hashKey)][$object->getPrototype($fatherHash)]['children'] : null;
                    $arrayObject[$object->getPrototype($hashKey)][$object->getPrototype($fatherHash)]             = $object;
                    $arrayObject[$object->getPrototype($hashKey)][$object->getPrototype($fatherHash)]['children'] = $children;
                } else {
                    $arrayObject[$object->getPrototype($hashKey)][$object->getPrototype($fatherHash)]['children'][] = $object;
                }
            }
        }
        mysqli_free_result($result);

        return $arrayObject;
    }

    function &results_fetch_object($set, $className) {
        //$db = new mysqli( 'localhost', 'Username', 'Password', 'DbName' );
        //$result = $db->query( 'SELECT id, partner_name, partner_type FROM submissions' );
        //$object = $result->fetch_object( 'SomeClass' );
        //mysqli_fetch_object();
        /* Start by getting the usual array */
        $row = mysqli_fetch_assoc($set);
        if ($row === null) return null;

        /* Create the object */
        $obj = new $className();
        /* Explode the array and set the objects's instance data */
        foreach ($row as $key => $value) {
            $obj->{$key} = $value;
        }

        return $obj;
        /*<?php
        class User {
            public $name;

            public static function getUser($id) {
                $conn = new mysqli('localhost', 'username', 'password', 'database');
                if ($result = $conn->query("SELECT * FROM users WHERE id = {$id} LIMIT 1")) {
                    return $result->fetch_object('User');
                    $result->close();
                }
            }
        }
        //Call the static method to obtain an instance of the User class with your data applied to it.
        $user = User::getUser('31');
        echo $user->name; // echo's 'Phil'
        ?>*/
    }

    public function getQueryRowResult($sql) {
        $result = $this->execute($sql);
        $row    = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        return $row;
    }

    public function getQueryOneResult($sql) {
        $result = $this->execute($sql);
        $row    = mysqli_fetch_row($result);
        mysqli_free_result($result);

        return $row[0];
    }

    /**
     * 返回当前插入记录的主键ID
     */
    public function getInsertid() {
        return mysqli_insert_id($this->conn);
    }

    /**
     * 格式化带limit的SQL语句
     */
    public function setlimit($sql, $limit) {
        return $sql . " LIMIT {$limit}";
    }

    /**
     * 执行一个SQL语句
     *
     * @param
     *            sql 需要执行的SQL语句
     */
    public function execute($sql) {
        sqlDebugLog($sql);
        if ($result = mysqli_query($this->conn, $sql)) {
            return $result;
        }
        throw new SQLException("{$sql}\r\n<br />执行错误:" . mysqli_error($this->conn));
    }

    public function executePrepareStatementSql($sql, $statement = '', $param = '') {
        $objStatement = $this->prepareStatement($sql, $statement, $param);
        /* 执行查询 */
        if (!$objStatement->execute()) {
            throw new SQLException("{$sql}\r\n<br />执行错误:" . mysqli_error($this->conn));
        }
        return $objStatement;
    }

    /**
     * @return mixed prepared statement
     */
    public function executePrepareStatement($sql, $statement = '', $param = '') {
        $objStatement = $this->executePrepareStatementSql($sql, $statement, $param);

        /* 关于语句对象 */
        $objStatement->close();
    }

    public function selectPrepareStatement($sql, $statement = '', $param = '') {
        $objStatement = $this->executePrepareStatementSql($sql, $statement, $param);
        /* 将查询结果绑定到变量 */
        $result = $objStatement->get_result();
        //关闭结果集
        $objStatement->free_result();
        /* 获取查询结果值 */
        $objStatement->fetch();
        /* 关于语句对象 */
        $objStatement->close();

        return $result;
    }

    private function prepareStatement($sql, $statement = '', $param = '') {
        sqlDebugLog($sql . ' param:' . json_encode($param));
        if ($objStatement = $this->conn->prepare($sql)) {
            /* 对于参数占位符进行参数值绑定 */
            if (!empty($statement) && !empty($param)) {
                $bind_param = [];
                /*方法1
                 * $types  = array_reduce($param, function ($string, &$arg) use (&$params) {
                    $bind_param[] = &$arg;
                    if (is_float($arg))         $string .= 'd';
                    elseif (is_integer($arg))   $string .= 'i';
                    elseif (is_string($arg))    $string .= 's';
                    else                        $string .= 'b';
                    return $string;
                }, '');
                array_unshift($bind_param, $types);
                $method = new ReflectionMethod('mysqli_stmt', 'bind_param');
                $method->invokeArgs($objStatement, $bind_param);*/

                //方法2
                foreach ($param as $key => $value) {
                    $bind_param[$key] = &$param[$key];
                }
                array_unshift($bind_param, $statement);
                $user_func_result = call_user_func_array([$objStatement, 'bind_param'], $bind_param);
                if (!$user_func_result) {
                    throw new Exception("bind_param Error." . $user_func_result . ';param:' . json_encode($param));
                }
            }

            return $objStatement;
        }
        throw new SQLException("{$sql}\r\n<br />执行错误:" . mysqli_error($this->conn));
    }

    /**
     * 返回影响行数
     */
    public function affected_rows() {
        return mysqli_affected_rows($this->conn);
    }

    /**
     * 获取数据表结构
     *
     * @param
     *            tbl_name 表名称
     */
    public function getTableDescribe($tbl_name) {
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
    public function close() {
        mysqli_close($this->conn);
    }
}

