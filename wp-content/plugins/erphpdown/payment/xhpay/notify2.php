<?php 
/**
 * 支付成功异步回调接口
 *
 * 当用户支付成功后，支付平台会把订单支付信息异步请求到本接口(最多5次)
 * 
 * @date 2017年3月13日
 * @copyright 重庆迅虎网络有限公司
 */
require_once('../../../../../wp-load.php');
require_once 'api2.php';

/**
 * 回调数据
 * @var array(
 *       'trade_order_id'，商户网站订单ID
         'total_fee',订单支付金额
         'transaction_id',//支付平台订单ID
         'order_date',//支付时间
         'plugins',//自定义插件ID,与支付请求时一致
         'status'=>'OD'//订单状态，OD已支付，WP未支付
 *   )
 */

$appid              = get_option('erphpdown_xhpay_appid2');//测试账户，仅支持一元内支付
$appsecret          = get_option('erphpdown_xhpay_appsecret2');//测试账户，仅支持一元内支付
$my_plugin_id       = 'erphpdown-xhpay2';

$data = $_POST;
foreach ($data as $k=>$v){
    $data[$k] = stripslashes($v);
}
if(!isset($data['hash'])||!isset($data['trade_order_id'])){
   echo 'failed';exit;
}

//自定义插件ID,请与支付请求时一致
if(isset($data['plugins'])&&$data['plugins']!=$my_plugin_id){
    echo 'failed';exit;
}

//APP SECRET
$appkey =$appsecret;
$hash =XH_Payment_Api::generate_xh_hash($data,$appkey);
if($data['hash']!=$hash){
    //签名验证失败
    echo 'failed';exit;
}

//商户订单ID
$trade_order_id =$data['trade_order_id'];

if($data['status']=='OD'){
    /************商户业务处理******************/
    //TODO:此处处理订单业务逻辑,支付平台会多次调用本接口(防止网络异常导致回调失败等情况)
    //     请避免订单被二次更新而导致业务异常！！！
    //     if(订单未处理){
    //         处理订单....
    //      }
    global $wpdb;
    $total_fee=$data['total_fee'];
    $money_info=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num='".$wpdb->escape($trade_order_id)."'");
    if($money_info){
        if(!$money_info->ice_success){
            addUserMoney($money_info->ice_user_id, $total_fee*get_option('ice_proportion_alipay'));
            $wpdb->query("UPDATE $wpdb->icemoney SET ice_money = '".$total_fee*get_option('ice_proportion_alipay')."',ice_success=1, ice_success_time = '".date("Y-m-d H:i:s")."' WHERE ice_num = '".$wpdb->escape($trade_order_id)."'");
        }
    }
    //....
    //...
    /*************商户业务处理 END*****************/
}else{
    //处理未支付的情况    
}

//以下是处理成功后输出，当支付平台接收到此消息后，将不再重复回调当前接口
echo 'success';
exit;
?>