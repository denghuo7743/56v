<?php
require( dirname(__FILE__) . '/../../../../../wp-load.php' );
if(is_user_logged_in()){
	global $wpdb;
	if($_POST['do']=='checkOrder'){
		global $current_user;
		$id = $wpdb->escape($_POST['order']);
		$result = $wpdb->get_var("select ice_success from $wpdb->icemoney where ice_user_id = '".$current_user->ID."' and ice_id='".$id."'");
		if($result){
			echo '1';
		}else{
			echo '0';
		}
	}elseif($_POST['do'] == 'delorder'){
		if(current_user_can('administrator')){
			$result = $wpdb->query("delete from $wpdb->icealipay where ice_id=".$wpdb->escape($_POST['id']));
			if($result){
				echo '1';
			}else{
				echo '0';
			}
		}
	}
}