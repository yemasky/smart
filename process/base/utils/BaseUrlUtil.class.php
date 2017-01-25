<?php

/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2016/9/25
 * Time: 8:43
 */
class BaseUrlUtil {
    public static function Url($arrayValues = '') {
        if(empty($arrayValues)) return 'index.php';
        $url = '';
        $mobule = '';
        foreach($arrayValues as $vk => $vv) {
            if(!empty($vv)) {
                if(!empty($url)) $url .= '&';
                $url .= $vk . '=' . $vv;
            }
        }
        //return $url;
        return 'index.php?'.$url;
    }
    public static function getHtmlUrl($name, $arrValue = NULL) {
        $htmlurl = '';
        if(!empty($arrValue) && is_array($arrValue)) {
            foreach($arrValue as $k => $v) {
                if(is_array($v)) {
                    foreach($v as $vk => $vv) {
                        if(!empty($vv)) $htmlurl .= '--' . $vk . '-' . urlencode($vv);
                    }
                } else {
                    if(!empty($v)) $htmlurl .= '--' . $k . '-' . urlencode($v);
                }
            }
        }
        return $name . $htmlurl . '.html';
    }

    public static function getRegisterUrl() {
        return self::getHtmlUrl('register');
    }

    public static function getSiteUrl($channel, $pn = NULL, $videoId = NULL, $tagOrSeries = NULL) {
        if($channel == 'search') return __WEB . $channel . '.html?s=' . $videoId . '&pn=' . $pn;
        if($tagOrSeries != '' && $videoId == NULL) $tagOrSeries = $tagOrSeries . '-';
        if($pn) return __WEB . $channel . '/' . $tagOrSeries . $pn . '.html';
        if($tagOrSeries > 0 && $videoId > 0) $tagOrSeries = '-' . $tagOrSeries;
        if($videoId > 0) return __WEB . $channel . '/view/' . $videoId . $tagOrSeries . '.html';
        return __WEB . $channel . '/';
    }
}