<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once GENLAB_THEME_DIR . '/inc/admin/views/header.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="info-box-wrapper">
				<div class="row">
					<div class="col-12">
						<div class="info-box">
								<h2><?php esc_html_e('Activate your theme license to receive automatic updates.', 'genlab') ?></h2>
								<hr/>
							<?php
							global $awaiken_theme_updater;
							if ( $awaiken_theme_updater ) {
								$awaiken_theme_updater->render_license_form( 'dashboard' );
							}
							?>
						</div>
					</div>
					<div class="col-12">
					<hr/>
						<div class="info-box">
							<p>
							<?php
								printf( /* translators: %s: Link to the WordPress dashboard */
										wp_kses_post( __( 'Need help finding your license key? Visit our <a target="_blank" rel="noopener noreferrer" href="%s">documentation</a> for step-by-step instructions.', 'genlab' ) ),
										esc_url( $this->settings['doc_license_key'] )
									);
							?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
require_once GENLAB_THEME_DIR . '/inc/admin/views/footer.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
