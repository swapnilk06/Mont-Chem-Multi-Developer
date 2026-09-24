<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Theme updater admin page and functions.
 *
 * @package EDD Sample Theme
 */

class AWAIKEN_Theme_Updater_Admin {

	/**
	 * Variables required for the theme updater
	 *
	 * @since 1.0.0
	 * @type string
	 */
	protected $remote_api_url = null;
	protected $theme_slug     = null;
	protected $version        = null;
	protected $author         = null;
	protected $download_id    = null;
	protected $renew_url      = null;
	protected $strings        = null;
	protected $item_name      = '';
	protected $beta           = false;
	protected $item_id        = null;

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $config = array(), $strings = array() ) {

		$config = wp_parse_args(
			$config,
			array(
				'remote_api_url' => '',
				'theme_slug'     => '',
				'item_name'      => '',
				'license'        => '',
				'version'        => '',
				'author'         => '',
				'download_id'    => '',
				'renew_url'      => '',
				'beta'           => false,
				'item_id'        => '',
			)
		);

		/**
		 * Fires after the theme $config is setup.
		 *
		 * @since 1.0.0
		 *
		 * @param array $config Array of EDD SL theme data.
		 */
		do_action( 'post_edd_sl_theme_updater_setup', $config );

		// Set config arguments
		$this->remote_api_url = $config['remote_api_url'];
		$this->item_name      = $config['item_name'];
		$this->theme_slug     = sanitize_key( $config['theme_slug'] );
		$this->version        = $config['version'];
		$this->author         = $config['author'];
		$this->download_id    = $config['download_id'];
		$this->renew_url      = $config['renew_url'];
		$this->beta           = $config['beta'];
		$this->item_id        = $config['item_id'];

		// Populate version fallback
		if ( '' === $config['version'] ) {
			$theme         = wp_get_theme( $this->theme_slug );
			$this->version = $theme->get( 'Version' );
		}

		// Strings passed in from the updater config
		$this->strings = $strings;

		add_action( 'init', array( $this, 'updater' ) );
		add_action( 'admin_init', array( $this, 'register_option' ) );
		add_action( 'admin_init', array( $this, 'license_action' ) );
		add_action( 'admin_menu', array( $this, 'license_menu' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'update_option_' . $this->theme_slug . '_license_key', array( $this, 'activate_license' ), 10, 2 );
		add_filter( 'http_request_args', array( $this, 'disable_wporg_request' ), 5, 2 );

	}

	/**
	 * Creates the updater class.
	 *
	 * since 1.0.0
	 */
	public function updater() {

		// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
		$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
		if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
			return;
		}

		/* If there is no valid license key status, don't allow updates. */
		if ( 'valid' !== get_option( $this->theme_slug . '_license_key_status', false ) ) {
			return;
		}

		if ( ! class_exists( 'AWAIKEN_Theme_Updater' ) ) {
			// Load our custom theme updater
			include dirname( __FILE__ ) . '/theme-updater-class.php';
		}

		new AWAIKEN_Theme_Updater(
			array(
				'remote_api_url' => $this->remote_api_url,
				'version'        => $this->version,
				'license'        => trim( get_option( $this->theme_slug . '_license_key' ) ),
				'item_name'      => $this->item_name,
				'author'         => $this->author,
				'beta'           => $this->beta,
				'item_id'        => $this->item_id,
				'theme_slug'     => $this->theme_slug,
			),
			$this->strings
		);
	}
	
	/**
	 * Adds a notices for the theme license.
	 *
	 * since 1.0.0
	 */
	public function admin_notices() {
		// Check if the current page is the "license" page (either location)
		if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array( $this->theme_slug . '-license', 'at-license' ), true ) ) {
			return;
		}
		
		$status  = get_option( $this->theme_slug . '_license_key_status', false );
		
		if ( 'valid' === $status ) {
			return;
		}
		
		$strings = $this->strings;

		$show_dashboard = apply_filters( 'at_show_dashboard', true );
			$license_url    = $show_dashboard
				? admin_url( 'admin.php?page=at-license' )
				: admin_url( 'themes.php?page=' . $this->theme_slug . '-license' );

			echo '<div class="notice notice-info is-dismissible at-license-activation-info">';
			echo '<p><strong>' . $strings['activate-license-info'] . '</strong></p>';
			echo '<p><a href="' . esc_url( $license_url ) . '">' . $strings['activate-license'] . '</a></p>';
			echo '</div>';
		
	}

	/**
	 * Adds a menu item for the theme license under the appearance menu.
	 *
	 * since 1.0.0
	 */
	public function license_menu() {

		$strings = $this->strings;

		$show_dashboard = apply_filters( 'at_show_dashboard', true );

		if ( $show_dashboard ) {
			// Dashboard is visible: license lives under the Theme Dashboard menu, hide it from Appearance.
			add_submenu_page( // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
				'at-dashboard',
				$strings['theme-license'],
				$strings['theme-license'],
				'manage_options',
				'at-license',
				array( $this, 'license_page_dashboard' )
			);
		} else {
			// Dashboard is hidden (white-label): expose license under Appearance instead.
			add_theme_page(
				$strings['theme-license'],
				$strings['theme-license'],
				'manage_options',
				$this->theme_slug . '-license',
				array( $this, 'license_page' )
			);
		}

	}

	/**
	 * Outputs the license page using the Theme Dashboard layout (header + form + footer).
	 */
	public function license_page_dashboard() {
		global $at_theme_dashboard;
		if ( $at_theme_dashboard ) {
			$at_theme_dashboard->license_page_template();
		}
	}

	/**
	 * Outputs the markup used on the Appearance > Theme License page.
	 *
	 * since 1.0.0
	 */
	public function license_page() {
		$strings = $this->strings;
		?>
		<div class="wrap">
			<h2><?php echo esc_html( $strings['theme-license'] ); ?></h2>
			<?php $this->render_license_form( 'appearance' ); ?>
		</div>
		<?php
	}

	/**
	 * Renders only the license form — used both by the Appearance page and the dashboard layout.
	 *
	 * @param string $origin 'appearance' or 'dashboard'
	 */
	public function render_license_form( $origin = 'appearance' ) {

		$strings = $this->strings;

		$this->render_api_error_notice();

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );

		if ( ! $license ) {
			$message = sprintf( $strings['enter-key'], 'https://docs.awaikenthemes.com/how-to-get-license-key/' );
		} else {
			if ( ! get_transient( $this->theme_slug . '_license_message', false ) ) {
				set_transient( $this->theme_slug . '_license_message', $this->check_license(), ( 60 * 60 * 24 ) );
			}
			$message = get_transient( $this->theme_slug . '_license_message' );
		}
		
		$status  = get_option( $this->theme_slug . '_license_key_status', false );
		
		?>
		<form method="post" class="license-form" action="options.php">

			<?php settings_fields( $this->theme_slug . '-license' ); ?>
			<input type="hidden" name="at_license_origin" value="<?php echo esc_attr( $origin ); ?>" />

			<table class="form-table">
				<tbody>

					<tr valign="top">
						<th scope="row" valign="top">
							<?php echo esc_html( $strings['license-key'] ); ?>
						</th>
						<td>
							<input id="<?php echo esc_attr( $this->theme_slug ); ?>_license_key" name="<?php echo esc_attr( $this->theme_slug ); ?>_license_key" type="text" class="regular-text" value="<?php echo esc_attr( $license ); ?>" />
							<p class="description">
								<?php echo wp_kses_post( $message ); ?>
								<?php
								$status_msg = ( $status === 'pending-active' ) ? $strings['pending-active'] : '';
								echo esc_html( $status_msg );
								?>
							</p>
						</td>
					</tr>

					<?php if ( $license && in_array( $status, array( 'valid', 'pending-active', 'inactive', 'site_inactive' ), true ) ) { ?>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php echo esc_html( $strings['license-action'] ); ?>
						</th>
						<td>
							<?php
							wp_nonce_field( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' );
							if ( 'valid' === $status ) {
								?>
								<input type="submit" class="button-secondary" name="<?php echo esc_attr( $this->theme_slug ); ?>_license_deactivate" value="<?php echo esc_attr( $strings['deactivate-license'] ); ?>"/>
								<?php
							} elseif ( in_array( $status, array( 'pending-active', 'inactive', 'site_inactive' ), true ) ) {
								?>
								<input type="submit" class="button-secondary" name="<?php echo esc_attr( $this->theme_slug ); ?>_license_activate" value="<?php echo esc_attr( $strings['activate-license'] ); ?>"/>
								<?php
							}
							?>
						</td>
					</tr>
					<?php } ?>

				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
		<?php
	}

	/**
	 * Registers the option used to store the license key in the options table.
	 *
	 * since 1.0.0
	 */
	public function register_option() {
		register_setting(
			$this->theme_slug . '-license',
			$this->theme_slug . '_license_key',
			array( $this, 'sanitize_license' )
		);

	}

	/**
	 * Sanitizes the license key.
	 *
	 * since 1.0.0
	 *
	 * @param string $new License key that was submitted.
	 * @return string $new Sanitized license key.
	 */
	public function sanitize_license( $new ) {

		$old = get_option( $this->theme_slug . '_license_key' );

		if ( $old && $old !== $new ) {
			// New license has been entered, so must reactivate
			delete_option( $this->theme_slug . '_license_key_status' );
			delete_transient( $this->theme_slug . '_license_message' );
		}

		return $new;
	}
	
	/**
	 * Makes a call to the API.
	 *
	 * @since 1.0.0
	 *
	 * @param array $api_params to be used for wp_remote_get.
	 * @return array $response decoded JSON response.
	 */
	public function get_api_response( $api_params ) {

		$verify_ssl = (bool) apply_filters( 'awaiken_sl_api_request_verify_ssl', true );
		$response   = wp_remote_post(
			$this->remote_api_url,
			array(
				'timeout'   => 15,
				'sslverify' => $verify_ssl,
				'body'      => $api_params,
			)
		);
		
		//print_r($response);
		
		if ( is_wp_error( $response ) ) {
			// cURL-level failure (timeout, DNS, SSL) — customer's server can't reach us.
			set_transient( $this->theme_slug . '_license_api_error', 'client_error', 60 );
		} elseif ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			// Our server returned a non-200 (e.g. 403, 500).
			set_transient( $this->theme_slug . '_license_api_error', 'server_error', 60 );
		} else {
			delete_transient( $this->theme_slug . '_license_api_error' );
		}
		
		return $response;
	}

	/**
	 * Renders a connection-error notice if the last API call failed.
	 * Called at the top of render_license_form() so it shows inline on the license page.
	 */
	private function render_api_error_notice() {
		$error = get_transient( $this->theme_slug . '_license_api_error' );
		if ( ! $error ) {
			return;
		}
		delete_transient( $this->theme_slug . '_license_api_error' );
		?>
		<div class="alf-box <?php echo  esc_attr( $error === 'client_error' ? 'alf-their-issue' : 'alf-our-issue' ); ?>" style="margin-bottom:16px;">
			<?php if ( $error === 'client_error' ) : ?>
				<h3>&#x1F7E0; <?php _e( 'Outbound Connection Blocked', 'genlab' ); ?></h3>
				<p><?php _e( 'Your server is unable to make an outbound connection to our server. This is usually caused by a firewall or network restriction on your hosting environment.', 'genlab' ); ?></p>
				<p><strong><?php _e( 'Please contact your hosting provider and share the following:', 'genlab' ); ?></strong></p>
				<ol>
					<li><?php _e( 'My WordPress site cannot make outbound HTTPS (cURL) connections to external URLs.', 'genlab' ); ?></li>
					<li><?php printf( esc_html__( 'Please whitelist or unblock the IP address: %s', 'genlab' ), '<code>35.213.158.242</code>' ); ?></li>
					<li><?php printf( __( 'Please whitelist outbound connections to: <code>%s</code>', 'genlab' ), esc_html( parse_url( $this->remote_api_url, PHP_URL_HOST ) ) ); ?></li>
					<li><?php _e( 'Error: cURL connection timed out or failed on port 443.', 'genlab' ); ?></li>
				</ol>
			<?php elseif ( $error === 'server_error' ) : ?>
				<h3>&#x1F7E0; <?php _e( 'License Server Error', 'genlab' ); ?></h3>
				<p><?php _e( 'Please try again in a few minutes. If the issue persists, please contact our support team at support@awaiken.com and include the details below.', 'genlab' ); ?></p>
				<ol>
					<li><?php _e( 'Your license key', 'genlab' ); ?></li>
					<li><?php _e( 'Your server IP address', 'genlab' ); ?></li>
					<li><?php _e( 'Your site URL', 'genlab' ); ?></li>
				</ol>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Builds a human-readable status message from a decoded EDD license API response object.
	 * Used by both activate_license() and check_license() to avoid duplicating logic.
	 *
	 * @param object $license_data Decoded JSON response from the EDD API.
	 * @return string
	 */
	private function build_license_message( $license_data ) {

		$strings    = $this->strings;
		$renew_link = '';
		$expires    = false;

		if ( isset( $license_data->expires ) && 'lifetime' !== $license_data->expires ) {
			$expires    = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) );
			$renew_link = '<a href="' . esc_url( $this->get_renewal_link() ) . '" target="_blank">' . $strings['renew'] . '</a>';
		} elseif ( isset( $license_data->expires ) && 'lifetime' === $license_data->expires ) {
			$expires = 'lifetime';
		}

		$site_count    = isset( $license_data->site_count )    ? $license_data->site_count    : '';
		$license_limit = isset( $license_data->license_limit ) ? $license_data->license_limit : '';

		if ( 0 === $license_limit ) {
			$license_limit = $strings['unlimited'];
		}

		switch ( $license_data->license ) {
			case 'valid':
				$message = $strings['license-key-is-active'] . ' ';
				if ( $expires && 'lifetime' !== $expires ) {
					$message .= sprintf( $strings['expires%s'], $expires ) . ' ';
				}
				if ( 'lifetime' === $expires ) {
					$message .= $strings['expires-never'] . ' ';
				}
				if ( $site_count && $license_limit ) {
					$message .= sprintf( $strings['%1$s/%2$-sites'], $site_count, $license_limit );
				}
				break;

			case 'expired':
				$message = $expires
					? sprintf( $strings['license-key-expired-%s'], $expires )
					: $strings['license-key-expired'];
				if ( $renew_link ) {
					$message .= ' ' . $renew_link;
				}
				break;

			case 'invalid':
			case 'invalid_item_id':
			case 'item_name_mismatch':
				$message = $strings['license-key-invalid'];
				break;

			case 'inactive':
				$message = $strings['license-is-inactive'];
				break;

			case 'disabled':
				$message = $strings['license-key-is-disabled'];
				break;

			case 'site_inactive':
				$message = $strings['site-is-inactive'];
				break;

			case 'error':
			default:
				$message = $strings['license-status-unknown'];
				break;
		}

		return $message;
	}

	/**
	 * Returns the URL of the license page based on where the form was submitted from.
	 */
	private function get_license_page_url() {
		$from_dashboard = isset( $_POST['at_license_origin'] ) && $_POST['at_license_origin'] === 'dashboard';
		return $from_dashboard
			? admin_url( 'admin.php?page=at-license' )
			: admin_url( 'themes.php?page=' . $this->theme_slug . '-license' );
	}

	public function activate_license() {

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		if ( empty( $license ) ) {
			return $this->strings['enter-key'];
		}

		// Data to send in our API request.
		$api_params = array(
			'edd_action'  => 'activate_license',
			'license'     => $license,
			'item_name'   => urlencode( $this->item_name ),
			'url'         => home_url(),
			'item_id'     => $this->item_id,
			'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
		);

		$response = $this->get_api_response( $api_params );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = $this->strings['error-generic'];
			}

			$base_url = $this->get_license_page_url();
			$redirect = add_query_arg(
				array(
					'sl_theme_activation' => 'false',
					'message'             => urlencode( $message ),
				),
				$base_url
			);

			wp_redirect( $redirect );
			exit();

		} else {


			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {

				switch ( $license_data->error ) {

					case 'expired':
						$message = sprintf(
							$this->strings['license-expired-on'],
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'disabled':
					case 'revoked':
						$message = $this->strings['license-key-is-disabled'];
						break;

					case 'missing':
						$message = $this->strings['license-key-invalid'];
						break;

					case 'missing_item_id':
					case 'key_mismatch':
					case 'invalid_item_id':
						$message = $this->strings['license-key-invalid'];
						break;

					case 'invalid':
					case 'site_inactive':
						$message = $this->strings['site-is-inactive'];
						break;

					case 'item_name_mismatch':
						$message = sprintf( $this->strings['item-mismatch'], $this->item_name );
						break;

					case 'no_activations_left':
						$message = $this->strings['activation-limit'];
						break;

					case 'missing_url':
					case 'bundle_activation_not_allowed':
						$message = $this->strings['error-generic'];
						break;

					default:
						$message = $this->strings['error-generic'];
						break;
				}

				if ( ! empty( $message ) ) {
					set_transient( $this->theme_slug . '_license_message', $message, ( 60 * 60 * 24 ) );
					wp_redirect( $this->get_license_page_url() );
					exit();
				}
			}
		}

		// $response->license will be either "valid" or "inactive"
		if ( $license_data && isset( $license_data->license ) ) {
			update_option( $this->theme_slug . '_license_key_status', $license_data->license );

			// Build and cache the status message from the activate response directly,
			// so render_license_form() does not fire a redundant check_license API call.
			$cached_message = $this->build_license_message( $license_data );
			set_transient( $this->theme_slug . '_license_message', $cached_message, ( 60 * 60 * 24 ) );
		}

		wp_redirect( $this->get_license_page_url() );
		exit();

	}

	/**
	 * Deactivates the license key.
	 *
	 * @since 1.0.0
	 */
	public function deactivate_license() {

		// Retrieve the license from the database.
		$license = trim( get_option( $this->theme_slug . '_license_key' ) );

		// Data to send in our API request.
		$api_params = array(
			'edd_action'  => 'deactivate_license',
			'license'     => $license,
			'item_name'   => rawurlencode( $this->item_name ),
			'url'         => home_url(),
			'item_id'     => $this->item_id,
			'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
		);

		$response = $this->get_api_response( $api_params );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = $this->strings['error-generic'];
			}

			$base_url = $this->get_license_page_url();
			$redirect = add_query_arg(
				array(
					'sl_theme_activation' => 'false',
					'message'             => urlencode( $message ),
				),
				$base_url
			);

			wp_redirect( $redirect );
			exit();

		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $license_data && $license_data->license === 'deactivated' ) {
			// Set status to inactive and cache the message directly — avoids a
			// redundant check_license() API call on the next page load.
			update_option( $this->theme_slug . '_license_key_status', 'inactive' );
			set_transient( $this->theme_slug . '_license_message', $this->strings['license-is-inactive'], ( 60 * 60 * 24 ) );
		} elseif ( $license_data && $license_data->license === 'failed' ) {
			set_transient( $this->theme_slug . '_license_message', $this->strings['error-generic'], ( 60 * 60 * 24 ) );
			wp_redirect( $this->get_license_page_url() );
			exit();
		}

		wp_redirect( $this->get_license_page_url() );
		exit();

	}

	/**
	 * Constructs a renewal link
	 *
	 * @since 1.0.0
	 */
	public function get_renewal_link() {

		// If a renewal link was passed in the config, use that
		if ( '' !== $this->renew_url ) {
			return $this->renew_url;
		}

		// If download_id was passed in the config, a renewal link can be constructed
		$license_key = trim( get_option( $this->theme_slug . '_license_key', false ) );
		if ( '' !== $this->download_id && $license_key ) {
			$url  = esc_url( $this->remote_api_url );
			$url .= '/checkout/?edd_license_key=' . urlencode( $license_key ) . '&download_id=' . urlencode( $this->download_id );
			return $url;
		}

		// Otherwise return the remote_api_url
		return $this->remote_api_url;

	}

	/**
	 * Checks if a license action was submitted.
	 *
	 * @since 1.0.0
	 */
	public function license_action() {

		if ( isset( $_POST[ $this->theme_slug . '_license_activate' ] ) ) {
			if ( check_admin_referer( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' ) ) {
				$this->activate_license();
			}
		}

		if ( isset( $_POST[ $this->theme_slug . '_license_deactivate' ] ) ) {
			if ( check_admin_referer( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' ) ) {
				$this->deactivate_license();
			}
		}
	}

	/**
	 * Checks if license is valid and gets expire date.
	 *
	 * @since 1.0.0
	 *
	 * @return string $message License status message.
	 */
	public function check_license() {

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		$strings = $this->strings;
		
		if ( empty( $license ) ) {
			return $strings['enter-key'];
		}
		
		$api_params = array(
				'edd_action'  => 'check_license',
				'license'     => $license,
				'item_name'   => rawurlencode( $this->item_name ),
				'url'         => home_url(),
				'item_id'     => $this->item_id,
				'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
			);

			$response = $this->get_api_response( $api_params );
		
		// If the API call failed, get_api_response() already set the error transient.
		// Return a neutral message; render_api_error_notice() will show the detailed notice.
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return $strings['license-status-unknown'];
		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
					

			// If response doesn't include license data, return unknown.
			if ( ! isset( $license_data->license ) ) {
				return $strings['license-status-unknown'];
			}

			update_option( $this->theme_slug . '_license_key_status', $license_data->license );

			return $this->build_license_message( $license_data );
		}

	}

	/**
	 * Disable requests to wp.org repository for this theme.
	 *
	 * @since 1.0.0
	 */
	public function disable_wporg_request( $r, $url ) {

		// If it's not a theme update request, bail.
		if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
			return $r;
		}

		// Decode the JSON response
		$themes = json_decode( $r['body']['themes'] );

		// Remove the active parent and child themes from the check
		$parent = get_option( 'template' );
		$child  = get_option( 'stylesheet' );
		unset( $themes->themes->$parent );
		unset( $themes->themes->$child );

		// Encode the updated JSON response
		$r['body']['themes'] = json_encode( $themes );

		return $r;
	}

}

/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 */
function at_theme_admin_notices() {
	if ( isset( $_GET['sl_theme_activation'] ) && ! empty( $_GET['message'] ) ) {

		switch ( $_GET['sl_theme_activation'] ) {

			case 'false':
				$message = urldecode( $_GET['message'] );
				?>
				<div class="error">
					<p><?php echo wp_kses_post($message); ?></p>
				</div>
				<?php
				break;

			case 'true':
			default:
				break;

		}
	}
}
add_action( 'admin_notices', 'at_theme_admin_notices' );
