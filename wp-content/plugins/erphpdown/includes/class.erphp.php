<?php 
if ( !defined('ABSPATH') ) {exit;}
class EPD{

	private $ip;
	public $user_id;
	public $post_id;
	public $is_logged = 0;

	public function __construct($post_id = 0, $user_id = 0){

		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->post_id = $post_id;
		$this->user_id = $user_id?$user_id:0;

		if(is_user_logged_in()){
			$this->is_logged = 1;
		}
	
	}

	public function isErphpdown($post_id){
		if(!$post_id)
			return false;

		$start_down = get_post_meta($post_id,'start_down',true);
		$start_see = get_post_meta($post_id,'start_see',true);
		$start_see2 = get_post_meta($post_id,'start_see2',true);
		$start_down2 = get_post_meta($post_id,'start_down2',true);
		if($start_see2 == 'yes' || $start_see == 'yes' || $start_down == 'yes' || $start_down2 == 'yes')
			return true;
	}

	public function isBought($post_id, $user_id = null){
		if(!$post_id)
			return false;

		if($user_id){
			$ice_user_id = $user_id;
		}else{
			$ice_user_id = $this->user_id;
		}

		global $wpdb;
		$days=get_post_meta($post_id, 'down_days', true);
		$isBought = $wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".$post_id."' and ice_success=1 and ice_user_id=".$ice_user_id." order by ice_time desc");

		if($days > 0){
			$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($isBought->ice_time)));
			$nowDate = date('Y-m-d H:i:s');
			if(strtotime($nowDate) > strtotime($lastDownDate)){
				$isBought = null;
			}
		}

		return $isBought;
	}

	public function checkout($money){
		if(!$this->is_logged)
			return false;

		if($money > 0){
			global $wpdb;
			return $wpdb->query("update $wpdb->iceinfo set ice_get_money=ice_get_money+".$money." where ice_user_id=".$this->user_id);
		}else{
			return false;
		}
	}

	public function checkoutReturn($money){
		if(!$this->is_logged)
			return false;

		if($money > 0){
			global $wpdb;
			return $wpdb->query("update $wpdb->iceinfo set ice_get_money=ice_get_money-".$money ." where ice_user_id=".$this->user_id);
		}else{
			return false;
		}
	}

	public function doAff($money){
		if(!$this->is_logged)
			return false;

		global $wpdb;
		$RefMoney=$wpdb->get_row("select father_id from ".$wpdb->users." where ID=".$this->user_id);
		if($RefMoney->father_id > 0){
			$this->addUserMoney($RefMoney->father_id, $money*get_option('ice_ali_money_ref',0)*0.01);
		}
	}

	public function addUserMoney($user_id, $money){
		if(!$user_id)
			return false;

		global $wpdb;
		$myinfo=$wpdb->get_row("select ice_id from ".$wpdb->iceinfo." where ice_user_id=".$user_id);
		if(!$myinfo){
			return $wpdb->query("insert into $wpdb->iceinfo(ice_have_money,ice_user_id,ice_get_money)values('$money','$user_id',0)");
		}else{
			return $wpdb->query("update $wpdb->iceinfo set ice_have_money=ice_have_money+".$money." where ice_user_id=".$user_id);
		}
	}

	public function addBuyLog($postName,$post_id,$price,$success,$postDownloadUrl,$postAuthor){
		if(!$this->is_logged)
			return false;

		if($price > 0){
			global $wpdb;
			$postName = str_replace("'","",$postName);
			$postName = str_replace("‘","",$postName);
			$url       = md5(date("YmdHis").$post_id.mt_rand(1000000, 9999999));
			$orderNum  = mt_rand(100, 999).date("mdH");
			$sql       = "INSERT INTO $wpdb->icealipay (ice_num,ice_title,ice_post,ice_price,ice_success,ice_url,ice_user_id,ice_time,ice_data,
			ice_author)VALUES ('$orderNum','$postName','$post_id','$price','$success','$url','".$this->user_id."','".date("Y-m-d H:i:s")."','".$postDownloadUrl."','$postAuthor')";
			if($wpdb->query($sql)){
				return $url;
			}
		}
		return false;
	}

	public function getPostErphpdownType($post_id){
		if(!$post_id)
			return false;

		$start_down = get_post_meta($post_id,'start_down',true);
		$start_see = get_post_meta($post_id,'start_see',true);
		$start_see2 = get_post_meta($post_id,'start_see2',true);
		if($start_see2 == 'yes')
			return 'start_see2';
		if($start_see == 'yes')
			return 'start_see';
		if($start_down == 'yes')
			return 'start_down';
	}

	public static function getPostPrice($post_id){
		if(!$post_id)
			return false;

		$down_price = get_post_meta($post_id,'down_price',true);
		return $down_price;
	}

	public static function getPostDownloadUrl($post_id){
		if(!$post_id)
			return false;

		$down_url = get_post_meta($post_id,'down_url',true);
		return $down_url;
	}

	public static function getPostHidden($post_id){
		if(!$post_id)
			return false;

		$hidden_content = get_post_meta($post_id,'hidden_content',true);
		return $hidden_content;
	}

	public static function getPostVipType($post_id){
		if(!$post_id)
			return false;

		$member_down = get_post_meta($post_id,'member_down',true);
		return $member_down;
	}

	public static function getUserVipType($user_id = null){
		if($user_id){
			$ice_user_id = $user_id;
		}else{
			if(!is_user_logged_in())
				return false;
			$ice_user_id = get_current_user_id();
		}

		global $wpdb;
		$userTypeInfo=$wpdb->get_row("select endTime, userType from ".$wpdb->iceinfo." where ice_user_id=".$ice_user_id);
		if($userTypeInfo){
			if(time() > strtotime($userTypeInfo->endTime) + 24*3600){
				$wpdb->query("update $wpdb->iceinfo set userType=0, endTime='1000-01-01' where ice_user_id=".$ice_user_id);
				return false;
			}
			return $userTypeInfo->userType;
		}
		return false;
	}

	public static function getUserMoney($user_id = null){
		if($user_id){
			$ice_user_id = $user_id;
		}else{
			if(!is_user_logged_in())
				return false;
			$ice_user_id = get_current_user_id();
		}

		global $wpdb;
		$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$ice_user_id);
		return $userMoney == false ? 0 : ($userMoney->ice_have_money - $userMoney->ice_get_money);
	}

	public function curl_post($url = '', $postData = ''){
		if(function_exists('curl_init')){
			$ch = curl_init();								//初始化curl
			curl_setopt($ch, CURLOPT_URL, $url);			//设置抓取的url	
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	//要求结果为字符串且输出到屏幕上
			curl_setopt($ch, CURLOPT_POST, true);			//设置post方式提交
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);//设置post数据
			curl_setopt($ch, CURLOPT_TIMEOUT, 30); 			//设置cURL允许执行的最长秒数
			//https请求 不验证证书和host
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}else{
			wp_die("网站未开启curl组件，正常情况下该组件必须开启，请开启curl组件解决该问题");
		}
	}

	public function checkWppayPaid($order_num){
		global $wpdb, $wppay_table_name;
		$wppay_check = $wpdb->get_var($wpdb->prepare("SELECT id FROM $wppay_table_name
										WHERE	post_id = %d
										AND     order_status = 1
										AND		order_num = %s", $this->post_id, $order_num));
			
		$wppay_check = intval($wppay_check);
		return $wppay_check && $wppay_check > 0;
	}

	public function isWppayPaid(){
		global $wpdb, $wppay_table_name;

		if( isset($_COOKIE['wppay_'.$this->post_id]) ){
			$order_num = $this->getWppayKey($_COOKIE['wppay_'.$this->post_id]);
			$wppay_check = $wpdb->get_var($wpdb->prepare("SELECT id FROM $wppay_table_name
										WHERE	post_id = %d
										AND     order_status = 1
										AND		order_num = %s", $this->post_id, $order_num));
			$wppay_check = intval($wppay_check);
			return $wppay_check && $wppay_check > 0;
		}
		
		if($this->user_id){
			// user is logged in	
			$wppay_check = $wpdb->get_var($wpdb->prepare("SELECT id FROM $wppay_table_name
											WHERE   post_id = %d
											AND     order_status = 1
											AND		user_id = %d", $this->post_id, $this->user_id));
			if(!$wppay_check){
				if(get_option('erphp_wppay_ip')){
					$wppay_check = $wpdb->get_var($wpdb->prepare("SELECT id FROM $wppay_table_name
													WHERE	post_id = %d
													AND     order_status = 1
													AND		ip_address = %s
													AND		user_id = %d", $this->post_id, $this->ip, 0));
				}else{
					$wppay_check = 0;
				}
			}
		} else{
			// user not logged in, check by ip address
			if(get_option('erphp_wppay_ip')){
				$wppay_check = $wpdb->get_var($wpdb->prepare("SELECT id FROM $wppay_table_name
												WHERE	post_id = %d
												AND     order_status = 1
												AND		ip_address = %s
												AND		user_id = %d", $this->post_id, $this->ip, 0));
			}else{
				$wppay_check = 0;
			}
		}

		$wppay_check = intval($wppay_check);

		return $wppay_check && $wppay_check > 0;
	}

	public function addWppay($order_num,$post_price){
		global $wpdb, $wppay_table_name;
		
		$result = $wpdb->insert($wppay_table_name, array(
			'order_num' => $order_num,
			'post_id' => $this->post_id,
			'post_price' => $post_price,
			'user_id' => $this->user_id,
			'order_time' => date("Y-m-d H:i:s"),
			'ip_address' => $this->ip), array('%s', '%d', '%s', '%d', '%s', '%s'));

		if($result){
	    	return true;
	    }
	    return false;
	}

	public function f2fpayWppayQr($out_trade_no,$price){
		require_once ERPHPDOWN_PATH.'/payment/f2fpay/f2fpay/model/builder/AlipayTradePrecreateContentBuilder.php';
		require_once ERPHPDOWN_PATH.'/payment/f2fpay/f2fpay/service/AlipayTradeService.php';
		$outTradeNo = $out_trade_no;
		$totalAmount = $price;
		$subject = get_bloginfo('name').'充值订单';
		$body = $subject;
		$operatorId = "erphpdown";

		$providerId = ""; //系统商pid,作为系统商返佣数据提取的依据
		$extendParams = new ExtendParams();
		$extendParams->setSysServiceProviderId($providerId);
		$extendParamsArr = $extendParams->getExtendParams();

		$timeExpress = "5m";
		$goodsDetailList = array();

		$goods1 = new GoodsDetail();
		$goods1->setGoodsId($out_trade_no);
		$goods1->setGoodsName($subject);
		$goods1->setPrice($price*100);
		$goods1->setQuantity(1);
		$goods1Arr = $goods1->getGoodsDetail();

		$goodsDetailList = array($goods1Arr);

		$appAuthToken = "";//根据真实值填写

		$qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
		$qrPayRequestBuilder->setOutTradeNo($outTradeNo);
		$qrPayRequestBuilder->setTotalAmount($totalAmount);
		$qrPayRequestBuilder->setTimeExpress($timeExpress);
		$qrPayRequestBuilder->setSubject($subject);
		$qrPayRequestBuilder->setBody($body);
		$qrPayRequestBuilder->setUndiscountableAmount($undiscountableAmount);
		$qrPayRequestBuilder->setExtendParams($extendParamsArr);
		$qrPayRequestBuilder->setGoodsDetailList($goodsDetailList);
		$qrPayRequestBuilder->setStoreId($storeId);
		$qrPayRequestBuilder->setOperatorId($operatorId);
		$qrPayRequestBuilder->setAlipayStoreId($alipayStoreId);

		$qrPayRequestBuilder->setAppAuthToken($appAuthToken);

		$qrPay = new AlipayTradeService($config);
		$qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);
		return $qrPayResult;
	}

	public function youzanWppayToken(){

		require_once ERPHPDOWN_PATH.'/payment/youzan/lib/YZTokenClient.php';
		$url = "https://open.youzan.com/oauth/token";
		$data = array("client_id" => get_option('erphpdown_youzan_id'),"client_secret" => get_option('erphpdown_youzan_secret'),"grant_type"=>'silent',"kdt_id"=>get_option('erphpdown_youzan_store'));
		$result = $this->curl_post($url,$data);
		$resultArray = json_decode($result,true);
		if(isset($resultArray['error_description'])){
			
		}else{
			return $resultArray['access_token'];
		}
		return false;
	}

	public function youzanWppayQr($out_trade_no,$price,$token){
		require_once ERPHPDOWN_PATH.'/payment/youzan/lib/YZTokenClient.php';
		$client = new YZTokenClient($token);
		$method = 'youzan.pay.qrcode.create'; //要调用的api名称
		$api_version = '3.0.0'; //要调用的api版本号
		$my_params = array('qr_name' => $out_trade_no,
		    'qr_price' => $price*100,
		    'qr_source' => $out_trade_no,
		    'qr_type' => 'QR_TYPE_NOLIMIT');
		$my_files = array();
		$qr = $client->post($method, $api_version, $my_params, $my_files);
		return $qr;
	}

	public function getWppayKey($key){
		return str_replace( md5(get_option('erphpdown_downkey')), '', base64_decode($key) );
	}

	public function setWppayKey($order_num){
		return base64_encode($order_num.md5(get_option('erphpdown_downkey')));
	}

	public static function send_request($body, $method='POST'){
	    $url = 'http://api.mobantu.com/auth/erphpdown.php';
	    $result = wp_remote_request($url, array('method' => $method, 'body'=>$body));
	    if(is_array($result)){
	        return $result['body'];
	    }
	}

}