<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
require_once GENLAB_THEME_DIR . '/inc/admin/views/header.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude

if( get_option( 'genlab_demo_imported' ) == 1 ) {
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<div class="info-box-wrapper">
				<div class="row">
					<div class="col-12">
						<div class="info-box">
							<h2 class="heading-main"><?php esc_html_e('Quickly access and customize the key areas of your theme from one place.', 'genlab') ?></h2>
							<hr/>
						</div>
					</div>
					<div class="col-12">
						<div class="info-box">
							<h2><?php esc_html_e('Logo & Favicon', 'genlab') ?></h2>
							<p><?php echo wp_kses_post(
    __( 'Upload your logo and favicon to brand your site. Navigate to <strong>Appearance > Customize > Site Identity</strong>', 'genlab' )); ?></p>
							<p><a target="_blank" class="btn-custom" href="<?php echo esc_url(admin_url('customize.php?autofocus[section]=title_tagline')); ?>"><?php esc_html_e('Edit Logo & Favicon', 'genlab') ?></a></p>
						</div>
					</div>
					<?php if ( defined( 'ELEMENTOR_VERSION' ) && is_callable( '\Elementor\Plugin::instance' ) ) : ?>
					<div class="col-12">
						<div class="info-box">
							<h2><?php esc_html_e('Typography & Colors', 'genlab') ?></h2>
							<p><?php esc_html_e('Set your brand fonts and color palette to match your identity.', 'genlab') ?></p>
							<p><a target="_blank" class="btn-custom" href="<?php echo esc_url($this->settings['site_settings_url']); ?>"><?php esc_html_e('Edit Typography & Colors', 'genlab') ?></a></p>
						</div>
					</div>
					<?php endif; ?>
					<div class="col-12">
						<div class="info-box">
							<h2><?php esc_html_e('Homepage Settings', 'genlab') ?></h2>
							<p><?php echo wp_kses_post(
    __('To change the homepage, navigate to <strong>Appearance > Customize > Homepage Settings</strong>', 'genlab')); ?></p>
							<p><a target="_blank" class="btn-custom" href="<?php echo esc_url(admin_url('customize.php?autofocus[section]=static_front_page')); ?>"><?php esc_html_e('Edit Homepage Settings', 'genlab') ?></a></p>
						</div>
					</div>
					<div class="col-12">
						<div class="info-box">
							<h2><?php esc_html_e('Header', 'genlab') ?></h2>
							<p><?php echo wp_kses_post(
    __('Customize your header layout, logo position, and navigation. Navigate to <strong>Appearance > Headers</strong>, click Edit with Elementor on the active header to customize it.', 'genlab')); ?></p>
							<p><a target="_blank" class="btn-custom" href="<?php echo esc_url(admin_url( 'edit.php?post_type=elementskit_template&elementskit_type_filter=header' )); ?>"><?php esc_html_e('Edit Header', 'genlab') ?></a></p>
						</div>
					</div>
					<div class="col-12">
						<div class="info-box">
							<h2><?php esc_html_e('Footer', 'genlab') ?></h2>
							<p><?php echo wp_kses_post(
    __('Customize your footer layout, widgets, and copyright text. Navigate to <strong>Appearance > Footers</strong>, click Edit with Elementor on the active footer to customize it.', 'genlab')); ?></p>
							<p><a target="_blank" class="btn-custom" href="<?php echo esc_url(admin_url( 'edit.php?post_type=elementskit_template&elementskit_type_filter=footer' )); ?>"><?php esc_html_e('Edit Footer', 'genlab') ?></a></p>
						</div>
					</div>
					<?php if ( class_exists('ShopEngine') ) { ?>
					<div class="col-12">
						<div class="info-box">
							<h2><?php esc_html_e('WooCommerce', 'genlab') ?></h2>
							<p><?php echo wp_kses_post(
    __('Customize your WooCommerce pages like Shop, Cart, and Checkout. Navigate to <strong>ShopEngine > Builder Templates</strong>, click Edit with Elementor to customize it.', 'genlab')); ?></p>
							<p><a target="_blank" class="btn-custom" href="<?php echo esc_url(admin_url( 'edit.php?post_type=shopengine-template' )); ?>"><?php esc_html_e('Edit WooCommerce Templates', 'genlab') ?></a></p>
						</div>
					</div>
					<?php } ?>
					<div class="col-12">
					<hr/>
						<div class="info-box">
							<p>
							<?php
								printf( /* translators: %s: Link to the WordPress dashboard */
										wp_kses_post( __( 'Need more help? Visit our <a target="_blank" rel="noopener noreferrer" href="%s">documentation</a> for detailed guides on all customization options.', 'genlab' ) ),
										esc_url( $this->settings['doc_link'] )
									);
							?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<?php require_once GENLAB_THEME_DIR . '/inc/admin/views/system-requirements.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude ?>
		</div>
	</div>
</div>
<?php 
}else{
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<div class="info-box-wrapper">
				<div class="row">
					<div class="col">
						<div class="info-box">
							<h2>
							<?php
								echo wp_kses_post(
									sprintf( /* translators: %s: Link to the Getting Started */
										__( 'Your site is not set up yet. Visit the <a href="%s">Getting Started</a> tab to begin the setup process.', 'genlab' ),
										esc_url( admin_url( 'admin.php?page=at-getting-started' ) )
									)
								);
							?>
							</h2>
							<p><?php 
								printf(
										wp_kses_post(__( '⚠️ <strong>Before You Begin:</strong> Please ensure your server meets our minimum system requirements for a smooth setup experience. You can check your current status on the right-hand side.', 'genlab')),
										esc_url( admin_url( 'themes.php?page=at-dashboard' ) )
									);
							?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<?php require_once GENLAB_THEME_DIR . '/inc/admin/views/system-requirements.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude ?>
		</div>
	</div>
</div>
<?php 	
}
require_once GENLAB_THEME_DIR . '/inc/admin/views/footer.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
