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
					<div class="col-12">
						<div class="info-box">
							<h2><?php esc_html_e('Follow these steps to get your site up and running.', 'genlab') ?></h2>
							<p><?php 
								printf(
										wp_kses_post(__( '⚠️ <strong>Before You Begin:</strong> Please ensure your server meets our minimum system requirements for a smooth setup experience. You can check your current status by visiting <a href="%s"><strong>Dashboard</strong></a> tab.', 'genlab')),
										esc_url( admin_url( 'themes.php?page=at-dashboard' ) )
									);
							?>
							</p>
							<hr/>
						</div>
					</div>
					<div class="col-12">
						<div class="info-box">
							<h2><?php esc_html_e('1. Activate Theme License', 'genlab'); ?></h2>
							<p><?php 
								printf(
										wp_kses_post(__( 'To activate theme license, Click on the <a href="%s" target="_blank"><strong>Activate License</strong></a> link at the top of the screen.', 'genlab')),
										esc_url( admin_url( 'admin.php?page=at-license' ) )
									);
							?>
							</p>
						</div>
					</div>
					<div class="col-12">
						<div class="info-box">
							<h2><?php esc_html_e('2. Install Plugins', 'genlab'); ?></h2>
							<p><?php 
								printf(
										wp_kses_post(__( 'To install plugins, Click on the <a href="%s" target="_blank"><strong>Begin installing plugins</strong></a> link at the top of the screen. Select all plugins, choose <strong>Install</strong> from the bulk actions dropdown, and click <strong>Apply</strong>. Once installed, activate all plugins the same way.', 'genlab')),
										esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins&plugin_status=install' ) )
									);
							?>
							</p>
							<p>
							<?php
								printf(
										wp_kses_post(__( '<strong>Important:</strong> For the best demo import experience, deactivate any plugins not required by our theme. Unnecessary active plugins may cause conflicts during the import process.', 'genlab'))
									);
							?>
							</p>
						</div>
					</div>
					<div class="col-12">
						<div class="info-box">
							<h2><?php esc_html_e('3. Import Demo Data', 'genlab') ?></h2>
							<p>
							<?php 
								printf(
										wp_kses_post(__( 'To import the demo content, go to <a href="%s" target="_blank"><strong>Appearance > Import Demo Data</strong></a>, Click on the <strong>Import Demo Data</strong> button, and then click <strong>Continue & Import</strong> to proceed. Wait for the import process to complete, this may take a few minutes depending on your server speed.', 'genlab')),
										esc_url( admin_url( 'themes.php?page=one-click-demo-import' ) )
									);
							?>
							</p>
						</div>
					</div>
					<div class="col-12">
						<div class="info-box">
							<h2><?php esc_html_e('4. Configure Theme Settings', 'genlab') ?></h2>
							<p>
							<?php
								printf(
										wp_kses_post(__( 'To configure site-wide settings like fonts, colors, edit any page with Elementor. Click the <strong>hamburger menu (☰)</strong> in the upper-left corner of the Elementor panel, then select <strong>Site Settings</strong> from the dropdown menu.', 'genlab'))
									);
							?>
							</p>
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
<?php 
require_once GENLAB_THEME_DIR . '/inc/admin/views/footer.php';
