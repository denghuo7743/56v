<?php
/*
mobantu.com
qq 82708210
*/
if ( !defined('ABSPATH') ) {exit;}

function erphpdown_metaboxs() {
	$meta_boxes = array(
		array(
			"name"             => "start_down",
			"title"            => "收费模式 *",
			"desc"             => "免登录下载仅支持有赞支付接口直接支付购买，请确保接口可用",
			"type"             => "erphpcheckbox",
			"capability"       => "manage_options"
		),
		array(
			"name"             => "member_down",
			"title"            => "VIP优惠 *",
			"desc"             => "专享指只有VIP用户可下载或查看，普通用户无权单独购买",
			"type"             => "vipradio",
			'options' => array(
				'1' => '无',
	            '4' => '专享',
	            '8' => '年费专享',
	            '9' => '终身专享',
	            '3' => '免费',
	            '6' => '年费免费',
	            '7' => '终身免费',
	            '2' => '5折',
	            '5' => '8折'
	        ),
	        'default' => '1',
			"capability"       => "manage_options"
		),
		array(
			"name"             => "down_price",
			"title"            => "收费价格 *",
			"desc"             => "<font color='red'>除VIP专享外，其他必须大于0，否则视为免费资源</font>，免登录模式时单位为元",
			"type"             => "number",
			'default'          => '0',
			'required'         => '1',
			"capability"       => "manage_options"
		),
		array(
			"name"             => "down_url",
			"title"            => "下载地址 *",
			"desc"             => "<font color='red'>收费查看模式不用填写</font>，地址一行一个，可外链以及内链。地址格式可为以下任意一种：<br><ol><li>/wp-content/uploads/moban-tu.zip</li><li>https://pan.baidu.com/test</li><li>某某地址,https://pan.baidu.com/test,提取码：2587</li><li>某某地址,https://pan.baidu.com/test</li><li>链接: https://pan.baidu.com/s/test 提取码: xxxx 复制这段内容后打开百度网盘手机App，操作更方便哦</li></ol>需要说明的是：1是内链，可加密下载地址；3与4格式用英文半角逗号隔开（名称,下载地址,提取码或解压密码），不能有空格；5是<b>网页版百度网盘</b>默认分享格式（名称 下载地址 提取码名称 提取码），英文空格分割，最后面那句广告可以去掉，不排除百度更改其格式的可能性哦~",
			"type"             => "erphptextarea",
			"capability"       => "manage_options"
		),
		array(
			"name"             => "hidden_content",
			"title"            => "隐藏内容",
			"desc"             => "收费下载模式的隐藏内容。填纯文本内容，一般填提取码或者解压密码。",
			"type"             => "text",
			"capability"       => "manage_options"
		),
		array(
			"name"             => "down_days",
			"title"            => "过期天数",
			"desc"             => "留空或0则表示一次购买永久下载，设置一个大于0的数字比如30，则表示购买30天后得重新购买",
			"type"             => "number",
			'default'          => '0',
			"required"         => "0",
			"capability"       => "manage_options"
		)
	);
	return apply_filters( 'ali_post_boxes', $meta_boxes );
}

function erphpdown_show_metabox() {
	global $post;
	$meta_boxes = erphpdown_metaboxs(); 
	?>
	<table class="form-table">
		<?php 
		foreach ( $meta_boxes as $meta ) :
			$value = get_post_meta( $post->ID, $meta['name'], true );
			if ( $meta['type'] == 'text' )
				erphpdown_show_text( $meta, $value );
			elseif ( $meta['type'] == 'number' )
				erphpdown_show_number( $meta, $value );
			elseif ( $meta['type'] == 'textarea' )
				erphpdown_show_textarea( $meta, $value );
			elseif ( $meta['type'] == 'erphptextarea' )
				erphpdown_show_erphptextarea( $meta, $value );
			elseif ( $meta['type'] == 'checkbox' )
				erphpdown_show_checkbox( $meta, $value );
			elseif ( $meta['type'] == 'erphpcheckbox' )
				erphpdown_show_erphpcheckbox( $meta, $value );
			elseif ($meta['type'] == 'vipradio')
				erphpdown_show_vipradio( $meta, $value );
		endforeach; 
		?>
	</table>
	<?php
}

function erphpdown_show_vipradio( $args = array(), $value = false ) {
	extract( $args ); ?>
	<tr>
		<th style="width:10%;">
			<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
		</th>
		<td>
			<?php
				$i=1;
	            foreach ($options as $key => $option) {
	            	if(!$value) $value=$default;
	            	if($key != 1 && $key != 3){$class="login";}else{$class="";}
	                echo '<span><input type="radio" name="'.$name.'" id="'.$name.$i.'" value="'. esc_attr( $key ) . '" '. checked( $value, $key, false) .' class="'.$class.'"/><label for="'.$name.$i.'">' . esc_html( $option ) . '</label>&nbsp;&nbsp;&nbsp;&nbsp;</span>';
	                $i ++;
	            }
            ?>
			<input type="hidden" name="<?php echo $name; ?>_input_name" id="<?php echo $name; ?>_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			<br />
			<p class="description"><?php echo $desc; ?></p>
		</td>
	</tr>
	<?php
}

function erphpdown_show_text( $args = array(), $value = false ) {
	extract( $args ); ?>
	<tr>
		<th style="width:10%;">
			<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
		</th>
		<td>
			<input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_html( $value, 1 ); ?>" style="width: 90%;max-width: 600px;" />
			<input type="hidden" name="<?php echo $name; ?>_input_name" id="<?php echo $name; ?>_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			<br />
			<p class="description"><?php echo $desc; ?></p>
		</td>
	</tr>
	<?php
}

function erphpdown_show_number( $args = array(), $value = false ) {
	extract( $args ); if(!$value) $value=$default; ?>
	<tr>
		<th style="width:10%;">
			<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
		</th>
		<td>
			<input type="number" min="0" step="0.01" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_html( $value, 1 ); ?>" style="width: 100px;" <?php if($required) echo 'required';?>/>
			<input type="hidden" name="<?php echo $name; ?>_input_name" id="<?php echo $name; ?>_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			<br />
			<p class="description"><?php echo $desc; ?></p>
		</td>
	</tr>
	<?php
}

function erphpdown_show_textarea( $args = array(), $value = false ) {
	extract( $args ); ?>
	<tr>
		<th style="width:10%;">
			<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
		</th>
		<td>
			<textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" cols="60" rows="4" tabindex="30" style="width: 100%;"><?php echo esc_html( $value, 1 ); ?></textarea>
			<input type="hidden" name="<?php echo $name; ?>_input_name" id="<?php echo $name; ?>_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			<br />
			<p class="description"><?php echo $desc; ?></p>	
		</td>
	</tr>
	<?php
}

function erphpdown_show_erphptextarea( $args = array(), $value = false ) {
	extract( $args ); ?>
	<tr>
		<th style="width:10%;">
			<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
		</th>
		<td>
			<textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" cols="60" rows="4" tabindex="30" style="width: 100%;"><?php echo esc_html( $value, 1 ); ?></textarea><a href="javascript:;" class="erphp-add-file button">上传媒体库文件</a> <a href="javascript:;" class="erphp-add-file2 button button-primary">上传本地文件</a> <span id="file-progress"></span>
			<input type="hidden" name="<?php echo $name; ?>_input_name" id="<?php echo $name; ?>_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			<br />
			<p class="description">
				<font color='red'>收费查看模式不用填写</font>，地址一行一个，可外链以及内链。地址格式可为以下任意一种：<a href="javascript:;" class="erphpshowtypes">显示格式</a><br>
				<div class="erphpurltypes" style="display: none;"><ol><li>/wp-content/uploads/moban-tu.zip</li><li>https://pan.baidu.com/test</li><li>某某地址,https://pan.baidu.com/test,提取码：2587</li><li>某某地址,https://pan.baidu.com/test</li><li>链接: https://pan.baidu.com/s/test 提取码: xxxx 复制这段内容后打开百度网盘手机App，操作更方便哦</li></ol>模板兔提示：1是内链，可加密下载地址；3与4格式用英文半角逗号隔开（名称,下载地址,提取码或解压密码），不能有空格；5是<b>网页版百度网盘</b>默认分享格式（名称 下载地址 提取码名称 提取码），英文空格分割，最后面那句广告可以去掉，不排除百度更改其格式的可能性哦~</div>
			</p>	
			<script src="<?php echo ERPHPDOWN_URL;?>/static/jquery.form.js"></script>
			<script>
		        jQuery(document).ready(function() {
		            var $ = jQuery;
		            if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
		                $(document).on('click', '.erphp-add-file', function(e) {
		                    e.preventDefault();
		                    var button = $(this);
		                    var id = button.prev();
		                    wp.media.editor.send.attachment = function(props, attachment) {
		                        //console.log(attachment)
		                        if($.trim(id.val()) != ''){
									id.val(id.val()+'\n'+attachment.url);
								}else{
									id.val(attachment.url);	
								}
		                    };
		                    wp.media.editor.open(button);
		                    return false;
		                });
		            }

		            $(".erphpshowtypes").click(function(){
		            	if($(this).hasClass('active')){
		            		$(".erphpurltypes").hide();
		            	}else{
		            		$(".erphpurltypes").show();
		            	}
		            	$(this).toggleClass("active");
		            });

		            $(".erphp-add-file2").click(function(){
                        $("body").append('<form style="display:none" id="erphpFileForm" action="<?php echo ERPHPDOWN_URL;?>/admin/action/file.php" enctype="multipart/form-data" method="post"><input type="file" id="erphpFile" name="erphpFile"></form>');
                        $("#erphpFile").trigger('click');
                        $("#erphpFile").change(function(){
                            $("#erphpFileForm").ajaxSubmit({
                                //dataType:  'json',
                                beforeSend: function() {
                                    
                                },
                                uploadProgress: function(event, position, total, percentComplete) {
                                    $('#file-progress').text(percentComplete+'%');
                                },
                                success: function(data) {
                                    $('#erphpFileForm').remove();
                                    var olddata = $('#<?php echo $name;?>').val();
                                    if($.trim(olddata)){
                                    	$('#<?php echo $name;?>').val(olddata+'\n'+data);   
                                    }else{
	                                    $('#<?php echo $name;?>').val(data);   
	                                }
                                },
                                error:function(xhr){
                                    $('#erphpFileForm').remove();
                                    alert('上传失败！'); 
                                }
                            });

                        });
                    });
		            
		        });
		    </script>	
		</td>
	</tr>
	<?php
}

function erphpdown_show_checkbox( $args = array(), $value = false ) {
	extract( $args ); ?>
	<tr>
	<th style="width:10%;">
		<label for="<?php echo $name; ?>"><?php echo $title; ?></label>		</th>
		<td>
			<input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="1"
			<?php if ( htmlentities( $value, 1 ) == '1' ) echo ' checked="checked"'; ?>
			style="width: auto;" />&nbsp;启用<?php echo $title; ?>
			<input type="hidden" name="<?php echo $name; ?>_input_name" id="<?php echo $name; ?>_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			<p class="description"><?php echo $desc; ?></p>

		</td>
	</tr>
<?php }

function erphpdown_show_erphpcheckbox( $args = array(), $value = false ) {
	extract( $args ); ?>
	<tr>
		<th style="width:10%;">
			<label for="<?php echo $name; ?>"><?php echo $title; ?></label>		
		</th>
		<td>
			<?php 
			global $post;
			$value1 = get_post_meta( $post->ID, 'start_down', true );
			$value2 = get_post_meta( $post->ID, 'start_see', true );
			$value3 = get_post_meta( $post->ID, 'start_see2', true );
			$value5 = get_post_meta( $post->ID, 'start_down2', true );
			?>
			<input type="radio" name="start_down" checked value="4" />不启用&nbsp;
			<input type="radio" name="start_down" <?php if($value1 == 'yes') echo 'checked'?> value="1" />下载 &nbsp;
			<input type="radio" name="start_down" <?php if($value5 == 'yes') echo 'checked'?> value="5" class="nologin"/>免登录下载 &nbsp;
			<?php if(!erphp_check_mobantu_theme()){?>
			<input type="radio" name="start_down" <?php if($value2 == 'yes') echo 'checked'?> value="2" />查看全部 &nbsp;
			<input type="radio" name="start_down" <?php if($value3 == 'yes') echo 'checked'?> value="3" />查看部分（短代码 [erphpdown]隐藏内容[/erphpdown]）&nbsp;
			<?php }?>

			<input type="hidden" name="erphpdown" value="1">
			<input type="hidden" name="start_down_input_name" id="start_down_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			<input type="hidden" name="start_down2_input_name" id="start_down2_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			<input type="hidden" name="start_see_input_name" id="start_see_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			<input type="hidden" name="start_see2_input_name" id="start_see2_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			<p class="description"><?php echo $desc; ?></p>
		</td>
		<script>
			jQuery(function(){
				if(jQuery("input[name='start_down'].nologin").is(":checked")){
					jQuery("input[name='member_down'].login").parent().hide();
				}
			});
			jQuery("input[name='start_down']").click(function(){
				if(jQuery(this).hasClass("nologin")){
					jQuery("input[name='member_down'].login").parent().hide();
				}else{
					jQuery("input[name='member_down'].login").parent().show();
				}
			});
		</script>
	</tr>
<?php }


add_action( 'admin_menu', 'erphpdown_create_metabox' );
add_action( 'save_post', 'erphpdown_save_metabox' );

function erphpdown_create_metabox() {
	$erphp_post_types = get_option('erphp_post_types');
	$args = array(
		'public'   => true,
	);
	$post_types = get_post_types($args);
	foreach ( $post_types  as $post_type ) {
		if($erphp_post_types){
			if(in_array($post_type,$erphp_post_types)) add_meta_box( 'erphpdown-postmeta-box','ErphpDown属性', 'erphpdown_show_metabox', $post_type, 'normal', 'high' );
		}
	}
	
}

function erphpdown_save_metabox( $post_id ) {

	$meta_boxes = array_merge( erphpdown_metaboxs() );
	foreach ( $meta_boxes as $meta_box ) :
		if($meta_box['type'] == 'erphpcheckbox'){

			if ( isset($_POST['start_down_input_name']) && (!wp_verify_nonce( $_POST['start_down_input_name'], plugin_basename( __FILE__ ) ) || !wp_verify_nonce( $_POST['start_see_input_name'], plugin_basename( __FILE__ ) ) || !wp_verify_nonce( $_POST['start_see2_input_name'], plugin_basename( __FILE__ ) ) || !wp_verify_nonce( $_POST['start_down2_input_name'], plugin_basename( __FILE__ ) )) )
				return $post_id;
			if ( isset($_POST['post_type']) && ('page' == $_POST['post_type'] && !current_user_can( 'edit_page', $post_id )) )
				return $post_id;
			elseif ( isset($_POST['post_type']) && ('post' == $_POST['post_type'] && !current_user_can( 'edit_post', $post_id )) )
				return $post_id;

			if(isset($_POST['start_down'])){
				$data = stripslashes( $_POST['start_down'] );
				$data1 = '';$data2='';$data3='';$data5='';
				if($data == '1') $data1 = 'yes';
				if($data == '2') $data2 = 'yes';
				if($data == '3') $data3 = 'yes';
				if($data == '5') $data5 = 'yes';
				update_post_meta( $post_id, 'start_down', $data1 );
				update_post_meta( $post_id, 'start_see', $data2 );
				update_post_meta( $post_id, 'start_see2', $data3 );
				update_post_meta( $post_id, 'start_down2', $data5 );
			}
		}else{
			if (isset($_POST[$meta_box['name'] . '_input_name']) && !wp_verify_nonce( $_POST[$meta_box['name'] . '_input_name'], plugin_basename( __FILE__ ) ) )
				return $post_id;
			if ( isset($_POST['post_type']) && ('page' == $_POST['post_type'] && !current_user_can( 'edit_page', $post_id )) )
				return $post_id;
			elseif ( isset($_POST['post_type']) && ('post' == $_POST['post_type'] && !current_user_can( 'edit_post', $post_id )) )
				return $post_id;

			if(isset($_POST[$meta_box['name']])){
				$data = stripslashes( $_POST[$meta_box['name']] );
				if ( get_post_meta( $post_id, $meta_box['name'] ) == '' )
					add_post_meta( $post_id, $meta_box['name'], $data, true );
				elseif ( $data != get_post_meta( $post_id, $meta_box['name'], true ) )
					update_post_meta( $post_id, $meta_box['name'], $data );
				elseif ( $data == '' )
					delete_post_meta( $post_id, $meta_box['name'], get_post_meta( $post_id, $meta_box['name'], true ) );
			}
		}


	endforeach;
}