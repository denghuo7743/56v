<?php
require_once('../../../../../wp-load.php');
require_once 'f2fpay/service/AlipayTradeService.php';

$sign = $_POST['sign'];
$signType = $_POST['sign_type'];
$total_fee= $_POST['total_amount'];
$out_trade_no = $wpdb->escape($_POST['out_trade_no']);
$trade_no = $wpdb->escape($_POST['trade_no']);
$buyer_logon_id = $wpdb->escape($_POST['buyer_logon_id']);
$status = $_POST['trade_status'];

ksort($_POST); //排序post参数
reset($_POST); //内部指针指向数组中的第一个元素
$signStr = '';//初始化
foreach ($_POST AS $key => $val) { //遍历POST参数
    if ($val == '' || $key == 'sign' || $key == 'sign_type') continue; //跳过这些不签名
    if ($signStr) $signStr .= '&'; //第一个字符串签名不加& 其他加&连接起来参数
    $signStr .= "$key=$val"; //拼接为url参数形式
}
$signStr = str_replace('\"', '"', $signStr);
$res = "-----BEGIN PUBLIC KEY-----\n".wordwrap($config['alipay_public_key'], 64, "\n", true) ."\n-----END PUBLIC KEY-----";
$result = (bool)openssl_verify($signStr, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);

if($result){

	if($status == 'TRADE_FINISHED' || $status == 'TRADE_SUCCESS') {

		global $wpdb, $wppay_table_name;
		if(strstr($out_trade_no,'wppay')){
			$order=$wpdb->get_row("select * from $wppay_table_name where order_num='".$out_trade_no."'");
			if($order){
				if(!$order->order_status){
					$wpdb->query("UPDATE $wppay_table_name SET order_status=1 WHERE order_num = '".$out_trade_no."'");
					if($order->user_id){
						$data=get_post_meta($order->post_id, 'down_url', true);
						$ppost = get_post($order->post_id);
						erphpAddDownloadByUid($ppost->post_title,$order->post_id,$order->user_id,$total_fee*get_option('ice_proportion_alipay'),1,$data,$ppost->post_author);
					}
				}
			}
		}else{

			$money_info=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num='".$out_trade_no."'");
			if($money_info){
				if(!$money_info->ice_success){
					addUserMoney($money_info->ice_user_id, $total_fee*get_option('ice_proportion_alipay'));
					$wpdb->query("UPDATE $wpdb->icemoney SET ice_money = '".$total_fee*get_option('ice_proportion_alipay')."', ice_alipay = '".$buyer_logon_id."',ice_success=1, ice_success_time = '".date("Y-m-d H:i:s")."' WHERE ice_num = '".$out_trade_no."'");
				}
			}
		}

	    echo "success";
	}

}else{
	echo 'error';
}