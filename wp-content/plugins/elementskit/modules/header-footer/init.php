<?php
namespace ElementsKit\Modules\Header_Footer;

defined( 'ABSPATH' ) || exit;

class Init{

	public $dir;

	public $url;

    public function __construct(){

        // get current directory path
        $this->dir = dirname(__FILE__) . '/';

        // get current module's url
		$this->url = \ElementsKit::plugin_url() . 'modules/header-footer/';

		// enqueue scripts
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_styles'] );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_scripts'] );

		// include all necessary files
		$this->include_files();

		add_action('admin_footer', [$this, 'modal_view']);

		Cpt_Hooks::instance();
		Activator::instance();
	}

	public function include_files(){
		include_once $this->dir . 'cpt.php';
		include_once $this->dir . 'cpt-api.php';
	}

	public function modal_view(){
		$screen = get_current_screen();
		if($screen->id == 'edit-elementskit_template'){
			include_once $this->dir . 'views/modal-editor.php';
		}
	}

	public function enqueue_styles() {
		$screen = get_current_screen();
		if($screen->id == 'edit-elementskit_template'){
			wp_enqueue_style( 'select2', \ElementsKit::plugin_url() . 'assets/libs/select2/select2.min.css', [], \ElementsKit::version() );
			wp_enqueue_style( 'elementskit-menu-admin-style', $this->url . 'assets/css/admin-style.css', [], \ElementsKit::version() );
		}
	}

	public function enqueue_scripts(){
		$screen = get_current_screen();
		if($screen->id == 'edit-elementskit_template'){
			wp_enqueue_script( 'select2-defer', \ElementsKit::plugin_url() . 'assets/libs/select2/select2.min.js', [ 'jquery' ], \ElementsKit::version(), true );
			wp_enqueue_script( 'elementskit-menu-admin-script-defer', $this->url . 'assets/js/elementskit-menu-admin.js', [ 'jquery', 'select2-defer' ], \ElementsKit::version(), true );
		}
	}
}
