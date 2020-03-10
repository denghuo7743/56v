<?php
/*
mobantu.com
erphpdown.com
erphp.com
qq 82708210
*/
if ( !defined('ABSPATH') ) {exit;}
function erphp_check_mobantu_theme(){
	$current_theme = wp_get_theme();
	if($current_theme->get( 'Name' ) == 'Mobantu-Modown')
		return true;
	return false;
}
if(isset($_REQUEST['aff']) && !isset($_COOKIE["erphprefid"])){setcookie("erphprefid",$_REQUEST['aff'],time()+2592000,'/');}
function erphpdown_style() {
	global $erphpdown_version;
	if(is_singular()){
		wp_enqueue_style( 'erphpdown', constant("erphpdown")."static/erphpdown.css", array(), $erphpdown_version,'screen' );
		wp_enqueue_script( 'erphpdown', constant("erphpdown")."static/erphpdown.js", false, $erphpdown_version, true);
		wp_localize_script( 'erphpdown', 'erphpdown_ajax_url', admin_url("admin-ajax.php"));
	}
}

if(!erphp_check_mobantu_theme()){
	add_action('wp_enqueue_scripts', 'erphpdown_style',20,1);
}

function erphpdown_head_style(){?>
	<script>window._ERPHPDOWN = {"uri":"<?php echo ERPHPDOWN_URL;?>", "payment": "<?php if(get_option('erphp_wppay_payment') == 'f2fpay') echo "1"; else echo "2";?>", "author": "mobantu"}</script>
<?php }
add_action( 'wp_head', 'erphpdown_head_style' );


function erphp_register_extra_fields($user_id, $password="", $meta=array()) {
	global $wpdb;
	if(isset($_COOKIE["erphprefid"]) && is_numeric($_COOKIE["erphprefid"])){
		$hasRe = $wpdb->get_var("select ID from $wpdb->users where reg_ip = '".$_SERVER['REMOTE_ADDR']."'");
		if($hasRe){
			//已被推荐注册过用户，防作弊
		}else{
			$sql = "update $wpdb->users set father_id='".esc_sql($_COOKIE["erphprefid"])."',reg_ip = '".$_SERVER['REMOTE_ADDR']."' where ID=".$user_id;
			$wpdb->query($sql);
			addUserMoney($_COOKIE["erphprefid"],get_option('ice_ali_money_reg'));
		}
	}

}
add_action('user_register', 'erphp_register_extra_fields');

function showMsgNotice($msg,$color=FALSE){
	echo '<div class="updated settings-error"><p>'.$msg.'</p></div>';
}

function erphp_noadmin_redirect(){
	global $wpdb;
	if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) && get_option('erphp_url_front_noadmin')=='yes') {
	  $current_user = wp_get_current_user();
	  if($current_user->roles[0] == get_option('default_role')) {
		$userpage = get_bloginfo('url');
		if(get_option('erphp_url_front_userpage')){
			$userpage = get_option('erphp_url_front_userpage');
		}
		wp_safe_redirect( $userpage );
		exit();
	  }
	}
}
add_action("init","erphp_noadmin_redirect");

function addDownLog($uid,$pid,$ip){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$sql="insert into $wpdb->down(ice_user_id,ice_post_id,ice_ip,ice_time)values('".$uid."','".$pid."','".$ip."','".date("Y-m-d H:i:s")."')";
	$wpdb->query($sql);
}

function checkDownLog($uid,$pid,$times,$ip){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$result = $wpdb->get_var("select count(distinct ice_post_id) from $wpdb->down where ice_user_id=".$uid." and DATEDIFF(ice_time,NOW())=0");
	if($result > $times) 
		return false;
	elseif($result == $times){
		$exist = $wpdb->get_var("select ice_id from $wpdb->down where ice_user_id=".$uid." and DATEDIFF(ice_time,NOW())=0 and ice_post_id = $pid");
		if($exist) 
			return true;
		else 
			return false;
	}
	else 
		return true;
	
}

function addVipLog($price,$userType){
	global $wpdb;
	$user_info = wp_get_current_user();
	$sql="insert into $wpdb->vip(ice_price,ice_user_id,ice_user_type,ice_time)values('".$price."','".$user_info->ID."','".$userType."','".date("Y-m-d H:i:s")."')";
	$wpdb->query($sql);
}

function addVipLogByAdmin($price,$userType,$uid){
	global $wpdb;
	$sql="insert into $wpdb->vip(ice_price,ice_user_id,ice_user_type,ice_time)values('".$price."','".$uid."','".$userType."','".date("Y-m-d H:i:s")."')";
	$wpdb->query($sql);
}

function addAffLog($price,$uid,$ip){
	global $wpdb;
	$sql="insert into $wpdb->aff(ice_price,ice_user_id,ice_ip,ice_time)values('".$price."','".$uid."','".$ip."','".date("Y-m-d H:i:s")."')";
	$wpdb->query($sql);
	addUserMoney($uid,$price);
}

function checkAffLog($uid,$ip){
	global $wpdb;
	$result = $wpdb->get_var("select ice_id from $wpdb->aff where ice_user_id=".$uid." and ice_ip='".$ip."'");
	if($result) return false;
	else return true;
}


function getUsreMemberType(){
	global $wpdb;
	$user_info = wp_get_current_user();
	$userTypeInfo=$wpdb->get_row("select * from  ".$wpdb->iceinfo." where ice_user_id=".$user_info->ID);
	if($userTypeInfo)
	{
		if(time() > strtotime($userTypeInfo->endTime) +24*3600)
		{
			$wpdb->query("update $wpdb->iceinfo set userType=0,endTime='1000-01-01' where ice_user_id=".$user_info->ID);
			return 0;
		}
		return $userTypeInfo->userType;
	}
	return FALSE;
}

function getUsreMemberTypeById($uid){
	global $wpdb;
	$userTypeInfo=$wpdb->get_row("select * from  ".$wpdb->iceinfo." where ice_user_id=".$uid);
	if($userTypeInfo)
	{
		if(time() > strtotime($userTypeInfo->endTime) +24*3600)
		{
			$wpdb->query("update $wpdb->iceinfo set userType=0,endTime='1000-01-01' where ice_user_id=".$uid);
			return 0;
		}
		return $userTypeInfo->userType;
	}
	return FALSE;
}

function getUsreMemberTypeEndTime(){
	global $wpdb;
	$user_info = wp_get_current_user();
	$userTypeInfo=$wpdb->get_row("select * from  ".$wpdb->iceinfo." where ice_user_id=".$user_info->ID);
	if($userTypeInfo)
	{
		return $userTypeInfo->endTime;
	}
	return FALSE;
}
function getUsreMemberTypeEndTimeById($uid){
	global $wpdb;
	$userTypeInfo=$wpdb->get_row("select * from  ".$wpdb->iceinfo." where ice_user_id=".$uid);
	if($userTypeInfo)
	{
		return $userTypeInfo->endTime;
	}
	return FALSE;
}
function versioncheck(){
	$url='http://api.mobantu.com/erphpdown/update.php';  
	$result=file_get_contents($url);  
	return $result;
}
function plugin_check_card(){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if(!is_plugin_active( 'erphpdown-add-on-card/erphpdown-add-on-card.php' )){
		return 0;
	}
	else{
		return 1;
	}
}
function plugin_check_cred(){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if(!is_plugin_active( 'erphpdown-add-on-mycred/erphpdown-add-on-mycred.php' )){
		return 0;
	}
	else{
		return 1;
	}
}
function checkUsreMemberType(){
	global $wpdb;
	$user_info = wp_get_current_user();
	$sql="select * from  ".$wpdb->iceinfo." where ice_user_id=".$user_info->ID;
	$info=$wpdb->get_row($sql);
	if(!$info)
	{
		showMsgNotice("您的账户余额不足，请先充值!");
		return FALSE;
	}
	return true;
}

function userPayMemberSetData($userType){
	global $wpdb;
	$user_info = wp_get_current_user();
	$oldUserType = getUsreMemberType();
	if($oldUserType){
		$oldEndTime = getUsreMemberTypeEndTime();
		if($userType==7)
		{
			$endTime=date("Y-m-d",strtotime("+1 month",strtotime($oldEndTime)));
		}
		elseif ($userType==8)
		{
			$endTime=date("Y-m-d",strtotime("+3 month",strtotime($oldEndTime)));
		}
		elseif ($userType==9)
		{
			$endTime=date("Y-m-d",strtotime("+1 year",strtotime($oldEndTime)));
		}
		elseif ($userType==10)
		{
			$endTime=date("Y-m-d",strtotime("2038-01-01"));
		}
	}else{
		$endTime=date("Y-m-d");
		if($userType==7)
		{
			$endTime=date("Y-m-d",strtotime("+1 month"));
		}
		elseif ($userType==8)
		{
			$endTime=date("Y-m-d",strtotime("+3 month"));
		}
		elseif ($userType==9)
		{
			$endTime=date("Y-m-d",strtotime("+1 year"));
		}
		elseif ($userType==10)
		{
			$endTime=date("Y-m-d",strtotime("2038-01-01"));
		}
	}

	if($oldUserType){
		if($oldUserType > $userType){
			$userType = $oldUserType;
		}
	}

	$sql="update ".$wpdb->iceinfo." set userType=".$userType.", endTime='".$endTime."' where ice_user_id=".$user_info->ID;

	$wpdb->query($sql);
	return true;

}

function userSetMemberSetData($userType,$uid)
{
	global $wpdb;
	$oldUserType = getUsreMemberTypeById($uid);
	if($oldUserType){
		$oldEndTime = getUsreMemberTypeEndTimeById($uid);
		if($userType==7)
		{
			$endTime=date("Y-m-d",strtotime("+1 month",strtotime($oldEndTime)));
		}
		elseif ($userType==8)
		{
			$endTime=date("Y-m-d",strtotime("+3 month",strtotime($oldEndTime)));
		}
		elseif ($userType==9)
		{
			$endTime=date("Y-m-d",strtotime("+1 year",strtotime($oldEndTime)));
		}
		elseif ($userType==10)
		{
			$endTime=date("Y-m-d",strtotime("2038-01-01"));
		}
	}else{
		$endTime=date("Y-m-d");
		if($userType==7)
		{
			$endTime=date("Y-m-d",strtotime("+1 month"));
		}
		elseif ($userType==8)
		{
			$endTime=date("Y-m-d",strtotime("+3 month"));
		}
		elseif ($userType==9)
		{
			$endTime=date("Y-m-d",strtotime("+1 year"));
		}
		elseif ($userType==10)
		{
			$endTime=date("Y-m-d",strtotime("2038-01-01"));
		}
	}

	if($oldUserType){
		if($oldUserType > $userType){
			$userType = $oldUserType;
		}
	}

	$sql="update ".$wpdb->iceinfo." set userType=".$userType.",endTime='".$endTime."' where ice_user_id=".$uid;
	$wpdb->query($sql);
	return true;
}

function erphp_admin_pagenavi($total_count, $number_per_page=15){

	$current_page = isset($_GET['paged'])?$_GET['paged']:1;

	if(isset($_GET['paged'])){
		unset($_GET['paged']);
	}

	$base_url = add_query_arg($_GET,admin_url('admin.php'));

	$total_pages	= ceil($total_count/$number_per_page);

	$first_page_url	= $base_url.'&amp;paged=1';
	$last_page_url	= $base_url.'&amp;paged='.$total_pages;
	
	if($current_page > 1 && $current_page < $total_pages){
		$prev_page		= $current_page-1;
		$prev_page_url	= $base_url.'&amp;paged='.$prev_page;

		$next_page		= $current_page+1;
		$next_page_url	= $base_url.'&amp;paged='.$next_page;
	}elseif($current_page == 1){
		$prev_page_url	= '#';
		$first_page_url	= '#';
		if($total_pages > 1){
			$next_page		= $current_page+1;
			$next_page_url	= $base_url.'&amp;paged='.$next_page;
		}else{
			$next_page_url	= '#';
		}
	}elseif($current_page == $total_pages){
		$prev_page		= $current_page-1;
		$prev_page_url	= $base_url.'&amp;paged='.$prev_page;
		$next_page_url	= '#';
		$last_page_url	= '#';
	}
	?>
	<div class="tablenav bottom">
		<div class="tablenav-pages">
			<span class="displaying-num">每页 <?php echo $number_per_page;?> 共 <?php echo $total_count;?></span>
			<span class="pagination-links">
				<a class="first-page <?php if($current_page==1) echo 'disabled'; ?>" title="前往第一页" href="<?php echo $first_page_url;?>">«</a>
				<a class="prev-page <?php if($current_page==1) echo 'disabled'; ?>" title="前往上一页" href="<?php echo $prev_page_url;?>">‹</a>
				<span class="paging-input">第 <?php echo $current_page;?> 页，共 <span class="total-pages"><?php echo $total_pages; ?></span> 页</span>
				<a class="next-page <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往下一页" href="<?php echo $next_page_url;?>">›</a>
				<a class="last-page <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往最后一页" href="<?php echo $last_page_url;?>">»</a>
			</span>
		</div>
		<br class="clear">
	</div>
	<?php
}


add_filter('admin_footer_text', 'erphp_left_admin_footer_text'); 
function erphp_left_admin_footer_text($text) {
	$text = '<span id="footer-thankyou">感谢使用<a href=http://cn.wordpress.org/ >WordPress</a>进行创作，使用<a href="http://www.erphpdown.com">Erphpdown</a>进行网站VIP支付下载功能。</span>'; 
	return $text;
}

function erphpSetUserOrderIsSuccess($orderNum,$money)
{
	global $wpdb;
	$row=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num='".$orderNum."'");
	{
		if($row->ice_success)
		{
			return true;
		}
		else
		{
			$updatOrder=$wpdb->query("update $wpdb->icemoney set ice_success=1,ice_money = '".$money*get_option('ice_proportion_alipay')."'  where ice_num=".$orderNum);
			if($updatOrder)
			{
				addUserMoney($row->ice_user_id,$money*get_option('ice_proportion_alipay'));
			}
			return false;
		}
	}
}
function erphpCheckAlipayReturnNum($orderNum,$money)
{
	global $wpdb;
	$row=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num='".$orderNum."'");
	if($row)
	{
		if($row->ice_money == $money)
		{
			return true;
		}
	}
	return false;
}
function erphpAddDownloadByUid($subject,$postid,$userid,$price,$success,$data,$postUserId)
{
	date_default_timezone_set('Asia/Shanghai');
	if($price > 0){
		global $wpdb;
		$subject = str_replace("'","",$subject);
		$subject = str_replace("‘","",$subject);
		$url       = md5(date("YmdHis").$postid.mt_rand(1000000, 9999999));
		$orderNum  = date("d").mt_rand(10000, 99999).mt_rand(10,99);
		$sql       = "INSERT INTO $wpdb->icealipay (ice_num,ice_title,ice_post,ice_price,ice_success,ice_url,ice_user_id,ice_time,ice_data,
		ice_author)VALUES ('$orderNum','$subject','$postid','$price','$success','$url','".$userid."','".date("Y-m-d H:i:s")."','".$data."','$postUserId')";
		if($wpdb->query($sql) && erphpdod() > 0)
		{
			return $url;
		}
	}
	return false;
}
function erphpAddDownload($subject,$postid,$price,$success,$data,$postUserId)
{
	date_default_timezone_set('Asia/Shanghai');
	if($price > 0){
		global $wpdb;
		$subject = str_replace("'","",$subject);
		$subject = str_replace("‘","",$subject);
		$user_info = wp_get_current_user();
		$url       = md5(date("YmdHis").$postid.mt_rand(1000000, 9999999));
		$orderNum  = date("d").mt_rand(10000, 99999).mt_rand(10,99);
		$sql       = "INSERT INTO $wpdb->icealipay (ice_num,ice_title,ice_post,ice_price,ice_success,ice_url,ice_user_id,ice_time,ice_data,
		ice_author)VALUES ('$orderNum','$subject','$postid','$price','$success','$url','".$user_info->ID."','".date("Y-m-d H:i:s")."','".$data."','$postUserId')";
		if($wpdb->query($sql) && erphpdod() > 0)
		{
			return $url;
		}
	}
	return false;
}
function erphpSetUserMoneyXiaoFei($num)
{
	if($num > 0){
		global $wpdb;
		$user_info=wp_get_current_user();
		return $wpdb->query("update $wpdb->iceinfo set ice_get_money=ice_get_money+".$num." where ice_user_id=".$user_info->ID);
	}else{
		return false;
	}
}
function erphpGetUserAllXiaofei($uid){
	global $wpdb;
	$money = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_success>0 and ice_user_id=".$uid);
	$money2 = $wpdb->get_var("SELECT sum(ice_price) FROM $wpdb->vip where ice_user_id=".$uid);
	$money += $money2;
	return $money ? $money :'0';
}
function erphpGetUserOkMoney()
{
	global $wpdb;
	$user_info=wp_get_current_user();
	if($user_info)
	{
		$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$user_info->ID);
		return $userMoney==false ?0:($userMoney->ice_have_money - $userMoney->ice_get_money);
	}
	return 0;
}
function erphpGetUserOkMoneyById($uid)
{
	global $wpdb;
	if($uid){
		$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$uid);
		return $userMoney==false ?0:($userMoney->ice_have_money - $userMoney->ice_get_money);
	}
	return 0;
}
function getProductSales($pid){
	global $wpdb;
	$total_trade  = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_success>0 and ice_post=".$pid);
	return $total_trade;
}

function getProductMember($pid){
	$type = get_post_meta($pid,"member_down",true);
	if($type == "1"){
		return "无";
	}elseif($type == "2"){
		return "VIP5折";
	}elseif($type == "3"){
		return "VIP免费";
	}elseif($type == "4"){
		return "VIP专享";
	}elseif($type == "5"){
		return "VIP八折";
	}elseif($type == "6"){
		return "年费VIP免费";
	}elseif($type == "7"){
		return "终身VIP免费";
	}elseif($type == "8"){
		return "年费VIP专享";
	}elseif($type == "9"){
		return "终身VIP专享";
	}else{
		return "未知";
	}
}

add_action('wp_dashboard_setup', 'erphp_modify_dashboard_widgets' );
function erphp_modify_dashboard_widgets() {
	global $wp_meta_boxes;
	add_meta_box( 'erphpdown_dashboard_widget', 'Erphpdown', 'erphpdown_dashboard_widget_function','dashboard', 'normal', 'core' );
}
function erphpdown_dashboard_widget_function() {
	global $wpdb;
	$user_info=wp_get_current_user();
	$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$user_info->ID);
	if(!$userMoney)
	{
		$okMoney=0;
	}
	else 
	{
		$okMoney=$userMoney->ice_have_money - $userMoney->ice_get_money;
	}
	$total_trade   = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_success>0 and ice_user_id=".$user_info->ID);
	$total_money   = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_success>0 and ice_user_id=".$user_info->ID);
	$lists = $wpdb->get_results("SELECT * FROM $wpdb->icealipay where ice_success=1 and ice_user_id=$user_info->ID order by ice_time DESC limit 0,5");
	echo '下载/查看：'.$total_trade.'个&nbsp;&nbsp;&nbsp;&nbsp;消费：'.sprintf("%.2f",$userMoney->ice_get_money).get_option('ice_name_alipay').'&nbsp;&nbsp;&nbsp;&nbsp;剩余：'.sprintf("%.2f",$okMoney).get_option('ice_name_alipay').'<br />';
	echo '<ul>';
	foreach ($lists as $list){
		echo '<li><a target="_blank" href="'.get_permalink($list->ice_post).'">'.$list->ice_title.'</a></li>';
	}
	echo '</ul>';
}


function erphpdod(){
	return "1";
}
function addUserMoney($userId,$money)
{
	global $wpdb;
	$myinfo=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$userId);
	if(!$myinfo)
	{
		return $wpdb->query("insert into $wpdb->iceinfo(ice_have_money,ice_user_id,ice_get_money)values('$money','$userId',0)");
	}
	else
	{
		return $wpdb->query("update $wpdb->iceinfo set ice_have_money=ice_have_money+".$money." where ice_user_id=".$userId);
	}
}

/*  www.erphp.com  */
function erphpmeta(){
	return true;
}
function mbtcheck(){
	return "1";
}

function erphpdown_check_xiaofei($uid){
	global $wpdb;
	$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_success=1 and ice_user_id=".$uid);
	if($down_info)
		return true;
	return false;
}

function erphpdown_see($atts, $content=null){ 
	global $post,$wpdb;
	$userType=getUsreMemberType();
	$memberDown=get_post_meta($post->ID, 'member_down',TRUE);
	$user_info=wp_get_current_user();
	$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".$post->ID."' and ice_success=1 and ice_user_id=".$user_info->ID);
	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$wppay = new EPD($post->ID, $user_id);

	if(is_user_logged_in()){
		if( (($memberDown==3 || $memberDown==4) && $userType) || $wppay->isWppayPaid() || $down_info || (($memberDown==6 || $memberDown==8) && $userType >= 9) || (($memberDown==7 || $memberDown==9) && $userType == 10) ){
			return '<p class="erphpdown-content-vip" style="color:inherit;text-align:inherit">'.do_shortcode($content).'</p>';
		}else{
			return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容！</p>';
		}
	}else{
		return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容！</p>';
	}
}  
add_shortcode('erphpdown','erphpdown_see');

function erphpdown_admin_notice() {
	$token = get_option('MBT_ERPHPDOWN_token');
	if($token){
		$ll = admin_url().'admin.php?page=erphpdown/admin/erphp-settings.php';
	}else{
		$ll = admin_url().'admin.php?page=erphpdown/admin/erphp-active.php';
	}
    ?>
    <br>
    <div id="message" class="error updated notice is-dismissible">
        <p>Erphpdown插件需要先设置下哦！<a href="<?php echo $ll;?>">去设置</a></p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text">忽略此通知。</span></button>
    </div>
    <?php
}
$ice_proportion_alipay = get_option('ice_proportion_alipay');
if(!$ice_proportion_alipay){
	add_action( 'admin_notices', 'erphpdown_admin_notice' );
}


function erphpGetIP(){
	$IPaddress='';

    if (isset($_SERVER)){

        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){

            $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];

        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {

            $IPaddress = $_SERVER["HTTP_CLIENT_IP"];

        } else {

            $IPaddress = $_SERVER["REMOTE_ADDR"];

        }

    } else {

        if (getenv("HTTP_X_FORWARDED_FOR")){

            $IPaddress = getenv("HTTP_X_FORWARDED_FOR");

        } else if (getenv("HTTP_CLIENT_IP")) {

            $IPaddress = getenv("HTTP_CLIENT_IP");

        } else {

            $IPaddress = getenv("REMOTE_ADDR");

        }

    }

    return $IPaddress; 
}


function erphpdown_lock_url($txt,$key){
  return base64_encode(urlencode($txt).'---'.$key);
}

function erphpdown_unlock_url($txt,$key){
  $str = base64_decode($txt);
  return urldecode(str_replace('---'.$key, '', $str));
}

function erphpdown_file_post($url = '', $postData = ''){
	$data = http_build_query($postData);
	$opts = array(
	   'http'=>array(
	     'method'=>"POST",
	     'header'=>"Content-type: application/x-www-form-urlencoded\r\n".
	               "Content-length:".strlen($data)."\r\n" .
	               "Cookie: foo=bar\r\n" .
	               "\r\n",
	     'content' => $data,
	   )
	);
	$cxContext = stream_context_create($opts);
	$result = file_get_contents($url, false, $cxContext);
	return $result;
}


function erphpdown_curl_post($url = '', $postData = ''){
    /**
	* 模拟post进行url请求
	* @param string $url
	* @param string $postData
	*/
	if(function_exists('curl_init')){
		$ch = curl_init();								//初始化curl
		curl_setopt($ch, CURLOPT_URL, $url);			//设置抓取的url	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, true);			//设置post方式提交
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);//设置post数据
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 			//设置cURL允许执行的最长秒数
		
		//https请求 不验证证书和host
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}else{
		wp_die("网站未开启curl组件，正常情况下该组件必须开启，请开启curl组件解决该问题");
	}
}

/*
class enstr {
    function enstrhex($str,$key) {
    	if(version_compare(phpversion(), '7.1.0') >= 0){
			$data = openssl_encrypt($str, 'AES-256-ECB', $key);
	  		return $data;
	  	}else{
	  		$td = mcrypt_module_open('twofish', '', 'ecb', '');
	        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	        $ks = mcrypt_enc_get_key_size($td);
	        $keystr = substr(md5($key), 0, $ks);
	        mcrypt_generic_init($td, $keystr, $iv);
	        $encrypted = mcrypt_generic($td, $str);
	        mcrypt_module_close($td);
	        $hexdata = bin2hex($encrypted);
	        return $hexdata;
	  	}
	  	return $str;
    }
   
    function destrhex($str,$key) {
    	if(version_compare(phpversion(), '7.1.0') >= 0){
			$decrypted = openssl_decrypt($str, 'AES-256-ECB', $key);
  			return $decrypted;
  		}else{
  			$td = mcrypt_module_open('twofish', '', 'ecb', '');
	        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	        $ks = mcrypt_enc_get_key_size($td);
	        $keystr = substr(md5($key), 0, $ks);
	        mcrypt_generic_init($td, $keystr, $iv);
	        $encrypted = pack( "H*", $str);
	        $decrypted = mdecrypt_generic($td, $encrypted);
	        mcrypt_generic_deinit($td);
	        mcrypt_module_close($td);
	        return $decrypted;
  		}
  		return $str;
    }
}*/


function erphpdown_download_file($file_dir){
	if(substr($file_dir,0,7) == 'http://' || substr($file_dir,0,8) == 'https://' || substr($file_dir,0,10) == 'thunder://' || substr($file_dir,0,7) == 'magnet:' || substr($file_dir,0,5) == 'ed2k:' || substr($file_dir,0,4) == 'ftp:')
	{
		$file_path= chop($file_dir);
		echo "<script type='text/javascript'>window.location='$file_path';</script>";
		exit;
	}
	$file_dir=chop($file_dir);
	if(!file_exists($file_dir))
	{
		return false;
	}
	$temp=explode("/",$file_dir);


	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"".end($temp)."\"");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($file_dir));
	ob_end_flush();
	@readfile($file_dir);
}


function erphpdown_modify_user_table( $column ) {
    $column['vip'] = 'VIP';
    $column['money'] = '余额';
    $column['cart'] = '消费记录';
    $column['reg'] = '注册时间';
    return $column;
}
add_filter( 'manage_users_columns', 'erphpdown_modify_user_table' );

function erphpdown_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'vip' :
        	$userType = getUsreMemberTypeById($user_id);
            if($userType == 7){
            	return '包月';
            }elseif($userType == 8){
            	return '包季';
            }elseif($userType == 9){
            	return '包年';
            }elseif($userType == 10){
            	return '终身';
            }else{
            	return '—';
            }
            break;
        case 'money':
            return erphpGetUserOkMoneyById($user_id);
        	break;
        default:
        case 'cart':
            return erphpdown_check_xiaofei($user_id)?'有':'—';
        	break;
        case 'reg':
        	$user = get_user_by("ID",$user_id);
            return get_date_from_gmt($user->user_registered);
        	break;
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'erphpdown_modify_user_table_row', 10, 3 );

add_filter( 'manage_users_sortable_columns', 'erphpdown_modify_user_table_row_sortable' );
function erphpdown_modify_user_table_row_sortable( $columns ) {
	return wp_parse_args( array( 'reg' => 'registered' ), $columns );
}

function erphpdown_column_width() {
    echo '<style type="text/css">';
    echo '.column-vip , .column-money , .column-cart{ text-align: center !important; width:74px;}.column-reg{ text-align: center !important; width:90px;}';
    echo '</style>';
}
add_action('admin_head', 'erphpdown_column_width');