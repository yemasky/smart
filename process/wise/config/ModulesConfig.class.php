<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 17:00
 */
namespace wise;

class ModulesConfig extends \ModulesConfig {
	public static $__VERSION = '1.0.2';
	public static $channel_type = ['Hotel'=>'酒店','Meal'=>'餐馆/Bar','Meeting'=>'商务会议',
								   'Sport'=>'健身娱乐','Shop'=>'商城/商店','Service'=>'商务服务','Tour'=>'旅行路线'];
	public static $channel_value = ['Hotel'=>'Hotel','Meal'=>'Meal','Meeting'=>'Meeting',
		'Sport'=>'Sport','Shop'=>'Shop','Service'=>'Service','Tour'=>'Tour'];
    public static $channel_config = [
        'Hotel'  =>['layout'=>'房型','room'=>'房间'],
        'Meal'   =>['cuisineCategory'=>'菜式类别','cuisine'=>'菜式','table'=>'餐桌'],
        'Meeting'=>['meeting'=>'会议'],
        'Sport'  =>['sport'=>'娱乐'],
        'Shop'   =>['shop'=>'商品'],
        'Service'=>['service'=>'商务服务'],
        'Tour'   =>['tour'=>'旅行']];

	public static $attr_default_value =
		['layout'=>
			[1=>[0=>[0=>['单人间','Single Room'],1=>['双人间','Double Room'],2=>['大床间','King Size & Queen Size Room'],3=>['标准间','Standard'],
					 4=>['三人间','Triple'],5=>['四人间','Quad'],6=>['套间','Suite'],7=>['公寓','Apartment'],8=>['别墅','Villa'],9=>['床位','Bed'],
					10=>['工位','Station']],
				 1=>[0=>['经济间','Economic Room'],1=>['普通间','Standard Room'],3=>['高级间','Superior Room'],4=>['豪华间','Deluxe Room'],
					 5=>['商务标间','Business Room'],6=>['行政标间','Executive Room']],
				 2=>[0=>['朝街房','Front View Room'],1=>['背街房','Rear View Room'],2=>['城景房','City View Room'],3=>['园景房','Garden View Room'],
					 4=>['海景房','Sea View Room'],5=>['湖景房','Lake View Room'],6=>['山景房','Mountain View Room'],7=>['无烟房','Non Smoking'],
                     8=>['残疾人客房 ','Handicapped Room']]],
             3=>[['标准床','Standard Bed'],['圆床','Round Bed'],['情调床','Sentiment Bed']],//床型
             4=>[['1.2米','1.2'],['1.5米','1.5'],['1.8米','1.8'],['2.0米','2.0'],['不规则','anomaly']],//床尺寸
             6=>[['中央空调','Central Air Conditioners'],['分体空调','Split Air Conditioner'],['风扇','Fan'],['暖气','Heating']],//
             8=>[['独立卫生间','Private Bathroom'],['共用卫生间','Shared Toilet'],['公寓卫生间','Apartment bathroom']],//
             9=>[['无窗',''],['落地窗',''],['大窗',''],['小窗','']],
            ],
		 'room'=>
			[9=>[0=>['朝街房','Front View Room'],1=>['背街房','Rear View Room'],2=>['城景房','City View Room'],3=>['园景房','Garden View Room'],
				 4=>['海景房','Sea View Room'],5=>['湖景房','Lake View Room'],6=>['山景房','Mountain View Room'],7=>['无烟房','Non Smoking'],
                 8=>['残疾人客房 ','Handicapped Room']]],
         'cuisine'=>
            [12=>[['闽菜',''],['湘菜',''],['川菜',''],['粤菜',''],['苏菜',''],['徽菜',''],['鲁菜','']],
             13=>[['变态辣',''],['很辣',''],['辣',''],['微辣',''],['不辣',''],['甜','']]],
         'cuisineCategory'=>
                [15=>[['主食',''],['点心',''],['早餐',''],['汤',''],['开胃菜',''],['小吃','']]]
		];

	public static $images_config = ['layout'=>'房型','room'=>'房间','cuisine'=>'菜式','table'=>'餐桌', 'delivery'=>'送餐配置','meeting'=>'会议',
									'sport'=>'娱乐','shop'=>'商品','service'=>'商务服务','tour'=>'旅行','employee'=>'员工'];

	public static $module = [
		'Channel'=>['Add'=>16,'Config'=>18],
		'ChannelConfig'=>['default'=>19,'room'=>20,'layout'=>24,'cuisineCategory'=>57,'cuisine'=>57,'table'=>57],
		'Upload'=>['images'=>22,'manager'=>23],
		'ChannelSetting'=>['paymentAddEdit'=>26, 'marketAddEdit'=>28],
		'PriceSetting'=>['RoomPriceList'=>30,'RoomPriceSystem'=>33,'RoomPriceSystemAddEdit'=>34,'RoomPriceAddEdit'=>35],
        'CancellationPolicy'=>['PolicyAddEdit'=>32]
		];

	//菜式规格
    public static $cuisineSkuAttr = [['cn'=>'','en'=>''],['cn'=>'份量','en'=>''],['cn'=>'味型','en'=>''],['cn'=>'热量','en'=>''],
                                     ['cn'=>'颜色','en'=>'']];
    public static $defaultMarket = [];

    //消费配置
    public static $consumeConfig = ['Meals'=>['channel_consume_id'=>12,'channel_consume_father_id'=>11,'consume_title'=>'餐费'],
        'Hotel'=>['channel_consume_id'=>2,'channel_consume_father_id'=>1,'consume_title'=>'房费']];

    public static $WX_APPID = "";
    public static $WX_SECRET = "";

}