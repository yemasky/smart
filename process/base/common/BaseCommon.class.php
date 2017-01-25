<?php 
/*
	auther: cooc 
	email:yemasky@msn.com
*/
class BaseCommon {
	public function __construct() {
	}
					
	public static function getMeta($index = 'index', $title = NULL, $description = NULL, $keywords = NULL, $content = NULL) {
		$arrMeta = NULL;
		include(__WWW_PATH_CONFIG . 'metaConfig.php');
		$arrMetaValue = $arrMeta['index'];
		if(isset($arrMeta[$index])) $arrMetaValue = $arrMeta[$index];
		$arrayMeta['Title'] = $title . $arrMetaValue['title'];
		$arrayMeta['Keywords'] = $keywords . $arrMetaValue['keywords'];
		$arrayMeta['Description'] = $description . $arrMetaValue['description'];
		$arrayMeta['Content'] = $content . $arrMetaValue['content'];
		return $arrayMeta;
	}
		
}
?>