<?php
/**
 * Created by PhpStorm.
 * User: CooC
 * Date: 2018/8/31
 * Time: 23:25
 */

abstract class Entity {
    //get_class_methods() 返回由类的方法名组成的数组
    //get_class_methods('myclass') 或 get_class_methods(new myclass()) ;
    //get_class() 返回对象的类名
    //get_class_vars() 返回由类的默认属性组成的数组
    public function __construct(array $array = array()) {
        if(!empty($array)) return $this->setPrototype($array);
    }

    public function getPrototype($pname = null) {
		if(empty($pname)) return get_object_vars($this);
		return $this->{$pname};
	}

	public function setPrototype(array $array) {
		foreach($array as $key => $value) {
			if(property_exists($this, $key)) {
                $this->{$key} = $value;
            }
		}

		return $this;
	}

	public function getField() : string {
        return implode(',', array_keys(get_object_vars($this)));
	}

}