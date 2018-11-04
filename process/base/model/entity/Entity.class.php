<?php
/**
 * Created by PhpStorm.
 * User: CooC
 * Date: 2018/8/31
 * Time: 23:25
 */

abstract class Entity {

    public function __construct(array $array = array()) {
        if(!empty($array)) return $this->setPrototype($array);
    }

    public function getPrototype($pname = null) {
		if(empty($pname)) return get_object_vars($this);
		return $this->{$pname};
	}

	public function setPrototype(array $array) {
		foreach($array as $key => $value) {
			if(property_exists($this, $key)) $this->{$key} = $value;
		}

		return $this;
	}

	public function getField() {
		
	}

}