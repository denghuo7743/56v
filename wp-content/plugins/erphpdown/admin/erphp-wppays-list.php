<?php
/*
*作者：mobantu.com
*/
if ( !defined('ABSPATH') ) {exit;}
if(!is_user_logged_in()){
	exit;
}
global $wpdb, $wppay_table_name;
$total   = $wpdb->get_var("SELECT COUNT(id) FROM $wppay_table_name WHERE order_status=1");
$perpage = 20;
$pages = ceil($total / $perpage);
$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
$offset = $perpage*($page-1);
$list = $wpdb->get_results("SELECT * FROM $wppay_table_name WHERE order_status=1 ORDER BY order_time DESC limit $offset,$perpage");
?>
<div class="wrap">
	<h2>所有订单</h2>
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th width="16%">订单号</th>
				<th width="38%">商品名称</th>
				<th width="8%">价格</th>
				<th width="12%">IP地址</th>
				<th width="12%">交易时间</th>	
				<th width="14%">用户ID</th>	
			</tr>
		</thead>
		<tbody>
	<?php
		if($list) {
			foreach($list as $value){
				echo "<tr>\n";
				echo "<td>".$value->order_num."</td>";
				echo "<td><a target='_blank' href='".get_permalink($value->post_id)."'>".get_the_title($value->post_id)."</a></td>\n";
				echo "<td>$value->post_price 元</td>\n";
				echo "<td>$value->ip_address</td>\n";
				echo "<td>$value->order_time</td>\n";
				if($value->user_id){
					echo "<td>".get_user_by('id',$value->user_id)->user_login."</td>";
				}else{
					echo "<td>游客</td>";
				}
				echo "</tr>";
			}
		}
		else{
			echo '<tr><td colspan="6" align="center"><strong>没有订单</strong></td></tr>';
		}
	?>
	</tbody>
	</table>
    <?php echo erphp_admin_pagenavi($total,$perpage);?>
</div>
