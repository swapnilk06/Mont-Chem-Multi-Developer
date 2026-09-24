<?php
/**
 * The template for displaying casestudy.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
global $GENLAB_STORAGE;
$archive_page_layout	=	get_theme_mod( 'casestudy_archive_page_layout', $GENLAB_STORAGE['casestudy_archive_page_layout'] );
if($archive_page_layout === 'full-width') {
	$column = 'col-md-12';
}
else{
	$column = 'col-lg-9 col-md-12';
}

$background_image 	= get_theme_mod( 'casestudy_page_header_background_image', $GENLAB_STORAGE['casestudy_page_header_background_image'] );
if($background_image) {
	$background_image 	= 	wp_get_attachment_image_src( $background_image , 'full' );
	if(isset($background_image[0])) {
		$background_image	=	$background_image[0];
	}
}

?>
<main id="content" class="site-main">
	<div class="page-header" <?php if($background_image) { ?> style="background-image: url('<?php echo esc_url($background_image); ?>')" <?php } ?>>
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-12">
					<div class="page-header-box">
						<h1 class="entry-title"><?php 
							$genlab_blog_title_text = genlab_get_archive_title();
							echo wp_kses_data( $genlab_blog_title_text ); ?></h1>
							<?php
								the_archive_description( '<div class="taxonomy-description">', '</div>' );
							?>
						<?php do_action('genlab_action_get_breadcrumb'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="page-content">
		<div class="page-blog-archive">
			<div class="container">
				<div class="row">
					<div class="<?php echo esc_attr( $column ); ?>">
						<div class="row">
						<?php
							while ( have_posts() ) {
								the_post();
								$post_link = get_permalink();
								$casestudy_category = wp_get_post_terms( get_the_ID(), 'awaiken-casestudy-category' );
							?>
							<div class="col-xl-4 col-md-6">
								<div class="case-study-item <?php if ( ! has_post_thumbnail() ) { echo 'no-image'; } ?>">
									<div class="case-study-item-image">
										<?php
											if ( has_post_thumbnail() ) {
												printf( '<a href="%s"><figure>%s</figure></a>', esc_url( $post_link ), get_the_post_thumbnail( $post, 'large' ) );
											}
										?>
									</div>
									<div class="case-study-item-content">
										<ul>
											<li>
												<?php
													if ($casestudy_category && !is_wp_error($casestudy_category)) {
														$first_category = $casestudy_category[0];
														echo '<a href="' . esc_url(get_term_link($first_category)) . '">' . esc_html($first_category->name) . '</a>';
													}
												?>
											</li>
										</ul>
										<?php
											printf( '<h2><a href="%s">%s</a></h2>', esc_url( $post_link ), wp_kses_post( get_the_title() ) );
										?>
									</div>
								</div>
							</div>
						<?php } ?>
							<div class="col-md-12">
								<?php
									echo get_the_posts_pagination( array(
											'mid_size' => 2,
											'prev_text' => '<i class="fa-solid fa-angle-left"></i>',
											'next_text' => '<i class="fa-solid fa-angle-right"></i>',
										) );
								?>
							</div>
						</div>
					</div>
					<?php 
						if($archive_page_layout === 'with-sidebar'):
							get_sidebar('casestudy');
						endif;
					?>
				</div>
			</div>
		</div>
	</div>
</main>