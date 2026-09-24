<?php
namespace ElementsKit\Modules\Marquee;

defined( 'ABSPATH' ) || exit;

class Init {
	private $dir;
	private $url;

	public function __construct() {
		// get current directory path
		$this->dir = plugin_dir_path(__FILE__);

		// get current module's url
		$this->url = \ElementsKit::plugin_url() . 'modules/marquee/';

		// Register frontend scripts (also fires inside the editor preview iframe)
		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_frontend_scripts' ], 20 );

		// include all necessary files
		$this->include_files();

		// calling the marquee controls
		new \Elementor\ElementsKit_Extend_Marquee();
	}
	
	public function include_files(){
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
			'scripts' => [
				'elementskit-marquee' => [
					'src'          => $this->url . 'assets/js/elementskit-marquee.js',
					'version'      => \ElementsKit::version(),
					'dependencies' => [ 'jquery', 'elementor-frontend' ],
					'in_footer'    => true,
				],
			],
			'styles' => [
				'elementskit-marquee' => [
					'src'          => $this->url . 'assets/css/elementskit-marquee.css',
					'version'      => \ElementsKit::version(),
					'dependencies' => [],
				],
			]
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
				wp_register_script(
					$handle,
					$script['src'],
					$script['dependencies'] ?? [],
					$script['version'] ?? \ElementsKit::version(),
					$script['in_footer'] ?? true
				);
			}
		}

		// Add to Elementor's assets loader if available
		if ( $this->is_assets_loader_exist() && $assets ) {
			\Elementor\Plugin::$instance->assets_loader->add_assets($assets);
		}
	}
}
