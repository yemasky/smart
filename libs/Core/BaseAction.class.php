<?php
/**
 * --------------------
 * 参照于struts框架设计,作为Controller层的基类
 * 注: HttpReuqest,HttpResponse类在此文件中定义,是为了快速装载的需要.
 *
 * @author cooc <yemasky@msn.com>
 * @date 2006-06-17
 */

/**
 * 请求对象,用于在各个模块之间传递参数
 * @date 2006-06-17
 */
class HttpRequest {
    /**
     * 分支KEY,即$_REQUEST['action'] *
     */
    private static $ACTION_KEY = "action";
    private $module = '';
    /**
     * 保存从浏览器提交变量,即$_REQUEST.不可修改 *
     */
    private $parameters = array();
    private $php_input = array();
    /**
     * 分支 *
     */
    private $actionValue = null;

    public function __construct() {
        $this->parameters = $_REQUEST;
        $this->analysisInput();
        if (isset($_SERVER['HTTP_METHOD'])) $this->parameters["method"] = $_SERVER['HTTP_METHOD'];
        if (isset($this->parameters["param"])) {
            if ($this->parameters["param"] != null) {
                $this->parameters = array_merge($this->getParse($this->__get("param")), $this->parameters);
                unset($this->parameters["param"]);
            }
        }
    }

    protected function analysisInput() {
        $param = file_get_contents("php://input");
        if (isset($_SERVER['CONTENT_TYPE']) && strpos(strtolower($_SERVER['CONTENT_TYPE']), 'application/json') !== false) {
            $param = json_decode($param, true);
        }
        $this->php_input = $param;
    }

    public function getInput($pname = '') {
        $param = null;
        if (!empty($pname)) {
            if (isset($this->php_input[$pname])) {
                $param = $this->php_input[$pname];
            } else {
                return null;
            }
        } else {
            $param = $this->php_input;
        }
        if (get_magic_quotes_gpc()) {
            return $param;
        }
        if (is_array($param)) {
            return $this->addArraySlashes($param);
        }

        return addslashes($param);
    }

    public function setInput($pname, $arrayInput) {
        if (!empty($pname)) {
            $this->php_input[$pname] = $arrayInput;
        } else {
            $this->php_input = $arrayInput;
        }
    }

    public function validInput($pname) {
        if (isset($this->php_input[$pname])) {
            $param = $this->php_input[$pname];
            if (get_magic_quotes_gpc()) {
                return $param;
            }
            if (is_array($param)) {
                return $this->addArraySlashes($param);
            }

            return addslashes($param);
        }

        return false;
    }

    public function unsetInput($pname) {
        if (!empty($pname)) {
            unset($this->php_input[$pname]);
        } else {
            unset($this->php_input);
        }
    }

    public function __get($pname) {
        $param = null;
        if (isset($this->parameters[$pname])) {
            $param = $this->parameters[$pname];
        } elseif (isset($this->php_input[$pname])) {
            $param = $this->php_input[$pname];
        } else {
            return null;
        }
        if (get_magic_quotes_gpc()) {
            return $param;
        }
        if (is_array($param)) {
            return $this->addArraySlashes($param);
        }

        return addslashes($param);
    }

    public function get($pname = '') {
        if (empty($pname)) return $this->parameters;
        if (isset($this->parameters[$pname])) {
            if (get_magic_quotes_gpc()) {
                return $this->parameters[$pname];
            }
            if (is_array($this->parameters[$pname])) {
                return $this->addArraySlashes($this->parameters[$pname]);
            }

            return addslashes($this->parameters[$pname]);
        } else {
            return null;
        }
    }

    public function getPost($pname = null) {
        if (!empty($pname)) {
            if (isset($_POST[$pname])) {
                return $this->__get($pname);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                return $_POST;
            }

            return $this->addArraySlashes($_POST);
        }

        return null;
    }

    public function setPost($pname = null, $arrayPost) {
        if (!empty($pname)) {
            $_POST[$pname] = $arrayPost;
        } else {
            $_POST = $arrayPost;
        }
    }

    public function addArraySlashes($arrRs) {
        if (empty($arrRs)) return null;
        foreach ($arrRs as $k => $v) {
            if (is_array($v)) {
                $arrRs[$k] = $this->addArraySlashes($v);
            } else {
                $arrRs[$k] = addslashes($v);
            }
        }

        return $arrRs;
    }

    public function __set($pname, $value) {
        if (empty($pname)) {
            return false;
        } else {
            $this->parameters[$pname] = $value;
        }
    }

    public function __isset($pname) {
        return isset($this->parameters[$pname]);
    }

    public function __unset($pname) {
        unset($this->parameters[$pname]);
    }

    // /xxx-aaa/yyy-bbb
    public function getParse($arg) {
        $arrayResult = array();
        $param       = explode("/", $arg);
        foreach ($param as $str) {
            $tmp = explode("-", $str);
            if (!isset($tmp[1])) $tmp[1] = '';
            $arrayResult[$tmp[0]] = $tmp[1];
        }

        return $arrayResult;
    }

    /**
     * 设置内部分支
     */
    public function setAction($actionValue) {
        $this->actionValue = $actionValue;
    }
    /**
     * 取得内部分支
     */
    public function getAction() {
        if ($this->actionValue == null) {
            if (isset($this->parameters[self::$ACTION_KEY])) {
                $this->actionValue = $this->parameters[self::$ACTION_KEY];
            }
        }

        return $this->actionValue;
    }

    /**
     * @return string
     */
    public function getModule(): string {
        return $this->module;
    }

    /**
     * @param string $module
     */
    public function setModule(string $module) {
        $this->module = $module;
    }

}

/**
 * 响应对象,用于设置向View层传递的参数
 *
 * @author cooc <yemasky@msn.com>
 * @date 2006-06-17
 */
class HttpResponse {
    /**
     * 模板文件名 *
     */
    private $tplName = null;
    /**
     * 模板参数 *
     */
    private $tplValues = null;

    private $arrayResponse = array();

    /**
     * 构造函数
     */
    public function __construct() {
        $this->tplName   = null;
        $this->tplValues = array();
    }

    /**
     * 取得模板名
     */
    public function getTplName() {
        return $this->tplName;
    }

    /**
     * 设定模板名
     */
    public function setTplName($tplName) {
        $this->tplName = $tplName;
    }

    /**
     * 设定(添加)模板参数
     */
    public function setTplValue($name, $value) {
        if (empty($name)) {
            throw new Exception("tpl value's name cann't empty.");
        }
        $this->tplValues[$name] = $value;
    }

    public function setResponse($key, $value) {
        $this->arrayResponse[$key] = $value;

        return true;
    }

    public function getResponse($key = '') {
        if (empty($key)) return $this->arrayResponse;
        if (isset($this->arrayResponse[$key])) return $this->arrayResponse[$key];

        return '';
    }

    public function __set($name, $value) {
        if (empty($name)) {
            throw new Exception("tpl value's name cann't empty.");
        }
        $this->tplValues[$name] = $value;
    }

    /**
     * 取得模板中的值(返回数组)
     */
    public function getTplValues($name = null) {
        if (!empty($name)) {
            if (!isset($this->tplValues[$name])) return null;

            return $this->tplValues[$name];
        }

        return $this->tplValues;
    }

    public function __get($name = null) {
        if (!empty($name)) {
            if (!isset($this->tplValues[$name])) return null;

            return $this->tplValues[$name];
        }

        return $this->tplValues;
    }

    public function successResponse($code, $arrayData = '', $url = '') {
        header('HTTP/1.1 200 OK');
        header("Content-type: application/json; charset=" . __CHARSET);
        header("Server: IIS/16.0 (Win64) OpenSSL/1.0.2h ASP.NET/20.3.6");
        header("X-Powered-By: ASP.NET/20.3.6");
        $arrayResult = array('success' => 1, 'code' => $code, 'item' => $arrayData, 'common' => $this->getResponse(), 'redirect' => $url, 'time' => getDateTime());
        echo str_replace('null', '""', json_encode($arrayResult));

        return '';
    }

    public function tablePageResponse($arrayDate) {
        header('HTTP/1.1 200 OK');
        header("Content-type: application/json; charset=" . __CHARSET);
        header("Server: IIS/16.0 (Win64) OpenSSL/1.0.2h ASP.NET/20.3.6");
        header("X-Powered-By: ASP.NET/20.3.6");
        //echo str_replace('null', '""', json_encode($arrayReturnDate));
        echo $arrayDate;

        return '';
    }

    public function errorResponse($code, $arrayData = '', $message = '', $url = '') {
        header('HTTP/1.1 200 OK');
        header("Content-type: application/json; charset=" . __CHARSET);
        header("Server: IIS/16.0 (Win64) OpenSSL/1.0.2h ASP.NET/20.3.6");
        header("X-Powered-By: ASP.NET/20.3.6");
        $arrayResult = array('success' => 0, 'code' => $code, 'message' => $message, 'item' => $arrayData, 'common' => $this->getResponse(), 'redirect' => $url, 'time' => getDateTime());
        echo json_encode($arrayResult);

        return '';
    }

    public function noticeResponse($code, $arrayData = '', $message = '', $url = '') {
        header('HTTP/1.1 200 OK');
        header("Content-type: application/json; charset=" . __CHARSET);
        header("Server: IIS/16.0 (Win64) OpenSSL/1.0.2h ASP.NET/20.3.6");
        header("X-Powered-By: ASP.NET/20.3.6");
        $arrayResult = array('success' => -1, 'code' => $code, 'message' => $message, 'item' => $arrayData, 'common' => $this->getResponse(), 'redirect' => $url, 'time' => getDateTime());
        echo json_encode($arrayResult);

        return '';
    }

    public function successServiceResponse(\SuccessService $successService) {
        header('HTTP/1.1 200 OK');
        header("Content-type: application/json; charset=" . __CHARSET);
        header("Server: IIS/16.0 (Win64) OpenSSL/1.0.2h ASP.NET/20.3.6");
        header("X-Powered-By: ASP.NET/20.3.6");
        $success = 1;
        if($successService->isNotice()) $success = -1;
        if(!$successService->isSuccess()) $success = 0;
        $arrayResult = array('success' => $success, 'code' => $successService->getCode(), 'message' => $successService->getMessage(),
            'item' => $successService->getData(), 'common' => $this->getResponse(), 'redirect' => $successService->getRedirectUrl(), 'time' => getDateTime());
        echo json_encode($arrayResult);

        return '';
    }
}

/**
 * 响应对象,保存了用于在View层显示的数据
 *
 * @author cooc <yemasky@msn.com>
 * @date 2006-06-17
 */
abstract class BaseAction {
    private $displayDisabled = false;
    private $showErrorPage = true;
    private $isHeader = false;
    private $compiler = false;
    private $_cache = false;
    private $_cache_id = '';
    private $_cache_time = 7200;
    private $_cache_dir = __CACHE;
    private $_renew_cachedir = true;
    private $_create_html = false;
    private $_html_name = '';
    private $_html_dir = __HTML;
    private $redirect_url = array();
    private $__commonResponse = array();

    /**
     * 检查入力参数,若是系统错误(严重错误,则抛出异常)
     */
    protected abstract function check(HttpRequest $objRequest, HttpResponse $objResponse);

    /**
     * 执行应用逻辑
     */
    protected abstract function service(HttpRequest $objRequest, HttpResponse $objResponse);

    protected abstract function doMethod(HttpRequest $objRequest, HttpResponse $objResponse);

    protected function checkMethod(HttpRequest $objRequest, HttpResponse $objResponse) {

    }

    public abstract function invoking(HttpRequest $objRequest, HttpResponse $objResponse);

    /**
     * 资源回收
     */
    protected function release(HttpRequest $objRequest, HttpResponse $objResponse) {
    }

    /**
     * 错误处理
     */
    protected function tryexecute(HttpRequest $objRequest, HttpResponse $objResponse) {
    }

    /**
     * 错误处理
     */
    protected function finalexecute(HttpRequest $objRequest, HttpResponse $objResponse) {
    }

    /**
     * 错误页面
     */
    protected function setErrorPage($flag = false) {
        $this->showErrorPage = $flag;
    }

    /**
     * 禁用显示
     */
    protected function setDisplay($flag = true) {
        $this->displayDisabled = $flag;
    }

    /**
     * 缓存页面
     */
    protected function setCache($_cache_id = null, $_cache_time = 7200, $flag = true, $_cache_dir = __CACHE, $_renew_cachedir = true) {
        if (empty($_cache_id)) {
            throw new Exception("_cache_id cann't empty.");
        }
        $this->_cache          = $flag;
        $this->_cache_id       = $_cache_id;
        $this->_cache_time     = $_cache_time;
        $this->_cache_dir      = $_cache_dir;
        $this->_renew_cachedir = $_renew_cachedir;
    }

    protected function setCreateHtml($_html_name, $_html_dir = __HTML, $flag = true) {
        $this->_create_html = $flag;
        $this->_html_name   = $_html_name;
        $this->_html_dir    = $_html_dir;
    }

    /**
     * 是否Header
     */
    protected function sendHeader($flag = true) {
        $this->isHeader = $flag;
    }

    /**
     * *
     * 受否编译模板
     */
    protected function setCompiler($flag = true) {
        $this->compiler = $flag;
    }

    protected function check_null($parameter, $key = null) {
        if (empty($parameter)) {
            throw new Exception("parameter is null:" . $key . '=>' . $parameter);
        }

        return $parameter;
    }

    protected function check_int($int, $key = null) {
        $this->check_null($int, $key);
        if (!is_numeric($int)) {
            throw new Exception("parameter not int:" . $key . '=>' . $int);
        }
        if ((int)$int == $int) {
            return (int)$int;
        } else {
            throw new Exception("parameter not int:" . $key . '=>' . $int);
        }
    }

    protected function check_numeric($numeric, $key = null) {
        $this->check_null($numeric, $key);
        if (!is_numeric($numeric)) {
            throw new Exception("parameter not numeric:" . $key . '=>' . $numeric);
        }

        return $numeric;
    }

    protected function setRedirect($url, $status = '302', $time = 0) {
        $this->setDisplay();
        $this->redirect_url['url']    = $url;
        $this->redirect_url['status'] = $status;
        $this->redirect_url['time']   = $time;
    }

    private function redirect($url, $status = '302', $time = 0) {
        if (is_numeric($url)) {
            header("Content-type: text/html; charset=" . __CHARSET);
            echo "<script>history.go('$url')</script>";
        } else {
            if ($time > 0) {
                echo "<meta http-equiv=refresh content=\"$time; url=$url\">";
            }
            if (headers_sent()) {
                echo "<meta http-equiv=refresh content=\"0; url=$url\">";
                echo "<script type='text/javascript'>location.href='$url';</script>";
            } else {
                if ($status == '302') {
                    header("HTTP/1.1 302 Moved Temporarily");
                    header("Location: $url");
                }
                header("Cache-Control: no-cache, must-revalidate");
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: $url");
            }
        }
        flush();
    }

    /**
     * Controller层的调用入口函数,在scripts中调用
     */
    public function execute($action = null, HttpRequest $objRequest = null, HttpResponse $objResponse = null) {
        $startTime = getMicrotime();
        try {
            $error_handler = set_error_handler("ErrorHandler");
            if (!is_object($objRequest)) $objRequest = new HttpRequest();
            if (!is_object($objResponse)) $objResponse = new HttpResponse();
            // 指定action
            if (!empty($action)) {
                $objRequest->setAction($action);
            }

            // 入力检查
            $this->check($objRequest, $objResponse);
            $isCacheValid = false;
            if ($this->_cache) {
                $objDBCache            = new DBCache();
                $objDBCache->cache_dir = $this->_cache_dir;
                $isCacheValid          = $objDBCache->isValid($this->_cache_id, $this->_cache_time, $this->_renew_cachedir);
            }
            // if(!ob_start("ob_gzhandler"))
            ob_start();
            if (!$isCacheValid) {
                // 执行方法
                $this->service($objRequest, $objResponse);
                if ($this->displayDisabled == false) {
                    $objResponse->__commonResponse = json_encode($objResponse->getResponse());
                    $this->display($objResponse, $this->compiler);
                    if ($this->_cache) {
                        $objDBCache->cachePage($this->_cache_id, json_encode(ob_get_contents()), $this->_renew_cachedir);
                    }
                    if ($this->_create_html) {
                        File::creatFile($this->_html_name, ob_get_contents(), $this->_html_dir);
                    }
                } else {
                    $this->__commonResponse = $objResponse->getResponse();
                }
            } else {
                echo json_decode($objDBCache->fetch($this->_cache_id, false, $this->_renew_cachedir));
            }
            ob_implicit_flush(1);
            ob_end_flush();
            // 资源回收
            $this->release($objRequest, $objResponse);
            // 数据库事务提交(由DBQuery判断是否需要做)//DBQuery::instance()->commit();
        } catch (Exception $e) {
            try {
                // 错误处理
                $this->tryexecute($objRequest, $objResponse);
                // 数据库事务回滚(由DBQuery判断是否需要做)//DBQuery::instance()->rollback();
                if (__Debug) {
                    if ($this->displayDisabled == false) {
                        echo('错误信息: ' . $e->getMessage() . "<br>");
                        echo(str_replace("\n", "\n<br>", $e->getTraceAsString()));
                    } else {
                        if (strpos($e->getMessage(), 'Duplicate entry') != false) {
                            preg_match("/Duplicate entry '(.+?)' for key '(.+?)'/", $e->getMessage(), $message);
                            $arrayErrorValue = explode('-', $message[1]);
                            $objResponse->errorResponse('000004', array($message[2], $arrayErrorValue[0], $e->getMessage(), $e->getTraceAsString()),
                                '保存失败,重复数据,请检查!');
                        } else {
                            preg_match("/(Field |column )'(.*)'/", $e->getMessage(), $message);
                            $error_message = '';
                            if (isset($message[2])) $error_message = $message[2];
                            $objResponse->errorResponse('000003', array($error_message, $e->getMessage(), $e->getTraceAsString()), '系统异常');
                        }
                    }
                } else {
                    if ($this->displayDisabled == false) {
                        //echo('错误信息: ' . $e->getMessage() . "<br>");
                        //echo(str_replace("\n", "\n<br>", $e->getTraceAsString()));
                    } else {
                        if (strpos($e->getMessage(), 'Duplicate entry') != false) {
                            preg_match("/Duplicate entry '(.+?)' for key '(.+?)'/", $e->getMessage(), $message);
                            $arrayErrorValue = explode('-', $message[1]);
                            $objResponse->errorResponse('000004', array($message[2], $arrayErrorValue[0]), '保存失败,重复数据,请检查!');
                        } else {
                            preg_match("/(Field |column )'(.*)'/", $e->getMessage(), $message);
                            $objResponse->errorResponse('000003', array($message[2]), '系统异常');
                        }
                    }
                }
            } catch (Exception $ex) {
                logError($ex->getMessage(), __MODEL_EXCEPTION);
                logError($ex->getTraceAsString(), __MODEL_EXCEPTION);
            }
            // 错误日志
            logError($e->getMessage(), __MODEL_EXCEPTION);
            logError($e->getTraceAsString(), __MODEL_EMPTY);
            // 重定向到错误页面
            // redirect("errorpage.htm");
            // 最终处理
            $this->finalexecute($objRequest, $objResponse);
            // set_exception_handler('exception_handler');
            if (__Debug == false) {
                if ($this->displayDisabled == false) {
                    if ($this->showErrorPage) {
                        $this->redirect(__WEB . "404.htm");

                        return;
                    }
                } else {
                    if (strpos($e->getMessage(), 'Duplicate entry') != false) {
                        preg_match("/^Duplicate entry '(.*)'$/", $ex->getMessage(), $message);
                        $objResponse->errorResponse('000004', array($message[1]), '重复数据,请检查!');
                    } else {
                        $objResponse->errorResponse('000003', array(0), '系统异常');
                    }
                }
            }
        }
        if (!empty($this->redirect_url)) {
            $this->redirect($this->redirect_url['url'], $this->redirect_url['status'], $this->redirect_url['time']);
        }
        // debug
        if (__Debug) {
            $endTime = getMicrotime();
            $useTime = $endTime - $startTime;
            logDebug("excute time $useTime s");
        }
    }

    /**
     * 调用View层输出
     */
    private function display(HttpResponse $objResponse, $compiler = true) {
        if ($this->isHeader == false) {
            header("Content-type: text/html; charset=" . __CHARSET);
            header("Server: IIS/16.0 (Win64) OpenSSL/1.0.2h ASP.NET/20.3.6");
            header("X-Powered-By: ASP.NET/20.3.6");
        }
        $tplName = $objResponse->getTplName();
        if (empty($tplName)) {
            throw new Exception("template name cann't empty.");
        }
        // dispaly
        require(__ROOT_PATH . 'libs/Smarty/libs/Smarty.class.php');
        $smarty                  = new Smarty();
        $smarty->left_delimiter  = "<%";
        $smarty->right_delimiter = "%>";
        $smarty->template_dir    = __ROOT_TPLS_TPATH;
        $smarty->compile_dir     = __ROOT_TPLS_TPATH . "templates_c/";
        $smarty->config_dir      = __ROOT_TPLS_TPATH . "config_dir/";
        $smarty->cache_dir       = __ROOT_TPLS_TPATH . "cache_dir/";
        // 设置默认值(项目相关)
        $smarty->assign("__CHARSET", __CHARSET);
        $smarty->assign("__LANGUAGE", __LANGUAGE);
        $smarty->assign("__WEB", __WEB);
        $smarty->assign("__RESOURCE", __RESOURCE);
        $smarty->assign("__USER_IMGWEB", __USER_IMGWEB);
        $smarty->assign("__IMGWEB", __IMGWEB);

        // bulk assign values
        $smarty->assign($objResponse->getTplValues());
        // diplay the template
        $smarty->display($tplName . ".tpl");
        /*
         * $temp = new Template(__ROOT_TPLS_TPATH, __ROOT_TPLS_TPATH . "templates_c/");
         * //$temp -> setTpl($tplName.".htm");
         * //$temp -> assign($objResponse -> getTplValues());
         * $temp -> assign("__CHARSET", __CHARSET);
         * $temp -> assign("__LANGUAGE", __LANGUAGE);
         * $temp -> assign("__WEB", __WEB);
         * $temp -> display($tplName . ".tpl");
         */
    }
}

class NotFound extends BaseAction {

    protected function check(HttpRequest $objRequest, HttpResponse $objResponse) {
    }

    protected function service(HttpRequest $objRequest, HttpResponse $objResponse) {
        switch ($objRequest->getAction()) {
            default :
                $this->doShowPage($objRequest, $objResponse);
                break;
        }
    }

    protected function doMethod(HttpRequest $objRequest, HttpResponse $objResponse) {
        // TODO: Implement doMethod() method.
    }

    public function invoking(HttpRequest $objRequest, HttpResponse $objResponse) { }

    /**
     * 首页显示
     */
    protected function doShowPage(HttpRequest $objRequest, HttpResponse $objResponse) {
        $objResponse->setTplName("NotFound");
    }
}

class DBQuery {
    private $dsn = null;
    private $conn = null;
    private static $instances = array();
    private static $defaultDsn = __DEFAULT_DSN;
    private $table_name = '';
    private $entity_class = '';
    private $table_key = '*';

    /**
     * 构造函数
     */
    public function __construct($dsn) {
        $this->dsn  = $dsn;
        $this->conn = $this->connect($dsn);
    }

    function connect($dsn = null) {
        if (strlen($dsn) > 0) {
            $this->dsn = $dsn;
        }
        if (is_array($dsn)) {
            $dsn = array_rand($dsn);
        }
        $arrayDsnInfo = $this->parseDsn($dsn);
        switch ($arrayDsnInfo['driver']) {
            case 'mysqli':
                require_once(__ROOT_PATH . 'libs/Drivers/mysqliDriver.php');
                $startTime  = getMicrotime();
                $this->conn = new mysqliDriver($arrayDsnInfo);
                break;
            default:
                require_once(__ROOT_PATH . 'libs/Drivers/mysqliDriver.php');
                $startTime  = getMicrotime();
                $this->conn = new mysqliDriver($arrayDsnInfo);
                break;
        }

        if (__Debug) logDebug("connect use time: " . (getMicrotime() - $startTime));
        $this->conn->setCharacter($arrayDsnInfo['character']);//character

        return $this->conn;
    }

    /**
     * 数据库连接实例
     */
    public static function instance($dsn = "") {
        if (empty($dsn)) {
            $dsn = self::$defaultDsn;
        }
        if (isset(self::$instances[$dsn])) {
            $instance = self::$instances[$dsn];
            if (!empty($instance) && is_object($instance)) {
                $instance->join_table = $instance->table_name = null;

                return $instance;
            }
        }
        $instance              = new DBQuery($dsn);
        self::$instances[$dsn] = $instance;

        return $instance;
    }

    public function setTable($table) {
        $this->table_name = '`' . $table . '`';

        return $this;
    }

    public function getTable() {
        $table = $this->table_name;
        if (empty($table)) throw new Exception("table is empty.");
        return $table;
    }

    public function setEntityClass($entity_class) {
        $this->entity_class = $entity_class;

        return $this;
    }

    public function getEntityClass(): string {
        $entity_class = $this->entity_class;
        if (empty($entity_class)) throw new Exception("entity_class is empty.");
        return $entity_class;
    }

    public function getEntityTable($entity_class = null): string {
        if(empty($entity_class)) $entity_class = $this->entity_class;
        if (empty($entity_class)) throw new Exception("entity_class is empty.");

        return strtolower(str_replace('Model', '',str_replace('Entity', '', substr($entity_class, strrpos($entity_class, '\\') + 1))));
    }

    public function setKey($table_key) {
        $this->table_key = $table_key;

        return $this;
    }

    private function getEntityField($entytyClass) {
        $reflect = new ReflectionClass($entytyClass);
        $props   = $reflect->getProperties();
        $field = '';
        foreach ($props as $prop) {
            $field .= $prop->getName() . ",";
        }
        return trim($field, ',');
    }

    /**
     * 从数据表中查找记录
     *
     * @param
     *            conditions 查找条件，数组array("字段名"=>"查找值")或字符串，
     *            请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
     * @param
     *            sort 排序，等同于"ORDER BY "
     * @param
     *            fields 返回的字段范围，默认为返回全部字段的值
     * @param
     *            limit 返回的结果数量限制，等同于"LIMIT "，如$limit = " 3, 5"，即是从第3条记录（从0开始计算）开始获取，共获取5条记录
     *            如果limit值只有一个数字，则是指代从0条记录开始。
     */
    public function getList($fields = '*', WhereCriteria $whereCriteria) {
        if ($this->table_key != '*' && !empty($this->table_key) && empty($whereCriteria->getOrder()))
            $whereCriteria->ORDER($this->table_key);
        //if($fields != '*' && strpos($fields, '`') === false) {
            //$fields = str_replace(' ', '', $fields);
            //$fields = '`' . str_replace(',', '`,`', $fields) . '`';
        //}
        $sql = "SELECT {$fields} FROM {$this->table_name} " . $whereCriteria->getWhere();
        if ($whereCriteria->getLimit() != '') $sql = $this->conn->setlimit($sql, $whereCriteria->getLimit());

        return $this->conn->getQueryArrayResult($sql, $whereCriteria);
    }

    public function getEntityList($fields = '*', WhereCriteria $whereCriteria) {
        if ($this->table_key != '*' && !empty($this->table_key) && empty($whereCriteria->getOrder()))
            $whereCriteria->ORDER($this->table_key);

        if(empty($fields) || $fields == '*') {
            if(!empty($this->getEntityClass())) {
                $fields = $this->getEntityField($this->getEntityClass());
            } else {
                $fields = '*';
            }
        }
        $sql = "SELECT {$fields} FROM " . $this->getEntityTable() . $whereCriteria->getWhere();
        if ($whereCriteria->getLimit() != '') $sql = $this->conn->setlimit($sql, $whereCriteria->getLimit());

        return $this->conn->getEntityArrayResult($sql, $this->getEntityClass(), $whereCriteria);
    }

    public function getEntity($fields = '*', WhereCriteria $whereCriteria) {
        $result = $this->getEntityList($fields, $whereCriteria);
        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    /**
     * 在数据表中新增一行数据
     *
     * @param
     *            row 数组形式，数组的键是数据表中的字段名，键对应的值是需要新增的数据。
     */
    public function insert($row, $insert_type = 'INSERT') {
        if (!is_array($row)) return FALSE;
        if (empty($row)) return FALSE;
        $cols = $statement = $vals = '';
        foreach ($row as $key => $value) {
            $cols       .= '`' . $key . '`,';
            $param_type = "s";
            if (is_integer($value)) $param_type = "i";
            $statement .= $param_type;
            $vals      .= '?,';
            $param[]   = $value;
        }
        $cols = trim($cols, ',');
        $vals = trim($vals, ',');

        $sql = $insert_type . " INTO {$this->table_name} ({$cols}) VALUES ($vals)";
        $this->conn->executePrepareStatement($sql, $statement, $param);

        return $this;
    }

    /*
     * //批量插入 insert into(key) values(val),(val) ......
     * //array(0=>array('key1'=>'val1','key2'=>'val2' ...), 1=>array('key1'=>'val1','key2'=>'val2' ...),.....);
     */
    public function batchInsert($arrayValues, $insert_type = 'INSERT') {
        if (!is_array($arrayValues)) return false;
        if (empty($arrayValues)) return false;
        $cols  = $vals = $field = $statement = '';
        $param = [];
        foreach ($arrayValues as $i => $arrayValue) {
            $vals .= '(';
            foreach ($arrayValue as $key => $value) {
                if ($field == '') $cols .= '`' . $key . '`,';
                $vals      .= "?,";
                $param[]   = $value;
                $statement .= 's';
            }
            $field = '1';
            $vals  = trim($vals, ',');
            $vals  .= '),';
        }
        $vals = trim($vals, ',');
        $cols = trim($cols, ',');

        $sql = $insert_type . " INTO {$this->table_name} ({$cols}) VALUES {$vals};";
        $this->conn->executePrepareStatement($sql, $statement, $param);

        return $this;
    }

    public function insertSelect($field = '', $secondField = '*', $secondTable = '', $insert_type = 'INSERT') {
        if (empty($secondTable)) $secondTable = $this->table_name;
        if (!empty($field)) $field = '(' . $field . ')';
        $sql = $insert_type . " INTO {$this->table_name} $field SELECT $secondField FROM {$secondTable} {$this->where};";
        $this->conn->execute($sql);

        return $this;
    }

    public function insertEntity($objEntity, $insert_type = 'INSERT') {
        //
        $arrayInsertData = [];
        foreach ($objEntity->getPrototype() as $key => $value) {
            if ($value !== null) {
                $arrayInsertData[$key] = $value;
            }
        }
        $this->table_name = $this->getEntityTable(get_class($objEntity));
        if(!empty($arrayInsertData)) return $this->insert($arrayInsertData, $insert_type);
        return $this;
    }

    public function batchInsertEntity($arrayEntityValues, $field_key = '', $insert_type = 'INSERT') : array {
        if (!is_array($arrayEntityValues)) return false;
        if (empty($arrayEntityValues)) return false;
        $insertId = [];
        foreach ($arrayEntityValues as $i => $objEntity) {
            $field_vaule = $i;
            if($field_key != '') $field_vaule = $objEntity->getPrototype($field_key);
            $insertId[$field_vaule] = $this->insertEntity($objEntity, $insert_type)->getInsertId();
        }

        return $insertId;
    }

    public function getInsertId() {
        return $this->conn->getInsertid();
    }

    public function execute($sql) {
        $this->conn->execute($sql);

        return $this;
    }

    /**
     * 按条件删除记录
     *
     * @param
     *            conditions 数组形式，查找条件，此参数的格式用法与getOne/getList的查找条件参数是相同的。
     */
    public function delete(WhereCriteria $whereCriteria) {
        $sql = "DELETE FROM {$this->table_name} " . $whereCriteria->getWhere();

        return $this->conn->executePrepareStatement($sql, $whereCriteria->getStatement(), $whereCriteria->getParam());
    }

    /**
     * 返回上次执行update,insertData,delete,exec的影响行数
     */
    public function affectedRows() {
        return $this->conn->affected_rows();
    }

    /**
     * 计算符合条件的记录数量
     *
     * @param
     *            conditions 查找条件，数组array("字段名"=>"查找值")或字符串，
     *            请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
     */
    public function getCount($fields = null, WhereCriteria $whereCriteria) {
        $fields = empty($fields) ? $this->table_key : $fields;
        $sql    = "SELECT COUNT({$fields}) AS count_num FROM {$this->table_name} " . $whereCriteria->getWhere();
        $result = $this->conn->getQueryArrayResult($sql, $whereCriteria);

        return isset($result[0]) ? $result[0]['count_num'] : 0;
    }

    /**
     * 修改数据，该函数将根据参数中设置的条件而更新表中数据
     *
     * @param
     *            conditions 数组形式，查找条件，此参数的格式用法与getOne/getList的查找条件参数是相同的。
     * @param
     *            row 数组形式，修改的数据，
     *            此参数的格式用法与insertData的$row是相同的。在符合条件的记录中，将对$row设置的字段的数据进行修改。
     */
    public function update(WhereCriteria $whereCriteria, array $row, string $update_type = '') {
        if (empty($row)) return false;
        $param = array();
        if (is_array($row)) {
            $cols = $statement = '';
            foreach ($row as $key => $value) {
                $cols       .= '`' . $key . '` = ?,';
                $param_type = "s";
                if (is_integer($value)) $param_type = "i";
                $statement .= $param_type;
                $param[]   = $value;
            }
            $cols = trim($cols, ',');
        } else {
            throw new Exception('更新失败。参数错误！');
        };
        $whereParam = $whereCriteria->getParam();
        $thisParam  = array_merge($param, $whereParam);
        $sql        = "UPDATE $update_type {$this->table_name} SET {$cols} " . $whereCriteria->getWhere();

        return $this->conn->executePrepareStatement($sql, $statement . $whereCriteria->getStatement(), $thisParam);
    }

    /*
     * //field=>[field1=>[1=>2,3=>4],field2=>[1=>3,3=>5]]
     * //key=>[name=>'',value=>[value1,value2]]
     */
    public function batchUpdateByKey($arrayUpdate, WhereCriteria $whereCriteria, $update_type = '') {
        if (!is_array($arrayUpdate)) return false;
        $fieldKey  = $arrayUpdate['key'];
        $updateSql = "UPDATE $update_type {$this->table_name} SET \n";
        foreach ($arrayUpdate['field'] as $field => $arrayValue) {
            $updateSql .= $field . ' = CASE ' . $fieldKey . "\n";
            foreach ($arrayValue as $key => $value) {
                if ($value == '') {
                    $updateSql .= " WHEN $key  THEN '{$value}'\n";
                } elseif ($value == 'NULL') {
                    $updateSql .= " WHEN $key THEN NULL\n";
                } elseif ($value == null) {
                    $updateSql .= " WHEN $key THEN NULL\n";
                } elseif (is_bool($value)) {
                    $updateSql .= " WHEN $key THEN " . $value . "\n";
                } else {
                    $updateSql .= " WHEN $key THEN '{$value}'\n";
                }
            }
            $updateSql .= "END,\n";
        }//$this->where(['IN' => [$arrayWhere['key'] => $arrayWhere['val']]]);
        $updateSql = trim($updateSql, ",\n") . "\n " . $whereCriteria->getWhere();
        $this->conn->executePrepareStatement($updateSql, $whereCriteria->getStatement(), $whereCriteria->getParam());

        return $this;
        /*  $arrayUpdate = [field=>[[1=>2],[2=>3]];
            UPDATE categories
            SET display_order = CASE id
                WHEN 1 THEN 3
                WHEN 2 THEN 4
                WHEN 3 THEN 5
            END,
            title = CASE id
                WHEN 1 THEN 'New Title 1'
                WHEN 2 THEN 'New Title 2'
                WHEN 3 THEN 'New Title 3'
            END
            WHERE id IN (1,2,3)*/
    }

    public function parseDsn($dsn) {
        //$dsn = "pdo:mysql://localhost:3306/softforum?user=soft&password=@!#$%&`~=+'\"&characterEncoding=utf-8";
        $arrayDsnKey = array('driver' => ':', 'type' => '://', 'host' => ':', 'port' => '/', 'database' => '?user=', 'login' => '&password=', 'password' => '&characterEncoding=');
        $arrayDriver = array();
        foreach ($arrayDsnKey as $key => $value) {
            $arrayDriver[$key] = substr($dsn, 0, strpos($dsn, $value));
            $dsn               = substr($dsn, strpos($dsn, $value) + strlen($value));
        }
        $arrayDriver['character'] = $dsn;
        $arrayDriver['driver']    = $arrayDriver['driver'] == 'pdo' ? 'pdoDriver' : $arrayDriver['driver'] . 'Driver';

        return $arrayDriver;
    }

    //事务
    public function startTransaction() {
        return $this->conn->startTransaction();
    }

    public function enableAutocommit() {
        return $this->conn->enableAutocommit();
    }

    public function disableAutocommit() {
        return $this->conn->disableAutocommit();
    }

    public function commit() {
        return $this->conn->commit();
    }

    public function rollback() {
        return $this->conn->rollback();
    }
    //

    /**
     * 魔术函数，执行模型扩展类的自动加载及使用
     */
    public function __call($objName, $args) {
        $objCallName = new $objName($args); // var_dump($objCallName);
        $objCallName->setCallObj($this, $args);

        return $objCallName;
    }

    public function __destruct() {
    }
}

class WhereCriteria {
    private static $instanceKey = array();
    //
    private $where = '';
    private $order = '';
    private $group = '';
    private $limit = '';
    private $AND = '';
    private $param = array();
    private $hashKey = '';
    private $multiple = false;
    private $fatherKey = '';
    private $childrenKey = '';
    private $statement = '';

    public function __construct() {
    }

    public static function instance(string $key): WhereCriteria {
        if (!isset(self::$instanceKey[$key])) self::$instanceKey[$key] = new WhereCriteria();

        return self::$instanceKey[$key];
    }

    public function emptyParam(): WhereCriteria {
        self::$instanceKey = array();
        //
        $this->where       = '';
        $this->order       = '';
        $this->group       = '';
        $this->limit       = '';
        $this->AND         = '';
        $this->param       = array();
        $this->hashKey     = '';
        $this->multiple    = false;
        $this->fatherKey   = '';
        $this->childrenKey = '';
        $this->statement   = '';

        return $this;
    }

    /**
     * @return string
     *  EQ =
     */
    public function getWhere(): string {
        $where = $this->where == '' ? '' : ' WHERE ';

        return $where . $this->where . $this->order . $this->group;
    }

    /**
     * @return array
     */
    public function getParam(): array {
        return $this->param;
    }

    /**
     * @return string
     */
    public function getStatement(): string {
        return $this->statement;
    }

    /**
     * @param string $key ,$value
     * EQ $key = $value
     */
    public function EQ(string $key, string $value): WhereCriteria {
        $this->where   .= $this->AND . '`' . $key . '` = ?';
        $this->param[] = $value;
        $this->AND     = ' AND ';
        $param_type    = "s";
        if (is_integer($value)) $param_type = "i";
        $this->statement .= $param_type;

        return $this;
    }

    /**
     * @param string $array
     * EQ $key = $value
     */
    public function ArrayEQ(array $arrayEQ): WhereCriteria {
        if (is_array($arrayEQ)) {
            foreach ($arrayEQ as $key => $value) {
                $this->EQ($key, $value);
            }
        }

        return $this;
    }

    /**
     * @param string $key ,$value
     * GT $key > $value
     */
    public function GT(string $key, string $value): WhereCriteria {
        $this->where   .= $this->AND . '`' . $key . '` > ?';
        $this->param[] = $value;
        $this->AND     = ' AND ';
        $param_type    = "s";
        if (is_integer($value)) $param_type = "i";
        $this->statement .= $param_type;

        return $this;
    }

    /**
     * @param string $key ,$value
     * GE $key >= $value
     */
    public function GE(string $key, string $value): WhereCriteria {
        $this->where   .= $this->AND . '`' . $key . '` >= ?';
        $this->param[] = $value;
        $this->AND     = ' AND ';
        $param_type    = "s";
        if (is_integer($value)) $param_type = "i";
        $this->statement .= $param_type;

        return $this;
    }

    /**
     * @param string $key ,$value
     * LT $key < $value
     */
    public function LT(string $key, string $value): WhereCriteria {
        $this->where   .= $this->AND . '`' . $key . '` < ?';
        $this->param[] = $value;
        $this->AND     = ' AND ';
        $param_type    = "s";
        if (is_integer($value)) $param_type = "i";
        $this->statement .= $param_type;

        return $this;
    }

    /**
     * @param string $key ,$value
     * LE $key <= $value
     */
    public function LE(string $key, string $value): WhereCriteria {
        $this->where   .= $this->AND . '`' . $key . '` <= ?';
        $this->param[] = $value;
        $this->AND     = ' AND ';
        $param_type    = "s";
        if (is_integer($value)) $param_type = "i";
        $this->statement .= $param_type;

        return $this;
    }

    /**
     * @param string $key ,$value
     * NE $key != $value
     */
    public function NE(string $key, string $value): WhereCriteria {
        $this->where   .= $this->AND . '`' . $key . '` != ?';
        $this->param[] = $value;
        $this->AND     = ' AND ';
        $param_type    = "s";
        if (is_integer($value)) $param_type = "i";
        $this->statement .= $param_type;

        return $this;
    }

    /**
     * @param string $key ,$value
     * IN $key IN ($value)
     */
    public function IN(string $key, string $value): WhereCriteria {
        $this->where   .= $this->AND . '`' . $key . '` IN (?)';
        $this->param[] = $value;
        $this->AND     = ' AND ';
        $param_type    = "s";
        if (is_integer($value)) $param_type = "i";
        $this->statement .= $param_type;

        return $this;
    }

    /**
     * @param string $key ,$value
     * IN $key IN ($value)
     */
    public function ArrayIN(string $key, array $value): WhereCriteria {
        if (empty($value)) return $this;
        //$size  = count($value);
        $param = $statement = '';
        //for ($i = 0; $i < $size; $i++) {
        foreach ($value as $i => $v) {
            $param      .= '?,';
            $param_type = "s";
            if (is_integer($v)) $param_type = "i";
            $statement     .= $param_type;
            $this->param[] = $v;
        }
        $param           = trim($param, ',');
        $this->where     .= $this->AND . '`' . $key . '` IN (' . $param . ')';
        $this->AND       = ' AND ';
        $this->statement .= $statement;

        return $this;
    }

    /**
     * @param string $key ,$value
     * LIKE $key LIKE ($value)
     */
    public function LIKE(string $key, array $value): WhereCriteria {
        $this->where   .= $this->AND . '`' . $key . '` LIKE (%?%)';
        $this->param[] = $value;
        $this->AND     = ' AND ';
        $param_type    = "s";
        if (is_integer($value)) $param_type = "i";
        $this->statement .= $param_type;

        return $this;
    }

    /**
     * @param string $key ,$value
     * MATCH $key MATCH ($value)
     */
    public function MATCH(string $key, array $value): WhereCriteria {
        $this->where   .= $this->AND . 'MATCH (' . $key . ') AGAINST (?)';
        $this->param[] = $value;
        $this->AND     = ' AND ';
        $param_type    = "s";
        if (is_integer($value)) $param_type = "i";
        $this->statement .= $param_type;

        return $this;
    }

    /**
     * @param string $sql
     * SQL
     */
    public function SQL($sql): WhereCriteria {
        $this->where .= $this->AND . $sql;
        $this->AND   = ' AND ';

        return $this;
    }

    /**
     * @param string $begin ,$pass
     * LIMIT
     */
    public function LIMIT(string $begin, string $pass): WhereCriteria {
        $this->limit = $begin . ',' . $pass;

        return $this;
    }

    /**
     * @return string
     */
    public function getLimit(): string {
        return $this->limit;
    }

    /**
     * @param string $key ,$sort
     * ORDER $key SORT
     */
    public function ORDER(string $key, string $sort = 'DESC'): WhereCriteria {
        $this->order .= $this->order == '' ? ' ORDER BY ' . $key . ' ' . $sort : ', ' . $key . ' ' . $sort;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrder(): string {
        return $this->order;
    }

    /**
     * @param string $key
     * GROUP $key
     */
    public function GROUP(string $key): WhereCriteria {
        $this->group .= $this->group == '' ? ' GROUP BY ' . $key : ', ' . $key;

        return $this;
    }

    /**
     * @param string $hashKey
     */
    public function setHashKey(string $hashKey): WhereCriteria {
        $this->hashKey = $hashKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getHashKey(): string {
        return $this->hashKey;
    }

    /**
     * @param bool $multiple
     */
    public function setMultiple(bool $multiple): WhereCriteria {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool {
        return $this->multiple;
    }

    /**
     * @param string $fatherKey
     */
    public function setFatherKey(string $fatherKey): WhereCriteria {
        $this->fatherKey = $fatherKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getFatherKey(): string {
        return $this->fatherKey;
    }

    /**
     * @param string $childrenKey
     */
    public function setChildrenKey(string $childrenKey): WhereCriteria {
        $this->childrenKey = $childrenKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getChildrenKey(): string {
        return $this->childrenKey;
    }

}

class DBCache {
    /**
     * 默认的数据生存期
     */
    public $life_time = 3600;//秒
    public $cache_dir = __CACHE_FILE;
    /**
     * 模型对象
     */
    private $objModel = null;

    /**
     * 调用时输入的参数
     */
    private $input_args = null;

    public function __construct() {
    }

    /**
     * 函数式使用模型辅助类的输入函数
     */
    public function setCallObj(& $objModel, $args) {
        $this->objModel   = $objModel; // var_dump($obj);
        $this->input_args = $args;//print_r($args);

        return $this;
    }

    /**
     * $func_args[1] 0 or null or 1 ：文件缓存；-1：删除文件缓存；  2：redis缓存；-2：删除redis缓存； 3：mencache缓存； -3：删除mencache缓存
     * 魔术函数，支持多重函数式使用类的方法 不支持自定义缓存文件夹，系统将自动生成缓存文件夹
     * $this->input_args[0] 缓存时间； 0 or 字符串 永久缓存
     */
    public function __call($func_name, $func_args) { // var_dump($this->objModel);
        //echo serialize($this->objModel) . $this->input_args[0] . $func_name . json_encode($func_args);
        if (is_numeric($this->input_args[0])) {
            $cache_id = md5(serialize($this->objModel) . $this->input_args[0] . $func_name . json_encode($func_args));
        } else {
            //没有缓存时间即为永久缓存
            $cache_id            = $this->input_args[0];//缓存时间不为字符串时，为自定义缓存
            $this->input_args[0] = 0;//永久缓存
        }
        $display = isset($this->input_args[3]) ? $this->input_args[3] : false;
        if ($this->input_args[0] >= 0) {
            $this->life_time = $this->input_args[0];
            $this->cache_dir = $this->cache_dir . $this->life_time . '/';
        }
        if (isset($this->input_args[1])) {
            if ($this->input_args[1] == -1) $this->deleteCache($cache_id);
        } elseif ($this->isValid($cache_id, $this->life_time)) {
            return $this->fetch($cache_id, $display);
        }
        $run_result = call_user_func_array(array($this->objModel, $func_name), $func_args);
        if (isset($this->input_args[1]) && $this->input_args[1] == -1) return $run_result;

        return $this->cache($cache_id, $run_result, true);
    }

    public function cache($cache_id, $run_result, $renew_cachedir = true) {
        $this->cachePage($cache_id, $run_result, $renew_cachedir);

        return $run_result;
    }

    public function deleteCache($cacheID, $renew_cachedir = true) {
        $filepath = PathManager::getCacheDir($cacheID, $this->cache_dir, $renew_cachedir);
        if (!file_exists($filepath . $cacheID)) {
            return true;
        }
        if (!unlink($filepath . $cacheID)) {
            throw new Exception(".error: can't delete file:" . $filepath . $cacheID);
        }

        return true;
    }

    public function fetch($cacheID, $display = false, $renew_cachedir = true) {
        $filepath  = PathManager::getCacheDir($cacheID, $this->cache_dir, $renew_cachedir);
        $_contents = File::readFile($cacheID, $filepath);
        if ($display) {
            echo json_decode($_contents, true);

            return;
        }

        return json_decode($_contents, true);
    }

    public function isValid($cacheID, $cacheTime, $renew_cachedir = true) {
        $filepath   = PathManager::getCacheDir($cacheID, $this->cache_dir, $renew_cachedir);
        $_cacheFile = $filepath . $cacheID;
        if (!is_readable($_cacheFile)) {
            return false;
        } //clearstatcache(); //clearn filemtime function cache
        if ($this->life_time == 0) return true;
        $now       = time();
        $fileMTime = filemtime($_cacheFile);

        return ($now - $fileMTime) < $cacheTime;
    }

    public function cachePage($cacheID, $contents, $renew_cachedir = true) {
        //if(isset($contents['ioy_cooc_cache']) && $contents['ioy_cooc_cache'] == false) return;
        $filepath = PathManager::createCacheDir($cacheID, $this->cache_dir, $renew_cachedir);
        $contents = json_encode($contents);

        return File::creatFile($cacheID, $contents, $filepath);
    }
}

class Session {

    public function __construct() {
        $this->sesstionStar();
    }

    private function sesstionStar() {
        if (!session_id()) {
            session_start();
        }
        // if(function_exists(session_cache_limiter)) {
        // session_cache_limiter("private, must-revalidate");
        // }
    }

    public function __set($name, $value) {
        $_SESSION[md5(__WEB . $name)] = $value;
    }

    public function __get($name) {
        if (isset($_SESSION[md5(__WEB . $name)])) {
            return $_SESSION[md5(__WEB . $name)];
        }

        return null;
    }

    public function __unset($name) {
        unset($_SESSION[md5(__WEB . $name)]);
    }

    public function clean() {
        session_unset();
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
    }
}

class Cookie {
    public static $objEncrypt = null;
    public $arrCookie = null;
    public $arrHash = null;

    public function __construct() {
        if (!is_object(self::$objEncrypt)) self::$objEncrypt = new Encrypt();
        if (!empty($_COOKIE)) {
            foreach ($_COOKIE as $k => $v) {
                $name                   = self::$objEncrypt->decode($k);
                $value                  = self::$objEncrypt->decode($v);
                $this->arrCookie[$name] = $value;
                $this->arrHash[$name]   = $k;
            }
        }
    }

    public function setCookie($name, $value = null, $time = null, $path = "/", $domain = "", $secure = false, $httponly = true) {
        if (!is_object(self::$objEncrypt)) self::$objEncrypt = new Encrypt();
        if ($time != null) {
            $time = time() + $time;
        }
        $name = isset($this->arrHash[md5(__WEB . $name)]) ? $this->arrHash[md5(__WEB . $name)] : self::$objEncrypt->encode(md5(__WEB . $name));
        setcookie($name, self::$objEncrypt->encode($value), $time, $path, $domain, $secure, $httponly);
    }

    public function __set($name, $value) {
        $this->setCookie($name, $value);
    }

    public function __get($name) {
        if (isset($this->arrCookie[md5(__WEB . $name)])) {
            return $this->arrCookie[md5(__WEB . $name)];
        }

        return null;
    }

    public function __isset($name) {
        return isset($this->arrCookie[md5(__WEB . $name)]);
    }

    public function __unset($name) {
        if (isset($this->arrHash[md5(__WEB . $name)])) {
            setcookie($this->arrHash[md5(__WEB . $name)], '', time() - 36000, "/", "", false, true);
        }
    }

    public function delSimpleCookie($name) {
        setcookie($name, '', time() - 3600);
    }

    public function setSimpleCookie($name, $value = null, $time = null, $path = "", $domain = "", $secure = false, $httponly = false) {
        if (empty($name)) {
            return false;
        }
        if ($time != null) {
            $time = time() + $time;
        }
        setcookie($name, $value, $time, $path, $domain, $secure, $httponly);
    }

    public function getSimpleCookie($name) {
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }
    }
}

?>