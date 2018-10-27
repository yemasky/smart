<?php
/**
 * User: YEMASKY
 * Date: 2016/7/23
 * Time: 19:17
 */

namespace wise;

class UploadAction extends \BaseAction {
	protected function check(\HttpRequest $objRequest, \HttpResponse $objResponse) {
	}

	protected function service(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		switch($objRequest->getAction()) {
			case 'images':
				$this->doUploadImages($objRequest, $objResponse);
			break;
			case 'manager':
				$this->doManagerImages($objRequest, $objResponse);
			break;
			default:
				$this->doDefault($objRequest, $objResponse);
			break;
		}
	}

	protected function doMethod(\HttpRequest $objRequest, \HttpResponse $objResponse) {
		// TODO: Implement method() method.
	}

	public function invoking(\HttpRequest $objRequest, \HttpResponse $objResponse) { }

	/**
	 * 首页显示
	 */
	protected function doDefault($objRequest, $objResponse) {
		//赋值
		//设置类别
		//设置Meta(共通)
	}

	protected function doUploadImages($objRequest, $objResponse) {
		$this->setDisplay();
		$company_id       = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
		$images_channel_type = $objRequest->channel_config;

		//定义允许上传的文件扩展名
		$ext_arr = array('image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'));
		//最大文件大小
		$max_size = 204800;//200k

		//PHP上传失败
		if(!empty($_FILES['imgFile']['error'])) {
			switch($_FILES['imgFile']['error']) {
				case '1':
					$error = '超过2M/800x600允许的大小。';
				break;
				case '2':
					$error = '超过表单允许的大小。';
				break;
				case '3':
					$error = '图片只有部分被上传。';
				break;
				case '4':
					$error = '请选择图片。';
				break;
				case '6':
					$error = '找不到临时目录。';
				break;
				case '7':
					$error = '写文件到硬盘出错。';
				break;
				case '8':
					$error = 'File upload stopped by extension。';
				break;
				case '999':
				default:
					$error = '未知错误。';
			}
			$this->alert($error);

			return;
		}

		//有上传文件时
		if(empty($_FILES) === false) {
			//原文件名
			$file_name = $_FILES['imgFile']['name'];
			//服务器上临时文件名
			$tmp_name = $_FILES['imgFile']['tmp_name'];
			//文件大小
			$file_size = $_FILES['imgFile']['size'];
			//检查文件名
			if(!$file_name) {
				$this->alert("请选择文件。");

				return;
			}
			$save_path = __DEFAULT_IMG;
			$save_url  = '';//__IMGWEB;
			//检查目录
			if(@is_dir($save_path) === false) {
				$this->alert("上传目录不存在。");

				return;
			}
			//检查目录写权限
			if(@is_writable($save_path) === false) {
				$this->alert("上传目录没有写权限。");

				return;
			}
			//检查是否已上传
			if(@is_uploaded_file($tmp_name) === false) {
				$this->alert("上传失败。");

				return;
			}
			//检查文件大小
			if($file_size > $max_size) {
				$this->alert("上传文件大小超过限制。");

				return;
			}
			//检查目录名
			/*$dir_name = 'image';
			if (empty($ext_arr[$dir_name])) {
				$this->alert("目录名不正确。");
				return;
			}*/
			//获得文件扩展名
			$temp_arr = explode(".", $file_name);
			$file_ext = array_pop($temp_arr);
			$file_ext = trim($file_ext);
			$file_ext = strtolower($file_ext);
			//检查扩展名
			if(in_array($file_ext, $ext_arr['image']) === false) {
				$this->alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr['image']) . "格式。");

				return;
			}
			$this_file_name = str_replace('.' . $file_ext, '', strtolower($file_name));
			//创建文件夹
			/*if ($dir_name !== '') {
				$save_path .= $dir_name . "/";
				$save_url .= $dir_name . "/";
				if (!file_exists($save_path)) {
					mkdir($save_path);
				}
			}*/
			$relative_file = '';
			$y             = date("Y");
			$save_path     .= $y . "/";
			$save_url      .= $y . "/";
			$relative_file .= $y . "/";
			if(!file_exists($save_path)) {
				mkdir($save_path);
			}
			$md            = date("md");
			$save_path     .= $md . "/";
			$save_url      .= $md . "/";
			$relative_file .= $md . "/";
			if(!file_exists($save_path)) {
				mkdir($save_path);
			}
			//新文件名
			$new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
			//移动文件
			$file_path     = $save_path . $new_file_name;
			$relative_file = $relative_file . $new_file_name;
			if(move_uploaded_file($tmp_name, $file_path) === false) {
				$this->alert("上传文件失败。");

				return;
			}
			@chmod($file_path, 0644);
			$file_url                           = $save_url . $new_file_name;
			$id                                 = rand(1000, 10000000);
			$item_id                            = decode($objRequest->item_id);
			$channel_id                         = decode($objRequest->channel_id);
			$arrayImages['company_id']          = $company_id;
			$arrayImages['item_id']             = empty($item_id) ? '0' : $item_id;
			$arrayImages['channel_id']          = empty($channel_id) ? '0' : $channel_id;
			$arrayImages['images_src']          = $relative_file;
			$arrayImages['images_size']         = $file_size;
			$arrayImages['images_add_datetime'] = getDateTime();
			$arrayImages['images_name']         = $this_file_name;
			$arrayImages['images_channel_type'] = $images_channel_type;
			$images_id                          = ChannelServiceImpl::instance()->saveUploadImages($arrayImages);

			header('Content-type: text/html; charset=UTF-8');
			echo json_encode(array('error' => 0, 'url' => __IMGWEB . $file_url, 'title' => $this_file_name, 'id' => $images_id));
		}
		//赋值
	}

	protected function doManagerImages($objRequest, $objResponse) {
		$this->setDisplay();
		$company_id = LoginServiceImpl::instance()->getLoginInfo()->getCompanyId();;
		$ext_arr    = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
		$dir_name   = __DEFAULT_IMG;
		$root_path  = __DEFAULT_IMG;
		$root_url   = __IMGWEB;
		$path       = $objRequest->path;

		$images_channel_type  = $objRequest->channel_config;

		if(empty($path)) {
			$current_path     = $root_path;
			$current_url      = $root_url;
			$current_dir_path = '';
			$moveup_dir_path  = '';
		} else {
			$current_path     = $root_path;
			$current_url      = $root_url;
			$current_dir_path = $path;
			$moveup_dir_path  = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
			$order            = empty($objRequest->order) ? 'name' : strtolower($objRequest->order);
		}
		if(preg_match('/\.\./', $current_path)) {
			$this->alert('Access is not allowed.');

			return;
		}
		//最后一个字符不是/
		if(!preg_match('/\/$/', $current_path)) {
			$this->alert('Parameter is not valid.');

			return;
		}

		$file_list = array();
		if(empty($path) && empty($images_channel_type)) {
			$images_config = ModulesConfig::$images_config;
			$i = 0;
			foreach($images_config as $key => $name) {
				$file_list[$i]['is_dir']   = true; //是否文件夹
				$file_list[$i]['has_file'] = true; //文件夹是否包含文件
				$file_list[$i]['filesize'] = 0; //文件大小
				$file_list[$i]['is_photo'] = false; //是否图片
				$file_list[$i]['filetype'] = $key; //文件类别，用扩展名判断
				$file_list[$i]['filename'] = $name;//文件名，包含扩展名
				$file_list[$i]['name']     = $name;
				$file_list[$i]['datetime'] = date('Y-m-d H:i:s'); //文件最后修改时间
				$i++;
			}
		} else {
			$arrayImages         = null;
            $whereCriteria = new \WhereCriteria();
            $whereCriteria->EQ('company_id', $company_id)->EQ('images_channel_type', $images_channel_type);
			$field 				 = 'images_id,images_name,images_size,images_src,images_add_datetime';
			$imagesCount 		 = ChannelServiceImpl::instance()->getUploadImagesCount($whereCriteria);
			$arrayImages         = ChannelServiceImpl::instance()->getUploadImages($whereCriteria, $field);
			if(!empty($arrayImages)) {
				foreach($arrayImages as $k => $v) {
					$file_list[$k]['is_dir']   = false;
					$file_list[$k]['has_file'] = false;
					$file_list[$k]['filesize'] = $v['images_size'];
					$file_list[$k]['is_photo'] = true;
					$temp_arr                  = explode(".", $v['images_src']);
					$file_ext                  = strtolower(trim(array_pop($temp_arr)));
					$file_list[$k]['filetype'] = $file_ext;
					$temp_arr                  = explode("/", $v['images_src']);
					//$file_list[$k]['filename'] = array_pop($temp_arr); //文件名，包含扩展名
					$file_list[$k]['filename'] = $v['images_src'];
					array_pop($temp_arr);
					$file_list[$k]['dir_path'] = implode('/', $temp_arr) . '/';
					$file_list[$k]['datetime'] = $v['images_add_datetime']; //文件最后修改时间
					$file_list[$k]['id']       = $v['images_id'];
					$file_list[$k]['name']     = $v['images_name'];
				}
			}
		}
		if(empty($arrayImages)) {
			//$this->alert("读取失败！");

			//return;
		}

		$result = array();
		//相对于根目录的上一级目录
		$result['moveup_dir_path'] = $moveup_dir_path;
		//相对于根目录的当前目录
		$result['current_dir_path'] = $current_dir_path;
		//当前目录的URL
		$result['current_url'] = $current_url;
		//文件数
		$result['total_count'] = count($file_list);
		//文件列表数组
		$result['file_list'] = $file_list;

		//输出JSON字符串
		header('Content-type: application/json; charset=UTF-8');
		echo json_encode($result);
	}

	protected function doDelete($objRequest, $objResponse) {
		$this->setDisplay();
	}

	protected function alert($msg) {
		header('Content-type: text/html; charset=UTF-8');
		echo json_encode(array('error' => 1, 'message' => $msg));
	}
}