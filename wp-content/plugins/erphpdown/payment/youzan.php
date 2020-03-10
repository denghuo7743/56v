<?php
require_once('../../../../wp-load.php');
header("Content-Type: text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
if(!is_user_logged_in()){wp_die('请先登录！');}
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
	require_once 'youzan/lib/YZTokenClient.php';
	$url = "https://open.youzan.com/oauth/token";
	$data = array("client_id" => get_option("erphpdown_youzan_id"),"client_secret" => get_option("erphpdown_youzan_secret"),"grant_type"=>'silent',"kdt_id"=>get_option("erphpdown_youzan_store"));
	$result = erphpdown_curl_post($url,$data);
	$resultArray = json_decode($result,true);
	if(isset($resultArray['error_description'])){
		wp_die($resultArray['error_description']."，请检查接口是否配置正确");
	}else{
		$token = $resultArray['access_token'];
		if($token){
			$client = new YZTokenClient($token);
			$method = 'youzan.pay.qrcode.create'; //要调用的api名称
			$api_version = '3.0.0'; //要调用的api版本号
			$my_params = [
			    'qr_name' => $out_trade_no,//请勿修改
			    'qr_price' => $price*100,
			    'qr_source' => $out_trade_no,
			    'qr_type' => 'QR_TYPE_NOLIMIT',
			];
			$my_files = [
			];
			$qr = $client->post($method, $api_version, $my_params, $my_files);
			if($qr['response']['qr_code']){
				

			?>
			<html>
			<head>
			    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
			    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
			    <title>在线支付</title>
			</head>
			<body style="background:#333 url(<?php echo constant("erphpdown");?>static/images/wxbg.png) top center; text-align:center; width:100%; margin:auto;">
				<div style="width:250px; margin:25px auto auto auto; text-align:center; background:#DDD; padding:20px 20px 40px 20px;border-radius: 6px; ">
			    <h1 style="font-size:20px; line-height:50px; font-family:'Microsoft YaHei'; font-weight:normal; border-bottom:1px dotted #666; color: #333">扫码支付 <b style="color:#F00"><?php echo $price?></b> 元
				<img alt="扫码支付" src="<?php echo $qr['response']['qr_code'];?>" style="width:200px;height:200px;"/>
				<p style="font-size: 15px;">扫码支付完成后请等待5秒左右</p>
				<p style="font-size: 15px;">或 <a href="<?php echo $qr['response']['qr_url'];?>" style="color:#2a30e4">点击进去支付页面</a></p>
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
				
			}else{
				wp_die("二维码生成失败，请稍后重试");
			}
		}else{
			wp_die("access_token不能为空，请检查接口是否配置正确");
		}
	}
}