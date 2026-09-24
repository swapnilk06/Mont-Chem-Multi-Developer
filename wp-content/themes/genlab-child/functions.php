<?php

defined( 'ABSPATH' ) || exit;

function genlab_child_theme_enqueue_styles() {
	wp_enqueue_style( 'genlab-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'genlab-style' ), GENLAB_THEME_VERSION ); 
}
add_action( 'wp_enqueue_scripts', 'genlab_child_theme_enqueue_styles', 999 );
