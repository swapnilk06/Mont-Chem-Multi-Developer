<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function genlab_admin_css() {
	wp_enqueue_style( 'theme-default-font-admin', genlab_slug_fonts_url(), array(), null );
	wp_enqueue_style( 'genlab-admin', GENLAB_THEME_URL . '/assets/css/admin.css', array(), GENLAB_THEME_VERSION );	
	
	}

// Hook the custom_admin_css function to the admin_enqueue_scripts action.
add_action('admin_enqueue_scripts', 'genlab_admin_css', 11);

add_action( 'in_admin_header', function() {
    global $wp_filter;
    if ( isset( $wp_filter['in_admin_header'] ) ) {
        foreach ( $wp_filter['in_admin_header']->callbacks as $priority => $callbacks ) {
            foreach ( $callbacks as $key => $callback ) {
                if ( is_array( $callback['function'] )
                    && is_object( $callback['function'][0] )
                    && method_exists( $callback['function'][0], 'render_banner_container' )
                ) {
                    remove_action( 'in_admin_header', $callback['function'], $priority );
                }
            }
        }
    }
}, 10 );
if ( apply_filters( 'at_show_dashboard', true ) ) {
	require_once GENLAB_THEME_DIR . '/inc/admin/class-dashboard.php';
}

