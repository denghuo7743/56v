<?php
require get_template_directory() . '/inc/function.php';
if(get_option('upload_path')=='wp-content/uploads' || get_option('upload_path')==null) {
update_option('upload_path',WP_CONTENT_DIR.'/uploads');
}
//WordPress 新用户注册随机数学验证码
function add_security_question_fields() {
//获取两个随机数, 范围 0~9
$num1=rand(0,9);
$num2=rand(0,9);
//最终网页中的具体内容
echo "<p><label for='math' class='small'>验证码：$num1 + $num2 = ? </label><input type='text' name='sum' class='input' value='' size='25'>"
."<input type='hidden' name='num1' value='$num1'>"
."<input type='hidden' name='num2' value='$num2'></p>";}
add_action('register_form','add_security_question_fields');
add_action( 'register_post', 'add_security_question_validate', 10, 3 );
function add_security_question_validate( $sanitized_user_login, $user_email, $errors) {
$sum=$_POST['sum'];//用户提交的计算结果
switch($sum){
//得到正确的计算结果则直接跳出
case $_POST['num1']+$_POST['num2']:break;
//未填写结果时的错误讯息
case null:wp_die('错误：请输入验证码！');break;
//计算错误时的错误讯息
default:wp_die('错误：验证码错误,请重试！');}}
add_action( 'add_security_question','register_form' );

//防镜像代码
add_action('wp_footer','deny_mirrored_websites');
function deny_mirrored_websites(){
    $currentDomain = 'www." + "wu6v." + "com'; //此处自行拆分一下自己的域名即可
    echo '<img style="display:none" src=" " onerror=\'this.onerror=null;var str1="'.$currentDomain.'";str2="docu"+"ment.loca"+"tion.host";str3=eval(str2);if( str1!=str3 ){ do_action = "loca" + "tion." + "href = loca" + "tion.href" + ".rep" + "lace(docu" +"ment"+".loca"+"tion.ho"+"st," + "\"' . $currentDomain .'\"" + ")";eval(do_action) }\' />';
}
//将所有超链接改为相对模式
if(!is_admin()){
       ob_start("rewrite_urls");
     }
function rewrite_urls($buffer){
	$buffer= preg_replace('/("|\')http(s|):\/\/([^"\']*?)'.$_SERVER["HTTP_HOST"].'/i','$1//$3'.$_SERVER["HTTP_HOST"],$buffer);
	return $buffer;
}
