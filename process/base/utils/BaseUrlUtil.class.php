<?php

/**
 * User: YEMASKY
 * Date: 2016/9/25
 * Time: 8:43
 */
class BaseUrlUtil {
    public static function Url($arrayValue = '') {
        if(empty($arrayValue)) return 'index.php';
        $url = '';
        if(isset($arrayValue['module']) && !empty($arrayValue['module'])) {
            $url .= 'module=' . encode($arrayValue['module']);
            unset($arrayValue['module']);
        }
        foreach($arrayValue as $vk => $vv) {
            if(!empty($vv)) {
                if(!empty($url)) $url .= '&';
                $url .= $vk . '=' . $vv;
            }
        }
        return 'index.php?'.$url;
    }

	public static function getAppUrl($arrayValue = '') {
		if(empty($arrayValue)) return 'index.php';
		$url = '';
		if(isset($arrayValue['module']) && !empty($arrayValue['module'])) {
			$url .= 'channel=' . encode($arrayValue['module']);
			unset($arrayValue['module']);
		}
		if(!empty($arrayValue) && is_array($arrayValue)) {
			foreach($arrayValue as $vk => $vv) {
				if(!empty($vv)) {
					if(!empty($url)) $url .= '&';
					$url .= $vk . '=' . $vv;
				}
			}
		}
		return 'app.do?'.$url;
	}
    ///xxx-aaa/yyy-bbb
    public static function getHtmlUrl($arrayValue = NULL, $name = '') {
        $htmlurl = '';
        if(isset($arrayValue['module']) && !empty($arrayValue['module'])) {
            $htmlurl .= '/' . encode($arrayValue['module']);
            unset($arrayValue['module']);
        }
        if(!empty($arrayValue) && is_array($arrayValue)) {
            foreach($arrayValue as $k => $v) {
                if(is_array($v)) {
                    foreach($v as $vk => $vv) {
                        if(!empty($vv)) $htmlurl .= '/' . $vk . '-' . urlencode($vv);
                    }
                } else {
                    if(!empty($v)) {
                        $htmlurl .= '/' . $k . '-' . urlencode($v);
                    }
                }
            }
        }
        return $name . $htmlurl . '.do';
    }

}