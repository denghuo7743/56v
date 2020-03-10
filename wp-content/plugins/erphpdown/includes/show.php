<?php
/**
author: www.thefox.cn
*/
if ( !defined('ABSPATH') ) {exit;}
function erphpdown_content_show($content){
	$content2 = $content;
	if(is_singular()){
		$start_down=get_post_meta(get_the_ID(), 'start_down', true);
		$start_see=get_post_meta(get_the_ID(), 'start_see', true);
		$start_see2=get_post_meta(get_the_ID(), 'start_see2', true);
		$price=get_post_meta(get_the_ID(), 'down_price', true);
		$url=get_post_meta(get_the_ID(), 'down_url', true);
		$memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
		$hidden=get_post_meta(get_the_ID(), 'hidden_content', true);
		$userType=getUsreMemberType();
		
		$erphp_url_front_vip = get_bloginfo('wpurl').'/wp-admin/admin.php?page=erphpdown/admin/erphp-update-vip.php';
		if(get_option('erphp_url_front_vip')){
			$erphp_url_front_vip = get_option('erphp_url_front_vip');
		}
		$erphp_url_front_login = wp_login_url();
		if(get_option('erphp_url_front_login')){
			$erphp_url_front_login = get_option('erphp_url_front_login');
		}
		
		if($start_down){
			$content.='<div class="foxpay" id="foxpay"><div class="down-detail">';
			$content.='<h5 style="color:#fff;"><i class="fa fa fa-eye fa-fw"></i> 资源下载</h5>';
			if(is_user_logged_in())
			{
				if($hidden){
					$content.='<p class="down-hidden"><i class="fa fa-eye-slash fa-fw"></i> 隐藏内容：******，购买后可见！</p>';
				}
				if($price){
					$content.='<p class="down-price"><i class="fa fa-jpy fa-fw"></i> 价格：<span>'.$price.'</span>&nbsp;'.get_option("ice_name_alipay").'</p>';
				}else{
					if($memberDown != 4)
						$content.='<p class="down-price"><i class="fa fa-jpy fa-fw"></i> 恭喜，此资源免费</p>';
				}
				if($price || $memberDown == 4){
					$content.='<p class="down-ordinary">';
					global $wpdb;
					$user_info=wp_get_current_user();
					$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and ice_user_id=".$user_info->ID);
					if($memberDown > 1 && $userType==false)
					{
						if($memberDown==3 && $down_info==null)
						{
							$content.='<strong><i class="fa fa-address-card-o fa-fw card" style="background-color: #ff3737;"></i> 会员免费下载</strong> <a href="'.$erphp_url_front_vip.'" target="_blank" class="vip" style="text-decoration: none;">升级会员</a>&nbsp;&nbsp;&nbsp;&nbsp;';
						}
						elseif ($memberDown==2 && $down_info==null)
						{
							$content.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 会员5折下载</strong> <a href="'.$erphp_url_front_vip.'" target="_blank" class="vip">升级会员</a>&nbsp;&nbsp;';
						}
						elseif ($memberDown==5 && $down_info==null)
						{
							$content.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 会员8折下载</strong> <a href="'.$erphp_url_front_vip.'" target="_blank" class="vip">升级会员</a>&nbsp;&nbsp;';
						}
						elseif ($memberDown==6 && $down_info==null)
						{
							$content.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 包年会员免费下载</strong> <a href="'.$erphp_url_front_vip.'" target="_blank" class="vip">升级会员</a>&nbsp;&nbsp;';
						}
						elseif ($memberDown==7 && $down_info==null)
						{
							$content.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 终身会员免费下载</strong> <a href="'.$erphp_url_front_vip.'" target="_blank" class="vip">升级会员</a>&nbsp;&nbsp;';
						}
					}
					if($memberDown==4 && $userType==FALSE)
					{
						$content.='<i class="fa fa-address-card-o fa-fw card"></i> 仅对会员开放下载 <a href="'.$erphp_url_front_vip.'" target="_blank" class="vip">升级会员</a>&nbsp;&nbsp;';
					}
					else 
					{
						
						if($userType && $memberDown > 1)
						{
							$msg='<i class="fa fa-download"></i> 下载地址：&nbsp;';
							if($memberDown==3 || $memberDown==4)
							{
								$msg.='您是会员，可以免费下载此资源！';
								$content.=$msg."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." class='erphpdown-down erphpdown-down-layui vip3'> 进入下载页面</a>";
							}
							elseif ($memberDown==2 && $down_info==null)
							{
								$msg.='您是会员，可以5折（价格为：'.($price*0.5).get_option('ice_name_alipay').'）购买下载此资源！';
								$content.=$msg.'<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
							}
							elseif ($memberDown==5 && $down_info==null)
							{
								$msg.='您是会员，可以8折（价格为：'.($price*0.8).get_option('ice_name_alipay').'）购买下载此资源！';
								$content.=$msg.'<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
							}
							elseif ($memberDown==6 && $down_info==null)
							{
								if($userType == 9){
									$msg.='您是包年会员，可以免费下载此资源！';
									$content.=$msg."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." class='erphpdown-down erphpdown-down-layui vip3'> 进入下载页面</a>";
										
								}elseif($userType == 10){
									$msg.='您是终身会员，可以免费下载此资源！';
									$content.=$msg."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." class='erphpdown-down erphpdown-down-layui vip3'> 进入下载页面</a>";
										
								}else{
									$msg.='您是会员，原价购买下载此资源！（年费会员免费）';
										$content.=$msg.'<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
								}
							}
							elseif ($memberDown==7 && $down_info==null)
							{
								if($userType == 10){
									$msg.='您是终身会员，可以免费下载此资源！';
									$content.=$msg."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." class='erphpdown-down erphpdown-down-layui vip3'> 进入下载页面</a>";
										
								}else{
									$msg.='您是会员，原价购买下载此资源！（终身会员免费）';
										$content.=$msg.'<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
								}
							}
							elseif($down_info)
							{
								$ice_url = $wpdb->get_var("SELECT ice_url FROM $wpdb->icealipay where ice_success=1 and ice_user_id=$user_info->ID and ice_post=".get_the_ID());
								$content.='<i class="fa fa-download"></i> <a href='.constant("erphpdown").'download.php?url='.$down_info->ice_url.' class="erphpdown-down erphpdown-down-layui vip3">您已购买过，直接去下载</a>';
							}
						}
						else 
						{
							
							if($down_info && $down_info->ice_price > 0)
							{
								$ice_url = $wpdb->get_var("SELECT ice_url FROM $wpdb->icealipay where ice_success=1 and ice_user_id=$user_info->ID and ice_post=".get_the_ID());
								$content.='<i class="fa fa-download"></i> <a href='.constant("erphpdown").'download.php?url='.$down_info->ice_url.' class="erphpdown-down erphpdown-down-layui vip3">您已购买过，直接去下载</a>';
							}
							else 
							{
								$content.='<strong><i class="fa fa-address-card-o fa-fw card" style="background-color: #4c4c4c;"></i> 普通用户</strong> <a class="erphpdown-iframe erphpdown-buy vip" href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().' target="_blank" style="text-decoration: none;">直接购买</a>';
							}
						}
					}
					$content.='</p>';
				}else{
					$content.='<p class="down-ordinary"><i class="fa fa-download"></i> <a  href="'.constant("erphpdown").'download.php?postid='.get_the_ID().'" class="erphpdown-down erphpdown-down-layui vip3"> 进入下载页面</a></p>';
				}
				
				
			}
			else {
				$content.='<p class="down-hidden"><i class="fa fa-eye-slash fa-fw"></i> 隐藏内容：******，购买后可见！</p>';
				$content.='<p class="down-price"><i class="fa fa-jpy fa-fw"></i> 价格：<span>'.$price.'</span>&nbsp;'.get_option('ice_name_alipay').'</p>';
				$content.='<p class="down-ordinary">';
				$content.='<i class="fa fa-user-circle fa-fw"></i> 您需要先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must"><i class="fa fa-wordpress"></i> 注册/登陆</a>后，才能购买资源</p>';
				
			}
			
			if(get_option('ice_tips')) $content.='<p class="down-tip"><i class="fa fa-lightbulb-o fa-fw"></i> '.get_option('ice_tips').'</p>';
			$content.='<p class="down-tip"><i class="fa fa-lightbulb-o fa-fw"></i> 0积分为免费资源！登陆后即可下载！</p></div></div>';
			
		}elseif($start_see){
			
			if(is_user_logged_in())
			{
				global $wpdb;
				$user_info=wp_get_current_user();
				$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and ice_user_id=".$user_info->ID);
				if( ($userType && ($memberDown==3 || $memberDown==4)) || ($down_info && $down_info->ice_price > 0) || ($memberDown==6 && $userType >= 9) || ($memberDown==7 && $userType == 10) ){
					return $content;
				}else{
				
					$content2='<div class="foxpay" id="foxpay"><div class="down-detail">';
					$content2.='<h5 style="color:#fff;"><i class="fa fa fa-eye fa-fw"></i> 内容查看</h5>';
					if($price){
						$content2.='<p class="down-price"><i class="fa fa-jpy fa-fw"></i> 价格 <span>'.$price.'</span>&nbsp;'.get_option('ice_name_alipay').'</p>';
					}
					$content2.='<p class="down-ordinary">';
					
					
					if($memberDown > 1 && $userType==false)
					{
						if($memberDown==3)
						{
							$content2.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 会员免费查看</strong> <a href="'.$erphp_url_front_vip.'" class="vip" target="_blank">升级会员</a>&nbsp;&nbsp;';
						}
						elseif ($memberDown==2)
						{
							$content2.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 会员5折查看</strong> <a href="'.$erphp_url_front_vip.'" class="vip" target="_blank">升级会员</a>&nbsp;&nbsp;';
						}
						elseif ($memberDown==5)
						{
							$content2.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 会员8折查看</strong> <a href="'.$erphp_url_front_vip.'" class="vip" target="_blank">升级会员</a>&nbsp;&nbsp;';
						}
						elseif ($memberDown==6)
						{
							$content2.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 包年会员免费查看</strong> <a href="'.$erphp_url_front_vip.'" class="vip" target="_blank">升级会员</a>&nbsp;&nbsp;';
						}
						elseif ($memberDown==7)
						{
							$content2.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 终身会员免费查看</strong> <a href="'.$erphp_url_front_vip.'" class="vip" target="_blank">升级会员</a>&nbsp;&nbsp;';
						}
					}
					
					if($memberDown==4 && $userType==FALSE)
					{
						$content2.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 仅对会员开放查看</strong> <a href="'.$erphp_url_front_vip.'" class="vip" target="_blank">升级会员</a>&nbsp;&nbsp;';
					}
					else 
					{
						if($userType && $memberDown > 1)
						{
							if ($memberDown==2 && $down_info==null)
							{
								$msg.='<i class="fa fa-jpy fa-fw"></i> 您是会员，可以5折（价格为：'.($price*0.5).get_option('ice_name_alipay').'）购买查看此内容！';
								$content2.=$msg.'<a class=\'iframe\' href='.constant("erphpdown").
							'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
							}
							elseif ($memberDown==5 && $down_info==null)
							{
								$msg.='您是会员，可以8折（价格为：'.($price*0.8).get_option('ice_name_alipay').'）购买查看此内容！';
								$content2.=$msg.'<a class=\'iframe\'  href='.constant("erphpdown").
							'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
							}
							elseif ($memberDown==6 && $down_info==null)
							{
								if($userType < 9){
										$msg.='您是会员，原价购买查看此内容！（包年会员免费查看）';
										$content2.=$msg.'<a class=\'iframe\'  href='.constant("erphpdown").
									'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
								}
							}
							elseif ($memberDown==7 && $down_info==null)
							{
								if($userType < 10){
										$msg.='您是会员，原价购买查看此内容！（终身会员免费查看）';
										$content2.=$msg.'<a class=\'iframe\'  href='.constant("erphpdown").
									'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
								}
							}
						}
						else 
						{
							if($down_info  && $down_info->ice_price > 0){
								
							}else {
								$content2.='<i class="fa fa-address-card-o fa-fw"></i> <strong>普通用户</strong> <a class=iframe href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().' target="_blank">直接购买</a>';
							}
						}
					}
				}	
			}else{
				$content2='<div class="foxpay" id="foxpay"><div class="down-detail">';
				$content2.='<h5 style="color:#fff;"><i class="fa fa fa-eye fa-fw"></i> 内容查看</h5>';
				$content2.='<p class="down-price"><i class="fa fa-jpy fa-fw"></i> 价格 <span>'.$price.'</span>&nbsp;'.get_option('ice_name_alipay').'</p>';
				$content2.='<p class="down-ordinary">';
				$content2.='<i class="fa fa-user-circle fa-fw"></i> 您需要先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must"><i class="fa fa-wordpress"></i> 注册/登陆</a>后，才能购买查看内容</p>';
			}
			$content2.='</div></div>';
			return $content2;
			
		}elseif($start_see2){

			if(is_user_logged_in())
			{
				global $wpdb;
				$user_info=wp_get_current_user();
				$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and ice_user_id=".$user_info->ID);
				if( (($memberDown==3 || $memberDown==4) && $userType) || ($down_info && $down_info->ice_price > 0) || ($memberDown==6 && $userType >= 9) || ($memberDown==7 && $userType == 10)){
					//
				}else{
					$content.='<div class="foxpay" id="foxpay"><div class="down-detail">';
					$content.='<h5 style="color:#fff;"><i class="fa fa fa-eye fa-fw"></i> 内容查看</h5>';
					if($price){
						$content.='<p class="down-price">价格 <span>'.$price.'</span>&nbsp;'.get_option('ice_name_alipay').'</p>';
					}
					$content.='<p class="down-ordinary">';
					
					if($memberDown > 1 && $userType==false)
					{
						if($memberDown==3 && $down_info==null)
						{
							$content.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 会员免费查看隐藏内容</strong> <a href="'.$erphp_url_front_vip.'" target="_blank" class="vip">升级会员</a>&nbsp;&nbsp;';
						}
						elseif ($memberDown==2 && $down_info==null)
						{
							$content.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 会员5折查看隐藏内容</strong> <a href="'.$erphp_url_front_vip.'" target="_blank" class="vip">升级会员</a>&nbsp;&nbsp;';
						}
						elseif ($memberDown==5 && $down_info==null)
						{
							$content.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 会员8折查看隐藏内容</strong> <a href="'.$erphp_url_front_vip.'" target="_blank" class="vip">升级会员</a>&nbsp;&nbsp;';
						}
						elseif ($memberDown==6 && $down_info==null)
						{
							$content.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 包年会员免费查看</strong> <a href="'.$erphp_url_front_vip.'" target="_blank" class="vip">升级会员</a>&nbsp;&nbsp;';
						}
						elseif ($memberDown==7 && $down_info==null)
						{
							$content.='<strong><i class="fa fa-address-card-o fa-fw card"></i> 终身会员免费查看</strong> <a href="'.$erphp_url_front_vip.'" target="_blank" class="vip">升级会员</a>&nbsp;&nbsp;';
						}
					}
					if($memberDown==4 && $userType==FALSE){
						$content.='<i class="fa fa-address-card-o fa-fw card"></i> 仅对会员开放查看隐藏内容 <a href="'.$erphp_url_front_vip.'" target="_blank" class="vip">升级会员</a>&nbsp;&nbsp;';
					}
					else 
					{
						
						if($userType && $memberDown > 1)
						{
							if ($memberDown==2 && $down_info==null)
							{
								$msg.='<i class="fa fa-jpy fa-fw"></i> 您是会员，可以5折（价格为：'.($price*0.5).get_option('ice_name_alipay').'）购买查看此隐藏内容！';
								$content.=$msg.'<a class=iframe href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
							}
							elseif ($memberDown==5 && $down_info==null)
							{
								$msg.='<i class="fa fa-jpy fa-fw"></i> 您是会员，可以8折（价格为：'.($price*0.8).get_option('ice_name_alipay').'）购买查看此隐藏内容！';
								$content.=$msg.'<a class=iframe href='.constant("erphpdown").
							'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
							}
							elseif ($memberDown==6 && $down_info==null)
							{
								if($userType < 9){
										$msg.='您是会员，原价购买查看此隐藏内容！（包年会员免费查看）';
										$content.=$msg.'<a class=iframe href='.constant("erphpdown").
									'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
								}
							}
							elseif ($memberDown==7 && $down_info==null)
							{
								if($userType < 10){
										$msg.='您是会员，原价购买查看此隐藏内容！（终身会员免费查看）';
										$content.=$msg.'<a class=iframe href='.constant("erphpdown").
									'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
								}
							}
							
						}
						else 
						{
							$content.='<i class="fa fa-address-card-o fa-fw"></i> <strong>普通用户</strong> <a class=iframe href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().' target="_blank">直接购买</a>';
						}
					}
					$content.='</p>';
					if(get_option('ice_tips')) $content.='<p class="down-tip"><i class="fa fa-lightbulb-o fa-fw"></i> '.get_option('ice_tips').'</p>';
					$content.='</div></div>';
				
				}
				
			}
			else {
				$content.='<div class="foxpay" id="foxpay"><div class="down-detail">';
				$content.='<h5 style="color:#fff;"><i class="fa fa fa-eye fa-fw"></i> 内容查看</h5>';
				$content.='<p class="down-price"><i class="fa fa-jpy fa-fw"></i> 价格 <span>'.$price.'</span>&nbsp;'.get_option('ice_name_alipay').'</p>';
				$content.='<p class="down-ordinary">';
				$content.='<i class="fa fa-user-circle fa-fw"></i> 您需要先<i class="fa fa-user-circle fa-fw"></i> <a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must"><i class="fa fa-wordpress"></i> 注册/登陆</a>后，才能购买查看隐藏内容！</p>';
				if(get_option('ice_tips')) $content.='<p class="down-tip"><i class="fa fa-lightbulb-o fa-fw"></i> '.get_option('ice_tips').'</p>';
				$content.='</div></div>';
				
			}

		}
		
	}
	
	return $content;
}
add_action('the_content','erphpdown_content_show');
