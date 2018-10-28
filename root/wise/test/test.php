<?php
require_once ("../config.php");


$aaa = new company();
$aaa ->setCompanyId(11111);
$aaa ->setCompanyName("ssssss");

print_r($aaa->getVars());

print_r(get_object_vars($aaa));
return;

WhereCriteria::instance('s')->EQ('company_id', 1)->EQ('group_unified_settings', "0");

$sss=DBQuery::instance(\wise\DbConfig::dsnRead())->setEntityClass('company')->getEntityList("company_id,company_name", WhereCriteria::instance('s'));

print_r($sss);

return;


class Prototype extends ArrayObject
{
	private $___class = null;

	public function __get($key)
	{
		return $this[$key];
	}

	public function __set($key, $value)
	{
		$this[$key] =  $value;
	}

	public function __call($key, $args)
	{
		if(is_object($this->___class) && is_callable([$this->___class, $key])){
			return call_user_func_array([$this->___class, $key],$args);
		}
		return is_callable($c = $this->__get($key)) ? call_user_func_array($c, $args) : null;
	}

	public function importObj($class,  $array = []){
		$this->___class = $class;
		if(count($array) > 0){
			$this->import($array);
		}
		return $this;
	}

	public function import($input)
	{
		$this->exchangeArray($input);
		return $this;
	}

	public function export()
	{
		return $this->objectToArray($this->getArrayCopy());
	}

	public function objectToArray ($object) {
		$o = [];
		foreach ($object as $key => $value) {
			$o[$key] = is_object($value) ? (array) $value: $value;
		}
		return $o;
	}

}

class user{
	public $name = 'Mahmoud Elnezamy';
	public function getName(){
		return 'You Name is ' . $this->name;
	}
}

//usage you can import object with some array

$add = ['age' => '27', 'country' => 'Egypt'];
$user = new user;
$Prototype = new Prototype;
$Prototype->importObj($user, $add);
//print_r($Prototype);

echo $Prototype->getName().' ';
echo $Prototype->age.' ';
echo $Prototype->country;

echo "<br>\r\n============================<br>\r\n";


class foo {
	private $a;
	public $b = 1;
	public $c;
	private $d;
	static $e;

	public function seta($a) {
		$this->a = $a;
	}
	public function test() {
		print_r(get_object_vars($this));
	}
}

$test = new foo;
$test ->seta(1111);
print_r(get_object_vars($test));

$test->test();



echo "<br>\r\n============================<br>\r\n";


class SomeClass {
	private $user_id;
	public $user_name;
	public function __set( $name, $value ) {
		//echo "__set was called!  Name = $name\n";
		$this->$name = $value;
	}
}

$object = new SomeClass();
$db = new mysqli( 'localhost', 'root', 'root', 'test' );
$result = $db->query( 'SELECT user_id, user_name, password FROM exampleuser' );
while($rowss[] = $result->fetch_object( 'SomeClass' )) {
	//$object = $rows->fetch_object( 'SomeClass');
}

var_dump($rowss);

while($rows[] = mysqli_fetch_object($result, 'SomeClass')) {
	//$object = $rows->fetch_object( 'SomeClass');
}

//var_dump($rows);

$object = $result->fetch_object( 'SomeClass' );

echo "<br>\r\n user_name:" . $object->user_name . "<br>\r\n";

var_dump($object);

return;
WhereCriteria::instance('s')->EQ('company_id', 1)->EQ('group_unified_settings', "0");

$sss=DBQuery::instance(\wise\DbConfig::dsnRead())->setTable('company')->getEntityList("company_id,company_name", WhereCriteria::instance('s'));

print_r($sss);

return;
$company = new company();
$company->setCompanyGroup(1);
$company->setCompanyId(222);

class company {
    public $company_id;
    public $company_group;
    public $group_unified_settings;
    public $company_name;
    public $valid;

	/**
	 * @param mixed $company_id
	 */
	public function setCompanyId($company_id) {
		$this->company_id = $company_id;
	}

	/**
	 * @return mixed
	 */
	public function getCompanyId() {
		return $this->company_id;
	}

	/**
	 * @param mixed $company_group
	 */
	public function setCompanyGroup($company_group) {
		$this->company_group = $company_group;
		
	}

	/**
	 * @return mixed
	 */
	public function getCompanyGroup() {
		return $this->company_group;
	}

	/**
	 * @param mixed $company_name
	 */
	public function setCompanyName($company_name) {
		$this->company_name = $company_name;
	}

	/**
	 * @return mixed
	 */
	public function getCompanyName() {
		return $this->company_name;
	}

	/**
	 * @param mixed $group_unified_settings
	 */
	public function setGroupUnifiedSettings($group_unified_settings) {
		$this->group_unified_settings = $group_unified_settings;
	}

	/**
	 * @return mixed
	 */
	public function getGroupUnifiedSettings() {
		return $this->group_unified_settings;
	}

	/**
	 * @param mixed $valid
	 */
	public function setValid($valid) {
		$this->valid = $valid;
	}

	/**
	 * @return mixed
	 */
	public function getValid() {
		return $this->valid;
	}

	public function toString() {
		return 'company_id,company_group,group_unified_settings,company_name,valid';
	}

    public function getVars() {
	    $vars = [];
        foreach ($this as $key => $val) {
            $vars[$key] = $val;
        }
        return $vars;
	}

}
print_r(get_object_vars($company));
return;

WhereCriteria::instance('s')->EQ('company_id', 1)->EQ('group_unified_settings', "0");

$sss=DBQuery::instance(\wise\DbConfig::dsnRead())->setTable('company')->getList("company_id", WhereCriteria::instance('s'));

$row['company_group'] = 2;
$row['group_unified_settings'] = 1;
$row['company_name'] = rand(10000, 9999999);
$row['valid'] = 1;

DBQuery::instance(\wise\DbConfig::dsnWrite())->setTable('company')->insert($row);


WhereCriteria::instance('w')->EQ('company_id', 2)->EQ('group_unified_settings', "2");

$row['valid'] = '0';

DBQuery::instance(\wise\DbConfig::dsnWrite())->setTable('company')->update($row, WhereCriteria::instance('w'));

print_r($sss);


return;



echo $aaa = \Encrypt::instance()->encode('11', getDay());

echo  "<br>";
echo  \Encrypt::instance()->decode($aaa, getDay());

exit;
$code = rand(100000, 999999);
$unikey = uniqid();
$objRedis = \RedisClient::getInstance(\api\DbConfig::getRedisConfig(), array('db_id'=>'hashCode'));

$objRedis->hSet('hashCode', $unikey, $code);

var_dump($objRedis->hGet('hashCode', $unikey));
exit();

$length = 16;
$billid = 8;
$date = date("Ymd");//20180205
$str_num = '99999999999999999999';
$str_num = substr($str_num, 0, $length - 8);
$billno = $date . ($billid % $str_num).'0';;
$id_lenght = strlen($billno);
for($i = $id_lenght; $i<$length; $i++) {
    $billno .= rand(1, 9);
}
echo $billno . "<br>";

for($i = 190; $i < 200 ; $i++) {
    echo $i % 999999 . "<br>";
}

return;
 phpinfo();
return;


$dsn = "pdo:mysql://localhost:3306/softforum?user=soft&password=@!#$%&`~=+'\"&characterEncoding=utf-8";
$arrayDsnKey = array('driver'=>':','type'=>'://','host'=>':','port'=>'/','database'=>'?user=','login'=>'&password=','password'=>'&characterEncoding=');
$arrayDriver = array();
foreach($arrayDsnKey as $key => $value) {
    $arrayDriver[$key] = substr($dsn, 0, strpos($dsn, $value));
    $dsn = substr($dsn, strpos($dsn, $value) + strlen($value));
}
$arrayDriver['character'] = $dsn;
print_r($arrayDriver);


return;
$dsn = "pdo:mysql://localhost:3306/softforum?user=soft&password=@!#$%&`~=+'\"&characterEncoding=utf-8";

echo $dsn . "\r\n<br>";

//var_dump( strpos($dsn, ':'));
$driver = substr($dsn, 0, strpos($dsn, ':'));
echo $driver. "\r\n<br>";

$nextDsn = substr($dsn, strpos($dsn, ':') + 1);
echo $nextDsn. "\r\n<br>";

$dbType = substr($nextDsn, 0, strpos($nextDsn, '://'));
echo $dbType. "\r\n<br>";

$nextDsn = substr($nextDsn, strpos($nextDsn, '://') + 3);
echo $nextDsn. "\r\n<br>";

$dbHost = substr($nextDsn, 0, strpos($nextDsn, ':'));
echo $dbHost. "\r\n<br>";

$nextDsn = substr($nextDsn, strpos($nextDsn, ':') + 1);
echo $nextDsn. "\r\n<br>";

$dbPort = substr($nextDsn, 0, strpos($nextDsn, '/'));
echo $dbPort. "\r\n<br>";

$nextDsn = substr($nextDsn, strpos($nextDsn, '/') + 1);
echo $nextDsn. "\r\n<br>";

$dbDb = substr($nextDsn, 0, strpos($nextDsn, '?user='));
echo $dbDb. "\r\n<br>";

$nextDsn = substr($nextDsn, strpos($nextDsn, '?user=') + 6);
echo $nextDsn. "\r\n<br>";

$dbUser = substr($nextDsn, 0, strpos($nextDsn, '&password='));
echo $dbUser. "\r\n<br>";

$nextDsn = substr($nextDsn, strpos($nextDsn, '&password=') + 10);
echo $nextDsn. "\r\n<br>";

$dbPassword = substr($nextDsn, 0, strpos($nextDsn, '&characterEncoding='));
echo $dbPassword. "\r\n<br>";

$dbCharacter = substr($nextDsn, strpos($nextDsn, '&characterEncoding=') + 19);
echo $dbCharacter. "\r\n<br>";











return;
for ($i = 0; $i <= 6; $i++ ) {
    echo 1 - $i . "<br>";
}

return;
function getOrderNumber($billid, $length = 16) {
	if(strlen($billid) >= $length) return $billid;
	$billno = $billid.'0';
	$id_lenght = strlen($billno) + 1;
	for($i = $id_lenght; $i<=$length; $i++) {
		$billno .= rand(1, 9);
	}
	return $billno;
}

for($i = 1; $i <= 50; $i++) {
	echo getOrderNumber($i). "<br>\r\n";
}

return;
function insertLocation() {
    $lines = file("city.txt");
    $locations_id = $province_id = $city_id = '';
    foreach ($lines as $line) {
        $line = rtrim($line);
        if(empty($line)) continue;
        //echo $line . "<br>";
        $arrayLocation = explode('     　', $line);
        $arrayProvince = explode('　', $arrayLocation[1]);
        print_r($arrayProvince);
        $locations_id = $arrayLocation[0];
        $countProvince = count($arrayProvince);
        if($countProvince == 1) {//Province
            $province_id = $arrayLocation[0];
            $city_id = null;
            $location_name = trim($arrayLocation[1]);
            $locations_type = 'province';
        } elseif ($countProvince == 2) {//city
            $city_id = $arrayLocation[0];
            $location_name = trim($arrayProvince[1]);
            $locations_type = 'city';
        } elseif ($countProvince == 3) {//town
            $location_name = trim($arrayProvince[2]);
            $locations_type = 'town';
        }
        $insertData['locations_id'] = $locations_id;
        $insertData['location_name'] = $location_name;
        $insertData['province_id'] = $province_id;
        $insertData['city_id'] = $city_id;
        $insertData['locations_type'] = $locations_type;

        DBQuery::instance(\DbConfig::hotel_dsn_write)->setTable('locations')->insert($insertData);

    }
}

function exportLocationXml() {
    $arrayLocation = DBQuery::instance(\DbConfig::hotel_dsn_write)->setTable('locations')->order('province_id ASC ,city_id ASC, locations_id ASC')->getList();
    //print_r($arrayLocation);
    header("Content-type:text/xml");
    $xml = '<?xml version="1.0" encoding="UTF-8"?><address>' . "\r\n";
    foreach ($arrayLocation as $k => $v) {
        if($v['locations_id'] == $v['province_id'] && $v['locations_type'] == 'province') {//省
            if($k > 0) {
                $xml .= "</province>\r\n";
            }
            $xml .= '<province location="'.$v['locations_id'].'" name="'.$v['location_name'].'">' . "\r\n";
        } elseif($v['locations_type'] == 'city') {//市
            $xml .= '<city location="'.$v['locations_id'].'" name="'.$v['location_name'].'">' . "\r\n";
            if(!isset($arrayLocation[$k + 1])) {
                $xml .= "</city>\r\n";
            } else {
                if ($arrayLocation[$k + 1]['locations_type'] != 'town') {
                    $xml .= "</city>\r\n";
                }
            }
        } elseif ($v['locations_type'] == 'town') {//区
            $xml .= '<country location="'.$v['locations_id'].'" name="'.$v['location_name'].'" />' . "\r\n";
            if(!isset($arrayLocation[$k + 1])) {
                $xml .= "</city>\r\n";
            } elseif ($arrayLocation[$k + 1]['locations_type'] != 'town') {
                $xml .= "</city>\r\n";
            }
        }
    }
    $xml .= '</province></address>';
    return $xml;
}

function readXml() {
    $xml = 'E:/SVN/hotel/root/hotel/static/area/Area.xml';
    $objXml = new XML();
    $arrayXml = $objXml->loadToArray($xml);
    //print_r($arrayXml);
    //$objXml->storeFromArray('E:/SVN/hotel/root/hotel/static/area/Area2.xml', $arrayXml);
    //$domObj = new xmlToArrayParser($xml);
    //print_r($domObj);
    Array2xml::instance($arrayXml);//->storeFromArray('E:/SVN/hotel/root/hotel/static/area/Area2.xml');
    //$xml = $objArray2Xml->getXml();
    //$objArray2Xml->storeFromArray('E:/SVN/hotel/root/hotel/static/area/Area2.xml', $xml);
}

function readXml2() {
    $xml = 'E:/SVN/hotel/root/hotel/static/area/Area2.xml';
    $objXml = new XML();
    $arrayXml = $objXml->loadToArray($xml);
    print_r($arrayXml);
}
echo  exportLocationXml();