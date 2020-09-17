<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class PriceSettingAction extends \BaseAction {
	protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $this->setDisplay();
	}

	protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		switch($objRequest->getAction()) {
			case 'RoomPriceList':
				$this->doRoomPriceList($objRequest, $objResponse);
			break;
			case 'RoomPriceAddEdit':
				$this->doRoomPriceAddEdit($objRequest, $objResponse);
			break;
			case 'RoomPriceSystem':
				$this->doRoomPriceSystem($objRequest, $objResponse);
			break;
			case 'RoomPriceSystemAddEdit':
				$this->doRoomPriceSystemAddEdit($objRequest, $objResponse);
			break;
            case 'RoomPackagePriceItems':
                $this->doRoomPackagePriceItems($objRequest, $objResponse);
                break;
			default:
				$this->doDefault($objRequest, $objResponse);
			break;
		}
	}

	protected function doMethod(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		// TODO: Implement method() method.
		$method = $objRequest->method;
		if(!empty($method)) {
			$method = 'doMethod' . ucfirst($method);

			return $this->$method($objRequest, $objResponse);
		}
	}

	public function invoking(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$this->check($objRequest, $objResponse);
		$this->service($objRequest, $objResponse);
	}

	/**
	 * 首页显示
	 */
	protected function doDefault(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$this->setDisplay();

		//赋值

		return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], '');
	}

	protected function doRoomPriceList(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$this->setDisplay();
		$company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
		//赋值
		$arrayResult['roomPriceSystemUrl']['url']     = ModuleServiceImpl::instance()->getEncodeModuleId('PriceSetting', 'RoomPriceSystem');
		$arrayResult['roomPriceSystemUrl']['view']    = 'RoomPriceSystem';
		$arrayResult['roomPriceSystemUrl']['channel'] = 'Setting';
		//
        $arrayResult['packagePriceItemsUrl']['url']     = ModuleServiceImpl::instance()->getEncodeModuleId('PriceSetting', 'RoomPackagePriceItems');
        $arrayResult['packagePriceItemsUrl']['view']    = 'RoomPackagePriceItems';
        $arrayResult['packagePriceItemsUrl']['channel'] = 'Setting';
		//客源市场
		$arrayResult['marketHash'] = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);
		//房型
		$objRequest->channel_config = 'layout';
		$objRequest->hashKey        = 'item_id';
		$arrayResult['layoutHash']  = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);
		//取消政策
		$arrayResult['policyList'] = ChannelServiceImpl::instance()->getCancellationPolicy($company_id);
		//
		$arrayResult['priceSystemHash'] = ChannelServiceImpl::instance()->getLayoutPriceSystemHash($company_id);
		//
		$objResponse->setResponse('saveAddEditUrl', ModuleServiceImpl::instance()->getEncodeModuleId('PriceSetting', 'RoomPriceAddEdit'));

		return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
	}

	protected function doRoomPriceAddEdit(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$this->setDisplay();
		$objSuccess = new \SuccessService();
		$method     = $objRequest->method;
		if(!empty($method)) {
			return $this->doMethod($objRequest, $objResponse);
		}
		$company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
		$arrayInput = $objRequest->getInput();

		$price_system_type = $objRequest->validInput('price_system_type');
		if($price_system_type === false) {
			return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['parameter_error']);
		}
		$arrayResult = [];
		if($price_system_type == 'formula') {//公式放盘
			$arrayUpdateData['formula'] = json_encode($arrayInput['formula']);
			$whereCriteria              = new \WhereCriteria();
			$whereCriteria->EQ('company_id', $company_id)->EQ('price_system_id', $arrayInput['price_system_id']);
			ChannelServiceImpl::instance()->updateLayoutPriceSystem($whereCriteria, $arrayUpdateData);
		}

		if($price_system_type == 'direct') {//手动放盘
			$begin_date      = $arrayInput['begin_date'];
			$end_date        = $arrayInput['end_date'];
			$price_system_id = $arrayInput['price_system_id'];
			$arrayWeek       = $arrayInput['week'];
			$layout_price    = $arrayInput['layout_price'];
			if($price_system_id > 0) {
				$begin_date = strtotime($begin_date);
				$end_date   = strtotime($end_date);
				//判斷時間是否超過有效時間90天
				$overTime = ($end_date - $begin_date) / 86400;
				if($overTime > 90) {
					return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['common']['over_date']['code']);
				}
				$arrayItemId = $keyMonth = [];
				foreach($layout_price as $channel_layout => $price) {
					if(!empty($price)) {
						$arrayChannelLayout                       = explode('-', $channel_layout);
						$arrayItemId[$arrayChannelLayout[1]]      = $arrayChannelLayout[0];//item_id=>channel_id
						$arrayLayoutPrice[$arrayChannelLayout[1]] = $price;
					}
				}
				if(empty($arrayLayoutPrice)) {
					return $objResponse->errorResponse('000010');
				}
				for($i = $begin_date; $i <= $end_date; $i += 86400) {
					$year  = date('Y', $i);//4 位数字完整表示的年份
					$month = date('m', $i);//数字表示的月份，有前导零
					$day   = date('d', $i);//月份中的第几天，有前导零的 2 位数字 	01 到 31
					$week  = date('w', $i);//星期中的第几天，数字表示 	0（表示星期天）到 6（表示星期六）
					if(isset($arrayWeek[$week])) {
						foreach($arrayLayoutPrice as $layout => $price) {
							$keyMonth[$layout][$year . '-' . $month . '-01'][$day] = $price;
						}
					}
				}
				//查找价格体系WHERE price_system_id = 1 AND item_id IN(1,2,3,5) AND layout_price_date >= 1 AND layout_price_date <=2;
				$arrayLayoutPrice = '';
				if(!empty($arrayItemId)) {
					$betinDate        = date('Y', $begin_date) . '-' . date('m', $begin_date) . '-01';
					$endDate          = date('Y', $end_date) . '-' . date('m', $end_date) . '-01';
					$arrayLayoutPrice = ChannelServiceImpl::instance()->getDateLayoutPrice($price_system_id, $betinDate, $endDate, array_keys($arrayItemId),
						$company_id);
				}
				$arrayBatchInsert = $arrayUpdate = $batchUpdate = $arrayBatchUpdate = $arrayBatchUpdateWhere = [];
				$k                = $l = 0;
				foreach($arrayItemId as $layout => $channel_id) {
					$updateData = $keyMonth[$layout];
					foreach($updateData as $date => $arrayDay) {//按月更新数据
						if(isset($arrayLayoutPrice[$layout][$date])) {//update
							if(!isset($arrayBatchUpdate[$date]['key'])) {
								//只能按月，分item_id 更新；或者按item_id ，分月更新。按月更新更好，因为item_id 更多。
								$arrayBatchUpdate[$date]['key'] = 'item_id';
							}
							$arrayBatchUpdateWhere[$date]['item_id'][] = $layout;
							foreach($arrayDay as $day => $price) {
								if(!empty($price) && $price > 0) $arrayBatchUpdate[$date]['field']['day_' . $day][$layout] = $price;
							}
							$l++;
						} else {//insert
							$arrayBatchInsert[$k]['price_system_id']   = $price_system_id;
							$arrayBatchInsert[$k]['layout_price_date'] = $date;
							$arrayBatchInsert[$k]['company_id']        = $company_id;
							$arrayBatchInsert[$k]['channel_id']        = $channel_id;
							$arrayBatchInsert[$k]['item_id']           = $layout;
							for($i = 1; $i <= 31; $i++) {
								if($i < 10) {
									$key_day = '0' . $i;
								} else {
									$key_day = '' . $i;
								}
								$price = null;
								if(isset($arrayDay[$key_day])) $price = $arrayDay[$key_day];
								$arrayBatchInsert[$k]['day_' . $key_day] = $price;
							}
							$arrayBatchInsert[$k]['add_datetime'] = getDateTime();
							$k++;
						}
					}
				}
				if(!empty($arrayBatchInsert)) {
					ChannelServiceImpl::instance()->batchInsertLayoutPrice($arrayBatchInsert);
				}
				if(!empty($arrayBatchUpdate)) {
					$whereCriteria = new \WhereCriteria();
					$whereCriteria->EQ('price_system_id', $price_system_id);
					foreach($arrayBatchUpdate as $date => $batchUpdate) {
						$whereCriteria->EQ('layout_price_date', $date)->ArrayIN('item_id', $arrayBatchUpdateWhere[$date]['item_id']);
						ChannelServiceImpl::instance()->batchUpdateLayoutPrice($whereCriteria, $batchUpdate);
					}
				}

				return $objResponse->successResponse(ErrorCodeConfig::$successCode['success']);
			}
		}

		$arrayResult['priceSystemHash'] = ChannelServiceImpl::instance()->getLayoutPriceSystemHash($company_id);

		return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
	}

	protected function doRoomPriceSystem(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$this->setDisplay();
		$company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;

		//客源市场
		$arrayResult['marketHash'] = ChannelServiceImpl::instance()->getCustomerMarketHash($company_id);
		//设置channel_id = ''
        $objRequest->channel_id = '';
		//房型
		$objRequest->channel_config = 'layout';
		$objRequest->hashKey        = 'item_id';
		$arrayResult['layoutHash']  = ChannelServiceImpl::instance()->getChannelItemHash($objRequest, $objResponse);
		//取消政策
		$arrayResult['policyList'] = ChannelServiceImpl::instance()->getCancellationPolicy($company_id);
		//
		$arrayResult['priceSystemHash'] = ChannelServiceImpl::instance()->getLayoutPriceSystemHash($company_id);
		//
		$objResponse->setResponse('saveAddEditUrl', ModuleServiceImpl::instance()->getEncodeModuleId('PriceSetting', 'RoomPriceSystemAddEdit'));
		$arrayResult['roomPriceListUrl']['url']     = ModuleServiceImpl::instance()->getEncodeModuleId('PriceSetting', 'RoomPriceList');
		$arrayResult['roomPriceListUrl']['view']    = 'RoomPriceList';
		$arrayResult['roomPriceListUrl']['channel'] = 'Setting';
		//
        $arrayResult['packagePriceItemsUrl']['url']     = ModuleServiceImpl::instance()->getEncodeModuleId('PriceSetting', 'RoomPackagePriceItems');
        $arrayResult['packagePriceItemsUrl']['view']    = 'RoomPackagePriceItems';
        $arrayResult['packagePriceItemsUrl']['channel'] = 'Setting';

		return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
	}

	protected function doRoomPriceSystemAddEdit(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$this->setDisplay();
		$company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
		$arrayInput             = $objRequest->getInput();
		$price_system_id        = $objRequest->getInput('price_system_id');
		$price_system_father_id = $objRequest->getInput('price_system_father_id');
		//
		$arrayReplaceData    = [];
		$keyReplace          = [];
		$arrayReplaceData[0] = ['0' => '0'];
		$keyReplace[0]       = 'channel_id';
		if(isset($arrayInput['channel_ids']) && is_array($arrayInput['channel_ids'])) {
			$arrayReplaceData[0] = $arrayInput['channel_ids'];
		}
		$keyReplace[1] = 'item_category_id';
		if(isset($arrayInput['layout_item']) && is_array($arrayInput['layout_item'])) {
			foreach($arrayInput['layout_item'] as $c_id => $item) {
				foreach($item as $item_id => $layout) {
					$arrayReplaceData[1][$item_id] = $item_id;
				}
			}
		} else {
			$arrayReplaceData[1] = ['0' => '0'];
		}
		$arrayReplaceData[2] = ['0' => '0'];
		$keyReplace[2]       = 'market_id';
		if(isset($arrayInput['market_ids']) && is_array($arrayInput['market_ids'])) {
			$arrayReplaceData[2] = $arrayInput['market_ids'];
		}
		$replaceData = [];
		$k           = 0;
		foreach($arrayReplaceData[0] as $channel_id => $v) {
			foreach($arrayReplaceData[1] as $item_category_id => $v) {
				foreach($arrayReplaceData[2] as $market_id => $v) {
					$replaceData[$k][$keyReplace[0]] = $channel_id;
					$replaceData[$k][$keyReplace[1]] = $item_category_id;
					$replaceData[$k][$keyReplace[2]] = $market_id;
					if($price_system_id > 0) $replaceData[$k]['price_system_id'] = $price_system_id;
					$replaceData[$k]['price_system_father_id'] = $price_system_father_id;
					$k++;
				}
			}
		}
		//
		$arrayInput['channel_ids'] = isset($arrayInput['channel_ids']) ? json_encode($arrayInput['channel_ids']) : '';
		$arrayInput['layout_item'] = isset($arrayInput['layout_item']) ? json_encode($arrayInput['layout_item']) : '';
		$arrayInput['market_ids']  = isset($arrayInput['market_ids']) ? json_encode($arrayInput['market_ids']) : '';
		//
		if(isset($arrayInput['price_system_id']) && $arrayInput['price_system_id'] > 0) {//update
			if(isset($arrayInput['company_id'])) unset($arrayInput['company_id']);
			if(isset($arrayInput['price_system_id'])) {
				unset($arrayInput['price_system_id']);
				$whereCriteria = new \WhereCriteria();
				$whereCriteria->EQ('company_id', $company_id)->EQ('price_system_id', $price_system_id);
				ChannelServiceImpl::instance()->updateLayoutPriceSystem($whereCriteria, $arrayInput);
				$arraypriceSystemHash = ChannelServiceImpl::instance()->getLayoutPriceSystemHash($company_id);
				$whereCriteria        = new \WhereCriteria();
				$whereCriteria->EQ('price_system_id', $price_system_id);
				ChannelServiceImpl::instance()->deletePriceSystemLayout($whereCriteria);
				ChannelServiceImpl::instance()->batchInsertPriceSystemLayout($replaceData);

				return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arraypriceSystemHash);
			}
		} else {
			$result = ChannelServiceImpl::instance()->checkSameNamePriceSystem($company_id, $arrayInput['price_system_name'], $arrayInput['price_system_en_name']);
			if(!$result) {
				return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['common']['duplicate_data']['code'], ['price_system_name']);
			}
			unset($arrayInput['price_system_id']);
			$arrayInput['company_id']   = $company_id;
			$arrayInput['add_datetime'] = getDateTime();
			$price_system_id            = ChannelServiceImpl::instance()->saveLayoutPriceSystem($arrayInput);
			foreach($replaceData as $k => $value) {
				$replaceData[$k]['price_system_id'] = $price_system_id;
			}
			ChannelServiceImpl::instance()->batchInsertPriceSystemLayout($replaceData);
			$arraypriceSystemHash = ChannelServiceImpl::instance()->getLayoutPriceSystemHash($company_id);

			return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arraypriceSystemHash);
		}

		return $objResponse->errorResponse(ErrorCodeConfig::$errorCode['no_data_update']);
	}

	protected function doMethodCheckHistoryPrice(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		$company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
		$arrayInput      = $objRequest->getInput();
		$price_system_id = $arrayInput['price_system_id'];
		$betinDate       = substr($arrayInput['begin_date'], 0, 7) . '-01';
		$endDate         = substr($arrayInput['end_date'], 0, 7) . '-01';
		$layout_id       = $arrayInput['layout_id'];

		$price_system_type          = $arrayInput['price_system_type'];
		$arrayResult['layoutPrice'] = [];
		if($price_system_type == 'direct') {
			$arrayResult['layoutPrice'] = ChannelServiceImpl::instance()->getDateLayoutPrice($price_system_id, $betinDate, $endDate,
				$layout_id, $company_id);
		}
		if($price_system_type == 'formula') {
			$channel_id             = $arrayInput['channel_id'];
			$price_system_father_id = $arrayInput['price_system_father_id'];
			//取出父价格
			$arrayResult['layoutPrice'] = ChannelServiceImpl::instance()->getDateLayoutPrice($price_system_father_id, $betinDate, $endDate,
				$layout_id, $company_id);
		}

		$objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
	}

	protected function doRoomPackagePriceItems(\HttpRequest $objRequest, \HttpResponse $objResponse) {
        $arrayResult['roomPriceListUrl']['url']     = ModuleServiceImpl::instance()->getEncodeModuleId('PriceSetting', 'RoomPriceList');
        $arrayResult['roomPriceListUrl']['view']    = 'RoomPriceList';
        $arrayResult['roomPriceListUrl']['channel'] = 'Setting';
        //
        $arrayResult['roomPriceSystemUrl']['url']     = ModuleServiceImpl::instance()->getEncodeModuleId('PriceSetting', 'RoomPriceSystem');
        $arrayResult['roomPriceSystemUrl']['view']    = 'RoomPriceSystem';
        $arrayResult['roomPriceSystemUrl']['channel'] = 'Setting';

        return $objResponse->successResponse(ErrorCodeConfig::$successCode['success'], $arrayResult);
    }

}