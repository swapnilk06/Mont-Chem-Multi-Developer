<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
 
$wp_version       = get_bloginfo( 'version' );
$php_version      = PHP_VERSION;
$memory_limit     = WP_MEMORY_LIMIT;
$php_post_max     = ini_get( 'post_max_size' );
$php_time_limit   = ini_get( 'max_execution_time' );
$php_max_input    = ini_get( 'max_input_vars' );
$php_memory_limit = ini_get( 'memory_limit' );


if ( ! function_exists( 'genlab_sysreq_badge' ) ) {
	function genlab_sysreq_badge( $ok ) {
		if ( $ok ) {
			return '<span class="at-sr-badge at-sr-badge-ok"><span class="dashicons dashicons-yes"></span>' . esc_html__( 'OK', 'genlab' ) . '</span>';
		}
		return '<span class="at-sr-badge at-sr-badge-fail"><span class="dashicons dashicons-warning"></span>' . esc_html__( 'Action needed', 'genlab' ) . '</span>';
	}
}

if ( ! function_exists( 'genlab_bytes_to_mb' ) ) {
	function genlab_bytes_to_mb( $bytes ) {
		return round( $bytes / 1048576 ) . ' MB';
	}
}

if ( ! function_exists( 'genlab_ini_to_bytes' ) ) {
	function genlab_ini_to_bytes( $val ) {
		$val  = trim( $val );
		$last = strtolower( $val[ strlen( $val ) - 1 ] );
		$num  = (int) $val;
		switch ( $last ) {
			case 'g': $num *= 1024;
			// fall through
			case 'm': $num *= 1024;
			// fall through
			case 'k': $num *= 1024;
		}
		return $num;
	}
}

// Thresholds
$req_wp_memory  = 512 * 1048576;
$req_php_memory = 512 * 1048576;
$req_post_max   = 32  * 1048576;
$req_upload     = 32  * 1048576;
$req_time_limit = 300;
$req_max_input  = 1000;

// PHP extensions
$has_curl     = function_exists( 'curl_version' );
$has_gd       = extension_loaded( 'gd' );
$has_imagick  = extension_loaded( 'imagick' );
$has_zip      = class_exists( 'ZipArchive' );
$has_mbstring = extension_loaded( 'mbstring' );
$has_dom      = extension_loaded( 'dom' );
$has_openssl  = extension_loaded( 'openssl' );
$has_bcmath     = extension_loaded( 'bcmath' );
$has_xmlreader  = extension_loaded( 'xmlreader' );
$allow_url    = (bool) ini_get( 'allow_url_fopen' );

// Plugin checks
$has_elementor = defined( 'ELEMENTOR_VERSION' );
$has_ekit      = defined( 'ELEMENTSKIT_VERSION' );
$has_cf7       = defined( 'WPCF7_VERSION' );

// -1 means unlimited — treat as always passing for memory checks
$wp_memory_ok  = ( '-1' === trim( $memory_limit ) )     || genlab_ini_to_bytes( $memory_limit )     >= $req_wp_memory;
$php_memory_ok = ( '-1' === trim( $php_memory_limit ) ) || genlab_ini_to_bytes( $php_memory_limit ) >= $req_php_memory;

// Overall status — mirrors exactly the rows rendered in the tables below
$all_ok = (
	version_compare( $wp_version, '6.6', '>=' ) &&
	$wp_memory_ok &&
	version_compare( $php_version, '8.0', '>=' ) &&
	$php_memory_ok &&
	genlab_ini_to_bytes( $php_post_max ) >= $req_post_max &&
	(int) $php_time_limit >= $req_time_limit &&
	(int) $php_max_input >= $req_max_input &&
	$has_curl &&
	$has_xmlreader
);


?>
			<div class="info-box-wrapper">
				<div class="info-box at-sysreq-box">
					<h2><?php esc_html_e( 'System Requirements', 'genlab' ); ?></h2>
					<p class="at-sysreq-intro"><?php esc_html_e( 'The table below compares your server environment against the minimum values required to run this theme correctly.', 'genlab' ); ?></p>
<hr/>
					<?php
					// ── helper to render a standard data row ─────────────────────────
					$genlab_row = function( $label, $required, $current, $ok, $note = '' ) {
						?>
						<tr class="<?php echo esc_attr( $ok ? 'at-row-ok' : 'at-row-fail' ); ?>">
							<td class="at-col-name">
								<?php echo esc_html( $label ); ?>
								<?php if ( $note ) : ?>
									<span class="at-req-note"><?php echo esc_html( $note ); ?></span>
								<?php endif; ?>
							</td>
							<td class="at-col-req"><?php echo esc_html( $required ); ?></td>
							<td class="at-col-cur"><?php echo esc_html( $current ); ?></td>
							<td class="at-col-status"><?php echo genlab_sysreq_badge( $ok ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
						<?php
					};

					// ── helper for extension rows ─────────────────────────────────────
					$genlab_ext_row = function( $label, $has, $note = '' ) {
						?>
						<tr class="<?php echo esc_attr( $has ? 'at-row-ok' : 'at-row-fail' ); ?>">
							<td class="at-col-name">
								<?php echo esc_html( $label ); ?>
								<?php if ( $note ) : ?>
									<span class="at-req-note"><?php echo esc_html( $note ); ?></span>
								<?php endif; ?>
							</td>
							<td class="at-col-req"><?php esc_html_e( 'Enabled', 'genlab' ); ?></td>
							<td class="at-col-cur"><?php echo esc_html( $has ? __( 'Enabled', 'genlab' ) : __( 'Disabled', 'genlab' ) ); ?></td>
							<td class="at-col-status"><?php echo genlab_sysreq_badge( $has ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
						<?php
					};
					?>

					<?php /* ── Theme & WordPress ── */ ?>
					<h3 class="at-sysreq-section"><?php esc_html_e( 'WordPress', 'genlab' ); ?></h3>
					<table class="at-sysreq-table">
						<thead>
							<tr>
								<th class="at-col-name"><?php esc_html_e( 'Requirement', 'genlab' ); ?></th>
								<th class="at-col-req"><?php esc_html_e( 'Required', 'genlab' ); ?></th>
								<th class="at-col-cur"><?php esc_html_e( 'Your Server', 'genlab' ); ?></th>
								<th class="at-col-status"><?php esc_html_e( 'Status', 'genlab' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$genlab_row(
								__( 'WordPress version', 'genlab' ),
								__( '6.6+', 'genlab' ),
								$wp_version,
								version_compare( $wp_version, '6.6', '>=' )
							);
							$genlab_row(
								__( 'WordPress memory limit', 'genlab' ),
								'512M',
								$memory_limit,
								$wp_memory_ok
							);
							?>
						</tbody>
					</table>

					<?php /* ── PHP Server ── */ ?>
					<h3 class="at-sysreq-section"><?php esc_html_e( 'PHP Server', 'genlab' ); ?></h3>
					<table class="at-sysreq-table">
						<thead>
							<tr>
								<th class="at-col-name"><?php esc_html_e( 'Requirement', 'genlab' ); ?></th>
								<th class="at-col-req"><?php esc_html_e( 'Required', 'genlab' ); ?></th>
								<th class="at-col-cur"><?php esc_html_e( 'Your Server', 'genlab' ); ?></th>
								<th class="at-col-status"><?php esc_html_e( 'Status', 'genlab' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$genlab_row( __( 'PHP version', 'genlab' ),        '8.0+',  $php_version,     version_compare( $php_version, '8.0', '>=' ) );
							$genlab_row( __( 'PHP memory limit', 'genlab' ),   '512M',  $php_memory_limit, $php_memory_ok );
							$genlab_row( __( 'PHP Post Max Size', 'genlab' ),  '32M',   $php_post_max,    genlab_ini_to_bytes( $php_post_max ) >= $req_post_max );
							$genlab_row( __( 'PHP time limit', 'genlab' ),     '300s',  $php_time_limit . 's', (int) $php_time_limit >= $req_time_limit );
							$genlab_row( __( 'PHP max input vars', 'genlab' ), '1000',  $php_max_input,   (int) $php_max_input >= $req_max_input );
							?>
						</tbody>
					</table>

					<?php /* ── PHP Extensions ── */ ?>
					<h3 class="at-sysreq-section"><?php esc_html_e( 'PHP Extensions', 'genlab' ); ?></h3>
					<table class="at-sysreq-table">
						<thead>
							<tr>
								<th class="at-col-name"><?php esc_html_e( 'Extension', 'genlab' ); ?></th>
								<th class="at-col-req"><?php esc_html_e( 'Required', 'genlab' ); ?></th>
								<th class="at-col-cur"><?php esc_html_e( 'Your Server', 'genlab' ); ?></th>
								<th class="at-col-status"><?php esc_html_e( 'Status', 'genlab' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$genlab_ext_row( 'cURL',       $has_curl,      '' );
							$genlab_ext_row( 'XMLReader',  $has_xmlreader, __( '(demo import / XML parsing)', 'genlab' ) );
							?>
						</tbody>
					</table>
				</div>
				<?php if ( $all_ok === false ) : ?>
				<div class="info-box systemreq-notice">
					<p>
						<?php
						printf( /* translators: %s: Link to the documentation */
							wp_kses_post( __( '⚠️ Some requirements need attention. Please contact your hosting provider or refer to our <a target="_blank" rel="noopener noreferrer" href="%s">documentation</a> to fix them.', 'genlab' ) ),
							esc_url( $this->settings['doc_system_requirements_link'] )
						);
						?>
					</p>
				</div>
				<?php endif; ?>
			</div>

				
