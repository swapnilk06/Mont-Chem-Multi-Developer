<?php
/**
 * The template for displaying sidebar.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="col-lg-3 col-md-12">
	<div class="sidebar-widget">
		<?php if ( is_active_sidebar( 'casestudy-sidebar' )  ) : ?>
			<?php dynamic_sidebar( 'casestudy-sidebar' ); ?>
		<?php endif; ?>
	</div>
</div>