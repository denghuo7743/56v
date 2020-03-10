<?php 
require_once('../../../../wp-load.php');
header("Content-Type: text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

if(!is_user_logged_in()){wp_die('请先登录！');}

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

$type=1;
if($_GET['type']) $type = $_GET['type'];


$codepay_id=get_option('erphpdown_codepay_appid');//这里改成码支付ID
$codepay_key=get_option('erphpdown_codepay_appsecret'); //这是您的通讯密钥

$data = array(
    "id" => $codepay_id,//你的码支付ID
    "pay_id" => $trade_order_id, //唯一标识 可以是用户ID,用户名,session_id(),订单ID,ip 付款后返回
    "type" => $type,//1支付宝支付 3微信支付 2QQ钱包
    "price" => $price,//金额100元
    "param" => "erphpdown",//自定义参数
    "notify_url"=>constant("erphpdown").'payment/codepay/notify.php',//通知地址
    "return_url"=>get_option('erphp_url_front_success'),//跳转地址
); //构造需要传递的参数

ksort($data); //重新排序$data数组
reset($data); //内部指针指向数组中的第一个元素

$sign = ''; //初始化需要签名的字符为空
$urls = ''; //初始化URL参数为空

foreach ($data AS $key => $val) { //遍历需要传递的参数
    if ($val == ''||$key == 'sign') continue; //跳过这些不参数签名
    if ($sign != '') { //后面追加&拼接URL
        $sign .= "&";
        $urls .= "&";
    }
    $sign .= "$key=$val"; //拼接为url参数形式
    $urls .= "$key=" . urlencode($val); //拼接为url参数形式并URL编码参数值

}
$query = $urls . '&sign=' . md5($sign .$codepay_key); //创建订单所需的参数
$url = "http://api2.fateqq.com:52888/creat_order/?{$query}"; //支付页面

header("Location:{$url}"); //跳转到支付页面