<?php 
require_once '../../../wp-load.php';
date_default_timezone_set('Asia/Shanghai');
if(!is_user_logged_in()){
	wp_die('请先登录系统');
}?>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="<?php echo constant("erphpdown"); ?>static/erphpdown.css" type="text/css" />
	<script type="text/javascript" src="<?php echo ERPHPDOWN_URL;?>/static/jquery-1.7.min.js"></script>
</head>
<body style="margin:18px;padding: 0">
	<div id="erphpdown-paybox">
	<?php
	$erphp_ajaxbuy = get_option('erphp_ajaxbuy');
	$postid=isset($_GET['postid']) && is_numeric($_GET['postid']) ?intval($_GET['postid']) :false;
	$postid = $wpdb->escape($postid);
	if($postid){
		$user_info=wp_get_current_user();
		$days=get_post_meta($postid, 'down_days', true);
		if($user_info->ID){

			$downInfo=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_user_id=".$user_info->ID ." and ice_post=".$postid." and ice_success=1 order by ice_time desc");
			if($days > 0){
				$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($downInfo->ice_time)));
				$nowDate = date('Y-m-d H:i:s');
				if(strtotime($nowDate) > strtotime($lastDownDate)){
					$downInfo = null;
				}
			}
			if($downInfo){
				?>
				<a class="erphpdown-btn" href="<?php echo constant("erphpdown");?>download.php?url=<?php echo $downInfo->ice_url?>">您已经购买过，点击直接下载</a>	
				<?php
			}else{
				$data=get_post_meta($postid, 'down_url', true);
				$price=get_post_meta($postid, 'down_price', true);
				$price_old = $price;
				$hidden=get_post_meta($postid, 'hidden_content', true);
				if($price){
					$okMoney=erphpGetUserOkMoney();
					$vip=false;
					$memberDown=get_post_meta($postid, 'member_down',TRUE);
					$userType=getUsreMemberType();
					if( $memberDown==4 || $memberDown==8 || $memberDown==9)
					{
						echo "您无权购买此资源！";exit;
					}
					if($userType && $memberDown==2)
					{
						$vip=TRUE;
						$price=$price*0.5;
					}
					if($userType && $memberDown==5)
					{
						$vip=TRUE;
						$price=$price*0.8;
					}

					$erphp_url_front_recharge = get_bloginfo('wpurl').'/wp-admin/admin.php?page=erphpdown/admin/erphp-add-money-online.php';
					if(get_option('erphp_url_front_recharge')){
						$erphp_url_front_recharge = get_option('erphp_url_front_recharge');
					}
					?>

					<table class="erphpdown-table" width="100%" align="center">
						<tr>
							<td><span>资源名称：</span><?php echo get_post($postid)->post_title?></td>
						</tr>
						<tr>
							<td><span>资源价格：</span><?php echo sprintf("%.2f",$price)?><?php echo  $vip==TRUE?'(原价:'.sprintf("%.2f",$price_old).')' :''?> <?php echo get_option('ice_name_alipay')?></td>
						</tr>
						<tr>
							<td><span>账户余额：</span><?php echo sprintf("%.2f",$okMoney)?> <?php echo get_option('ice_name_alipay')?></td>
						</tr>
						<tr>
							<td>
							<?php if($okMoney >= $price) {?>
								<?php if($erphp_ajaxbuy){?>
								<button class="erphpdown-btn do-erphpdown-pay" data-href="<?php echo constant("erphpdown").'checkout-ajax.php?postid='.$postid;?>" style="border:none;cursor: pointer;">使用余额支付</button>
								<?php }else{?>
								<a class="ss-button erphpdown-btn" href="<?php echo constant("erphpdown").'checkout.php?postid='.$postid; ?>"
									target="_blank">使用余额支付</a>
								<?php }?>
							<?php }else{echo "余额不足以完成此次付款，<a target=_blank class='erphpdown-btn' href='".$erphp_url_front_recharge."'>点击充值</a>";}?>
							</td>
						</tr>
					</table>
						<?php
					}else{
						echo "获取文章价格出错!";
					}
				}
			}else{
				echo "用户信息获取失败";
			}
		}else{
			echo "文章ID错误";
		}
		?>

	</div>
	<?php if($erphp_ajaxbuy){?>
	<script>
		$(".do-erphpdown-pay").click(function(){
			var that = $(this);
			that.text("处理中...").attr("disabled","disabled");
			$.ajax({  
	            type: 'GET',  
	            url:  $(this).data("href"),  
	            dataType: 'json',
				data: {

				},
	            success: function(data){
	            	that.text("使用余额支付").removeAttr("disabled");  
	                if( data.error ){
	                    if( data.msg ){
	                        alert(data.msg)
	                    }
	                    return
	                }else{
	                	//window.parent.alertSuccess();
	                	if(data.jump == '2'){
	                		parent.location.reload();
	                	}else if(data.jump == '1'){
	                		parent.location.href=data.link;
	                	}else{
	                		parent.location.reload();
	                	}
	                }

	            }  

	        });
	        return false;
		});
	</script>
	<?php }?>
</body>
</html>
