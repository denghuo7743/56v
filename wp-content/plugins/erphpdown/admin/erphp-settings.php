<?php
/**
 * setting
 www.mobantu.com
 E-mail:82708210@qq.com
 */
 if ( !defined('ABSPATH') ) {exit;}

 if(isset($_POST['Submit'])) {
 	update_option('ice_ali_money_limit', trim($_POST['ice_ali_money_limit']));
 	update_option('ice_ali_money_site', trim($_POST['ice_ali_money_site']));
 	update_option('ice_ali_money_author', trim($_POST['ice_ali_money_author']));
 	update_option('ice_ali_money_ref', trim($_POST['ice_ali_money_ref']));
 	update_option('ice_ali_money_reg', trim($_POST['ice_ali_money_reg']));
 	update_option('erphp_mycred', trim($_POST['erphp_mycred']));
 	update_option('erphp_to_mycred', trim($_POST['erphp_to_mycred']));
 	update_option('ice_tips', trim($_POST['ice_tips']));
 	update_option('erphpdown_downkey', trim($_POST['erphpdown_downkey']));
 	update_option('erphp_ajaxbuy', trim($_POST['erphp_ajaxbuy']));
 	update_option('erphp_popdown', trim($_POST['erphp_popdown']));
 	update_option('ice_name_alipay', trim($_POST['ice_name_alipay']));
 	update_option('ice_proportion_alipay', trim($_POST['ice_proportion_alipay']));
 	update_option('erphpdown_min_price', trim($_POST['erphpdown_min_price']));
 	update_option('erphp_wppay_cookie', trim($_POST['erphp_wppay_cookie']));
 	update_option('erphp_wppay_ip', trim($_POST['erphp_wppay_ip']));
 	update_option('erphp_wppay_payment', trim($_POST['erphp_wppay_payment']));

 	echo'<div class="updated settings-error"><p>更新成功！</p></div>';

 }

 $ice_ali_money_limit    = get_option('ice_ali_money_limit');
 $ice_ali_money_site    = get_option('ice_ali_money_site');
 $ice_ali_money_author   = get_option('ice_ali_money_author');
 $ice_ali_money_ref    = get_option('ice_ali_money_ref');
 $ice_ali_money_reg    = get_option('ice_ali_money_reg');
 $erphp_mycred    = get_option('erphp_mycred');
 $erphp_to_mycred    = get_option('erphp_to_mycred');
 $ice_tips    = get_option('ice_tips');
 $erphpdown_downkey    = get_option('erphpdown_downkey')?get_option('erphpdown_downkey'):'erphpdown';
 $erphp_ajaxbuy    = get_option('erphp_ajaxbuy');
 $erphp_popdown    = get_option('erphp_popdown');
 $ice_name_alipay    = get_option('ice_name_alipay');
 $ice_proportion_alipay    = get_option('ice_proportion_alipay');
 $erphpdown_min_price    = get_option('erphpdown_min_price');
 $erphp_wppay_cookie    = get_option('erphp_wppay_cookie');
 $erphp_wppay_ip    = get_option('erphp_wppay_ip');
 $erphp_wppay_payment    = get_option('erphp_wppay_payment');
 ?>
 <style>.form-table th{font-weight: 400}</style>
 <div class="wrap">
 	<h1>Erphp down基础设置</h1>
 	<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
 		<table class="form-table">
 			<tr>
 				<th valign="top">货币昵称</th>
 				<td>
 					<input type="text" id="ice_name_alipay" name="ice_name_alipay" value="<?php echo $ice_name_alipay;?>" class="regular-text"/> （例如：模板兔币）
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">充值比例</th>
 				<td>
 					<input type="number" id="ice_proportion_alipay" name="ice_proportion_alipay" value="<?php echo $ice_proportion_alipay;?>" required="required" class="regular-text"/> （请输入一个整数，例如：10，代表1元=10 模板兔币）
 				</td>
 			</tr> 
 			<tr>
 				<th valign="top">推广消费提成（百分点）</th>
 				<td>
 					<input type="number" id="ice_ali_money_ref" name="ice_ali_money_ref" value="<?php echo $ice_ali_money_ref; ?>" required="required" class="regular-text"/>% 
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">作者分成（百分点）</th>
 				<td>
 					<input type="number" id="ice_ali_money_author" name="ice_ali_money_author" value="<?php echo $ice_ali_money_author; ?>" required="required" class="regular-text"/>% （例如输入80，表示作者A发布的收费资源用户B购买后，A将得到其资源价格的80%，不填则默认100%）
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">推广注册奖励</th>
 				<td>
 					<input type="number" id="ice_ali_money_reg" name="ice_ali_money_reg" value="<?php echo $ice_ali_money_reg; ?>" required="required" class="regular-text"/> 模板兔币 （请输入一个整数）
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">提现规则</th>
 				<td>
 					<input type="number" id="ice_ali_money_limit" name="ice_ali_money_limit" value="<?php echo $ice_ali_money_limit; ?>" required="required" class="regular-text"/> 模板兔币以上方可提现 （请输入一个整数）
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">提现手续费（百分点）</th>
 				<td>
 					<input type="number" id="ice_ali_money_site" name="ice_ali_money_site" value="<?php echo $ice_ali_money_site; ?>" required="required" class="regular-text"/>% （请输入一个整数）
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">购买说明</th>
 				<td>
 					<textarea id="ice_tips" name="ice_tips" placeholder="客服QQ：82708210" rows="5" cols="70"><?php echo $ice_tips; ?></textarea>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">下载标识码</th>
 				<td>
 					<input type="text" id="erphpdown_downkey" name="erphpdown_downkey" value="<?php echo $erphpdown_downkey;?>" class="regular-text"/> （建议设置一个随机字符串，长度为8位左右即可，不要告知他人）
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">Mycred积分兑换</th>
 				<td>
 					<input type="checkbox" id="erphp_mycred" name="erphp_mycred" value="yes" <?php if($erphp_mycred == 'yes') echo 'checked'; ?> /> （需安装<a href="https://wordpress.org/plugins/mycred/" target="_blank">mycred插件</a>与<a href="http://www.mobantu.com/6017.html" target="_blank">erphpdown集成mycred插件</a>） 兑换比例：
 					<input type="number" id="erphp_to_mycred" name="erphp_to_mycred" value="<?php echo $erphp_to_mycred; ?>" style="width:100px" />（输入100则为 100积分 = 1模板兔币）
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">Ajax无跳转购买</th>
 				<td>
 					<input type="checkbox" id="erphp_ajaxbuy" name="erphp_ajaxbuy" value="yes" <?php if($erphp_ajaxbuy == 'yes') echo 'checked'; ?> /> 
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">弹窗下载</th>
 				<td>
 					<input type="checkbox" id="erphp_popdown" name="erphp_popdown" value="yes" <?php if($erphp_popdown == 'yes') echo 'checked'; ?> /> 
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">最小充值金额</th>
 				<td>
 					<input type="text" id="erphpdown_min_price" name="erphpdown_min_price" value="<?php echo $erphpdown_min_price;?>" class="regular-text"/> 元（这里是充值的最小金额，不设置则不限制）
 				</td>
 			</tr>
 		</table>
 		<h3>免登录下载设置</h3>
 		<p style="color:red">仅支持支付宝当面付、有赞云支付接口</p>
 		<table class="form-table">
 			<tr>
 				<th valign="top">接口类型</th>
 				<td>
 					<select name="erphp_wppay_payment">
 						<option value="youzan" <?php if($erphp_wppay_payment == 'youzan') echo 'selected="selected"';?>>有赞云支付</option>
 						<option value ="f2fpay" <?php if($erphp_wppay_payment == 'f2fpay') echo 'selected="selected"';?>>支付宝当面付</option>
 					</select>
 				</td>
 			</tr>
 			<tr>
				<th valign="top">Cookie过期天数</th>
				<td>
					<input type="number" id="erphp_wppay_cookie" name="erphp_wppay_cookie" value="<?php echo $erphp_wppay_cookie ; ?>" class="regular-text"/>
				</td>
			</tr>
 			<tr>
 				<th valign="top">加通过IP判断</th>
 				<td>
 					<input type="checkbox" id="erphp_wppay_ip" name="erphp_wppay_ip" value="yes" <?php if($erphp_wppay_ip == 'yes') echo 'checked'; ?> />（勾选后就算cookie过期，只要IP不变，一样会判断成已支付）
 				</td>
 			</tr>
 		</table>
 		<p class="submit">
 			<input type="submit" name="Submit" value="保存设置" class="button-primary"/>
 			<div >技术支持：mobantu.com <a href="http://www.mobantu.com/6658.html" target="_blank">使用教程>></a></div>
 		</p>      
 	</form>
 </div>