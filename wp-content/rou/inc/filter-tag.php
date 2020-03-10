<?php
if (zm_get_option('filters_a')) {
	// 筛选标签A
	add_action( 'init', 'create_filtersa' );
	function create_filtersa() {
	$labels = array(
		'name' => '筛选A',
		'singular_name' => 'filtersa' ,
		'search_items' => '搜索标签',
		'edit_item' => '编辑标签',
		'update_item' => '更新标签',
		'add_new_item' => '添加新标签',
		);

	register_taxonomy( 'filtersa','post',array(
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'filtersa' ),
		'labels' => $labels
		));
	}
}

if (zm_get_option('filters_b')) {
	// 筛选标签B
	add_action( 'init', 'create_filtersb' );
	function create_filtersb() {
	$labels = array(
		'name' => '筛选B',
		'singular_name' => 'filtersb' ,
		'search_items' => '搜索标签',
		'edit_item' => '编辑标签',
		'update_item' => '更新标签',
		'add_new_item' => '添加新标签',
		);

	register_taxonomy( 'filtersb','post',array(
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'filtersb' ),
		'labels' => $labels
		));
	}
}

if (zm_get_option('filters_c')) {
	// 筛选标签C
	add_action( 'init', 'create_filtersc' );
	function create_filtersc() {
	$labels = array(
		'name' => '筛选C',
		'singular_name' => 'filtersc' ,
		'search_items' => '搜索标签',
		'edit_item' => '编辑标签',
		'update_item' => '更新标签',
		'add_new_item' => '添加新标签',
		);

	register_taxonomy( 'filtersc','post',array(
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'filtersc' ),
		'labels' => $labels
		));
	}
}