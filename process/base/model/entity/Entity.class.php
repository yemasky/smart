<?php
/**
 * Created by PhpStorm.
 * User: CooC
 * Date: 2018/8/31
 * Time: 23:25
 */

abstract class Entity {

	public function getPrototype() : array {
		return get_object_vars($this);
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