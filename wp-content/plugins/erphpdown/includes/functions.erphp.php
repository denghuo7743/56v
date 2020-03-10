<?php
	if ( !defined('ABSPATH') ) {exit;}
	
	add_action('admin_menu', 'mobantu_erphp_menu');
	function mobantu_erphp_menu() {

//		$token = get_option('MBT_ERPHPDOWN_token');
//		if($token){
			if (function_exists('add_menu_page')) {
				add_menu_page('erphpdown', 'ErphpDown', 'activate_plugins', 'erphpdown/admin/erphp-settings.php', '','dashicons-admin-network');
				add_menu_page('erphpdown2', '会员推广下载', 'read', 'erphpdown/admin/erphp-my-money.php', '','dashicons-shield');
			}
			if (function_exists('add_submenu_page')) {
				add_submenu_page('erphpdown/admin/erphp-settings.php', '基础设置','基础设置', 'activate_plugins', 'erphpdown/admin/erphp-settings.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '支付设置', '支付设置', 'activate_plugins', 'erphpdown/admin/erphp-payment.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '显示设置', '显示设置', 'activate_plugins', 'erphpdown/admin/erphp-front.php');
				if(plugin_check_card()){
					add_submenu_page('erphpdown/admin/erphp-settings.php', '所有充值卡','所有充值卡', 'activate_plugins', 'erphpdown-add-on-card/card-list.php');
					add_submenu_page('erphpdown/admin/erphp-settings.php', '添加充值卡','添加充值卡', 'activate_plugins', 'erphpdown-add-on-card/card-add.php');
				}
				add_submenu_page('erphpdown/admin/erphp-settings.php', 'VIP设置','VIP设置','activate_plugins', 'erphpdown/admin/erphp-vip-setting.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', 'VIP订单','VIP订单','activate_plugins', 'erphpdown/admin/erphp-vip-items.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', 'VIP用户','VIP用户','activate_plugins', 'erphpdown/admin/erphp-vip-users.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '后台充值/扣钱', '后台充值/扣钱', 'activate_plugins', 'erphpdown/admin/erphp-add-money.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '后台赠送VIP', '后台赠送VIP', 'activate_plugins', 'erphpdown/admin/erphp-add-vip.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '查询用户', '查询用户', 'activate_plugins', 'erphpdown/admin/erphp-check-users.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '所有资源统计', '所有资源统计', 'activate_plugins', 'erphpdown/admin/erphp-shop-list.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '所有销售排行', '所有销售排行', 'activate_plugins', 'erphpdown/admin/erphp-items-list.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '所有充值统计', '所有充值统计', 'activate_plugins', 'erphpdown/admin/erphp-chong-list.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '所有消费统计', '所有消费统计', 'activate_plugins', 'erphpdown/admin/erphp-orders-list.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '所有免登录消费统计', '所有免登录消费统计', 'activate_plugins', 'erphpdown/admin/erphp-wppays-list.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '所有提现统计', '所有提现统计', 'activate_plugins', 'erphpdown/admin/erphp-tixian-list.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '所有推广统计', '所有推广统计', 'activate_plugins', 'erphpdown/admin/erphp-reference-all.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', 'VIP免费下载统计', 'VIP免费下载统计', 'activate_plugins', 'erphpdown/admin/erphp-vipdown-list.php');
		        add_submenu_page('erphpdown/admin/erphp-settings.php', '清理数据表', '清理数据表', 'activate_plugins', 'erphpdown/admin/erphp-clear.php');
				add_submenu_page('erphpdown/admin/erphp-settings.php', '检查更新', '检查更新', 'activate_plugins', 'erphpdown/admin/update.php');
				
				add_submenu_page('erphpdown/admin/erphp-my-money.php', '我的资产', '我的资产', 'read', 'erphpdown/admin/erphp-my-money.php');
				add_submenu_page('erphpdown/admin/erphp-my-money.php', '在线充值', '在线充值', 'read', 'erphpdown/admin/erphp-add-money-online.php');
				if(plugin_check_cred() && get_option('erphp_mycred') == 'yes'){
					add_submenu_page('erphpdown/admin/erphp-my-money.php', '积分兑换','积分兑换', 'read', 'erphpdown-add-on-mycred/erphp-to-mycred.php');
				}
				add_submenu_page('erphpdown/admin/erphp-my-money.php', '充值记录', '充值记录', 'read', 'erphpdown/admin/erphp-add-money-list.php');
				add_submenu_page('erphpdown/admin/erphp-my-money.php', '升级VIP', '升级VIP', 'read', 'erphpdown/admin/erphp-update-vip.php');
				add_submenu_page('erphpdown/admin/erphp-my-money.php', 'VIP记录', 'VIP记录', 'read', 'erphpdown/admin/erphp-update-vip-list.php');
				add_submenu_page('erphpdown/admin/erphp-my-money.php', '消费清单', '消费清单', 'read', 'erphpdown/admin/erphp-get-items.php');
				add_submenu_page('erphpdown/admin/erphp-my-money.php', '销售订单', '销售订单', 'edit_posts', 'erphpdown/admin/erphp-items.php');
				add_submenu_page('erphpdown/admin/erphp-my-money.php', '提现列表', '提现列表', 'read', 'erphpdown/admin/erphp-money-list.php');
				add_submenu_page('erphpdown/admin/erphp-my-money.php', '申请提现', '申请提现', 'read', 'erphpdown/admin/erphp-money.php');
				add_submenu_page('erphpdown/admin/erphp-my-money.php', '推广注册', '推广注册', 'read', 'erphpdown/admin/erphp-reference.php');
				add_submenu_page('erphpdown/admin/erphp-my-money.php', '推广下载', '推广下载', 'read', 'erphpdown/admin/erphp-reference-list.php');
				add_submenu_page('erphpdown/admin/erphp-my-money.php', '推广VIP', '推广VIP', 'read', 'erphpdown/admin/erphp-reference-vip-list.php');
				add_submenu_page('erphpdown/admin/erphp-my-money.php', 'VIP免费下载记录', 'VIP免费下载记录', 'read', 'erphpdown/admin/erphp-vipdown-list-my.php');
		    }
//		}else{
//			if (function_exists('add_menu_page')) {
//				add_menu_page('erphpdown', 'ErphpDown', 'activate_plugins', 'erphpdown/admin/erphp-active.php', '','dashicons-admin-network');
//			}
//		}
	    
	}

	function epd_wppay_callback(){
		$post_id = $_POST['post_id'];
		$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
		$price = get_post_meta($post_id,'down_price',true);
		$code='';$link='';$msg='';$num='';$status=400;
		$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999).'wppay';

		if($price){
			$wppay = new EPD($post_id, $user_id);

			if(get_option('erphp_wppay_payment') == 'f2fpay'){
				//当面付
				$qrPayResult = $wppay->f2fpayWppayQr($out_trade_no, $price);
				if($qrPayResult->getTradeStatus() == 'SUCCESS'){
					if($wppay->addWppay($out_trade_no, $price)){
						$response = $qrPayResult->getResponse();
						$code = constant("erphpdown").'payment/f2fpay/qrcode.php?data='.urlencode($response->qr_code);
						$link = '';
						$num = $out_trade_no;
						$status=200;
					}
				}else{
					$status=201;
					$msg = '获取支付信息失败！';
				}
			}else{
				//有赞
				$token = $wppay->youzanWppayToken();
				if($token){
					$qr = $wppay->youzanWppayQr($out_trade_no, $price, $token);
					if($qr['response']['qr_code']){
						if($wppay->addWppay($out_trade_no, $price)){
							$code = $qr['response']['qr_code'];
							$link = $qr['response']['qr_url'];
							$num = $out_trade_no;
							$status=200;
						}
					}
				}else{
					$status=201;
					$msg = '获取支付信息失败！';
				}
			}
		}

		$result = array(
			'status' => $status,
			'price' =>$price,
			'code' => $code,
			'link' => $link,
			'num' => $num,
			'msg' => $msg
		);

		header('Content-type: application/json');
		echo json_encode($result);
		exit;
	}
	add_action( 'wp_ajax_epd_wppay', 'epd_wppay_callback');
	add_action( 'wp_ajax_nopriv_epd_wppay', 'epd_wppay_callback');

	function epd_wppay_pay_callback(){
		$post_id = $_POST['post_id'];
		$order_num = $_POST['order_num'];
		$status = 0;
		$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
		$wppay = new EPD($post_id, $user_id);
		if($wppay->checkWppayPaid($order_num)){
			$days = get_option('erphp_wppay_cookie');
			$expire = time() + $days*24*60*60;
		    setcookie('wppay_'.$post_id, $wppay->setWppayKey($order_num), $expire, '/', $_SERVER['HTTP_HOST'], false);
		    $status = 1;
		}else{
			//setcookie('wppay_'.$post_id, '', time(), '/', $_SERVER['HTTP_HOST'], false);
		}

		$result = array(
			'status' => $status
		);

		header('Content-type: application/json');
		echo json_encode($result);
		exit;
	}
	add_action( 'wp_ajax_epd_wppay_pay', 'epd_wppay_pay_callback');
	add_action( 'wp_ajax_nopriv_epd_wppay_pay', 'epd_wppay_pay_callback');


	function epd_download_html($content){
		echo $content;
		exit;
	}

	function erphpdown_install() {
		global $wpdb, $erphpdown_version, $wppay_table_name;
		$charset_collate = $wpdb->get_charset_collate();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		if( $wpdb->get_var("show tables like '{$wppay_table_name}'") != $wppay_table_name ) {
			$wpdb->query("CREATE TABLE {$wppay_table_name} (
				id      BIGINT(20) NOT NULL AUTO_INCREMENT,
				order_num VARCHAR(50) NOT NULL,
				post_id BIGINT(20) NOT NULL,
				post_price double(10,2) NOT NULL,
				user_id BIGINT(20) NOT NULL DEFAULT 0,
				order_pay_num VARCHAR(100),
				order_time datetime NOT NULL,
				order_status int(1) NOT NULL DEFAULT 0,
				ip_address VARCHAR(25) NOT NULL,
				UNIQUE KEY id (id)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
		}

		$create_ice_alipay_sql = "CREATE TABLE $wpdb->icealipay (".
				"ice_id int(11) NOT NULL auto_increment,".
				"ice_num varchar(50) NOT NULL,".
				"ice_title varchar(100) NOT NULL,".
				"ice_post int(11) NOT NULL,".
				"ice_price double(10,2) NOT NULL,".
				"ice_url varchar(32) NOT NULL,".
				"ice_user_id int(11) NOT NULL,".
				"ice_time datetime NOT NULL,".
				"ice_data text NOT NULL ,".
				"ice_success int(11) NOT NULL,".
				"ice_author int(11) NOT NULL,".
				"PRIMARY KEY (ice_id)) $charset_collate;";
		dbDelta( $create_ice_alipay_sql );
		
		$create_ice_money_sql="CREATE TABLE $wpdb->icemoney (".
				"ice_id int(11) NOT NULL auto_increment,".
				"ice_num varchar(50) NOT NULL,".
				"ice_money double(10,2) NOT NULL,".
				"ice_user_id int(11) NOT NULL,".
				"ice_post_id int(11),".
				"ice_user_type int(2),".
				"ice_time datetime NOT NULL,".
				"ice_success int(10) NOT NULL,".
				"ice_note varchar(50) NOT NULL,".
				"ice_success_time datetime NOT NULL,".
				"ice_alipay varchar(200) NOT NULL,".
				"PRIMARY KEY (ice_id)) $charset_collate;";
		dbDelta( $create_ice_money_sql );
		
		$create_money_info_sql="CREATE TABLE $wpdb->iceinfo (".
				"ice_id int(11) NOT NULL auto_increment,".
				"ice_have_money double(10,2) NOT NULL,".
				"ice_user_id int(11) NOT NULL,".
				"ice_get_money double(10,2) NOT NULL,".
				"userType TINYINT(4) NOT NULL DEFAULT 0,".
				"endTime DATE NOT NULL DEFAULT '1000-01-01',".
				"PRIMARY KEY (ice_id)) $charset_collate;";
		dbDelta( $create_money_info_sql );
		
		$create_get_money_sql="CREATE TABLE $wpdb->iceget (".
				"ice_id int(11) NOT NULL auto_increment,".
				"ice_alipay varchar(100) NOT NULL,".
				"ice_name varchar(30) NOT NULL,".
				"ice_user_id int(11) NOT NULL,".
				"ice_money double(10,2) NOT NULL,".
				"ice_time datetime NOT NULL,".
				"ice_success int(10) NOT NULL,".
				"ice_note varchar(50) NOT NULL,".
				"ice_success_time datetime NOT NULL,".
				"PRIMARY KEY (ice_id)) $charset_collate;";
		dbDelta( $create_get_money_sql );
		
		$create_ice_vip_sql = "CREATE TABLE $wpdb->vip (".
				"ice_id int(11) NOT NULL auto_increment,".
				"ice_price double(10,2) NOT NULL,".
				"ice_user_id int(11) NOT NULL,".
				"ice_user_type tinyint(4) NOT NULL default 0,".
				"ice_time datetime NOT NULL,".
				"PRIMARY KEY (ice_id)) $charset_collate;";
		dbDelta( $create_ice_vip_sql );
		
		$create_ice_aff_sql = "CREATE TABLE $wpdb->aff (".
				"ice_id int(11) NOT NULL auto_increment,".
				"ice_price double(10,2) NOT NULL,".
				"ice_user_id int(11) NOT NULL,".
				"ice_user_id_visit int(11),".
				"ice_ip varchar(50),".
				"ice_time datetime NOT NULL,".
				"PRIMARY KEY (ice_id)) $charset_collate;";
		dbDelta( $create_ice_aff_sql );

		$create_ice_down_sql = "CREATE TABLE $wpdb->down (".
				"ice_id int(11) NOT NULL auto_increment,".
				"ice_user_id int(11) NOT NULL,".
				"ice_post_id int(11),".
				"ice_ip varchar(50),".
				"ice_time datetime NOT NULL,".
				"PRIMARY KEY (ice_id)) $charset_collate;";
		dbDelta( $create_ice_down_sql );
		
		$up1to2="ALTER TABLE `".$wpdb->users."` ADD  `father_id` INT( 10 ) NOT NULL DEFAULT  '0'";
		$wpdb->query($up1to2);

		$up6to7="ALTER TABLE `".$wpdb->users."` ADD  `reg_ip` varchar( 60 ) DEFAULT  ''";
		$wpdb->query($up6to7);
		
		$up7to8="ALTER TABLE `".$wpdb->icemoney."` modify column ice_num varchar(50)";
		$wpdb->query($up7to8);

		$up8to9="ALTER TABLE `".$wpdb->icealipay."` modify column ice_num varchar(50)";
		$wpdb->query($up8to9);

		$up9to9="ALTER TABLE `".$wpdb->icemoney."` ADD `ice_post_id` int(11), add `ice_user_type` int(2)";
		$wpdb->query($up9to9);

		if(get_option('erphpdown_version') < 9.00){
			update_option('erphp_post_types',array('post'));
		}

		update_option( 'erphpdown_version', $erphpdown_version );
	}

	add_action('admin_enqueue_scripts', 'erphpdown_setting_scripts');
	function erphpdown_setting_scripts(){
		if( isset($_GET['page']) && $_GET['page'] == "erphpdown/admin/erphp-active.php" ){
			wp_enqueue_script( 'erphpdown_setting', ERPHPDOWN_URL.'/static/setting.js', array(), false, true );	
		}
	}