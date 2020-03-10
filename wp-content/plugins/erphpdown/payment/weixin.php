<?php
//error_reporting(E_ERROR);
require_once('../../../../wp-load.php');
header("Content-Type: text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
require_once "weixin/lib/WxPay.Api.php";
require_once "weixin/lib/WxPay.NativePay.php";

$price   = isset($_GET['ice_money']) && is_numeric($_GET['ice_money']) ?$_GET['ice_money'] :0;
$price = $wpdb->escape($price);
$erphpdown_min_price    = get_option('erphpdown_min_price');
if($erphpdown_min_price > 0){
	if($price < $erphpdown_min_price){
		wp_die('您最低需充值'.$erphpdown_min_price.'元');
	}
}
if($price && is_user_logged_in()){

	global $wpdb;
	$subject = get_bloginfo('name').'充值订单['.get_the_author_meta( 'user_login', wp_get_current_user()->ID ).']';  
	$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);	
	$time = date('Y-m-d H:i:s');
	if(!empty($price)){
		$user_Info   = wp_get_current_user();
		$sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay)
		VALUES ('$price','$out_trade_no','".$user_Info->ID."','".date("Y-m-d H:i:s")."',0,'0','".date("Y-m-d H:i:s")."','')";
		$a=$wpdb->query($sql);
		if(!$a){
			wp_die('系统发生错误，请稍后重试!');
		}else{
			$money_info=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num='".$out_trade_no."'");
		}
	}else{
		wp_die('请输入您要充值的金额');
	}


	//模式一
	/**
	 * 流程：
	 * 1、组装包含支付信息的url，生成二维码
	 * 2、用户扫描二维码，进行支付
	 * 3、确定支付之后，微信服务器会回调预先配置的回调地址，在【微信开放平台-微信支付-支付配置】中进行配置
	 * 4、在接到回调通知之后，用户进行统一下单支付，并返回支付信息以完成支付（见：native_notify.php）
	 * 5、支付完成之后，微信服务器会通知支付成功
	 * 6、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
	 */
	$notify = new NativePay();
	//$url1 = $notify->GetPrePayUrl($out_trade_no);
	
	//模式二
	/**
	 * 流程：
	 * 1、调用统一下单，取得code_url，生成二维码
	 * 2、用户扫描二维码，进行支付
	 * 3、支付完成之后，微信服务器会通知支付成功
	 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
	 */
	$input = new WxPayUnifiedOrder();
	$input->SetBody($subject);
	$input->SetAttach("ERPHPDOWN");
	$input->SetOut_trade_no($out_trade_no);
	$input->SetTotal_fee($price*100);
	$input->SetTime_start(date("YmdHis"));
	//$input->SetTime_expire(date("YmdHis", time() + 600));
	$input->SetGoods_tag("MBT");
	$input->SetNotify_url(constant("erphpdown").'payment/weixin/notify.php');
	$input->SetTrade_type("NATIVE");
	$input->SetProduct_id($out_trade_no);
	$result = $notify->GetPayUrl($input);
	//var_dump($result);
	$url2 = $result["code_url"];
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>微信支付</title>
    <link rel='stylesheet'  href='../static/erphpdown.css' type='text/css' media='all' />
</head>
<body>

	<div class="wppay-custom-modal-box mobantu-wppay">
		<section class="wppay-modal">
                    
            <section class="erphp-wppay-qrcode mobantu-wppay wppay-net">
                <section class="tab">
                    <a href="javascript:;" class="active"><div class="payment"><img src="<?php echo constant("erphpdown");?>static/images/payment-weixin.png"></div>扫一扫支付 <span class="price"><?php echo $price?></span> 元</a>
                           </section>
                <section class="tab-list" style="background-color: #21ab36 !important;">
                    <section class="item">
                        <section class="qr-code">
                            <img src="<?php echo constant("erphpdown");?>payment/weixin/qrcode.php?data=<?php echo urlencode($url2);?>" class="img" alt="">
                        </section>
                        <p class="account">支付完成后请等待5秒左右，期间请勿关闭此页面</p>
                        <p class="desc">手机端可长按二维码选择用微信打开</p>
                    </section>
                </section>
            </section>
        
    	</section>
    </div>

    <script src="<?php echo ERPHPDOWN_URL;?>/static/jquery-1.7.min.js"></script>
	<script>
		setOrder = setInterval(function() {
			$.ajax({  
	            type: 'POST',  
	            url: '<?php echo ERPHPDOWN_URL;?>/admin/action/order.php',  
	            data: {
	            	do: 'checkOrder',
	            	order: '<?php echo $money_info->ice_id;?>'
	            },  
	            dataType: 'text',
	            success: function(data){  
	                if( $.trim(data) == '1' ){
	                    clearInterval(setOrder);
	                    alert('充值成功！');
	                    <?php if(get_option('erphp_url_front_success')){?>
	                    location.href="<?php echo get_option('erphp_url_front_success');?>";
	                    <?php }else{?>
	                    window.close();
	                	<?php }?>
	                }  
	            },
	            error: function(XMLHttpRequest, textStatus, errorThrown){
	            	//alert(errorThrown);
	            }
	        });

		}, 5000);
	</script>
</body>
</html>

<?php }?>