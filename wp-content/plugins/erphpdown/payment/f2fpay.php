<?php 
require_once('../../../../wp-load.php');
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
if(!is_user_logged_in()){wp_die('请先登录！');}
require_once 'f2fpay/f2fpay/model/builder/AlipayTradePrecreateContentBuilder.php';
require_once 'f2fpay/f2fpay/service/AlipayTradeService.php';

$price   = isset($_GET['ice_money']) && is_numeric($_GET['ice_money']) ?$_GET['ice_money'] :0;
$price = $wpdb->escape($price);
$erphpdown_min_price    = get_option('erphpdown_min_price');
if($erphpdown_min_price > 0){
	if($price < $erphpdown_min_price){
		wp_die('您最低需充值'.$erphpdown_min_price.'元');
	}
}
if($price){
	
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

	// (必填) 商户网站订单系统中唯一订单号，64个字符以内，只能包含字母、数字、下划线，
	// 需保证商户系统端不能重复，建议通过数据库sequence生成，
	//$outTradeNo = "qrpay".date('Ymdhis').mt_rand(100,1000);
	$outTradeNo = $out_trade_no;

	// (必填) 订单标题，粗略描述用户的支付目的。如“xxx品牌xxx门店当面付扫码消费”
	//$subject = $_POST['subject'];

	// (必填) 订单总金额，单位为元，不能超过1亿元
	// 如果同时传入了【打折金额】,【不可打折金额】,【订单总金额】三者,则必须满足如下条件:【订单总金额】=【打折金额】+【不可打折金额】
	$totalAmount = $price;


	// (不推荐使用) 订单可打折金额，可以配合商家平台配置折扣活动，如果订单部分商品参与打折，可以将部分商品总价填写至此字段，默认全部商品可打折
	// 如果该值未传入,但传入了【订单总金额】,【不可打折金额】 则该值默认为【订单总金额】- 【不可打折金额】
	//String discountableAmount = "1.00"; //

	// (可选) 订单不可打折金额，可以配合商家平台配置折扣活动，如果酒水不参与打折，则将对应金额填写至此字段
	// 如果该值未传入,但传入了【订单总金额】,【打折金额】,则该值默认为【订单总金额】-【打折金额】
	//$undiscountableAmount = "0.01";

	// 卖家支付宝账号ID，用于支持一个签约账号下支持打款到不同的收款账号，(打款到sellerId对应的支付宝账号)
	// 如果该字段为空，则默认为与支付宝签约的商户的PID，也就是appid对应的PID
	//$sellerId = "";

	// 订单描述，可以对交易或商品进行一个详细地描述，比如填写"购买商品2件共15.00元"
	$body = $subject;

	//商户操作员编号，添加此参数可以为商户操作员做销售统计
	$operatorId = "erphpdown";

	// (可选) 商户门店编号，通过门店号和商家后台可以配置精准到门店的折扣信息，详询支付宝技术支持
	//$storeId = "test_store_id";

	// 支付宝的店铺编号
	//$alipayStoreId= "test_alipay_store_id";

	// 业务扩展参数，目前可添加由支付宝分配的系统商编号(通过setSysServiceProviderId方法)，系统商开发使用,详情请咨询支付宝技术支持
	$providerId = ""; //系统商pid,作为系统商返佣数据提取的依据
	$extendParams = new ExtendParams();
	$extendParams->setSysServiceProviderId($providerId);
	$extendParamsArr = $extendParams->getExtendParams();

	// 支付超时，线下扫码交易定义为5分钟
	$timeExpress = "5m";

	// 商品明细列表，需填写购买商品详细信息，
	$goodsDetailList = array();

	// 创建一个商品信息，参数含义分别为商品id（使用国标）、名称、单价（单位为分）、数量，如果需要添加商品类别，详见GoodsDetail
	$goods1 = new GoodsDetail();
	$goods1->setGoodsId($out_trade_no);
	$goods1->setGoodsName($subject);
	$goods1->setPrice($price*100);
	$goods1->setQuantity(1);
	//得到商品1明细数组
	$goods1Arr = $goods1->getGoodsDetail();

	// 继续创建并添加第一条商品信息，用户购买的产品为“xx牙刷”，单价为5.05元，购买了两件
	//$goods2 = new GoodsDetail();
	//$goods2->setGoodsId("apple-02");
	//$goods2->setGoodsName("ipad");
	//$goods2->setPrice(1000);
	//$goods2->setQuantity(1);
	//得到商品1明细数组
	//$goods2Arr = $goods2->getGoodsDetail();

	$goodsDetailList = array($goods1Arr);

	//第三方应用授权令牌,商户授权系统商开发模式下使用
	$appAuthToken = "";//根据真实值填写

	// 创建请求builder，设置请求参数
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


	// 调用qrPay方法获取当面付应答
	$qrPay = new AlipayTradeService($config);
	$qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);

	//	根据状态值进行业务处理
	switch ($qrPayResult->getTradeStatus()){
		case "SUCCESS":
			//echo "支付宝创建订单二维码成功:"."<br>---------------------------------------<br>";
			$response = $qrPayResult->getResponse();
			//$qrcode = $qrPay->create_erweima($response->qr_code);
			//echo $qrcode;
			//echo '<img alt="扫码支付" src="'.constant("erphpdown").'payment/f2fpay/qrcode.php?data='.urlencode($response->qr_code).'" style="width:200px;height:200px;"/>';
			//print_r($response);
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>支付宝支付</title>
    <link rel='stylesheet'  href='../static/erphpdown.css' type='text/css' media='all' />
</head>
<body>
	<div class="wppay-custom-modal-box mobantu-wppay">
		<section class="wppay-modal">
                    
            <section class="erphp-wppay-qrcode mobantu-wppay wppay-net">
                <section class="tab">
                    <a href="javascript:;" class="active"><div class="payment"><img src="<?php echo constant("erphpdown");?>static/images/payment-alipay.png"></div>扫一扫支付 <span class="price"><?php echo $price?></span> 元</a>
                           </section>
                <section class="tab-list" style="background-color: #00a3ee !important;">
                    <section class="item">
                        <section class="qr-code">
                            <img src="<?php echo constant("erphpdown").'payment/f2fpay/qrcode.php?data='.urlencode($response->qr_code);?>" class="img" alt="">
                        </section>
                        <p class="account">支付完成后请等待5秒左右，期间请勿关闭此页面</p>
                        <p class="desc">手机端可长按二维码选择用支付宝打开</p>
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
<?php			
			break;
		case "FAILED":
			echo "支付宝创建订单二维码失败!!!"."<br>--------------------------<br>";
			if(!empty($qrPayResult->getResponse())){
				print_r($qrPayResult->getResponse());
			}
			break;
		case "UNKNOWN":
			echo "系统异常，状态未知!!!"."<br>--------------------------<br>";
			if(!empty($qrPayResult->getResponse())){
				print_r($qrPayResult->getResponse());
			}
			break;
		default:
			echo "不支持的返回状态，创建订单二维码返回异常!!!";
			break;
	}
	return ;
}

?>