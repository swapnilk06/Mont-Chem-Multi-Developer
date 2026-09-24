<?php

namespace ElementsKit\Modules\Facebook_Messenger;

defined( 'ABSPATH' ) || exit;

use ElementsKit_Lite\Libs\Framework\Attr;

class Init {

	/**
	 * @var string - current directory path
	 */
	private $dir;

	/**
	 * @var string - current module's url
	 */
	private $url;


	public function __construct() {


		$this->dir = dirname(__FILE__) . '/';

		$this->url = \ElementsKit::plugin_url() . 'modules/facebook-messenger/';


		/**
		 * action hooks
		 */
		add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_style']);
		add_action('wp_footer', [$this, 'load_modules_script'], 100);

	}


	/**
	 * Get this module's saved settings
	 * @return array
	 */
	private function get_module_settings() {
		$data = Attr::instance()->utils->get_option('user_data', []);

		return empty($data['fbm_module']) ? [] : $data['fbm_module'];
	}


	/**
	 * The button is only rendered when a page id is configured and we are not
	 * inside the Elementor editor preview - assets follow the same condition.
	 * @return bool
	 */
	private function is_button_enabled() {
		$settings = $this->get_module_settings();

		if(empty($settings['pg_id'])) {
			return false;
		}

		return ! \Elementor\Plugin::$instance->preview->is_preview_mode();
	}


	public function enqueue_frontend_style() {
		if(! $this->is_button_enabled()) {

			return;
		}

		wp_enqueue_style(
			'elementskit-facebook-messenger',
			$this->url . 'assets/css/elementskit-facebook-messenger.css',
			[],
			\ElementsKit::version()
		);
	}


	public function load_modules_script() {
		if(! $this->is_button_enabled()) {

			return;
		}

		$settings       = $this->get_module_settings();
		$color          = empty($settings['txt_color']) ? '#000000' : $settings['txt_color'];
		$bg_color	    = empty($settings['bg_color']) ? '#2458ab' : $settings['bg_color'];
		?>

        <!-- Load Facebook SDK for JavaScript -->

		<script>
			function openMessengerTab() {
				let pageId = <?php echo intval($settings['pg_id']) ?> // Your Facebook Page ID
				let messengerUrl = "https://m.me/" + pageId;
				window.open(messengerUrl, "_blank"); // Opens in a new tab
			}
		</script>
		<button class="elementskit-messenger-button messenger-button" onclick="openMessengerTab()" style="--ekit-fbm-bg: <?php echo esc_attr($bg_color); ?>; --ekit-fbm-color: <?php echo esc_attr($color); ?>;">
			<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 50 50">
			<path d="M 25 2 C 12.347656 2 2 11.597656 2 23.5 C 2 30.007813 5.132813 35.785156 10 39.71875 L 10 48.65625 L 11.46875 47.875 L 18.6875 44.125 C 20.703125 44.664063 22.800781 45 25 45 C 37.652344 45 48 35.402344 48 23.5 C 48 11.597656 37.652344 2 25 2 Z M 25 4 C 36.644531 4 46 12.757813 46 23.5 C 46 34.242188 36.644531 43 25 43 C 22.835938 43 20.742188 42.6875 18.78125 42.125 L 18.40625 42.03125 L 18.0625 42.21875 L 12 45.375 L 12 38.8125 L 11.625 38.53125 C 6.960938 34.941406 4 29.539063 4 23.5 C 4 12.757813 13.355469 4 25 4 Z M 22.71875 17.71875 L 10.6875 30.46875 L 21.5 24.40625 L 27.28125 30.59375 L 39.15625 17.71875 L 28.625 23.625 Z"></path>
			</svg>
		</button>

		<?php
	}
}
