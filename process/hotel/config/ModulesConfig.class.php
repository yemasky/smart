<?php

/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 17:00
 */
namespace hotel;

class ModulesConfig extends \ModulesConfig {
	public static $modulesConfig = array(
	    'company'           => array('edit'=>24, 'delete'=>25, 'add'=>28),
	    'hotel'             => array('edit'=>26, 'delete'=>27, 'add'=>29, 'view'=>16, 'saveAttrValue'=>46,
                                  'upload_type'=>'hotel'),
        'hotelAttribute'    => array('edit'=>00, 'delete'=>45, 'add'=>44),
        'roomsSetting'      => array('edit'=>34, 'delete'=>35, 'add'=>32, 'view'=>18,
                                  'room_type'=>array('room'=>1,'office'=>0,'store'=>0,'equipment room'=>0,'dining'=>0,'restaurant'=>1,
                                  'multiple-function hall'=>0,'garden'=>0,'meeting room'=>0,'gazebo'=>0)),
        'roomsAttribute'    => array('edit'=>000,'delete'=>43, 'add'=>33, 'view'=>19),
        'roomsLayout'       => array('edit'=>38, 'delete'=>39, 'add'=>37, 'view'=>36, 'saveAttrValue'=>41,
                                  'orientations'=>array('east','south','west','north','southeast','northeast','southwest','northwest','no'),
                                  'upload_type'=>'rooms_layout'),
        'upload'            => array('uploadImages'=>42),
        'book'              => array('edit'=>50, 'delete'=>51, 'add'=>49, 'view'=>48),
        'roomLayoutPrice'   => array('edit'=>52, 'delete'=>54, 'add'=>53, 'view'=>21,
                                  'editSystem'=>55, 'agreement_corp'=>90, 'editAgreement_corp'=>91),
        'accessorialService'=> array('edit'=>59, 'delete'=>58, 'add'=>57, 'view'=>56),
        'roomsSellLayout'   => array('edit'=>62, 'delete'=>63, 'add'=>61, 'view'=>60),
        'department'        => array('edit'=>72, 'delete'=>73, 'add'=>71, 'view'=>30),
        'employee'          => array('edit'=>75, 'delete'=>76, 'add'=>74, 'view'=>31, 'personnelFile'=>89),
        'memberSetting'     => array('edit'=>78, 'delete'=>79, 'add'=>77, 'view'=>20),
        'modeOfPayment'     => array('edit'=>81, 'delete'=>82, 'add'=>80, 'view'=>23),
        'nightAudit'        => array('edit'=>70, 'delete'=>00, 'add'=>69, 'view'=>68),
        'cancellationPolicy'=> array('edit'=>85, 'delete'=>84, 'add'=>83, 'view'=>22),
        'role'              => array('edit'=>87, 'delete'=>88, 'add'=>86, 'view'=>47),

    );

    public static $memberType = array('OTA', 'member', 'agreement', 'team', 'walk-in', 'other');

    public static $cacheKey = array(
        'company' => array('company_default_id'=>'company_default_id_'),
	    'hotel'   => array('hotel_default_id'=>'hotel_default_id_','hotel_attribute'=>'hotel_attribute_',
                           'room_attribute'=>'room_attribute_')
    );

    public static $idCardType = array('id_card', 'passport', 'certificate_officers', 'other');
    //<!--A3 单状态 -1 失效 0预定成功 1入住 2退房-->
    public static $orderStatus = array(-1=>'order_failure', 0=>'order_success', 1=>'order_chechin', 2=>'order_no_show');

    public $room_layout_price_system_id = 1;

    public static $roomLayout = array('layoutHouseConfig' => array('0.9'=>'0.9米以下','1'=>'1米','1.2'=>'1.2米','1.35'=>'1.35米','1.5'=>'1.5米','1.8'=>'1.8米','2.0'=>'2.0米','2.2'=>'2米以上'))
        ;

}