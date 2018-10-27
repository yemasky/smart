<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 10:58
 */
abstract class BaseService{
	/**
	 * 魔术函数，执行模型扩展类的自动加载及使用
	 */
    public function __call($name, $args){
        $objCallName = new $name($args);
        $objCallName->setCallObj($this, $args);
        return $objCallName;
    }

}