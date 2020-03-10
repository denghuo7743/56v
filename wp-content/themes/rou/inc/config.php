<?php  
add_action('wp_head','begin_width');
add_shortcode('reply','reply_read');
add_shortcode('pass','reply_password');
add_shortcode('password','secret');
add_shortcode('login','login_to_read');
add_shortcode('img','gallery');
add_shortcode('slide','image');
add_shortcode('file','button_a');
add_shortcode('button','button_b');
add_shortcode('url','button_url');
add_shortcode('fieldset','fieldset_label');
add_shortcode('videos','my_videos');
add_action('wp_ajax_nopriv_zm_ding','begin_ding');
add_action('wp_ajax_zm_ding','begin_ding');
add_shortcode('s','show_more');
add_shortcode('p','section_content');
add_shortcode('ad','post_ad');
add_filter('post_class','post_classes');
add_filter('comments_popup_link_attributes','nofollow_comments_popup_link');
add_action('init','begin_smilies',5);
if ((current_user_can('edit_posts') && current_user_can('edit_pages'))) {add_action('media_buttons','begin_select',11);
}add_action('admin_head','begin_button');
add_action('save_post','clear_archives');
add_filter('user_contactmethods','begin_user_contact');
add_filter('protected_title_format','change_protected_title_prefix');
add_filter('esc_html','begin_post_format');
add_filter('comment_class','remove_comment_body_author_class');
add_filter('body_class','remove_comment_body_author_class');
add_action('wp_head','begin_color');
add_action('wp_head','modify_css');
add_action('wp_head','begin_thumbnail_width');
add_action('wp_head','zm_meta_left');
add_action('admin_init','ssid_add');
add_action('wp_login','user_last_login');
add_action('admin_head','spces_code_plugin');
add_action('admin_init','tinymce_button');
$add_domain=$_SERVER['SERVER_NAME'];
$is_allow=false;
$url=trim($_SERVER['SERVER_NAME']);
$allow_domain=explode(',',$add_domain);
require(get_template_directory().'/inc/load.php');
foreach($allow_domain as $value){$value=trim($value);
$tmparr=explode($value,$url);
}if (!$is_allow) {exit('你个渣渣知更鸟大傻逼，还TM加恶意代码，我让你写数据库改数据库！');
}
require(get_template_directory().'/inc/widgets.php');
require(get_template_directory().'/inc/comment-template.php');
require(get_template_directory().'/inc/my-field.php');
require(get_template_directory().'/inc/notify.php');
require(get_template_directory().'/inc/meta-boxs.php');
if (zm_get_option('down_root')) {require(get_template_directory().'/inc/down.php');
} else {require(get_template_directory().'/inc/download.php');
}require(get_template_directory().'/inc/post-taxonomy.php');
require(get_template_directory().'/inc/modify.php');
require(get_template_directory().'/inc/inc.php');
if (zm_get_option('no_products')) {require(get_template_directory().'/inc/show-meta.php');
}if (zm_get_option('qt')) {require(get_template_directory().'/inc/qaptcha.php');
}if (zm_get_option('auto_tags')) {add_action('save_post','auto_tags');
}if (zm_get_option('page_html')) {add_action('init','html_page_permalink',-1);
}if (zm_get_option('login_link')) {add_action('login_enqueue_scripts','login_protect');
}if (zm_get_option('save_image')) {require(get_template_directory().'/inc/save-image.php');
}if (zm_get_option('search_title')) {add_filter('posts_search','wpse_11826_search_by_title',10,2);
}if (zm_get_option('scroll')) {add_action('wp_footer','ajax_scroll_js',100);
}if (zm_get_option('comment_scroll')) {add_action('wp_footer','ajax_c_scroll_js',100);
}if (zm_get_option('custum_font')) {add_filter('tiny_mce_before_init','custum_font_family');
}if (zm_get_option('first_avatar')) {require(get_template_directory().'/inc/first-letter-avatar.php');
}
function begin_title(){get_template_part('inc/title');
}
function post_classes($classes){$classes[]='vww';
return $classes;
}
function type_breadcrumb(){get_template_part('/inc/type-navigation');
}
function setTitle(){$term=get_term_by('slug',get_query_var('term'),get_query_var('taxonomy'));
$title=$term->name;
echo '$title';
}
