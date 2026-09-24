<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Easy Digital Downloads Theme Updater
 *
 * @package EDD Sample Theme
 */

// Includes the files needed for the theme updater
if ( ! class_exists( 'AWAIKEN_Theme_Updater_Admin' ) ) {
	include dirname( __FILE__ ) . '/theme-updater-admin.php';
}

// Loads the updater classes
global $awaiken_theme_updater;
$awaiken_theme_updater = new AWAIKEN_Theme_Updater_Admin(
	// Config settings
	array(
		'remote_api_url' => 'https://awaikenthemes.com', // Site where EDD is hosted
		'item_name'      => AWAIKEN_ITEM_NAME, // Name of theme
		'theme_slug'     => AWAIKEN_THEME_SLUG, // Theme slug
		'version'        => wp_get_theme( get_template() )->get( 'Version' ), // The current version of this theme
		'author'         => 'AwaikenThemes', // The author of this theme
		'download_id'    => AWAIKEN_ITEM_ID, // Optional, used for generating a license renewal link
		'renew_url'      => '', // Optional, allows for a custom license renewal link
		'beta'           => false, // Optional, set to true to opt into beta versions
		'item_id'        => AWAIKEN_ITEM_ID,
	),
	// Strings
	array(
		'activate-license-info'     => __( 'Your theme license is not activated. Please activate your license.', 'genlab' ),
		'theme-license'             => __( 'Theme License', 'genlab' ),
		'enter-key'                 => __( 'Enter your theme license key. Not sure where to find it? <a href="%s" rel="noopener noreferrer" target="_blank">View Instructions</a>', 'genlab' ),
		'license-key'               => __( 'License Key', 'genlab' ),
		'license-action'            => __( 'License Action', 'genlab' ),
		'deactivate-license'        => __( 'Deactivate License', 'genlab' ),
		'activate-license'          => __( 'Activate License', 'genlab' ),
		'renew'                     => __( 'Renew Now', 'genlab' ),
		'unlimited'                 => __( 'Unlimited', 'genlab' ),
		'license-key-is-active'     => __( 'Your license key is active.', 'genlab' ),
		/* translators: the license expiration date */
		'expires%s'                 => __( 'Expires %s.', 'genlab' ),
		'expires-never'             => __( 'Lifetime License.', 'genlab' ),
		/* translators: 1. the number of sites activated 2. the total number of activations allowed. */
		'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'genlab' ),
		'activation-limit'          => __( 'Your license key has reached its activation limit.', 'genlab' ),
		/* translators: the license expiration date */
		'license-key-expired-%s'    => __( 'License key expired %s.', 'genlab' ),
		'license-key-expired'       => __( 'Your license key has expired. Please renew to continue receiving updates and support.', 'genlab' ),
		/* translators: the license expiration date */
		'license-expired-on'        => __( 'Your license key expired on %s.', 'genlab' ),
		'license-keys-do-not-match' => __( 'License key mismatch. Please verify and try again.', 'genlab' ),
		'license-is-inactive'       => __( 'Your license is inactive. Click the Activate License button below to activate it.', 'genlab' ),
		'license-key-is-disabled'   => __( 'Your license key has been disabled. Please contact support for assistance.', 'genlab' ),
		'license-key-invalid'       => __( 'Invalid license key. Please check and try again.', 'genlab' ),
		'site-is-inactive'          => __( 'Your license is not active for this URL.', 'genlab' ),
		/* translators: the theme name */
		'item-mismatch'             => __( 'This appears to be an invalid license key for %s.', 'genlab' ),
		'license-status-unknown'    => __( 'Could not connect to the server.', 'genlab' ),
		'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'genlab' ),
		'error-generic'             => __( 'An unexpected error occurred. Please try again or contact support.', 'genlab' ),
		'pending-active'            => __( 'Click the Activate License button below to activate your license.', 'genlab' ),
		'purchased-from'            => __( 'Purchased from', 'genlab' ),
	)
);
