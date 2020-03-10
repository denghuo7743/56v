<?php
header("Content-type:text/html;character=utf-8");
require_once('../../../wp-config.php');
function assignPageTitle(){
	return "文件下载";
}
add_filter('wp_title', 'assignPageTitle');
if(!is_user_logged_in())
{
	showMsg('请先登录系统');
}

$postid=isset($_GET['postid']) && is_numeric($_GET['postid']) ?intval($_GET['postid']) :false;
$url=isset($_GET['url']) ? $_GET['url'] :FALSE;
$key=isset($_GET['key']) ? $_GET['key'] :FALSE;


$postid = esc_sql($postid);
$url = esc_sql($url);
$key = esc_sql($key);
if($postid==false && $url==false )
{
	showMsg("来路错误");
}
if($key)
{
	if(is_numeric($key))
	{
		$key=intval($key);
	}
	else 
	{
		showMsg('下载的文件地址错误');
	}
}
if ($postid)
{
	$ypost = get_post($postid);
	if(!$ypost){
		showMsg("来路错误");
	}
	$isDown=FALSE;
	$data=get_post_meta($postid, 'down_url', true);
	$price=get_post_meta($postid, 'down_price', true);
	$memberDown=get_post_meta($postid, 'member_down',TRUE);
	$userType=getUsreMemberType();
	$user_info=wp_get_current_user();

	if(!$price && $memberDown != 4){
		$erphp_reg_times  = get_option('erphp_reg_times');
		if(!$userType && $erphp_reg_times > 0){
			if( checkDownLog($user_info->ID,$postid,$erphp_reg_times,erphpGetIP()) ){

			}else{
				wp_die("普通用户每天只能下载".$erphp_reg_times."个免费资源！");
			}
		}

	}else{
		if($memberDown == 3 || $memberDown == 4 || $memberDown == 6 || $memberDown == 7){
			
			if($userType){
				
				$erphp_life_times    = get_option('erphp_life_times');
				$erphp_year_times    = get_option('erphp_year_times');
				$erphp_quarter_times = get_option('erphp_quarter_times');
				$erphp_month_times  = get_option('erphp_month_times');

				if($userType == 7 && $erphp_month_times > 0){
					if( checkDownLog($user_info->ID,$postid,$erphp_month_times,erphpGetIP()) ){

					}else{
						wp_die("包月VIP用户每天只能免费下载".$erphp_month_times."个VIP资源！");
					}
				}elseif($userType == 8 && $erphp_quarter_times > 0){
					if( checkDownLog($user_info->ID,$postid,$erphp_quarter_times,erphpGetIP()) ){

					}else{
						wp_die("包季VIP用户每天只能免费下载".$erphp_quarter_times."个VIP资源！");
					}
				}elseif($userType == 9 && $erphp_year_times > 0){
					if( checkDownLog($user_info->ID,$postid,$erphp_year_times,erphpGetIP()) ){

					}else{
						wp_die("包年VIP用户每天只能免费下载".$erphp_year_times."个VIP资源！");
					}
				}elseif($userType == 10 && $erphp_life_times > 0){
					if( checkDownLog($user_info->ID,$postid,$erphp_life_times,erphpGetIP()) ){

					}else{
						wp_die("终身VIP用户每天只能免费下载".$erphp_life_times."个VIP资源！");
					}
				}
				
			}
		}
	}


	if(strlen($data) > 2)
	{
		$memberDown=get_post_meta($postid,'member_down',TRUE);
		$user_info=wp_get_current_user();
		$userType=getUsreMemberType();
		if($user_info && $userType && ($memberDown ==3 || $memberDown ==4))
		{
			$isDown=true;
			$pp = $postid;
		}
		elseif($user_info && ($userType == 9 || $userType == 10) && $memberDown ==6 )
		{
			$isDown=true;
			$pp = $postid;
		}
		elseif($user_info && $userType == 10 && $memberDown ==7 )
		{
			$isDown=true;
			$pp = $postid;
		}
		else 
		{
			if( empty($price) || $price==0 )
			{
				if($memberDown ==4 && !$userType){
					
				}else{
					$isDown=true;
					$pp = $postid;
				}
			}
		}
	}
	if(!$isDown)
	{
		showMsg('下载地址不存在!');
	}
}
if($url)
{
	$user_info=wp_get_current_user();
	if($user_info->ID)
	{
		$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_url='".$wpdb->escape($url)."' and ice_user_id=".$user_info->ID." order by ice_time desc");
		if($down_info->ice_price > 0){
			$downPostId=$down_info->ice_post;
			$days=get_post_meta($downPostId, 'down_days', true);
			if($days > 0){
				$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
				$nowDate = date('Y-m-d H:i:s');
				if(strtotime($nowDate) > strtotime($lastDownDate)){
					showMsg('下载权限已过期，请重新购买');
				}
			}
			$pp = $downPostId;
			$data=get_post_meta($downPostId, 'down_url', true);
		}
	}
	if(!$down_info || !$data)
	{
		showMsg('下载信息错误');
	}
}
?>
<?php 
$data=$data ?$data :$down_info->ice_data;
$downList=explode("\r\n",$data);
$downMsg='<div class="title"><span>文件下载地址</span></div>';
if($key)
{
	$user_info=wp_get_current_user();
	$file=$downList[$key-1];
	$file = iconv('UTF-8', 'GBK//TRANSLIT', $file);
	$times=time();
	$md5key=md5($user_info->ID.'erphpdown'.$key.$times.get_option('erphpdown_downkey'));
	$entemp = erphpdown_lock_url($file,get_option('erphpdown_downkey'));

	$file = trim($file);

	header("Location:downloadfile.php?id=".$pp."&filename=".$key."&md5key=".$md5key."&times=".$times."&session_name=".$entemp);
	exit;
	
}
foreach ($downList as $k=>$v)
{
	$filepath = $downList[$k];
	if($filepath){
		$filearr = explode(',',$filepath);
		$arrlength = count($filearr);
		if($arrlength == 1){
			$downMsg.="<a href='download.php?postid=".$postid."&url=".$down_info->ice_url."&key=".($k+1)."' target='_blank' class='erphpdown-download-button'>网盘下载 ".($k+1)."</a>";
		}elseif($arrlength == 2){
			$downMsg.="<a href='download.php?postid=".$postid."&url=".$down_info->ice_url."&key=".($k+1)."' target='_blank' class='erphpdown-download-button'>".$filearr[0]."</a>";
		}elseif($arrlength == 3){
			$downMsg.="<a href='download.php?postid=".$postid."&url=".$down_info->ice_url."&key=".($k+1)."' target='_blank' class='erphpdown-download-button'>".$filearr[0]." <i style='font-size: 8px; font-weight:100;'> ( ".$filearr[2]." ) </i></a>";
		}
	}
}
$hiddens = get_post_meta($pp,'hidden_content',true);
if($hiddens){
	$downMsg .='<div class="title"><span>隐藏信息</span></div><p>'.$hiddens.'</p>';
}
if(erphp_check_mobantu_theme() && function_exists('MBThemes_erphpdown_download')){
	MBThemes_erphpdown_download($downMsg,$pp);
}else{
	showMsg($downMsg,$pp);
}
?>