<?php
namespace ElementsKit\Widgets\Init;

defined( 'ABSPATH' ) || exit;

class Enqueue_Scripts{

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [$this, 'register_widget_assets'], 10 );
	}

	public function register_widget_assets() {
		// Registering Gsap js files
		wp_register_script( 'gsap', \ElementsKit::plugin_url() . 'assets/libs/gsap/js/gsap.js', array(),  \ElementsKit::version(), [ 'strategy' => 'defer', 'in_footer' => true ] );
		wp_register_script( 'gsap-scroll-trigger', \ElementsKit::plugin_url() . 'assets/libs/gsap/js/gsap-scroll-trigger.js', array(), \ElementsKit::version(), [ 'strategy' => 'defer', 'in_footer' => true ] );

		// chart js
		wp_register_script( 'chart-kit-js', \ElementsKit::plugin_url() . 'assets/libs/chart/js/chart.js', array('jquery'), \ElementsKit::version(), true );

		// TODO: Need to remove this script after 4.4.6 or later version, because it's already included in free plugin
		wp_register_script( 'ekit-core', \ElementsKit::widget_url() . 'init/assets/js/widgets/core-free.js', ['jquery', 'elementor-frontend'], \ElementsKit::version(), true );


		// Circle Menu Script
		wp_register_script( 'circle-menu', \ElementsKit::plugin_url() . 'assets/libs/circle-menu/js/circle-menu.min.js', ['jquery'], \ElementsKit::version(), true );

		// Registering Core js file
		wp_register_script(
			'ekit-pro-core',
			\ElementsKit::widget_url() . 'init/assets/js/widgets/core.js',
			array( 'jquery', 'elementor-frontend', 'ekit-core' ),
			\ElementsKit::version(),
			true
		);

		// Google Map widget scripts
		$user_data = \ElementsKit_Lite\Libs\Framework\Attr::instance()->utils->get_option('user_data', []);
		$gmap_api_key = !empty($user_data['google_map']) ? $user_data['google_map']['api_key'] : '';
		wp_register_script( 'ekit-google-map-api', 'https://maps.googleapis.com/maps/api/js?key=' . $gmap_api_key . '', array('jquery'), \ElementsKit::version(), [ 'strategy' => 'defer', 'in_footer' => true ] );
		wp_register_script( 'ekit-google-gmaps', \ElementsKit::plugin_url() . 'assets/libs/gmap/js/gmaps.min.js', array('jquery'), \ElementsKit::version(), [ 'strategy' => 'defer', 'in_footer' => true ] );
		wp_register_script( 'ekit-pro-google-map', \ElementsKit::widget_url() . 'init/assets/js/widgets/google-map.js', array('jquery', 'ekit-google-map-api', 'ekit-google-gmaps', 'ekit-pro-core'), \ElementsKit::version(), [ 'strategy' => 'defer', 'in_footer' => true ] );

		// table widget script
		wp_register_script( 'datatables', \ElementsKit::plugin_url() . 'assets/libs/datatables/js/datatables.min.js', array( 'jquery' ), \ElementsKit::version(), true );
		//table widget style
		wp_register_style( 'datatables', \ElementsKit::plugin_url() . 'assets/libs/datatables/css/datatables.min.css', array(), \ElementsKit::version() );
		wp_register_style( 'datatables-responsive', \ElementsKit::plugin_url() . 'assets/libs/datatables/css/responsive.dataTables.min.css', array(), \ElementsKit::version() );


		// register script for gallery, video gallery
		wp_register_script('gallery-filter', \ElementsKit::plugin_url() . 'assets/libs/gallery-filter/js/gallery-filter.js', array('jquery'), \ElementsKit::version(), true);
		wp_register_style('gallery-filter', \ElementsKit::plugin_url() . 'assets/libs/gallery-filter/css/gallery-filter.css', array(), \ElementsKit::version());

		// register script for gallery
		wp_register_script( 'tilt', \ElementsKit::plugin_url() . 'assets/libs/tilt/js/tilt.jquery.min.js', array('jquery'), \ElementsKit::version(), true );

		//Event calendar widget
		wp_register_script( 'full-calendar', \ElementsKit::plugin_url() . 'assets/libs/fullcalendar/index.global.min.js', [], \ElementsKit::version(), true );
		wp_register_script( 'full-calendar-locales', \ElementsKit::plugin_url() . 'assets/libs/fullcalendar/locales-all.global.min.js', [], \ElementsKit::version(), true );
		wp_register_script( 'full-calendar-google', \ElementsKit::plugin_url() . 'assets/libs/fullcalendar/google.global.min.js', [], \ElementsKit::version(), true );

		// Registering common css file for Pinterest Feed, Dribbble Feed and Behance Feed widget
		wp_register_style( 'ekit-feeds-common', \ElementsKit::widget_url() . 'init/assets/css/feeds-common.css', [], \ElementsKit::version() );

		// Registering common css file for Facebook Review and Yelp widget
		wp_register_style( 'ekit-social-reviews-common', \ElementsKit::widget_url() . 'init/assets/css/social-reviews-common.css', [], \ElementsKit::version() );

		// Registering layout responsive css file for Pinterest Feed, Facebook Feed, Behance Feed, Facebook Review and Yelp widget
		wp_register_style( 'ekit-layout-responsive', \ElementsKit::widget_url() . 'init/assets/css/layout-responsive.css', [], \ElementsKit::version() );

		// Registering widgets JS and CSS files
		$widget_list = \ElementsKit_Lite\Config\Widget_List::instance()->get_list( 'all' );
		foreach ( $widget_list as $widget_slug => $widget ) {
			if ( ( $widget['package'] ?? '' ) !== 'pro' ) {
				continue;
			}

			if ( ! empty( $widget['hasJS'] ) && file_exists( \ElementsKit::widget_dir() . 'init/assets/js/widgets/' . $widget_slug . '.js' ) ) {
				$handle = 'ekit-pro-' . $widget_slug;
				wp_register_script(
					$handle,
					\ElementsKit::widget_url() . 'init/assets/js/widgets/' . $widget_slug . '.js',
					array( 'ekit-pro-core' ),
					\ElementsKit::version(),
					true
				);
			}

			$css_file = $widget_slug . '.css';
			if ( file_exists( \ElementsKit::widget_dir() . 'init/assets/css/' . $css_file ) ) {
				wp_register_style(
					'ekit-pro-' . $widget_slug,
					\ElementsKit::widget_url() . 'init/assets/css/' . $css_file,
					[ 'ekit-widget-common' ],
					\ElementsKit::version()
				);
			}
		}
	}
}
