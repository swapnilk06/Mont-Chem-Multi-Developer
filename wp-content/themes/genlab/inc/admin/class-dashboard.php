<?php
/**
 *
 * Dashboard
 * @package Dashboard
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Dashboard class.
 */
class Genlab_Dashboard
{

    /**
     * The settings of page.
     *
     * @var array $settings The settings.
     */
    public $settings = array();

    /**
     * Constructor.
     */
    public function __construct()
    {

        if( ! is_admin() ) {
            return;
        }
		
		add_action('init', array($this, 'set_settings'));
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('admin_menu', array($this, 'add_menu_page'));
	}

    /**
     * Settings
     *
     * @param array $settings The settings.
     */
    public function set_settings()
    {
		$setting =	 array();
		$setting['hero_title']       = esc_html__( 'Welcome to Genlab', 'genlab' );
		$setting['hero_desc'] = esc_html__( 'Genlab has been successfully installed and is ready to use. Click the Getting Started tab to quickly get started with a pre-made template, or visit the Dashboard tab for a complete overview.', 'genlab' );
		

		// Promo.
		$setting['promo_link']   = '';
		$setting['doc_link']   = 'https://docs.awaikenthemes.com/';
		$setting['doc_troubleshooting_link']   = 'https://docs.awaikenthemes.com/category/troubleshooting-and-faqs/';
		$setting['doc_system_requirements_link']   = 'https://docs.awaikenthemes.com/system-requirements/';
		$setting['doc_license_key']   = 'https://docs.awaikenthemes.com/how-to-get-license-key/';
		
		$setting['site_settings_url']   = '';

		if ( defined( 'ELEMENTOR_VERSION' ) && is_callable( '\Elementor\Plugin::instance' ) ) {
			$kit_id = get_option( 'elementor_active_kit' );
			if ( $kit_id ) {
				$kit = \Elementor\Plugin::$instance->documents->get( $kit_id );
				
				if ( $kit && method_exists( $kit, 'get_edit_url' ) ) {
					$setting['site_settings_url'] = $kit->get_edit_url();
				}
			}
		}
        $this->settings = apply_filters('genlab_dashboard_settings', $setting);
    }

    /**
     * Add menu page
     */
    public function add_menu_page()
    {
	
		$capability = 'manage_options';
		$parent_menu_item = 'at-dashboard';

		if ( ! current_user_can( $capability ) ) {
			return;
		}

        add_menu_page( // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_menu_page
            __( 'Genlab Dashboard', 'genlab' ),
            __( 'Genlab', 'genlab' ),
            $capability,
            $parent_menu_item,
            '',
            GENLAB_THEME_URL.'/inc/admin/assets/images/at-icon.svg',
            2
        );

        add_submenu_page( // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
            $parent_menu_item,
            __( 'Genlab Dashboard', 'genlab' ),
            __( 'Dashboard', 'genlab' ),
            $capability,
            $parent_menu_item,
            [ $this, 'dashboard_page_template' ],
            0
        );
		
		add_submenu_page( // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
            $parent_menu_item,
            __( 'Getting Started', 'genlab' ),
            __( 'Getting Started', 'genlab' ),
            $capability,
            'at-getting-started',
            [ $this, 'getting_started_page_template' ],
        );


		
		add_submenu_page( // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
            $parent_menu_item,
            __( 'Troubleshooting', 'genlab' ),
            __( 'Troubleshooting', 'genlab' ),
            $capability,
            'at-troubleshooting',
            [ $this, 'troubleshooting_page_template' ],
        );

    }

    /**
     * This function will register scripts and styles for admin dashboard.
     *
     * @param string $page Current page.
     */
    public function admin_enqueue_scripts($hook)
    {
		wp_enqueue_style( 'bootstrap-5.3.2', GENLAB_THEME_URL . '/inc/admin/assets/css/bootstrap-grid.css', array(), GENLAB_THEME_VERSION );
        wp_enqueue_style( 'at-dashboard', GENLAB_THEME_URL . '/inc/admin/assets/css/dashboard.css', array(), GENLAB_THEME_VERSION);
    }
	
    public function dashboard_page_template() {
		
        $user_id             = get_current_user_id();
        $current_user        = wp_get_current_user();
        $notifications_count = 1;

        $theme_slug     = get_template();
        $theme_version  = wp_get_theme()->get( 'Version' );
		require_once GENLAB_THEME_DIR . '/inc/admin/views/dashboard.php';

	}
	
	public function getting_started_page_template() {
		require_once GENLAB_THEME_DIR . '/inc/admin/views/getting-started.php';
	}
	
	public function troubleshooting_page_template() {
		require_once GENLAB_THEME_DIR . '/inc/admin/views/troubleshooting.php';
	}

	public function license_page_template() {
		require_once GENLAB_THEME_DIR . '/inc/admin/views/license.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
	}

}

global $at_theme_dashboard;
$at_theme_dashboard = new Genlab_Dashboard();


add_action( 'wp_ajax_genlab_reapply_elementor_kit', 'genlab_reapply_elementor_kit_callback' );
function genlab_reapply_elementor_kit_callback() {

    // Security check.
    check_ajax_referer( 'genlab_reapply_kit_nonce', 'nonce' );

    // Check user capability.
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( [ 'message' => __( 'You do not have permission to perform this action.', 'genlab' ) ] );
    }

    // Get Elementor Kit.
    $kit_page = get_posts(
        [
            'post_type'              => 'elementor_library',
            'title'                  => 'Genlab - Default Kit',
            'post_status'            => 'all',
            'numberposts'            => 1,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
        ]
    );

    if ( ! empty( $kit_page ) ) {
        update_option( 'elementor_active_kit', $kit_page[0]->ID );
		if ( did_action( 'elementor/loaded' ) ) {
			// Regenerate CSS files
			\Elementor\Plugin::instance()->files_manager->clear_cache();
		}
		update_option( 'genlab_kit_applied', '1' );
        wp_send_json_success( [ 'message' => __( 'Style has been re-applied successfully! If you have any caching plugins active, please clear their cache to see the changes.', 'genlab' ) ] );
    } else {
        wp_send_json_error( [ 'message' => __( 'Style not found. Please contact our support team.', 'genlab' ) ] );
    }
}