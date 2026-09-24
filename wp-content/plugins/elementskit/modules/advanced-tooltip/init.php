<?php
namespace ElementsKit\Modules\Advanced_Tooltip;

defined( 'ABSPATH' ) || exit;

class Init {
	private $dir;
	private $url;

	private $lib_url;

	public function __construct() {
		global $post;

		// get current directory path
		$this->dir = dirname(__FILE__) . '/';

		// get current module's url
		$this->url = \ElementsKit::plugin_url() . 'modules/advanced-tooltip/';

		// get current module's lib url
		$this->lib_url = \ElementsKit::plugin_url() . 'assets/libs/tippy/';

		// Register frontend scripts
		add_action('elementor/frontend/before_register_scripts', [$this, 'register_frontend_scripts']);

		// include all necessary files
		$this->include_files();

		// calling the advanced tooltip
		new \Elementor\ElementsKit_Extend_Advanced_Tooltip();
	}

	public function include_files() {
		include $this->dir . 'extend-controls.php';
	}

	/**
	 * Check if Elementor's assets loader exists
	 * @return bool
	 */
	private function is_assets_loader_exist() {
		return ! ! \Elementor\Plugin::$instance->assets_loader;
	}

	/**
	 * Get assets configuration
	 * @return array
	 */
	private function get_assets_config() {

		return [
			'styles' => [
				'ekit-adv-tooltip' => [
					'src' => $this->lib_url . 'tippy-custom.css',
					'version' => \ElementsKit::version(),
					'dependencies' => [],
				],
			],
			'scripts' => [
				'popper-defer' => [
					'src' => $this->lib_url . 'popper.min.js',
					'version' => \ElementsKit::version(),
					'dependencies' => ['jquery'],
					'in_footer' => true,
				],
				'tippyjs-defer' => [
					'src' => $this->lib_url . 'tippy.min.js',
					'version' => \ElementsKit::version(),
					'dependencies' => ['jquery', 'popper-defer'],
					'in_footer' => true,
				],
				'ekit-adv-tooltip-defer' => [
					'src' => $this->url . 'assets/js/init.js',
					'version' => \ElementsKit::version(),
					'dependencies' => ['jquery', 'tippyjs-defer'],
					'in_footer' => true,
				],
			],
		];
	}

	/**
	 * Register frontend scripts and styles
	 */
	public function register_frontend_scripts() {
		$assets = $this->get_assets_config();

		// Register styles
		if (!empty($assets['styles'])) {
			foreach ($assets['styles'] as $handle => $style) {
				wp_register_style(
					$handle,
					$style['src'],
					$style['dependencies'] ?? [],
					$style['version'] ?? \ElementsKit::version(),
					$style['media'] ?? 'all'
				);
			}
		}

		// Register scripts
		if (!empty($assets['scripts'])) {
			foreach ($assets['scripts'] as $handle => $script) {
				$args = [];

				// Handle in_footer parameter
				if (isset($script['in_footer'])) {
					$args['in_footer'] = $script['in_footer'];
				}

				wp_register_script(
					$handle,
					$script['src'],
					$script['dependencies'] ?? [],
					$script['version'] ?? \ElementsKit::version(),
					$args
				);
			}
		}

		// Add to Elementor's assets loader if available
		if ( $this->is_assets_loader_exist() && $assets ) {
			\Elementor\Plugin::$instance->assets_loader->add_assets($assets);
		}
	}
}
