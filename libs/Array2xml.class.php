<?php
/*
	class.Array2xml.php
	auther: cooc 
	email:yemasky@msn.com
*/

class Array2xml {
	private $xml;
    private static $instancesXml = null;
	public function Array2xml($array = NULL, $isItemfalse = false, $encoding='UTF-8') {
		if($array == NULL) {
			return NULL;
		}
		$this->xml='<?xml version="1.0" encoding="'.$encoding.'"?>';
		if($isItemfalse) {
            $this->xml .=$this->arrayTurnItemXml($array);
        } else {
            $this->xml .=$this->arrayTurnXml($array);
            echo htmlentities($this->xml);
        }
	}

	public static function instance($array, $isItemfalse = false, $encoding='UTF-8') {
        if(!empty(self::$instancesXml) && is_object(self::$instancesXml)) {
            return self::$instancesXml;
        }
        self::$instancesXml = new Array2xml($array, $isItemfalse, $encoding);
        return self::$instancesXml;
	}

    private function arrayTurnItemXml($array) {
        $xml = '';
        foreach($array as $key=>$val) {
            is_numeric($key) && $key="item id=\"$key\"";
            $xml.="<$key>";
            $xml.=is_array($val)?$this->arrayTurnItemXml($val):$val;
            list($key,)=explode(' ',$key);
            $xml.="</$key>";
        }
        return $xml;
	}

    private function arrayTurnXml($array) {

    }

    private function getAttributes($arrayAttr) {
        $strAttr = '';
        foreach ($arrayAttr as $attrKey => $attrValue) {
            if(!is_array($attrValue)) {
                $strAttr .= ' ' . $attrKey . '="' . $attrValue . '"';
            }
        }
        return $strAttr;
    }

    private function getChildNodes($arrayNode) {
        $strChildNodes = '';
        foreach ($arrayNode as $nodeKey => $nodeValue) {
            if(is_numeric($nodeKey) && is_array($nodeValue)) {
                foreach ($nodeValue as $key => $value) {
                    if(is_array($value)) $strChildNodes .= $this->arrayTurnXml($value);
                }
            }
        }
        return $strChildNodes;
    }



    public function storeFromArray($pathfile) {
		if(($fp=fopen($pathfile, "w+")) == false) {
			throw new Exception("can not open [$pathfile](r).");
		}
		chmod($pathfile, 0666); //
		$len = fwrite($fp, $this->xml);
		if($len < strlen($this->xml)) {
			throw new Exception("fwrite string length is wrong [$len].");
			fclose($fp);
		}
		fclose($fp);
	}
	
}
?>