<?php 
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
* Set our Customizer default options
*/
if ( ! function_exists( 'awaiken_generate_defaults' ) ) {
	function awaiken_generate_defaults() {
		global $GENLAB_STORAGE;

		return apply_filters( 'awaiken_customizer_defaults', $GENLAB_STORAGE );
	}
}


/**
 * Customizer Setup and Custom Controls
 *
 */

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class awaiken_initialise_customizer_settings {
	// Get our default values
	private $defaults;

	public function __construct() {
		// Get our Customizer defaults
		$this->defaults = awaiken_generate_defaults();


		// Register sections
		add_action( 'customize_register', array( $this, 'awaiken_add_customizer_sections' ) );
		
		// Register general control
		add_action( 'customize_register', array( $this, 'awaiken_register_general_options_controls' ) );
		
		// Register casestudy control
		add_action( 'customize_register', array( $this, 'awaiken_register_casestudy_options_controls' ) );

		// Register blog control
		add_action( 'customize_register', array( $this, 'awaiken_register_blog_options_controls' ) );

		// Register 404 control
		add_action( 'customize_register', array( $this, 'awaiken_register_404_options_controls' ) );
		
		// Register footer control
		add_action( 'customize_register', array( $this, 'awaiken_register_footer_options_controls' ) );
		
	}


	/**
	 * Register the Customizer sections
	 */
	public function awaiken_add_customizer_sections( $wp_customize ) {
		
		// Add section general options
		$wp_customize->add_section( 'general_options' , array(
			'title'      => __( 'General Options', 'genlab' ),
		) );
		
		// Add section casestudy options
		$wp_customize->add_section( 'casestudy_options' , array(
			'title'      => __( 'Case Study Options', 'genlab' ),
		) );
		
		// Add section blog options
		$wp_customize->add_section( 'blog_options' , array(
			'title'      => __( 'Blog Options', 'genlab' ),
		) );

		// Add section 404 options
		$wp_customize->add_section( '404_options' , array(
			'title'      => __( '404 Options', 'genlab' ),
		) );
		
		// Add section footer options
		$wp_customize->add_section( 'footer_options' , array(
			'title'      => __( 'Footer Options', 'genlab' ),
		) );
		
	}
	
	/**
	 * Register general option controls
	 */

	public function awaiken_register_general_options_controls( $wp_customize ) {  
		
		$section	=	'general_options';
		
		// Preloader
		$wp_customize->add_setting( 'show_preloader',
			array(
				'default' => $this->defaults['show_preloader'],
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_switch_sanitization'
			)
		);
		
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'show_preloader',
			array(
				'label' => __( 'Preloader', 'genlab' ),
				'description' => esc_html__( 'Display preloader while the page is loading.', 'genlab' ),
				'section' => $section
			)
		) );
		
		// Magic Cursor
		$wp_customize->add_setting( 'magic_cursor',
			array(
				'default' => $this->defaults['magic_cursor'],
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_switch_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'magic_cursor',
			array(
				'label' => __( 'Magic Cursor', 'genlab' ),
				'description' => esc_html__( 'Show Magic Cursor.', 'genlab' ),
				'section' => $section
			)
		) );

		// Custom fancy scrollbar
		$wp_customize->add_setting( 'custom_fancy_scrollbar',
			array(
				'default' => $this->defaults['custom_fancy_scrollbar'],
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_switch_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'custom_fancy_scrollbar',
			array(
				'label' => __( 'Custom Fancy Scrollbar', 'genlab' ),
				'description' => esc_html__( 'Custom fancy scrollbar Disable/Enable.', 'genlab' ),
				'section' => $section
			)
		) );
		
		// Smooth scrolling
		$wp_customize->add_setting( 'smooth_scrolling',
			array(
				'default' => $this->defaults['smooth_scrolling'],
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_switch_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'smooth_scrolling',
			array(
				'label' => __( 'Smooth Scrolling', 'genlab' ),
				'description' => esc_html__( 'Smooth Scrolling Disable/Enable', 'genlab' ),
				'section' => $section
			)
		) );
		
		// heading icon 
		$wp_customize->add_setting( 'show_small_heading_icon',
			array(
				'default' => $this->defaults['show_small_heading_icon'],
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_switch_sanitization'
			)
		);
		
		$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'show_small_heading_icon',
			array(
				'label' => __( 'Display Small Icon', 'genlab' ),
				'description' => esc_html__( 'Display small icon before small heading.', 'genlab' ),
				'section' => $section
			)
		) );
		
		// heading icon
		$wp_customize->add_setting( 'small_heading_icon',
			array(
				'default' => $this->defaults['small_heading_icon'],
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'small_heading_icon',
			array(
				'label' => __( 'Small heading icon', 'genlab' ),
				'description' => esc_html__( 'If you want to change the current icon, select it here.', 'genlab' ),
				'section' => $section,
				'mime_type' => 'image',
				'button_labels' => array(
					'select' => __( 'Select File', 'genlab' ),
					'change' => __( 'Change File', 'genlab' ),
					'default' => __( 'Default', 'genlab' ),
					'remove' => __( 'Remove', 'genlab' ),
					'placeholder' => __( 'No file selected', 'genlab' ),
					'frame_title' => __( 'Select File', 'genlab' ),
					'frame_button' => __( 'Choose File', 'genlab' ),
				)
			)
		) );
		
		// Preloader icon
		$wp_customize->add_setting( 'preloader_icon',
			array(
				'default' => $this->defaults['preloader_icon'],
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'preloader_icon',
			array(
				'label' => __( 'Preloader icon', 'genlab' ),
				'description' => esc_html__( 'If you want to change the current loading icon, select it here.', 'genlab' ),
				'section' => $section,
				'mime_type' => 'image',
				'button_labels' => array(
					'select' => __( 'Select File', 'genlab' ),
					'change' => __( 'Change File', 'genlab' ),
					'default' => __( 'Default', 'genlab' ),
					'remove' => __( 'Remove', 'genlab' ),
					'placeholder' => __( 'No file selected', 'genlab' ),
					'frame_title' => __( 'Select File', 'genlab' ),
					'frame_button' => __( 'Choose File', 'genlab' ),
				)
			)
		) );
		
		// Header background image
		$wp_customize->add_setting( 'header_background_image',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'header_background_image',
			array(
				'label' => __( 'Header Background Image', 'genlab' ),
				'description' => esc_html__( 'Header background image is intended for pages that are not created using Elementor.', 'genlab' ),
				'section' => $section,
				'mime_type' => 'image',
				'button_labels' => array(
					'select' => __( 'Select File', 'genlab' ),
					'change' => __( 'Change File', 'genlab' ),
					'default' => __( 'Default', 'genlab' ),
					'remove' => __( 'Remove', 'genlab' ),
					'placeholder' => __( 'No file selected', 'genlab' ),
					'frame_title' => __( 'Select File', 'genlab' ),
					'frame_button' => __( 'Choose File', 'genlab' ),
				)
			)
		) );

	}
	
	/**
	 * Register case study option controls
	 */
	
	public function awaiken_register_casestudy_options_controls( $wp_customize ) { 
			
		$section	=	'casestudy_options';

		// Case Study page title 
		$wp_customize->add_setting( 'casestudy_page_title', array(
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( 'casestudy_page_title', array(
			'type' => 'text',
			'section' => $section,
			'label'       => esc_html__( 'Case Study Page Archive Title', 'genlab' ),
		) );
		
		// Header background image
		$wp_customize->add_setting( 'casestudy_page_header_background_image',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'casestudy_page_header_background_image',
			array(
				'label' => __( 'Header Background Image', 'genlab' ),
				'description' => esc_html__( 'Header background image for casestudy archive and single pages that are not created using Elementor.', 'genlab' ),
				'section' => $section,
				'mime_type' => 'image',
				'button_labels' => array(
					'select' => __( 'Select File', 'genlab' ),
					'change' => __( 'Change File', 'genlab' ),
					'default' => __( 'Default', 'genlab' ),
					'remove' => __( 'Remove', 'genlab' ),
					'placeholder' => __( 'No file selected', 'genlab' ),
					'frame_title' => __( 'Select File', 'genlab' ),
					'frame_button' => __( 'Choose File', 'genlab' ),
				)
			)
		) );
		
		// Archive page layout
		$wp_customize->add_setting( 'casestudy_archive_page_layout', array(
		  'default' => $this->defaults['casestudy_archive_page_layout'],
		   'sanitize_callback' => 'sanitize_text_field',
		) );
		
		$wp_customize->add_control( 'casestudy_archive_page_layout', array(
			  'label'          => __( 'Case Study Archive Page Layout', 'genlab' ),
			  'section' => $section,
			  'settings' => 'casestudy_archive_page_layout',
			  'type' => 'radio',
			  'choices' => array(
				'full-width'   => __( 'Full Width', 'genlab' ),
				'with-sidebar'  => __( 'With Sidebar', 'genlab' )
			  ),
		) );
		
		// Archive page single page layout
		$wp_customize->add_setting( 'casestudy_single_page_layout', array(
		  'default' => $this->defaults['casestudy_single_page_layout'],
		   'sanitize_callback' => 'sanitize_text_field',
		) );
		
		$wp_customize->add_control( 'casestudy_single_page_layout', array(
			  'label'          => __( 'Case Study Single Layout', 'genlab' ),
			  'description' => esc_html__( 'Works with the Default Template only.', 'genlab' ),
			  'section' => $section,
			  'settings' => 'casestudy_single_page_layout',
			  'type' => 'radio',
			  'choices' => array(
				'full-width'   => __( 'Full Width', 'genlab' ),
				'with-sidebar'  => __( 'With Sidebar', 'genlab' )
			  ),
		) );
		
	}
	
	/**
	 * Register blog option controls
	 */
	
	public function awaiken_register_blog_options_controls( $wp_customize ) { 
			
		$section	=	'blog_options';

		// Blog page title 
		$wp_customize->add_setting( 'blog_page_title', array(
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( 'blog_page_title', array(
			'type' => 'text',
			'section' => $section,
			'label'       => esc_html__( 'Blog Page Title', 'genlab' ),
		) );
		
		//Header Background Image
		$wp_customize->add_setting( 'blog_page_header_background_image',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'blog_page_header_background_image',
			array(
				'label' => __( 'Header Background Image', 'genlab' ),
				'description' => esc_html__( 'Header background image for blog archive and single page.', 'genlab' ),
				'section' => $section,
				'mime_type' => 'image',
				'button_labels' => array(
					'select' => __( 'Select File', 'genlab' ),
					'change' => __( 'Change File', 'genlab' ),
					'default' => __( 'Default', 'genlab' ),
					'remove' => __( 'Remove', 'genlab' ),
					'placeholder' => __( 'No file selected', 'genlab' ),
					'frame_title' => __( 'Select File', 'genlab' ),
					'frame_button' => __( 'Choose File', 'genlab' ),
				)
			)
		) );
		
		// Archive page layout
		$wp_customize->add_setting( 'archive_page_layout', array(
		  'default' => $this->defaults['archive_page_layout'],
		   'sanitize_callback' => 'sanitize_text_field',
		) );
		
		$wp_customize->add_control( 'archive_page_layout', array(
			  'label'          => __( 'Archive Page Layout', 'genlab' ),
			  'section' => $section,
			  'settings' => 'archive_page_layout',
			  'type' => 'radio',
			  'choices' => array(
				'full-width'   => __( 'Full Width', 'genlab' ),
				'with-sidebar'  => __( 'With Sidebar', 'genlab' )
			  ),
		) );
		
		// Archive page single page layout
		$wp_customize->add_setting( 'blog_single_page_layout', array(
		  'default' => $this->defaults['blog_single_page_layout'],
		   'sanitize_callback' => 'sanitize_text_field',
		) );
		
		$wp_customize->add_control( 'blog_single_page_layout', array(
			  'label'          => __( 'Blog Single Layout', 'genlab' ),
			  'description' => esc_html__( 'Works with the Default Template only.', 'genlab' ),
			  'section' => $section,
			  'settings' => 'blog_single_page_layout',
			  'type' => 'radio',
			  'choices' => array(
				'full-width'   => __( 'Full Width', 'genlab' ),
				'with-sidebar'  => __( 'With Sidebar', 'genlab' )
			  ),
		) );
		
		// Social Sharing
		$wp_customize->add_setting( 'social_sharing',
			array(
				'default' => $this->defaults['social_sharing'],
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_text_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Pill_Checkbox_Custom_Control( $wp_customize, 'social_sharing',
			array(
				'label' => __( 'Social Sharing', 'genlab' ),
				'description' => esc_html__( 'Choose the social network you want to display in the social share box.', 'genlab' ),
				'section' => $section,
				'input_attrs' => array(
					'sortable' => true,
					'fullwidth' => true,
				),
				'choices' => array(
					'facebook' => esc_attr__( 'Facebook', 'genlab' ),
					'twitter' => esc_attr__( 'Twitter', 'genlab' ),
					'whatsapp' => esc_attr__( 'Whatsapp', 'genlab' ),
					'linkedin' => esc_attr__( 'LinkedIn', 'genlab' ),
					'reddit' => esc_attr__( 'Reddit', 'genlab' ),
					'tumblr' => esc_attr__( 'Tumblr', 'genlab' ),
					'pinterest' => esc_attr__( 'Pinterest', 'genlab' ),
					'vk' => esc_attr__( 'vk', 'genlab' ),
					'email' => esc_attr__( 'Email', 'genlab' ),
					'telegram' => esc_attr__( 'Telegram', 'genlab' ),
				)
			)
		) );

	}

	/**
	 * Register 404 controls
	 */
	
	 public function awaiken_register_404_options_controls( $wp_customize ) { 
			
		$section	=	'404_options';
		
		// 404 Image
		$wp_customize->add_setting( 'not_found_image',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'not_found_image',
			array(
				'label' => __( '404 Image', 'genlab' ),
				'section' => $section,
				'mime_type' => 'image',
				'button_labels' => array(
					'select' => __( 'Select File', 'genlab' ),
					'change' => __( 'Change File', 'genlab' ),
					'default' => __( 'Default', 'genlab' ),
					'remove' => __( 'Remove', 'genlab' ),
					'placeholder' => __( 'No file selected', 'genlab' ),
					'frame_title' => __( 'Select File', 'genlab' ),
					'frame_button' => __( 'Choose File', 'genlab' ),
				)
			)
		) );
		
		// 404 Heading
		$wp_customize->add_setting( 'not_found_heading',
			array(
				'default' => $this->defaults['not_found_heading'],
				'transport' => 'refresh',
				'sanitize_callback' => 'wp_kses_post'
			)
		);
		$wp_customize->add_control( 'not_found_heading',
			array(
				'label' => esc_html__( '404 Heading', 'genlab' ),
				'section' => $section,
				'type' => 'text',

			)
		);
		
		// 404 text
		$wp_customize->add_setting( 'not_found_text',
			array(
				'default' => $this->defaults['not_found_text'],
				'transport' => 'refresh',
				'sanitize_callback' => 'wp_kses_post'
			)
		);
		$wp_customize->add_control( 'not_found_text',
			array(
				'label' => esc_html__( '404 Text', 'genlab' ),
				'section' => $section,
				'type' => 'textarea',
			)
		);
	}
	
	/**
	 * Register footer controls
	 */
	
	public function awaiken_register_footer_options_controls( $wp_customize ) { 
		
		$section	=	'footer_options';
		
		//Footer logo
		$wp_customize->add_setting( 'footer_logo',
			array(
				'default' => '',
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'footer_logo',
			array(
				'label' => __( 'Footer Logo', 'genlab' ),
				'section' => $section,
				'mime_type' => 'image',
				'button_labels' => array(
					'select' => __( 'Select File', 'genlab' ),
					'change' => __( 'Change File', 'genlab' ),
					'default' => __( 'Default', 'genlab' ),
					'remove' => __( 'Remove', 'genlab' ),
					'placeholder' => __( 'No file selected', 'genlab' ),
					'frame_title' => __( 'Select File', 'genlab' ),
					'frame_button' => __( 'Choose File', 'genlab' ),
				)
			)
		) );
		
		// Copyright text
		$wp_customize->add_setting( 'footer_copyright_text',
			array(
				'default' => $this->defaults['footer_copyright_text'],
				'transport' => 'refresh',
				'sanitize_callback' => 'wp_kses_post'
			)
		);
		$wp_customize->add_control( 'footer_copyright_text',
			array(
				'label' => __( 'Copyright Text', 'genlab' ),
				'section' => $section,
				'type' => 'textarea',
			)
		);
		
		// Social media URLs
		$wp_customize->add_setting( 'social_urls',
			array(
				'default' => $this->defaults['social_urls'],
				'transport' => 'refresh',
				'sanitize_callback' => 'skyrocket_url_sanitization'
			)
		);
		$wp_customize->add_control( new Skyrocket_Sortable_Repeater_Custom_Control( $wp_customize, 'social_urls',
			array(
				'label' => __( 'Social URLs', 'genlab' ),
				'description' => esc_html__( 'Enter the social profile URLs.', 'genlab' ),
				'section' => $section,
				'button_labels' => array(
					'add' => __( 'Add Row', 'genlab' ),
				)
			)
		) );
		
	}
	
}

/**
 * Load all our Customizer Custom Controls
 */
require_once GENLAB_THEME_DIR . '/inc/customizer/custom-controls.php';

/**
 * Initialise our Customizer settings
 */
$awaiken_settings = new awaiken_initialise_customizer_settings();
