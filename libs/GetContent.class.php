<?php
/*
	class.GetContent.php
	auther: cooc 
	email:yemasky@msn.com
*/

class GetContent {
	private string $userAgent = '';
	private array|null $arrAgent = NULL;
	private array|null $arrAgentCookie = NULL;
	private bool $headerCookie = false;
		/* get content */
	public static function instance() : GetContent {
        return new GetContent();
    }
    public function getCurl($url, $name = NULL, $pass = NULL, $post_data = NULL, $referer = "", $ssl = false) :string {
		set_time_limit(0);
		$cUrl = curl_init();
		if(!$ssl) {
			curl_setopt($cUrl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($cUrl, CURLOPT_SSL_VERIFYHOST, FALSE);
		} else {
			curl_setopt($cUrl,CURLOPT_SSL_VERIFYPEER,true);
			curl_setopt($cUrl,CURLOPT_CAINFO, $ssl);
		}	
		curl_setopt($cUrl, CURLOPT_URL, $url);
        $referer = $referer == "" ? $url : $referer;
		curl_setopt($cUrl, CURLOPT_REFERER, $referer); // 
		if(isset($_SERVER['HTTP_USER_AGENT']))
		    curl_setopt($cUrl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
		if($name != NULL && $pass != NULL) {
			$cookie_jar = __CRAWL . md5($name)."cookie.txt";
			curl_setopt($cUrl, CURLOPT_COOKIEJAR, $cookie_jar);
			curl_setopt($cUrl, CURLOPT_COOKIEFILE, $cookie_jar);
			//curl_setopt($cUrl, CURLOPT_COOKIE, $this->cookieToStr($_COOKIE));
		}
		if($post_data != NULL) {
			curl_setopt($cUrl, CURLOPT_POST, 1); 
			curl_setopt($cUrl, CURLOPT_POSTFIELDS, $post_data);
		}
		curl_setopt($cUrl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($cUrl, CURLOPT_TIMEOUT, 30);
		//curl_setopt($cUrl, CURLOPT_USERAGENT, "Connection: Keep-Alive");
		
		$pageContent = trim(curl_exec($cUrl));
		curl_close($cUrl);
		return $pageContent;
	}

	public function simpleGetCurl($url, $name = NULL, $cookie = true, $referer = "", $rand = true): string {
		$cUrl = curl_init();
		$referer = $referer == "" ? $url : $referer;
		curl_setopt($cUrl, CURLOPT_URL, $url);
		curl_setopt($cUrl, CURLOPT_REFERER, $referer); // 
		curl_setopt($cUrl, CURLOPT_USERAGENT, $this->setUserAgent($rand));
		curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);// 获取的信息以文件流的形式返回
		if(!empty($cookie)) {
			curl_setopt($cUrl, CURLOPT_HEADER, 0);//设定是否输出页面内容 
			curl_setopt($cUrl, CURLOPT_FOLLOWLOCATION, 1);// 不使用自动跳转
			//if(!empty($name)) {
				//if($rand) $name = md5($this->$userAgent).$name;
				$cookie_jar = __CRAWL . md5($name)."cookie.txt";
				curl_setopt($cUrl, CURLOPT_COOKIEJAR, $cookie_jar);
				curl_setopt($cUrl, CURLOPT_COOKIEFILE, $cookie_jar);
			//} else {
				if($cookie !== true) {
					curl_setopt($cUrl, CURLOPT_COOKIE, $cookie); 
				} else {
					curl_setopt($cUrl, CURLOPT_COOKIE, $this->cookieToStr($rand));
				}
			//}
		}
		curl_setopt($cUrl, CURLOPT_TIMEOUT, 30);
		curl_setopt($cUrl, CURLOPT_ENCODING, "gzip" );
		curl_setopt($cUrl, CURLOPT_HTTPHEADER, array(
                "User-Agent:	".$this->setUserAgent($rand),
                "Accept-Language:	zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3",
				"Accept:	text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
				"Accept-Encoding:	gzip, deflate",
				"Connection:	keep-alive"
            ));

		
		$pageContent = curl_exec($cUrl);
		$info = curl_getinfo($cUrl);
		$strHead = substr($pageContent,0,$info['header_size']);
		preg_match("/Set-Cookie:(.+?)\n/i", $strHead, $arrCookie);
		if(!empty($arrCookie[1])) {
			$this->arrAgentCookie[md5($this->userAgent)] = $arrCookie[1];
			$this->headerCookie = true;
			//echo $arrCookie[1] . "<br>";
		}
		curl_close($cUrl);//echo substr($pageContent,$info['header_size']+1);
		//if(!empty($cookie) && !empty($name)) 
		return $pageContent;
	}

	public function cookieToStr($rand = true): string {
		$userAgent = $this->userAgent;
		$md5id = md5($userAgent);
		$strCookie = '';
		if($this->headerCookie) {
			if(isset($this->$arrAgentCookie[$md5id])) $strCookie = $this->$arrAgentCookie[$md5id];
		} else {
			if(!empty($_COOKIE) && count($_COOKIE) > 0) {
				foreach($_COOKIE as $k => $v) {
					$strCookie .= $k . '=' . $v . ';';
				}
				foreach($_COOKIE as $k => $v) {
					//unset($_COOKIE[$k]);
				}
			}
			if(isset($this->$arrAgentCookie[$md5id])) {
				$strCookie = $this->$arrAgentCookie[$md5id];
			} else {
				$this->arrAgentCookie[$md5id] = $strCookie;
			}
		}
		writeLog('#crawQiyi.tv.cookie.log',$strCookie . '<>' . $userAgent);
		return $strCookie;
	}
	
	public function setUserAgent($rand = true): string {
		if(!$rand) return $_SERVER['HTTP_USER_AGENT'];
		$arrUserAgent = $this->arrAgent;
		if(empty($arrUserAgent)) {
			include(__ROOT_PATH . 'etc/crawlConfig.php');
			$this->arrAgent = $arrUserAgent;
		}
		if(!empty($arrUserAgent)) {
            $num = count($arrUserAgent) - 1;
            if($this->userAgent == '') {
                $this->userAgent = $arrUserAgent[$num];
                return $this->userAgent;
            }
            $num = rand(0, $num);
            $userAgent = $arrUserAgent[$num];
            $this->$userAgent = $userAgent;
            return $userAgent;
        }
		return "";
	}
	
	public function request_headers():array {
		if(function_exists("apache_request_headers")) {
			if($headers = apache_request_headers()) {
				return $headers;
			}
		}
		$headers = array();
		foreach(array_keys($_SERVER) as $skey){ 
			if(str_starts_with($skey, "HTTP_")) {
				$headername = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($skey, 0, 5)))));
				$headers[$headername] = $_SERVER[$skey];
			}
		}
		return $headers;
	}

	public function pregmatchContent($preg, $content): array {
		preg_match($preg, $content, $arrMatches);
		return $arrMatches;
	}

	public function pregmatchallContent($preg, $content): array {
		preg_match_all($preg, $content, $arrMatches);
		return $arrMatches;
	}

	public function getSocket($host, $target = '/', $byte = 1024, $port = 80): string {
		$fp = fsockopen($host, $port, $errno, $errstr, 30);
		if (!$fp) {
			//echo "$errstr ($errno)<br />\n";
			return "$errstr ($errno)<br />\n";
		} else {
			$out = "GET $target HTTP/1.1\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n\r\n";
			fwrite($fp, $out);
			$str = '';
			while (!feof($fp)) {
				$str .= fgets($fp, $byte);
			}
			fclose($fp);
			return $str;
		}	
	}
	
	public function getByte($filename, $byte = NULL): ?string {
		$handle = fopen($filename, "r");
		if(!$handle) return NULL;
		$contents = '';
		if(empty($byte)) $byte = filesize($filename);
		while (!feof($handle)) {
			$contents .= fread($handle, 8192);
			if(strlen($contents) >= $byte) break;
		}
		fclose($handle);	
		return $contents;
	}
}
