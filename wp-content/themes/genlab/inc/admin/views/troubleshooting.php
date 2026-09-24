<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
require_once GENLAB_THEME_DIR . '/inc/admin/views/header.php';
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="info-box-wrapper">
				<div class="row">

					<!-- Page Intro -->
					<div class="col-12">
						<div class="info-box">
							<h2><?php esc_html_e( 'Having issues after demo import? Use the tools below to fix common problems.', 'genlab' ); ?></h2>
							<hr/>
						</div>
					</div>

					<!-- Elementor Kit Section -->
					<div class="col-12">
						<div class="info-box">
							<h2><?php esc_html_e( 'After Import Style is Missing', 'genlab' ); ?></h2>
							<p>
								<?php esc_html_e( 'If your site looks unstyled after the demo import, and fonts, colors, or layouts appear broken, it may be because the Elementor Kit was not applied automatically. This commonly happens on some staging environments. Click the button below to re-apply the styles.', 'genlab' ); ?>
							</p>
							<p>
								<strong>⚠️ <?php esc_html_e( 'Note:', 'genlab' ); ?></strong>
								<?php esc_html_e( 'This action is safe and will not delete or affect your existing content. Please note that it can only be applied once.', 'genlab' ); ?>
							</p>
							<p>
								<button id="at-reapply-kit-btn" class="btn-custom" 
									<?php echo get_option( 'genlab_kit_applied' ) === '1' ? 'disabled' : ''; ?>>
									<?php echo get_option( 'genlab_kit_applied' ) === '1' 
										? esc_html__( 'Style Applied', 'genlab' ) 
										: esc_html__( 'Re-Apply Style', 'genlab' ); ?>
								</button>
							</p>
							<!-- Status Message -->
							<p id="at-kit-status" style="display:none; margin-top: 10px; font-weight: 600;"></p>
						</div>
					</div>
					<div class="col-12">
					<hr/>
						<div class="info-box">
							<p>
							<?php
								printf( /* translators: %s: Link to the WordPress dashboard */
										wp_kses_post( __( 'Need help? Visit our <a target="_blank" rel="noopener noreferrer" href="%s">troubleshooting</a> documentation for detailed guides on issues.', 'genlab' ) ),
										esc_url( $this->settings['doc_troubleshooting_link'] )
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

<script type="text/javascript">
	( function() {
		const btn    = document.getElementById( 'at-reapply-kit-btn' );
		const status = document.getElementById( 'at-kit-status' );

		btn.addEventListener( 'click', function() {
			// Disable button and show loading state.
			btn.disabled    = true;
			btn.textContent = '<?php echo esc_js( __( 'Applying...', 'genlab' ) ); ?>';
			status.style.display = 'none';

			const formData = new FormData();
			formData.append( 'action', 'genlab_reapply_elementor_kit' );
			formData.append( 'nonce', '<?php echo esc_js( wp_create_nonce( 'genlab_reapply_kit_nonce' ) ); ?>' );

			fetch( '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>', {
				method : 'POST',
				body   : formData,
			} )
			.then( res => res.json() )
			.then( data => {
				status.style.display = 'block';

				if ( data.success ) {
					status.style.color  = '#4CAF50';
					status.textContent  = data.data.message;
					btn.textContent     = '<?php echo esc_js( __( 'Re-Apply Style', 'genlab' ) ); ?>';
				} else {
					status.style.color  = '#f44336';
					status.textContent  = data.data.message;
					btn.textContent     = '<?php echo esc_js( __( 'Re-Apply Style', 'genlab' ) ); ?>';
				}

				btn.disabled = false;
			} )
			.catch( () => {
				status.style.display = 'block';
				status.style.color   = '#f44336';
				status.textContent   = '<?php echo esc_js( __( 'Something went wrong. Please try again.', 'genlab' ) ); ?>';
				btn.textContent      = '<?php echo esc_js( __( 'Re-Apply Style', 'genlab' ) ); ?>';
				btn.disabled         = false;
			} );
		} );
	} )();
</script>

<?php
require_once GENLAB_THEME_DIR . '/inc/admin/views/footer.php';