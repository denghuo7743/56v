<?php 
/**
 * 调用支付
 * 
 * 实现微信、支付宝支付的接口
 * @date 2017年3月13日
 * @copyright 重庆迅虎网络有限公司
 */
require_once('../../../../wp-load.php');
header("Content-Type: text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
require_once 'xhpay/api2.php';
if(!is_user_logged_in()){wp_die('请先登录！');}

$appid              = get_option('erphpdown_xhpay_appid2');
$appsecret          = get_option('erphpdown_xhpay_appsecret2');
$my_plugin_id ='erphpdown-xhpay2';

$trade_order_id = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
$price   = isset($_GET['ice_money']) && is_numeric($_GET['ice_money']) ?$_GET['ice_money'] :0;
$price = $wpdb->escape($price);

$subject = 'order['.get_the_author_meta( 'user_login', wp_get_current_user()->ID ).']';
$erphpdown_min_price    = get_option('erphpdown_min_price');
if($erphpdown_min_price > 0){
    if($price < $erphpdown_min_price){
        wp_die('您最低需充值'.$erphpdown_min_price.'元');
    }
}
if($price > 0){
    $user_Info   = wp_get_current_user();
    $sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay)
    VALUES ('$price','$trade_order_id','".$user_Info->ID."','".date("Y-m-d H:i:s")."',0,'0','".date("Y-m-d H:i:s")."','')";
    $a=$wpdb->query($sql);
    if(!$a){
        wp_die('系统发生错误，请稍后重试!');
    }
}else{
    wp_die('请输入您要充值的金额');
}  

$payment = 'alipay';

if($_GET['type'] == '2') $payment = 'wechat';

$data=array(
    'version'   => '1.1',//固定值，api 版本，目前暂时是1.1
    'lang'       => 'zh-cn', //必须的，zh-cn或en-us 或其他，根据语言显示页面
    'plugins'   => $my_plugin_id,//必须的，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+ 
    'appid'     => $appid, //必须的，APPID
    'trade_order_id'=> $trade_order_id, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+ 
    'payment'   => $payment,//必须的，支付接口标识：wechat(微信接口)|alipay(支付宝接口)
    'total_fee' => $price,//人民币，单位精确到分(测试账户只支持0.1元内付款)
    'title'     => $subject, //必须的，订单标题，长度32或以内
    'time'      => time(),//必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
    'notify_url'=>  constant("erphpdown").'payment/xhpay/notify2.php', //必须的，支付成功异步回调接口
    'return_url'=> get_option('erphp_url_front_success'),//必须的，支付成功后的跳转地址
    'callback_url'=>get_option('erphp_url_front_success'),//必须的，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
    'nonce_str' => str_shuffle(time())//必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
);

$hashkey =$appsecret;
$data['hash']     = XH_Payment_Api::generate_xh_hash($data,$hashkey);
/**
 * 个人支付宝/微信即时到账，支付网关：https://pay2.xunhupay.com/v2
 * 微信支付宝代收款，需提现，支付网关：https://pay.wordpressopen.com
 */
$url              = 'https://pay2.xunhupay.com/v2/payment/do.html';

try {
    $response     = XH_Payment_Api::http_post($url, json_encode($data));
    /**
     * 支付回调数据
     * @var array(
     *      order_id,//支付系统订单ID
     *      url//支付跳转地址
     *  )
     */
    $result       = $response?json_decode($response,true):null;
    if(!$result){
        throw new Exception('Internal server error',500);
    }
     
    $hash         = XH_Payment_Api::generate_xh_hash($result,$hashkey);
    if(!isset( $result['hash'])|| $hash!=$result['hash']){
        throw new Exception(__('Invalid sign!',XH_Wechat_Payment),40029);
    }

    if($result['errcode']!=0){
        throw new Exception($result['errmsg'],$result['errcode']);
    }
    
    
    $pay_url =$result['url'];
    header("Location: $pay_url");
    exit;
} catch (Exception $e) {
    echo "errcode:{$e->getCode()},errmsg:{$e->getMessage()}";
    //TODO:处理支付调用异常的情况
}
?>