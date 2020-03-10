<?php
/**
 * setting
 www.mobantu.com
 E-mail:82708210@qq.com
 */
 if ( !defined('ABSPATH') ) {exit;}

 if(isset($_POST['Submit'])) {

 	update_option('erphpdown_alipay_type', trim($_POST['erphpdown_alipay_type']));
 	update_option('ice_ali_partner', trim($_POST['ice_ali_partner']));
 	update_option('ice_ali_security_code', trim($_POST['ice_ali_security_code']));
 	update_option('ice_ali_seller_email', trim($_POST['ice_ali_seller_email']));
 	update_option('ice_ali_seller_name', trim($_POST['ice_ali_seller_name']));
 	update_option('ice_payapl_api_uid', trim($_POST['ice_payapl_api_uid']));
 	update_option('ice_payapl_api_pwd', trim($_POST['ice_payapl_api_pwd']));
 	update_option('ice_payapl_api_md5', trim($_POST['ice_payapl_api_md5']));
 	update_option('ice_payapl_api_rmb', trim($_POST['ice_payapl_api_rmb']));   
 	update_option('erphpdown_xhpay_appid2', trim($_POST['erphpdown_xhpay_appid2']));
 	update_option('erphpdown_xhpay_appsecret2', trim($_POST['erphpdown_xhpay_appsecret2']));
 	update_option('ice_china_bank_uid', trim($_POST['ice_china_bank_uid']));
 	update_option('ice_china_bank_pwd', trim($_POST['ice_china_bank_pwd']));
 	update_option('erphpdown_tenpay_uid', trim($_POST['erphpdown_tenpay_uid']));
 	update_option('erphpdown_tenpay_pwd', trim($_POST['erphpdown_tenpay_pwd']));
 	update_option('erphpdown_youzan_id', trim($_POST['erphpdown_youzan_id']));
 	update_option('erphpdown_youzan_secret', trim($_POST['erphpdown_youzan_secret']));
 	update_option('erphpdown_youzan_store', trim($_POST['erphpdown_youzan_store']));
 	update_option('ice_weixin_mchid', trim($_POST['ice_weixin_mchid']));
 	update_option('ice_weixin_appid', trim($_POST['ice_weixin_appid']));
 	update_option('ice_weixin_key', trim($_POST['ice_weixin_key']));
 	update_option('ice_weixin_secret', trim($_POST['ice_weixin_secret']));
 	update_option('erphpdown_zfbjk_uid', trim($_POST['erphpdown_zfbjk_uid']));
 	update_option('erphpdown_zfbjk_key', trim($_POST['erphpdown_zfbjk_key']));
 	update_option('erphpdown_zfbjk_alipay', trim($_POST['erphpdown_zfbjk_alipay']));
 	update_option('erphpdown_zfbjk_name', trim($_POST['erphpdown_zfbjk_name']));
 	update_option('erphpdown_zfbjk_qr', trim($_POST['erphpdown_zfbjk_qr']));
 	update_option('erphpdown_codepay_appid', trim($_POST['erphpdown_codepay_appid']));
 	update_option('erphpdown_codepay_appsecret', trim($_POST['erphpdown_codepay_appsecret']));
 	update_option('erphpdown_f2fpay_id', trim($_POST['erphpdown_f2fpay_id']));
 	update_option('erphpdown_f2fpay_public_key', trim($_POST['erphpdown_f2fpay_public_key']));
 	update_option('erphpdown_f2fpay_private_key', trim($_POST['erphpdown_f2fpay_private_key']));

 	echo'<div class="updated settings-error"><p>更新成功！</p></div>';

 }

 $erphpdown_alipay_type = get_option('erphpdown_alipay_type');
 $ice_ali_partner       = get_option('ice_ali_partner');
 $ice_ali_security_code = get_option('ice_ali_security_code');
 $ice_ali_seller_email  = get_option('ice_ali_seller_email');
 $ice_ali_seller_name   = get_option('ice_ali_seller_name');
 $ice_payapl_api_uid    = get_option('ice_payapl_api_uid');
 $ice_payapl_api_pwd    = get_option('ice_payapl_api_pwd');
 $ice_payapl_api_md5    = get_option('ice_payapl_api_md5');
 $ice_payapl_api_rmb    = get_option('ice_payapl_api_rmb');
 $erphpdown_xhpay_appid2    = get_option('erphpdown_xhpay_appid2');
 $erphpdown_xhpay_appsecret2    = get_option('erphpdown_xhpay_appsecret2');
 $ice_china_bank_uid  = get_option('ice_china_bank_uid');
 $ice_china_bank_pwd  = get_option('ice_china_bank_pwd');
 $erphpdown_tenpay_uid  = get_option('erphpdown_tenpay_uid');
 $erphpdown_tenpay_pwd  = get_option('erphpdown_tenpay_pwd');
 $erphpdown_youzan_id  = get_option('erphpdown_youzan_id');
 $erphpdown_youzan_secret  = get_option('erphpdown_youzan_secret');
 $erphpdown_youzan_store  = get_option('erphpdown_youzan_store');
 $ice_weixin_mchid  = get_option('ice_weixin_mchid');
 $ice_weixin_appid  = get_option('ice_weixin_appid');
 $ice_weixin_key  = get_option('ice_weixin_key');
 $ice_weixin_secret  = get_option('ice_weixin_secret');
 $erphpdown_zfbjk_uid  = get_option('erphpdown_zfbjk_uid');
 $erphpdown_zfbjk_key  = get_option('erphpdown_zfbjk_key');
 $erphpdown_zfbjk_alipay  = get_option('erphpdown_zfbjk_alipay');
 $erphpdown_zfbjk_name  = get_option('erphpdown_zfbjk_name');
 $erphpdown_zfbjk_qr  = get_option('erphpdown_zfbjk_qr');
 $erphpdown_codepay_appid    = get_option('erphpdown_codepay_appid');
 $erphpdown_codepay_appsecret    = get_option('erphpdown_codepay_appsecret');
 $erphpdown_f2fpay_id       = get_option('erphpdown_f2fpay_id');
 $erphpdown_f2fpay_public_key       = get_option('erphpdown_f2fpay_public_key');
 $erphpdown_f2fpay_private_key       = get_option('erphpdown_f2fpay_private_key');
 ?>
 <style>.form-table th{font-weight: 400}</style>
 <div class="wrap">
 	<h1>Erphp down支付设置</h1>
 	<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
 		<div>以下所有接口均属于第三方服务，我们仅提供技术集成服务</div>
 		<h3>1、支付宝PC电脑支付（官方接口）</h3>
 		如果需要集成在手机浏览器里唤起支付宝APP（H5支付），可联系我们模板兔集成（另收费）
 		<table class="form-table">
 			<tr>
 				<th valign="top">接口类型</th>
 				<td>
 					<select name="erphpdown_alipay_type">
 						<option value="create_direct_pay_by_user" <?php if($erphpdown_alipay_type == 'create_direct_pay_by_user') echo 'selected="selected"';?>>即时到账</option>
 						<option value ="create_partner_trade_by_buyer" <?php if($erphpdown_alipay_type == 'create_partner_trade_by_buyer') echo 'selected="selected"';?>>担保交易（官方已下架）</option>
 						<option value ="trade_create_by_buyer" <?php if($erphpdown_alipay_type == 'trade_create_by_buyer') echo 'selected="selected"';?>>双接口（官方已下架）</option>
 					</select>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">合作者身份(Partner ID)</th>
 				<td>
 					<input type="text" id="ice_ali_partner" name="ice_ali_partner" value="<?php echo $ice_ali_partner ; ?>" class="regular-text"/>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">安全校验码(Key)</th>
 				<td>
 					<input type="text" id="ice_ali_security_code" name="ice_ali_security_code" value="<?php echo $ice_ali_security_code; ?>" class="regular-text"/>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">支付宝收款账号</th>
 				<td>
 					<input type="text" id="ice_ali_seller_email" name="ice_ali_seller_email" value="<?php echo $ice_ali_seller_email; ?>" class="regular-text"/>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">收款方名称</th>
 				<td>
 					<input type="text" id="ice_ali_seller_name" name="ice_ali_seller_name" value="<?php echo $ice_ali_seller_name; ?>" class="regular-text"/>
 				</td>
 			</tr>
 		</table>
 		<br />

 		<br />
		<h3>2、支付宝当面付（官方接口）</h3>
		<table class="form-table">
				<tr>
 				<th valign="top">应用ID</th>
 				<td>
 					<input type="text" id="erphpdown_f2fpay_id" name="erphpdown_f2fpay_id" value="<?php echo $erphpdown_f2fpay_id ; ?>" class="regular-text"/>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">应用商户私钥</th>
 				<td>
 					<textarea id="erphpdown_f2fpay_private_key" name="erphpdown_f2fpay_private_key" class="regular-text" style="height: 200px;"><?php echo $erphpdown_f2fpay_private_key; ?></textarea>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">支付宝公钥</th>
 				<td>
 					<textarea id="erphpdown_f2fpay_public_key" name="erphpdown_f2fpay_public_key" class="regular-text" style="height: 200px;"><?php echo $erphpdown_f2fpay_public_key; ?></textarea>
 				</td>
 			</tr>
		</table>

 		<h3>3、微信扫码支付（官方接口）</h3>
 		如果需要集成在微信浏览器里直接微信支付（公众号支付）或者手机浏览器唤起微信APP（H5支付），可联系我们模板兔集成（另收费）<br><br><div style="color:red">支付授权目录：<?php echo home_url();?>/wp-content/plugins/erphpdown/payment/</div>
 			<table class="form-table">
 				<tr>
 					<th valign="top">商户号(MCHID)</th>
 					<td>
 						<input type="text" id="ice_weixin_mchid" name="ice_weixin_mchid" value="<?php echo $ice_weixin_mchid ; ?>" class="regular-text"/>
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">APPID</th>
 					<td>
 						<input type="text" id="ice_weixin_appid" name="ice_weixin_appid" value="<?php echo $ice_weixin_appid; ?>" class="regular-text"/>
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">商户支付密钥(KEY)</th>
 					<td>
 						<input type="text" id="ice_weixin_key" name="ice_weixin_key" value="<?php echo $ice_weixin_key; ?>" class="regular-text"/><br>
 						设置地址：<a href="https://pay.weixin.qq.com/index.php/account/api_cert" target="_blank">https://pay.weixin.qq.com/index.php/account/api_cert </a>，建议为32位字符串
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">公众帐号Secret</th>
 					<td>
 						<input type="text" id="ice_weixin_secret" name="ice_weixin_secret" value="<?php echo $ice_weixin_secret; ?>" class="regular-text"/>
 					</td>
 				</tr>
 			</table>

 			<br />
 			<h3>4、PayPal</h3>
 			<table class="form-table">
 				<tr>
 					<th valign="top">API帐号</th>
 					<td>
 						<input type="text" id="ice_payapl_api_uid" name="ice_payapl_api_uid" value="<?php echo $ice_payapl_api_uid ; ?>" class="regular-text"/>
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">API密码</th>
 					<td>
 						<input type="text" id="ice_payapl_api_pwd" name="ice_payapl_api_pwd" value="<?php echo $ice_payapl_api_pwd; ?>" class="regular-text"/>
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">API签名</th>
 					<td>
 						<input type="text" id="ice_payapl_api_md5" name="ice_payapl_api_md5" value="<?php echo $ice_payapl_api_md5; ?>" class="regular-text"/>
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">汇率</th>
 					<td>
 						<input type="text" id="ice_payapl_api_rmb" name="ice_payapl_api_rmb" value="<?php echo $ice_payapl_api_rmb; ?>" class="regular-text"/>
 					</td>
 				</tr>
 			</table>

 			<br />
 			<h3>5、银联</h3>
 			<div>申请地址 http://chinabank.com.cn/</div>
 			<table class="form-table">
 				<tr>
 					<th valign="top">商户号</th>
 					<td>
 						<input type="text" id="ice_china_bank_uid" name="ice_china_bank_uid" value="<?php echo $ice_china_bank_uid ; ?>" class="regular-text"/>
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">MD5密钥</th>
 					<td>
 						<input type="text" id="ice_china_bank_pwd" name="ice_china_bank_pwd" value="<?php echo $ice_china_bank_pwd; ?>" class="regular-text"/>
 					</td>
 				</tr>
 			</table>
 			
 			
 			<br />
 			<h3>6、虎皮椒（个人支付宝/微信即时到账）</h3>
 			<div>关于此接口的安全稳定性，请使用者自行把握，我们只提供技术集成服务，接口申请地址：<a href="http://mp.xunhupay.com/sign-up/451.html" target="_blank" rel="nofollow">点击查看</a></div>
 			<table class="form-table">
 				<tr>
 					<th valign="top">appid</th>
 					<td>
 						<input type="text" id="erphpdown_xhpay_appid2" name="erphpdown_xhpay_appid2" value="<?php echo $erphpdown_xhpay_appid2 ; ?>" class="regular-text"/>
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">appsecret</th>
 					<td>
 						<input type="text" id="erphpdown_xhpay_appsecret2" name="erphpdown_xhpay_appsecret2" value="<?php echo $erphpdown_xhpay_appsecret2; ?>" class="regular-text"/>
 					</td>
 				</tr>

 			</table>
 			
 			<br />
 			<h3>7、支付宝免签即时到账</h3>
 			<p>关于此接口的安全稳定性，请使用者自行把握，我们只提供技术集成服务，接口申请地址：<a href="http://t.cn/RtkFoqD" target="_blank">点击查看</a></p>
 			<font color="red">通知网址：<?php bloginfo('url')?>/wp-content/plugins/erphpdown/payment/alipay_jk/notify_url.php</font>
 			<table class="form-table">
 				<tr>
 					<th valign="top">商户id</th>
 					<td>
 						<input type="text" id="erphpdown_zfbjk_uid" name="erphpdown_zfbjk_uid" value="<?php echo $erphpdown_zfbjk_uid ; ?>" class="regular-text"/>
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">商户秘钥</th>
 					<td>
 						<input type="text" id="erphpdown_zfbjk_key" name="erphpdown_zfbjk_key" value="<?php echo $erphpdown_zfbjk_key; ?>" class="regular-text"/>
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">支付宝账号</th>
 					<td>
 						<input type="text" id="erphpdown_zfbjk_alipay" name="erphpdown_zfbjk_alipay" value="<?php echo $erphpdown_zfbjk_alipay; ?>" class="regular-text"/>
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">收款人姓名</th>
 					<td>
 						<input type="text" id="erphpdown_zfbjk_name" name="erphpdown_zfbjk_name" value="<?php echo $erphpdown_zfbjk_name; ?>" class="regular-text"/>（用于校验真实姓名）
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">收款二维码图片地址</th>
 					<td>
 						<input type="text" id="erphpdown_zfbjk_qr" name="erphpdown_zfbjk_qr" value="<?php echo $erphpdown_zfbjk_qr; ?>" placeholder="http://"  class="regular-text"/>（用于手机扫码转账）
 					</td>
 				</tr>
 			</table>

 			<br />
 			<h3>8、码支付（支付宝/微信/QQ钱包）</h3>
 			<div>关于此接口的安全稳定性，请使用者自行把握（接口方可能跑路给你带来的损失），我们只提供技术集成服务，接口申请地址：<a href="https://codepay.fateqq.com/?from=erphpdown" target="_blank" rel="nofollow">点击查看</a></div>
 			<table class="form-table">
 				<tr>
 					<th valign="top">码支付ID</th>
 					<td>
 						<input type="text" id="erphpdown_codepay_appid" name="erphpdown_codepay_appid" value="<?php echo $erphpdown_codepay_appid ; ?>" class="regular-text"/>
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">通讯密钥</th>
 					<td>
 						<input type="text" id="erphpdown_codepay_appsecret" name="erphpdown_codepay_appsecret" value="<?php echo $erphpdown_codepay_appsecret; ?>" class="regular-text"/>
 					</td>
 				</tr>
 			</table>

 			<br />
 			<h3>9、有赞云</h3>
 			<p style="color:red">请确保你的接口目前可用</p>
 			<table class="form-table">
 				<tr>
 					<th valign="top">client id</th>
 					<td>
 						<input type="text" id="erphpdown_youzan_id" name="erphpdown_youzan_id" value="<?php echo $erphpdown_youzan_id ; ?>" class="regular-text"/>
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">client secret</th>
 					<td>
 						<input type="text" id="erphpdown_youzan_secret" name="erphpdown_youzan_secret" value="<?php echo $erphpdown_youzan_secret; ?>" class="regular-text"/>
 					</td>
 				</tr>
 				<tr>
 					<th valign="top">授权店铺id</th>
 					<td>
 						<input type="text" id="erphpdown_youzan_store" name="erphpdown_youzan_store" value="<?php echo $erphpdown_youzan_store ; ?>" class="regular-text"/>
 					</td>
 				</tr>
 			</table>
 			<p class="submit">
 				<input type="submit" name="Submit" value="保存设置" class="button-primary"/>
 				<div >技术支持：mobantu.com <a href="http://www.mobantu.com/6658.html" target="_blank">使用教程>></a></div>
 			</p>      
 		</form>
 	</div>