<?php
if (zm_get_option('check_admin')) {
    if (!is_user_logged_in()) {
        add_filter('preprocess_comment', 'usercheck');
    }
}
if (zm_get_option('no_gallery')) {
    add_action('widgets_init', create_function('', 'register_widget( "img_widget" );'));
}
if (zm_get_option('no_videos')) {
    add_action('widgets_init', create_function('', 'register_widget( "video_widget" );'));
}
if (zm_get_option('no_tao')) {
    add_action('widgets_init', create_function('', 'register_widget( "tao_widget" );'));
}
if (zm_get_option('no_products')) {
    add_action('widgets_init', create_function('', 'register_widget( "show_widget" );'));
}
if (zm_get_option('qq_info')) {
    require get_template_directory() . '/inc/qq-user.php';
}
if (zm_get_option('favorite_p')) {
    require get_template_directory() . '/inc/favorite/favorite-posts.php';
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