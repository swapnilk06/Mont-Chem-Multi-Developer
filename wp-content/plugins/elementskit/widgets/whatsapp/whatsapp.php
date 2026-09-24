<?php

namespace Elementor;

use \Elementor\ElementsKit_Widget_Whatsapp_Handler as Handler;
use \ElementsKit_Lite\Modules\Controls\Controls_Manager as ElementsKit_Controls_Manager;
use ElementsKit_Lite;

if (!defined('ABSPATH')) exit;

class ElementsKit_Widget_Whatsapp extends Widget_Base
{
	use \ElementsKit_Lite\Widgets\Widget_Notice;

	public $base;

	public function get_name() {
		return Handler::get_name();
	}

	public function get_title() {
		return Handler::get_title();
	}

	public function get_icon() {
		return Handler::get_icon();
	}

	public function get_categories() {
		return Handler::get_categories();
	}

	public function get_keywords() {
		return Handler::get_keywords();
	}

	public function get_help_url() {
		return 'https://wpmet.com/doc/get-whatsapp-button-on-website-elementskit/';
	}

	public function get_style_depends() {
		return ['ekit-pro-whatsapp'];
	}

	public function get_script_depends() {
		return ['ekit-pro-whatsapp'];
	}

    protected function is_dynamic_content(): bool {
        return false;
    }

	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'user_tab_section',
			[
				'label' => esc_html__('Header', 'elementskit'),
			]
		);

		$this->add_control(
			'whatsapp_user_image',
			[
				'label' => esc_html__('Choose Profile Photo', 'elementskit'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Handler::get_url().'assets/images/whatsapp_user.png',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'whatsapp_username',
			[
				'label' => esc_html__('Username', 'elementskit'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('John Doe', 'elementskit'),
				'placeholder' => esc_html__('Type your title here', 'elementskit'),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'whatsapp_user_text',
			[
				'label' => esc_html__('User Text', 'elementskit'),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__('Typically replies within a day', 'elementskit'),
				'placeholder' => esc_html__('Type your text here', 'elementskit'),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'ekit_whatsapp_active',
			[
				'label' => esc_html__( 'Enable Active Dot', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__header--img:after' => 'opacity: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ekit_whatsapp_active_custom',
			[
				'label' => esc_html__( 'Enable Custome Active Time', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
				'condition' => [
					'ekit_whatsapp_active' => '1'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_active_start_time',
			[
				'label' => esc_html__( 'Start Time', 'elementskit' ),
				'type' => Controls_Manager::DATE_TIME,
				'picker_options' => [
					'noCalendar'=> true
				],
				'condition' => [
					'ekit_whatsapp_active_custom' => 'yes',
					'ekit_whatsapp_active' => '1'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_active_end_time',
			[
				'label' => esc_html__( 'End Time', 'elementskit' ),
				'type' => Controls_Manager::DATE_TIME,
				'picker_options' => [
					'noCalendar'=> true
				],
				'condition' => [
					'ekit_whatsapp_active_custom' => 'yes',
					'ekit_whatsapp_active' => '1'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_active_holidays',
			[
				'label' => esc_html__( 'Choose Holidays', 'elementskit' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'Friday'  => esc_html__( 'Friday', 'elementskit' ),
					'Saturday' => esc_html__( 'Saturday', 'elementskit' ),
					'Sunday' => esc_html__( 'Sunday', 'elementskit' ),
					'Monday' => esc_html__( 'Monday', 'elementskit' ),
					'Tuesday' => esc_html__( 'Tuesday', 'elementskit' ),
					'Wednesday' => esc_html__( 'Wednesday', 'elementskit' ),
					'Thursday' => esc_html__( 'Thursday', 'elementskit' ),
				],
				'condition' => [
					'ekit_whatsapp_active_custom' => 'yes',
					'ekit_whatsapp_active' => '1'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_vacation_text',
			[
				'label' => esc_html__('Enter Vacation Message', 'elementskit'),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__('We are closed for vacation now', 'elementskit'),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'ekit_whatsapp_active_custom' => 'yes',
					'ekit_whatsapp_active' => '1'
				]
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'body_tab_section',
			[
				'label' => esc_html__('Body', 'elementskit'),
			]
		);

		$this->add_control(
			'ekit_whatsapp_body_loader',
			[
				'label' => esc_html__( 'Enable Loader?', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'ekit_whatsapp_body_username',
			[
				'label' => esc_html__( 'Show Username?', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'whatsapp_asking_text',
			[
				'label' => esc_html__('Asking Text', 'elementskit'),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__('Hey, Do you want to talk with us?', 'elementskit'),
				'placeholder' => esc_html__('Type your text here', 'elementskit'),
				'dynamic' => [
					'active' => true,
				],
			]
		);	

		$this->add_control(
			'ekit_whatsapp_btn_position_toggle',
			[
				'label' => esc_html__( 'Position', 'elementskit' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => esc_html__( 'Default', 'elementskit' ),
				'label_on' => esc_html__( 'Custom', 'elementskit' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_control(
			'ekit_whatsapp_btn_direction',
			[
				'label' => esc_html__( 'Direction', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'  => esc_html__( 'Left', 'elementskit' ),
					'right' => esc_html__( 'Right', 'elementskit' ),
				],
			]
		);

		$this->add_control(
			'ekit_whatsapp_btn_direction_verticle',
			[
				'label' => esc_html__( 'Vertical Position (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'min' => -1000,
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__content' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'ekit_whatsapp_btn_direction_horizontal',
			[
				'label' => esc_html__( 'Horizontal Position (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'default' => [
					'unit' => 'px',
					'min' => -1000,
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__content' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__content' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				],
			]
		);

		$this->end_popover();

		$this->end_controls_section();

		$this->start_controls_section(
			'footer_tab_section',
			[
				'label' => esc_html__('Footer', 'elementskit'),
			]
		);

		$this->add_control(
			'ekit_whatsapp_footer_style',
			[
				'label' => esc_html__( 'Choose Style', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'input',
				'options' => [
					'input'  => esc_html__( 'Input', 'elementskit' ),
					'button' => esc_html__( 'Button', 'elementskit' ),
					'inner-input' => esc_html__( 'Inner Input', 'elementskit' ),
				],
			]
		);

		$this->add_control(
            'ekit_whatsapp_footer_btn_icon',
            [
                'label' => esc_html__( 'Icon', 'elementskit' ),
                'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value' => 'fab fa-whatsapp',
					'library' => 'fa-brands',
				],
                'label_block' => true,
				'condition' => [
					'ekit_whatsapp_footer_style' => 'button'
				]
            ]
        );

		$this->add_control(
			'ekit_whatsapp_input_footer_btn_text',
			[
				'label' => esc_html__('Text', 'elementskit'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Start Chat', 'elementskit'),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'ekit_whatsapp_footer_style' => 'button'
				]
			]
		);

		$this->add_control(
			'whatsapp_input_placeholder',
			[
				'label' => esc_html__('Input Placeholder', 'elementskit'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Write Something', 'elementskit'),
				'placeholder' => esc_html__('Type your text here', 'elementskit'),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'ekit_whatsapp_footer_style!' => 'button'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_footer_link_target',
			[
				'label' => esc_html__( 'Open Link Option', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'default' => '_self',
				'options' => [
					'_self'  => esc_html__( 'Same Page', 'elementskit' ),
					'_blank' => esc_html__( 'New Tab', 'elementskit' ),
					'popup' => esc_html__( 'Popup', 'elementskit' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_tab_section',
			[
				'label' => esc_html__('Button', 'elementskit'),
			]
		);

		$this->add_control(
            'ekit_whatsapp_style',
            [
                'label' => esc_html__('Choose Style', 'elementskit'),
                'type' => ElementsKit_Controls_Manager::IMAGECHOOSE,
                'default' => 'icon',
                'options' => [
                    'icon' => [
                        'title' => esc_html__( 'Icon', 'elementskit' ),
                        'imagelarge' => Handler::get_url() . 'assets/imagechoose/only-icon.png',
                        'imagesmall' => Handler::get_url() . 'assets/imagechoose/only-icon.png',
                        'width' => '50%',
                    ],
                    'icon_with_text' => [
                        'title' => esc_html__( 'Icon With Text', 'elementskit' ),
                        'imagelarge' => Handler::get_url() . 'assets/imagechoose/icon-with-text.png',
                        'imagesmall' => Handler::get_url() . 'assets/imagechoose/icon-with-text.png',
                        'width' => '50%',
                    ],
                    'icon_separate_text' => [
                        'title' => esc_html__( 'Icon Separate Text', 'elementskit' ),
                        'imagelarge' => Handler::get_url() . 'assets/imagechoose/icon-separate-text.png',
                        'imagesmall' => Handler::get_url() . 'assets/imagechoose/icon-separate-text.png',
                        'width' => '50%',
					],
                    'photo_with_text' => [
                        'title' => esc_html__( 'Icon Separate Text', 'elementskit' ),
                        'imagelarge' => Handler::get_url() . 'assets/imagechoose/photo-with-text.png',
                        'imagesmall' => Handler::get_url() . 'assets/imagechoose/photo-with-text.png',
                        'width' => '50%',
					],
                ]
            ]
        );

		$this->add_control(
            'ekit_whatsapp_btn_icon',
            [
                'label' => esc_html__( 'Icon', 'elementskit' ),
                'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
                'label_block' => true,
				'condition' => [
					'ekit_whatsapp_style!' => 'photo_with_text'
				]
            ]
        );

		$this->add_control(
			'ekit_whatsapp_btn_text',
			[
				'label' => esc_html__('Text', 'elementskit'),
				'type' => Controls_Manager::TEXT,
				'label_block' => 'true',
				'default' => 'Contact us',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'ekit_whatsapp_style!' => 'icon'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_btn_subtext',
			[
				'label' => esc_html__('Subtext', 'elementskit'),
				'type' => Controls_Manager::TEXT,
				'label_block' => 'true',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'ekit_whatsapp_style' => 'photo_with_text'
				]
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'settings_tab_section',
			[
				'label' => esc_html__('Settings', 'elementskit'),
			]
		);

		$this->add_control(
			'whatsapp_number',
			[
				'label' => esc_html__('Whatsapp Number', 'elementskit'),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__('+8801700000000', 'elementskit'),
				'placeholder' => esc_html__('Type your whatsapp number', 'elementskit'),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'show_from_first',
			[
				'label' => esc_html__( 'Show From First', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementskit' ),
				'label_off' => esc_html__( 'Hide', 'elementskit' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'whatsapp_btn_style_section',
			[
				'label' => esc_html__( 'Sticky Button', 'elementskit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'ekit_whatsapp_sticky_btn_width',
			[
				'label' => esc_html__( 'Button Width (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp .elementskit-whatsapp__popup--btn' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_whatsapp_sticky_btn_height',
			[
				'label' => esc_html__( 'Button Height (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp .elementskit-whatsapp__popup--btn' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'ekit_whatsapp_sticky_btn_border_radius',
			[
				'label' => esc_html__('Border Radius (px)', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],	
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__popup--btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_sticky_btn_bg',
			[
				'label' => esc_html__( 'Button Background', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__popup--btn' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'name'      => 'ekit_whatsapp_sticky_btn_box_shadow',
                'selector'  => '{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__popup--btn',
            ]
        );		

		$this->add_control(
			'ekit_whatsapp_sticky_btn_padding',
			[
				'label' => esc_html__('Padding (px)', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],	
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__popup--btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_sticky_btn_icon_heading',
			[
				'label' => esc_html__( 'Icon', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'ekit_whatsapp_style!' => 'photo_with_text'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_sticky_btn_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__popup--btn-icon svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .elementskit-whatsapp__popup--btn i' => 'color: {{VALUE}}',
				],
				'condition' => [
					'ekit_whatsapp_style!' => 'photo_with_text'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_sticky_btn_icon_background',
			[
				'label' => esc_html__( 'Icon Background', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54CC61',
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__popup--btn.icon_separate_text .elementskit-whatsapp__popup--btn-icon' => 'background: {{VALUE}}',
				],
				'condition' => [
					'ekit_whatsapp_style' => 'icon_separate_text'
				]
			]
		);

		$this->add_responsive_control(
			'ekit_whatsapp_sticky_btn_icon_size',
			[
				'label' => esc_html__( 'Icon Size (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 26,
				],
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp .elementskit-whatsapp__popup--btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementskit-whatsapp .elementskit-whatsapp__popup--btn i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_whatsapp_style!' => 'photo_with_text'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_sticky_btn_icon_padding',
			[
				'label' => esc_html__( 'Icon Padding (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__popup--btn.icon_separate_text .elementskit-whatsapp__popup--btn-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_whatsapp_style' => 'icon_separate_text'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_sticky_btn_img_heading',
			[
				'label' => esc_html__( 'Image', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'ekit_whatsapp_style' => 'photo_with_text'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_sticky_btn_img_size',
			[
				'label' => esc_html__( 'Size (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}}  .elementskit-whatsapp__popup--btn.photo_with_text img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_whatsapp_style' => 'photo_with_text'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_sticky_btn_text_heading',
			[
				'label' => esc_html__( 'Text', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'ekit_whatsapp_style!' => 'icon'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_sticky_btn_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__popup--btn-text' => 'color: {{VALUE}}',
				],
				'condition' => [
					'ekit_whatsapp_style!' => 'icon'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ekit_whatsapp_sticky_btn_text_typography',
				'selector' => '{{WRAPPER}} .elementskit-whatsapp__popup--btn-text',
				'exclude' => ['font_style', 'text_decoration'],
				'condition' => [
					'ekit_whatsapp_style!' => 'icon'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_sticky_btn_space',
			[
				'label' => esc_html__( 'Space between (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp .elementskit-whatsapp__popup--btn .elementskit-whatsapp__popup--btn-text' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_whatsapp_style!' => 'icon'
				]
			]
		);
		
		$this->add_control(
			'ekit_whatsapp_sticky_btn_subtext_heading',
			[
				'label' => esc_html__( 'Subtext', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'ekit_whatsapp_style' => 'photo_with_text'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_sticky_btn_subtext_color',
			[
				'label' => esc_html__( 'Subtext Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__popup--btn.photo_with_text .elementskit-whatsapp__popup--btn-text span:nth-child(2)' => 'color: {{VALUE}}',
				],
				'condition' => [
					'ekit_whatsapp_style' => 'photo_with_text'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ekit_whatsapp_sticky_btn_subtext_typography',
				'selector' => '{{WRAPPER}} .elementskit-whatsapp__popup--btn.photo_with_text .elementskit-whatsapp__popup--btn-text span:nth-child(2)',
				'exclude' => ['font_style', 'text_decoration'],
				'fields_options' => [
					'typography' => [
						'default'	=> 'yes'
					],
					'font_size'		=> [
						'default' => [
							'unit' => 'px',
							'size' => 10,
						],
					],
				],
				'condition' => [
					'ekit_whatsapp_style' => 'photo_with_text'
				]
			]
		);

		$this->add_responsive_control(
			'ekit_whatsapp_sticky_btn_align',
			[
				'label' => esc_html__( 'Alignment', 'elementskit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'elementskit' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementskit' ),
						'icon' => 'eicon-h-align-center',
					],
					'end' => [
						'title' => esc_html__( 'End', 'elementskit' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'whatsapp_header_style_section',
			[
				'label' => esc_html__( 'Header', 'elementskit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'header_bg',
			[
				'label' => esc_html__( 'Header Background', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__header' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'user_photo_headings',
			[
				'label' => esc_html__( 'User Image Style', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'user_img_border',
				'label' => esc_html__( 'Image Border', 'elementskit' ),
				'selector' => '{{WRAPPER}} .elementskit-whatsapp__header--img img, {{WRAPPER}} .elementskit-whatsapp__popup--btn.photo_with_text img',
			]
		);

		$this->add_control(
			'user_info_headings',
			[
				'label' => esc_html__( 'User Info', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'username_color',
			[
				'label' => esc_html__( 'Username Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__header--name' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'user_text_color',
			[
				'label' => esc_html__( 'User Text Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__header--text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'username_typography',
				'label' => 'Username Typography',
				'selector' => '{{WRAPPER}} .elementskit-whatsapp__header--name',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'user_text_typography',
				'label' => 'User Text Typography',
				'selector' => '{{WRAPPER}} .elementskit-whatsapp__header--text',
			]
		);

		$this->add_control(
			'ekit_whatsapp_user_dot_headings',
			[
				'label' => esc_html__( 'Active / Inactive Dot', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'ekit_whatsapp_active' => '1'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_user_dot_size',
			[
				'label' => esc_html__( 'Size', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 15,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' =>10,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__header--img:after' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'ekit_whatsapp_active' => '1'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_user_dot_color',
			[
				'label' => esc_html__('Border Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'default' => '#008069',
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__header--img:after' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'ekit_whatsapp_active' => '1'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_user_close_headings',
			[
				'label' => esc_html__( 'Close Icon', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'ekit_whatsapp_user_close_color',
			[
				'label' => esc_html__('Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__header--close' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_user_close_hover_color',
			[
				'label' => esc_html__('Hover Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__header--close:hover' => 'color: {{VALUE}};',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'whatsapp_body_style_section',
			[
				'label' => esc_html__( 'Body', 'elementskit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ekit_whatsapp_theme_color',
			[
				'label' => esc_html__( 'Theme Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp .elementskit-whatsapp__wrapper' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'asking_text_color',
			[
				'label' => esc_html__( 'Asking Text Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__chat--title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'asking_text_typography',
				'label' => 'Asking Text Typography',
				'selector' => '{{WRAPPER}} .elementskit-whatsapp__chat--title',
			]
		);

		$this->add_control(
			'ekit_whatsapp_asking_text_username_text_color',
			[
				'label' => esc_html__( 'Useranme Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#999999',
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__chat--title-username' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
				'condition' => [
					'ekit_whatsapp_body_username' => 'yes'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ekit_whatsapp__asking_text_username_typography',
				'label' => 'Username Typography',
				'fields_options' => [
					'typography' => [
						'default'	=> 'yes'
					],
					'font_weight'		=> [
						'default' => '600'
					],
				],
				'selector' => '{{WRAPPER}} .elementskit-whatsapp__chat--title-username',
				'condition' => [
					'ekit_whatsapp_body_username' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'whatsapp_footer_style_section',
			[
				'label' => esc_html__( 'Footer', 'elementskit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ekit_whatsapp_footer_background',
			[
				'label' => esc_html__( 'Background', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__input--wrapper' => 'background: {{VALUE}}',
				],
				'condition' => [
					'ekit_whatsapp_footer_style' => 'button'
				]
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label' => esc_html__( 'Input Placeholder Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__input--field::placeholder' => 'color: {{VALUE}}',
				],
				'condition' => [
					'ekit_whatsapp_footer_style!' => 'button'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_placeholder_typography',
				'label' => 'Input Placeholder Typography',
				'selector' => '{{WRAPPER}} .elementskit-whatsapp__input--field::placeholder',
				'condition' => [
					'ekit_whatsapp_footer_style!' => 'button'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_sticky_footer_btn_padding',
			[
				'label' => esc_html__('Padding', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em'],	
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__input--wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'ekit_whatsapp_footer_style' => 'button'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_footer_btn_icon_heading',
			[
				'label' => esc_html__( 'Icon', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'ekit_whatsapp_footer_style' => 'button'
				],
			]
		);

		$this->add_control(
			'ekit_whatsapp_footer_btn_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__input--button svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .elementskit-whatsapp__input--button i' => 'color: {{VALUE}}',
				],
				'condition' => [
					'ekit_whatsapp_footer_style' => 'button'
				]
			]
		);

		$this->add_responsive_control(
			'ekit_whatsapp_footer_btn_icon_size',
			[
				'label' => esc_html__( 'Icon Size (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp .elementskit-whatsapp__input--button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementskit-whatsapp .elementskit-whatsapp__input--button i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_whatsapp_footer_style' => 'button'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_footer_btn_text_heading',
			[
				'label' => esc_html__( 'Text', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'ekit_whatsapp_footer_style' => 'button'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_footer_btn_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__input--button-text' => 'color: {{VALUE}}',
				],
				'condition' => [
					'ekit_whatsapp_footer_style' => 'button'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ekit_whatsapp_footer_btn_text_typography',
				'selector' => '{{WRAPPER}} .elementskit-whatsapp__input--button-text',
				'exclude' => ['font_style', 'text_decoration', 'line_height'],
				'fields_options' => [
					'typography' => [
						'default'	=> 'yes'
					],
					'font_size'		=> [
						'default' => [
							'unit' => 'px',
							'size' => 15,
						],
					],
				],
				'condition' => [
					'ekit_whatsapp_footer_style' => 'button'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_footer_btn_space',
			[
				'label' => esc_html__( 'Space between (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .elementskit-whatsapp__input--button-text' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_whatsapp_footer_style' => 'button'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_footer_btn_heading',
			[
				'label' => esc_html__( 'Button', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'ekit_whatsapp_footer_style' => 'button'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_footer_btn_background',
			[
				'label' => esc_html__( 'Background', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#5CC263',
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__input--button' => 'background: {{VALUE}}',
				],
				'condition' => [
					'ekit_whatsapp_footer_style' => 'button'
				]
			]
		);

		$this->add_control(
			'ekit_whatsapp_footer_btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__input--button' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementskit-whatsapp__input.inner-input .elementskit-whatsapp__input--wrapper' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_whatsapp_footer_style!' => 'input'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'whatsapp_content_style_section',
			[
				'label' => esc_html__( 'Content Wrapper', 'elementskit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'ekit_whatsapp_content_width',
			[
				'label' => esc_html__( 'Width (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 270,
						'max' => 350,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__content' => 'width: {{SIZE}}{{UNIT}}; --ekit-whatsapp-width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'ekit_whatsapp_content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__content' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp .elementskit-whatsapp__wrapper' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0px 0px;',
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__header' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0px 0px;',
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__input--wrapper' => 'border-radius: 0px 0px {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp .elementskit-whatsapp__wrapper:has(.inner-input)' => 'border-radius:{{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'name'      => 'ekit_whatsapp_content_box_shadow',
                'selector'  => '{{WRAPPER}} .ekit-wid-con .elementskit-whatsapp__content',
            ]
        );


		$this->end_controls_section();


		$this->insert_pro_message();
	}

	protected function render() {
		echo '<div class="ekit-wid-con" >';
		$this->render_raw();
		echo '</div>';
	}

	protected function render_raw() {
		$settings = $this->get_settings_for_display();
		extract($settings);

		$local_time = current_time('H:i');
		$active_dot = '';
		
		if( $ekit_whatsapp_active_custom == 'yes') {
			if(!empty($ekit_whatsapp_active_holidays) && in_array( gmdate("l", current_time('timestamp')), $ekit_whatsapp_active_holidays)){
				$active_dot = 'inactive';
				!empty($ekit_whatsapp_vacation_text) && $whatsapp_asking_text = $ekit_whatsapp_vacation_text;
			} elseif (!($local_time > $ekit_whatsapp_active_start_time && $local_time <= $ekit_whatsapp_active_end_time)) {
				$active_dot = 'inactive';
				!empty($ekit_whatsapp_vacation_text) && $whatsapp_asking_text = $ekit_whatsapp_vacation_text;
			}
		}

		$user_img = $whatsapp_user_image['url'] ?? Handler::get_url().'assets/images/whatsapp_user.png';
		$whatsapp_background = Handler::get_url().'assets/images/bg-whatsapp.png';
		$show_first = $settings['show_from_first'] === 'yes' ? 'show' : 'hide';
		$loader_class = '';
		$footer_class = '';
		?>
		<div class="elementskit-whatsapp elementskit-whatsapp--<?php echo esc_attr($ekit_whatsapp_btn_direction); ?>">
			<div class="elementskit-whatsapp__content" data-show="<?php echo esc_attr($show_first); ?>">
				<div class="elementskit-whatsapp__wrapper" style="background-image : url('<?php echo esc_url($whatsapp_background); ?>">
					<div class="elementskit-whatsapp__header">
						<div class="elementskit-whatsapp__header--img <?php echo esc_attr($active_dot) ?>">
							<img src="<?php echo esc_url($user_img); ?>" alt="<?php echo esc_html($whatsapp_username); ?>">
						</div>
						<div class="elementskit-whatsapp__header--content">
							<h4 class="elementskit-whatsapp__header--name"><?php  echo esc_html($whatsapp_username,'elementskit'); ?></h4>
							<p class="elementskit-whatsapp__header--text"><?php  echo esc_html($whatsapp_user_text,'elementskit'); ?></p>
						</div>
						<span class="elementskit-whatsapp__header--close dashicons dashicons-no-alt"></span>
                 	</div>

					<div class="elementskit-whatsapp__body">
						<div class="elementskit-whatsapp__chat">
							<?php if($ekit_whatsapp_body_loader == 'yes') : 
								$loader_class = 'loader-active'; 
								?>
								<div class="ekit-whatsapp-loader">
									<div class="loader-one"></div>
									<div class="loader-two"></div>
									<div class="loader-three"></div>
								</div>
							<?php endif; ?>
							<p class="elementskit-whatsapp__chat--title <?php echo esc_html($loader_class) ?>" data-time="<?php echo esc_attr($local_time) ?>">
								<?php if($ekit_whatsapp_body_username == 'yes') : ?>
									<span class="elementskit-whatsapp__chat--title-username"><?php echo esc_html($whatsapp_username) ?></span>
								<?php endif;
								 echo wp_kses(\ElementsKit_Lite\Utils::kspan($whatsapp_asking_text), \ElementsKit_Lite\Utils::get_kses_array()); ?>
							</p>
						</div>
					</div>

					<?php if($ekit_whatsapp_footer_style == "inner-input") : 
						$footer_class = 'inner-input';
						include Handler::get_dir().'parts/footer.php';
					endif; ?>
				</div>

				<?php if($ekit_whatsapp_footer_style != "inner-input") : 
					include Handler::get_dir().'parts/footer.php';
				endif;?>
			</div>

			<div class="elementskit-whatsapp__popup">
				<button class="elementskit-whatsapp__popup--btn <?php echo esc_attr($ekit_whatsapp_style)  ?>" aria-label="whatsapp" >
					<span class="elementskit-whatsapp__popup--btn-icon">
						<?php if( $ekit_whatsapp_style !=  'photo_with_text' ) : 
							if(!empty($ekit_whatsapp_btn_icon['value'])) : 
								Icons_Manager::render_icon( $ekit_whatsapp_btn_icon, [ 'aria-hidden' => 'true', 'class' => 'whatsapp-rotate-icon' ]);
							else : ?>
								<svg xmlns="http://www.w3.org/2000/svg" class="whatsapp-rotate-icon" width="24" height="24" viewBox="0 0 24 24" fill="#fff">
									<path d="M11.583 0C17.9555 0 23.1668 5.2106 23.167 11.583C23.1669 17.9554 17.9555 23.167 11.583 23.167C9.61164 23.1669 7.64135 22.6488 5.95898 21.708L2.63867 22.6572C1.33697 23.0287 0.124438 21.8446 0.464844 20.5342L1.36035 17.0889C0.524021 15.4357 4.29782e-05 13.5672 0 11.583C0.000186632 5.2107 5.21073 0.000164798 11.583 0ZM11.583 1.5C6.03916 1.50017 1.50019 6.03913 1.5 11.583C1.50005 13.3924 2.00283 15.1147 2.82031 16.6475C2.90861 16.8134 2.93107 17.0065 2.88379 17.1885L1.91602 20.9111C1.86761 21.098 2.04071 21.2675 2.22656 21.2148L5.85254 20.1787L5.92773 20.1611C6.10489 20.1298 6.28859 20.1634 6.44434 20.2568C7.95132 21.161 9.76757 21.6669 11.583 21.667C17.1271 21.667 21.6669 17.127 21.667 11.583C21.6668 6.03903 17.1271 1.5 11.583 1.5ZM8.46094 5.95801C8.69857 5.95803 8.94239 6.00873 9.16895 6.11523L9.16992 6.11426C9.17344 6.11588 9.17716 6.1175 9.18066 6.11914L9.17969 6.12012C9.41193 6.22902 9.62753 6.39584 9.79492 6.63477L10.9248 8.22461L11.0068 8.34473C11.0831 8.46236 11.148 8.5829 11.2002 8.70801C11.2736 8.8789 11.3301 9.08323 11.3301 9.30176C11.33 9.56151 11.254 9.80403 11.1289 10.0166C11.125 10.0235 11.1222 10.0312 11.1182 10.0381L11.1172 10.0371C11.0158 10.211 10.8847 10.37 10.7432 10.5127L10.7441 10.5137L10.6152 10.6465C10.6715 10.7224 10.7416 10.8125 10.8291 10.916L11.1562 11.2842C11.2672 11.4053 11.3815 11.5267 11.5 11.6475C11.7552 11.8974 11.9931 12.12 12.2305 12.3213C12.3368 12.4111 12.4295 12.4827 12.5078 12.5391L12.6309 12.4189C12.7716 12.2788 12.9346 12.1424 13.1191 12.0391C13.1261 12.0349 13.1336 12.0314 13.1406 12.0273C13.148 12.0233 13.1556 12.0186 13.1631 12.0146C13.3678 11.8987 13.5981 11.8272 13.8555 11.8271C14.0581 11.8271 14.2523 11.8706 14.4375 11.9463C14.5226 11.9811 14.6048 12.0219 14.6846 12.0664L14.917 12.2109L14.9287 12.2188L16.5449 13.3643C16.7374 13.4986 16.902 13.6708 17.0186 13.8887L17.0664 13.9863L17.0762 14.0098C17.1524 14.2001 17.208 14.4174 17.208 14.6689C17.208 14.9571 17.1432 15.2466 17.0137 15.5205C16.9009 15.759 16.7534 15.9875 16.5596 16.2012L16.5586 16.2021C16.2533 16.5381 15.9 16.7979 15.4873 16.9668L15.4863 16.9658C15.1 17.1258 14.6863 17.208 14.251 17.208C13.6347 17.208 13.0028 17.0629 12.3662 16.792C11.7472 16.5287 11.1365 16.1775 10.5391 15.7432C9.94691 15.3112 9.38526 14.8333 8.85156 14.3057L8.8457 14.2998C8.31746 13.7671 7.83842 13.2074 7.41016 12.6201L7.4082 12.6162C6.98145 12.0238 6.62896 11.4194 6.36914 10.8086L6.36816 10.8066C6.10225 10.1759 5.95801 9.54503 5.95801 8.9209C5.95806 8.50177 6.03246 8.09101 6.18652 7.70508C6.34667 7.29887 6.59679 6.93464 6.93066 6.62012L6.93164 6.62207C7.35543 6.2099 7.87318 5.95801 8.46094 5.95801ZM8.46094 7.45801C8.33114 7.45801 8.16987 7.50541 7.9707 7.70117L7.95996 7.71191C7.77667 7.88458 7.65626 8.06675 7.58203 8.25488L7.58008 8.25977C7.50093 8.4575 7.45806 8.67873 7.45801 8.9209C7.45801 9.31009 7.54794 9.74178 7.75 10.2217L7.92188 10.5928C8.1098 10.967 8.34451 11.3498 8.625 11.7393C9.00507 12.2601 9.43123 12.7597 9.90527 13.2383C10.3868 13.7144 10.8908 14.1435 11.4209 14.5303L11.8066 14.7959C12.1921 15.0465 12.575 15.2513 12.9531 15.4121C13.4391 15.6189 13.8714 15.708 14.251 15.708C14.4976 15.708 14.7168 15.6625 14.915 15.5801L14.9189 15.5791C15.1017 15.5043 15.2753 15.3836 15.4482 15.1934C15.5374 15.0951 15.605 14.9923 15.6582 14.8799C15.6946 14.8029 15.708 14.7317 15.708 14.6689C15.708 14.6526 15.7047 14.6336 15.6943 14.6016C15.6916 14.5995 15.6886 14.5966 15.6846 14.5938L15.6777 14.5889L14.0615 13.4434C13.9756 13.3859 13.9145 13.3535 13.8721 13.3359L13.8555 13.3447C13.835 13.3554 13.7791 13.392 13.6875 13.4834L13.6836 13.4873L13.3154 13.8486C13.1289 14.0347 12.8783 14.1514 12.5859 14.1514C12.4839 14.1513 12.3493 14.1394 12.2012 14.084L12.1846 14.0781L12.168 14.0713C12.1411 14.0597 12.116 14.0482 12.1045 14.043C12.0975 14.0398 12.0946 14.0379 12.0928 14.0371L12.0615 14.0244L12.0254 14.0059C11.7942 13.8836 11.5388 13.7002 11.2627 13.4668C10.9844 13.2309 10.7149 12.978 10.4453 12.7139L10.4404 12.709L10.4355 12.7031C10.1683 12.4311 9.92266 12.1608 9.69531 11.8984L9.69043 11.8926C9.46248 11.6236 9.27377 11.3693 9.15039 11.1436L9.11816 11.085L9.09961 11.0312C9.09467 11.0196 9.08119 10.9868 9.06738 10.9502L9.05176 10.9092L9.04102 10.8672C9.03019 10.8236 9.02113 10.7779 9.01465 10.7295L9.00488 10.5742L9.00879 10.4766C9.02919 10.2498 9.12217 10.0279 9.30273 9.84766L9.66309 9.47363L9.67383 9.46289C9.74967 9.38717 9.7937 9.32687 9.81738 9.28809L9.7793 9.21094C9.76165 9.18 9.73844 9.14377 9.70801 9.10156L9.70508 9.09766L8.57227 7.50293L8.56641 7.49609C8.56222 7.49006 8.55996 7.48745 8.55859 7.48633C8.55695 7.48498 8.55197 7.48116 8.54199 7.47656L8.53223 7.47168C8.51796 7.46484 8.49279 7.45803 8.46094 7.45801Z"/>
								</svg>
							<?php endif; 
						else: ?>
							<img src="<?php echo esc_url($user_img); ?>" alt="<?php echo esc_html($whatsapp_username); ?>">
						<?php endif;?>
					</span>
					<?php if(!empty($ekit_whatsapp_btn_text)) : ?> 
						<div class="elementskit-whatsapp__popup--btn-text">
							<span><?php echo esc_html( $ekit_whatsapp_btn_text ); ?></span>
							<?php if(!empty($ekit_whatsapp_btn_subtext)) : ?>
								<span><?php echo esc_html( $ekit_whatsapp_btn_subtext ); ?></span>
							<?php endif;?>
						</div>
					<?php endif;?>
				</button>
			</div>
		</div>
<?php
	}
	
}
