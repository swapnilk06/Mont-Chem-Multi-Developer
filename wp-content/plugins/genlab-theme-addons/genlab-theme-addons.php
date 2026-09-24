<?php 
/*
Plugin Name:  Genlab Theme Addons
Plugin URI:   https://awaikenthemes.com
Description:  This plugin is intended for use with the genlab theme.
Version:      1.0.0
Author:       Awaiken Technology
Author URI:   https://awaiken.com
License:      GNU General Public License v3 or later.
License URI:  https://www.gnu.org/licenses/gpl-3.0.html
Text Domain:  genlab-theme-addons
Domain Path:  /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'GENLAB_ADDONS_URL', plugins_url( '/', __FILE__ ) );
define( 'GENLAB_ADDONS_PATH', plugin_dir_path( __FILE__ ) );
define( 'GENLAB_PLUGIN_VERSION', '1.0.0' );


// Load translation.
add_action( 'init', 'genlab_i18n' );

/**
 * Load the plugin text domain for translation.
 *
 * @since    1.0.0
 */
function genlab_i18n() {
	load_plugin_textdomain( 'genlab-theme-addons' );
}

/* Allow SVG upload */
add_filter( 'wp_check_filetype_and_ext', function( $data, $file, $filename, $mimes ) {

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
      'ext' => $filetype['ext'],
      'type' => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function genlab_allow_svg_upload( $mimes ) {
  $mimes['svg'] = 'image/svg+xml';
  $mimes['svgz'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'genlab_allow_svg_upload' );

require GENLAB_ADDONS_PATH . 'includes/secondary-image.php';

/*
* CaseStudy CPT
*/
if(!class_exists('Awaiken_Casestudy')) { 
	class Awaiken_Casestudy {

		const CPT_CASESTUDY_SLUG = 'awaiken-casestudy';
		const TAXONOMY_CASESTUDY_CATEGORY_SLUG = 'awaiken-casestudy-category';

		public function register_data() {

			//Case Study Post Type 
			$labels = [
				'name' => esc_html_x( 'Case Study', 'Case Study', 'genlab-theme-addons' ),
				'singular_name' => esc_html_x( 'Case Study', 'Case Study', 'genlab-theme-addons' ),
				'menu_name' => esc_html_x( 'Case Study', 'Case Study', 'genlab-theme-addons' ),
				'name_admin_bar' => esc_html__( 'Case Study Item', 'genlab-theme-addons' ),
				'archives' => esc_html__( 'Case Study Item Archives', 'genlab-theme-addons' ),
				'parent_item_colon' => esc_html__( 'Parent Item:', 'genlab-theme-addons' ),
				'all_items' => esc_html__( 'All Items', 'genlab-theme-addons' ),
				'add_new_item' => esc_html__( 'Add New Case Study', 'genlab-theme-addons' ),
				'add_new' => esc_html__( 'Add New', 'genlab-theme-addons' ),
				'new_item' => esc_html__( 'New Case Study', 'genlab-theme-addons' ),
				'edit_item' => esc_html__( 'Edit Case Study', 'genlab-theme-addons' ),
				'update_item' => esc_html__( 'Update Case Study', 'genlab-theme-addons' ),
				'view_item' => esc_html__( 'View Case Study', 'genlab-theme-addons' ),
				'search_items' => esc_html__( 'Search Case Study', 'genlab-theme-addons' ),
				'not_found' => esc_html__( 'Not found', 'genlab-theme-addons' ),
				'not_found_in_trash' => esc_html__( 'Not found in Trash', 'genlab-theme-addons' ),
				'featured_image' => esc_html__( 'Featured Image', 'genlab-theme-addons' ),
				'set_featured_image' => esc_html__( 'Set featured image', 'genlab-theme-addons' ),
				'remove_featured_image' => esc_html__( 'Remove featured image', 'genlab-theme-addons' ),
				'use_featured_image' => esc_html__( 'Use as featured image', 'genlab-theme-addons' ),
				'insert_into_item' => esc_html__( 'Insert into Case Study', 'genlab-theme-addons' ),
				'uploaded_to_this_item' => esc_html__( 'Uploaded to this Case Study', 'genlab-theme-addons' ),
				'items_list' => esc_html__( 'Items list', 'genlab-theme-addons' ),
				'items_list_navigation' => esc_html__( 'Items list navigation', 'genlab-theme-addons' ),
				'filter_items_list' => esc_html__( 'Filter items list', 'genlab-theme-addons' ),
			];

			$casestudy_slug = apply_filters( 'awaiken_casestudy_slug', 'casestudy' );

			$rewrite = [
				'slug' => $casestudy_slug,
				'with_front' => false,
			];

			$args = [
				'labels' => $labels,
				'public' => true,
				'menu_position' => 25,
				'menu_icon' => 'dashicons-analytics',
				'capability_type' => 'post',
				'supports' => [ 'title', 'editor', 'thumbnail', 'author', 'excerpt', 'comments', 'revisions', 'page-attributes', 'custom-fields', 'elementor' ],
				'has_archive' => true,
				'rewrite' => $rewrite,
			];

			register_post_type( self::CPT_CASESTUDY_SLUG, $args );

			// Categories
			$casestudy_category_slug = apply_filters( 'awaiken_casestudy_category_slug', 'casestudy-category' );

			$rewrite = [
				'slug' => $casestudy_category_slug,
				'with_front' => false,
			];

			$args = [
				'hierarchical' => true,
				'show_ui' => true,
				'show_in_nav_menus' => false,
				'show_admin_column' => true,
				'labels' => $labels,
				'rewrite' => $rewrite,
				'public' => true,
				'labels' => [
					'name' => esc_html_x( 'Categories', 'Case Study', 'genlab-theme-addons' ),
					'singular_name' => esc_html_x( 'Category', 'Case Study', 'genlab-theme-addons' ),
					'all_items' => esc_html_x( 'All Categories', 'Case Study', 'genlab-theme-addons' ),
				],
			];
			register_taxonomy( self::TAXONOMY_CASESTUDY_CATEGORY_SLUG, self::CPT_CASESTUDY_SLUG, $args );
		}

		public function __construct() {
			add_action( 'init', [ $this, 'register_data' ], 1 );
		}
	}
	/**
	 * initialize 
	 */
	$Awaiken_Casestudy = new Awaiken_Casestudy();
}