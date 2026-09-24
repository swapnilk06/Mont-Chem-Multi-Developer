<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


?>
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="at-welcome-panel">
				<div class="at-welcome-panel-header">
					<h1 class="welcome-panel-title"><?php echo esc_html($this->settings['hero_title']); ?></h1>
					<p class="welcome-panel-description"><?php echo esc_html($this->settings['hero_desc']); ?></p>
				</div>
				<div class="at-dashboard-tabs">
				<?php 
					global $pagenow;
					$current_page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';

					if ( 'themes.php' === $pagenow ) {
						$current_url = admin_url( 'themes.php?page=' . $current_page );
					} else {
						$current_url = admin_url( 'admin.php?page=' . $current_page );
					}

					$tabs = [
						[
							'href'  => admin_url( 'admin.php?page=at-dashboard' ),
							'label' => esc_html__( 'Dashboard', 'genlab' ),
						],
						[
							'href'  => admin_url( 'admin.php?page=at-getting-started' ),
							'label' => esc_html__( 'Getting Started', 'genlab' ),
						],
						[
							'href'  => admin_url( 'admin.php?page=at-license' ),
							'label' => esc_html__( 'License', 'genlab' ),
						],
						[
							'href'  => admin_url( 'admin.php?page=at-troubleshooting' ),
							'label' => esc_html__( 'Troubleshooting', 'genlab' ),
						],
					];
					?>

					<ul class="at-dashboard-tabs-list">
						<?php foreach ( $tabs as $tab ) : ?>
							<li>
								<a href="<?php echo esc_url_raw( $tab['href'] ); ?>"
								   class="<?php echo esc_attr(( $current_url === $tab['href'] ) ? 'at-tab-active' : ''); ?>">
									<?php echo esc_html($tab['label']); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>

					<div class="premium-btn">
						<?php
							printf( // translators: %s: URL to the upgrade page
								wp_kses_post( __( '<a href="%s" rel="noopener noreferrer" target="_blank">Documentation</a>', 'genlab' ) ),
								esc_url( $this->settings['doc_link'] )
							);
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

