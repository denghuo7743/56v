<?php
require_once('../../../../../wp-load.php');
if(version_compare(phpversion(), '7.0.0') >= 0){
	$json = file_get_contents('php://input');
}else{
	$json = $GLOBALS['HTTP_RAW_POST_DATA'];
}
$jsondata = json_decode($json, true);  

if($jsondata['test'] != "true"){
	$client_id = get_option("erphpdown_youzan_id");
	$client_secret = get_option("erphpdown_youzan_secret");
	$sign = md5($client_id."".$jsondata['msg']."".$client_secret);
	
	if($jsondata['mode'] == "1" and $sign == $jsondata['sign'] and $jsondata['type'] == "trade_TradePaid"){
		$imsg = json_decode(urldecode($jsondata['msg']),true);
		$amount = $imsg['full_order_info']['orders']['0']['payment'];
		$title = $imsg['full_order_info']['orders']['0']['title'];
		$id = $jsondata['id'];
		$total_fee=$wpdb->escape($amount);
		global $wpdb, $wppay_table_name;

		if(strstr($title,'wppay')){
			$order=$wpdb->get_row("select * from $wppay_table_name where order_num='".$wpdb->escape($title)."'");
			if($order){
				if(!$order->order_status){
					$wpdb->query("UPDATE $wppay_table_name SET order_pay_num = '".$wpdb->escape($id)."',order_status=1 WHERE order_num = '".$wpdb->escape($title)."'");

					if($order->user_id){
						$data=get_post_meta($order->post_id, 'down_url', true);
						$ppost = get_post($order->post_id);
						erphpAddDownloadByUid($ppost->post_title,$order->post_id,$order->user_id,$total_fee*get_option('ice_proportion_alipay'),1,$data,$ppost->post_author);
					}

					echo '{"code":0,"msg":"success"}';
				}
			}
		}else{
			$money_info=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num='".$wpdb->escape($title)."'");
			if($money_info){
				if(!$money_info->ice_success){
					addUserMoney($money_info->ice_user_id, $total_fee*get_option('ice_proportion_alipay'));
					$wpdb->query("UPDATE $wpdb->icemoney SET ice_money = '".$total_fee*get_option('ice_proportion_alipay')."', ice_alipay = '".$wpdb->escape($id)."',ice_success=1, ice_success_time = '".date("Y-m-d H:i:s")."' WHERE ice_num = '".$wpdb->escape($title)."'");
					echo '{"code":0,"msg":"success"}';
				}
			}
		}


	}
}else{
	echo '{"code":0,"msg":"success"}';
}