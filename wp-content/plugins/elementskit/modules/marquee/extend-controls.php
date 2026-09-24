<?php


namespace Elementor;

use Elementor\Controls_Manager;
use ElementsKit_Lite\Utils;

defined('ABSPATH') || die();

class ElementsKit_Extend_Marquee {

	public function __construct() {
		// Flexbox Container support
		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls' ] );
	}

	/**
	 * Get marquee assets configuration
	 * @return array
	 */
	private function get_marquee_assets() {
		return [
			'scripts' => [
				[
					'name' => 'elementskit-marquee',
					'conditions' => [
						'terms' => [
							[
								'name' => 'ekit_marquee_enable',
								'operator' => '===',
								'value' => 'yes',
							],
						],
					],
				],
			],
			'styles' => [
				[
					'name' => 'elementskit-marquee',
					'conditions' => [
						'terms' => [
							[
								'name' => 'ekit_marquee_enable',
								'operator' => '===',
								'value' => 'yes',
							],
						],
					],
				],
			],
		];
	}

	/**
	 * Register marquee controls
	 * @param Controls_Stack $element
	 */
	public function register_controls( Controls_Stack $element ) {
		$element->start_controls_section(
			'ekit_section_marquee',
			[
				'label' => ( method_exists( Utils::class, 'get_promo_icon' ) ? Utils::get_promo_icon() : 'ElementsKit ' ) . esc_html__( 'Marquee', 'elementskit' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'ekit_marquee_enable',
			[
				'label' => esc_html__( 'Enable Marquee', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elementskit' ),
				'label_off' => esc_html__( 'No', 'elementskit' ),
				'return_value' => 'yes',
				'default' => '',
				'description' => esc_html__( 'Animate the container content continuously in Flexbox or Grid layouts.', 'elementskit' ),
				'prefix_class' => 'ekit-container-safe-marquee ekit-marquee--',
				'render_type' => 'template',
				'frontend_available' => true,
				'assets' => $this->get_marquee_assets(),
			]
		);

		$element->add_control(
			'ekit_marquee_direction',
			[
				'label' => esc_html__( 'Direction', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right-to-left',
				'options' => [
					'left-to-right' => esc_html__( 'Left to Right', 'elementskit' ),
					'right-to-left' => esc_html__( 'Right to Left', 'elementskit' ),
					'bottom-to-top' => esc_html__( 'Bottom to Top', 'elementskit' ),
					'top-to-bottom' => esc_html__( 'Top to Bottom', 'elementskit' ),
				],
				'condition' => [
					'ekit_marquee_enable' => 'yes',
				],
				'prefix_class' => 'ekit-marquee-direction-',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'ekit_marquee_pause_behavior',
			[
				'label' => esc_html__( 'Pause Behavior', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'  => esc_html__( 'None', 'elementskit' ),
					'hover' => esc_html__( 'Pause on Hover', 'elementskit' ),
					'click' => esc_html__( 'Pause on Click', 'elementskit' ),
				],
				'condition' => [
					'ekit_marquee_enable' => 'yes',
				],
				'prefix_class' => 'ekit-marquee-pause-',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'ekit_marquee_soft_edge_mask',
			[
				'label' => esc_html__( 'Soft Edge Mask', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elementskit' ),
				'label_off' => esc_html__( 'No', 'elementskit' ),
				'return_value' => 'yes',
				'default' => '',
				'prefix_class' => 'ekit-marquee--soft-edge-mask-',
				'condition' => [
					'ekit_marquee_enable' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		// Horizontal direction mask controls.
		$element->add_control(
			'ekit_marquee_left_mask_size',
			[
				'label' => esc_html__( 'Left Mask Size', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 30,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.ekit-marquee--soft-edge-mask-yes .ekit-marquee-wrap:not(.ekit-marquee-wrap--vertical)' => '--ekit-marquee-mask-start: {{SIZE}}%;',
				],
				'condition' => [
					'ekit_marquee_enable'        => 'yes',
					'ekit_marquee_soft_edge_mask' => 'yes',
					'ekit_marquee_direction'     => [ 'left-to-right', 'right-to-left' ],
				],
			]
		);

		$element->add_control(
			'ekit_marquee_right_mask_size',
			[
				'label' => esc_html__( 'Right Mask Size', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 30,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.ekit-marquee--soft-edge-mask-yes .ekit-marquee-wrap:not(.ekit-marquee-wrap--vertical)' => '--ekit-marquee-mask-end: {{SIZE}}%;',
				],
				'condition' => [
					'ekit_marquee_enable'        => 'yes',
					'ekit_marquee_soft_edge_mask' => 'yes',
					'ekit_marquee_direction'     => [ 'left-to-right', 'right-to-left' ],
				],
			]
		);

		// Vertical direction mask controls.
		$element->add_control(
			'ekit_marquee_top_mask_size',
			[
				'label' => esc_html__( 'Top Mask Size', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 30,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.ekit-marquee--soft-edge-mask-yes .ekit-marquee-wrap.ekit-marquee-wrap--vertical' => '--ekit-marquee-mask-start: {{SIZE}}%;',
				],
				'condition' => [
					'ekit_marquee_enable'        => 'yes',
					'ekit_marquee_soft_edge_mask' => 'yes',
					'ekit_marquee_direction'     => [ 'bottom-to-top', 'top-to-bottom' ],
				],
			]
		);

		$element->add_control(
			'ekit_marquee_bottom_mask_size',
			[
				'label' => esc_html__( 'Bottom Mask Size', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 30,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.ekit-marquee--soft-edge-mask-yes .ekit-marquee-wrap.ekit-marquee-wrap--vertical' => '--ekit-marquee-mask-end: {{SIZE}}%;',
				],
				'condition' => [
					'ekit_marquee_enable'        => 'yes',
					'ekit_marquee_soft_edge_mask' => 'yes',
					'ekit_marquee_direction'     => [ 'bottom-to-top', 'top-to-bottom' ],
				],
			]
		);

		$element->add_control(
			'ekit_marquee_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 's' ],
				'range' => [
					's' => [
						'min' => 1,
						'max' => 120,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 's',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ekit-marquee-duration: {{SIZE}}s;',
				],
				'condition' => [
					'ekit_marquee_enable' => 'yes',
				],
			]
		);

		$element->end_controls_section();
	}
}
