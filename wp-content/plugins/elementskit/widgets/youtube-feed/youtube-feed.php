<?php

namespace Elementor;

use \Elementor\ElementsKit_Widget_Youtube_Feed_Handler as Handler;

defined('ABSPATH') || exit;


/**
 * Class ElementsKit_Widget_Youtube_Feed
 *
 * This youtube feed need to must have user permission to work properly
 *
 *
 * @package Elementor
 */
class ElementsKit_Widget_Youtube_Feed extends Widget_Base {
	use \ElementsKit_Lite\Widgets\Widget_Notice;

	public $base;

	public function __construct($data = [], $args = null) {
		add_action('elementor/editor/after_enqueue_scripts', function () {
			wp_enqueue_script('ekit-youtube-feed-delete-cache', Handler::get_url() . 'assets/js/script.js', ['elementor-editor'], \ElementsKit_Lite::version(), true);

			$config = [
				'restUrl' => get_rest_url(),
				'nonce'   => wp_create_nonce('wp_rest'),
			];

			Utils::print_js_config('ekit-youtube-feed-delete-cache', 'youtubeFeedDeleteConfig', $config);
		});

		parent::__construct($data, $args);
	}

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

    public function get_style_depends(): array {
		// magnific-popup styles the video popup shell (overlay, close button,
		// arrows). The editor preview enqueues every widget's styles, so leaving
		// it out here only showed up on the frontend, where the popup opened
		// without any of its own styling.
		return [ 'swiper', 'gallery-filter', 'magnific-popup', 'ekit-pro-youtube-feed' ];
	}

    public function get_script_depends() {
        return ['imagesloaded', 'gallery-filter', 'magnific-popup', 'ekit-pro-youtube-feed'];
    }

    public function get_help_url() {
        return 'https://wpmet.com/doc/youtube-feed/';
    }

	protected function register_controls() {

		$this->start_controls_section(
			'ekit_yf_content_section',
			[
				'label' => esc_html__('Content', 'elementskit'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'ekit_yf_layout_style',
			[
				'label' => esc_html__( 'Layout Style', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'youtube_grid',
				'options' => [
					'youtube_grid'  => esc_html__( 'Grid', 'elementskit' ),
					'youtube_masonary' => esc_html__( 'Masonary', 'elementskit' ),
					'youtube_carousel' => esc_html__( 'Carousel', 'elementskit' ),
				],
				'assets' => [
					'styles' => [
						[
							'name' => 'swiper',
							'conditions' => [
								'terms' => [
									[
										'name' => 'ekit_yf_layout_style',
										'operator' => '===',
										'value' => 'youtube_carousel',
									],
								],
							],
						],
					],
				],
			]
		);

        $this->add_control(
            'ekit_yf_video_popup',
            [
                'label' => esc_html__('Video Play In', 'elementskit'),
                'type' => Controls_Manager::SELECT,
                'default' => 'video-popup',
                'options' => [
                    'video-popup' => esc_html__('Pop Up', 'elementskit'),
                    'inline'      => esc_html__('Play Inline', 'elementskit'),
                    'video-redirect' => esc_html__('Direct Youtube', 'elementskit'),
                ],
                'assets' => [
                    'scripts' => [
                        [
                            'name' => 'magnific-popup',
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name'     => 'ekit_yf_video_popup',
                                        'operator' => '===',
                                        'value'    => 'video-popup',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'ekit_yf_grid_inner_spacing',
            [
                'label' => esc_html__( 'Gap', 'elementskit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
					'unit' => 'px',
					'size' => 15,
				],
                'selectors' => [
                    '{{WRAPPER}} .ekit-wid-con .youtube-grid' => 'grid-gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'ekit_yf_layout_style' => ['youtube_masonary', 'youtube_grid'],
                ],
                // Masonary reads the gap from the rendered gallery config, so it needs a re-render.
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
			'ekit_yf_columns',
			[
				'label' => __( 'Columns', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
                    '1' => esc_html__( '1 Column', 'elementskit' ),
                    '2' => esc_html__( '2 Columns', 'elementskit' ),
                    '3' => esc_html__( '3 Columns', 'elementskit' ),
                    '4' => esc_html__( '4 Columns', 'elementskit' ),
                    '5' => esc_html__( '5 Columns', 'elementskit' ),
                    '6' => esc_html__( '6 Columns', 'elementskit' ),
				],
				'selectors' => [
					'{{WRAPPER}} .youtube-grid' => 'grid-template-columns: repeat( {{VALUE}}, 1fr );',
				],
                'condition' => [
                    'ekit_yf_layout_style' => ['youtube_masonary', 'youtube_grid'],
                ],
                'render_type' => 'template'
			]
		);

        $this->add_control(
			'ekit_yf_access_token',
			[
				'label' => esc_html__( 'Access Tokens', 'elementskit' ),
				'type' => Controls_Manager::TEXT,
                'label_block' => true,
				'placeholder' => esc_html__( 'UCW9L21UJk8gAYfAJ6xxxofA', 'elementskit' ),
			]
		);

        $this->add_control(
			'ekit_yf_access_info',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => 'Note : How to <a href="https://console.cloud.google.com/apis/credentials"  target="_blank" rel="noopener noreferrer">(Generate Token ?)</a>',
			]
		);

        $this->add_control(
			'ekit_yf_type',
			[
				'label' => esc_html__( 'Feed Type', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'playlist',
                'options' => [
					'channel' => esc_html__( 'Channel', 'elementskit' ),
					'playlist' => esc_html__( 'Playlist', 'elementskit' ),
                    'search' => esc_html__( 'Search', 'elementskit' ),
				]
			]
		);


        $this->add_control(
			'ekit_yf_channel_id',
			[
				'label' => esc_html__( 'Channel Id', 'elementskit' ),
				'type' => Controls_Manager::TEXT,
                'label_block' => true,
				'placeholder' => esc_html__( 'wpmet', 'elementskit' ),
                'condition' => [
                    'ekit_yf_type' => ['channel', 'playlist'],
                ],
                'dynamic' => [
					'active' => true,
				],
			]
		);

        $this->add_control(
			'ekit_yf_playlist_id',
			[
				'label' => esc_html__( 'Playlist ID', 'elementskit' ),
				'type' => Controls_Manager::TEXT,
                'label_block' => true,
				'placeholder' => esc_html__( 'PL1ZOroSK5AqX1rA4OJOcxKOhjKTzwAHXG', 'elementskit' ),
                'condition' => [
                    'ekit_yf_type' => 'playlist',
                ],
                'dynamic' => [
					'active' => true,
				],
			]
		);

        $this->add_control(
			'ekit_yf_video_search',
			[
				'label' => esc_html__( 'Search Value', 'elementskit' ),
				'type' => Controls_Manager::TEXT,
                'label_block' => true,
				'placeholder' => esc_html__( 'Search here', 'elementskit' ),
                'condition' => [
                    'ekit_yf_type' => 'search',
                ],
                'dynamic' => [
					'active' => true,
				],
			]
		);

        $this->add_control(
			'ekit_yf_thumb_size',
			[
				'label' => esc_html__( 'Thumbnail Quality', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'medium',
                'options' => [
                    'default'  => esc_html__( 'Default', 'elementskit' ),
					'medium' => esc_html__( 'Medium', 'elementskit' ),
                    'high'  => esc_html__( 'High', 'elementskit' ),
				]
			]
		);

        $this->add_control(
			'ekit_yf_video_order',
			[
				'label' => esc_html__( 'Video Order', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
                'options' => [
					'date' => esc_html__( 'Date','elementskit' ),
					'title' => esc_html__( 'Title','elementskit' ),	
					'rating' => esc_html__( 'Rating','elementskit' ),
					'relevance' => esc_html__( 'Relevance','elementskit' ),
					'viewCount' => esc_html__( 'Views','elementskit' ),		
				],
                'condition' => [
                    'ekit_yf_type' => [ 'search', 'channel' ],
                ],
			]
		);

        $this->add_control(
			'ekit_yf_video_max_result',
			[
				'label' => esc_html__( 'Max Results', 'elementskit' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 500,
				'default' => 9,
			]
		);

        // Delete cache
		 $this->add_control( 
			'ekit_yf_video_empty_cache', 
			[
				'label' => esc_html__( 'Delete Cache?', 'elementskit' ),
				'type'        => Controls_Manager::BUTTON,
				'button_type' => 'default',
				'text'        => esc_html__('Click Here', 'elementskit'),
				'event'       => 'ekit:editor:youtube_feed_delete_cache',
			]
		);

		$this->end_controls_section();

        // Statistics Icon
        $this->start_controls_section(
            'ekit_yf_icon_section',
            [
                'label' => esc_html__('Icon', 'elementskit'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
    
        $this->add_control(
            'ekit_yf_youtube_thumb_icon',
            [
                'label' => esc_html__( 'Youtube Icon', 'elementskit' ),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                'value' => 'icon icon-youtube-v',
                'library' => 'fab brands',
                ],
            ]
        );

        $this->add_control(
            'ekit_yf_video_btn_icon',
            [
                'label' => esc_html__( 'Play Icon', 'elementskit' ),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-play',
					'library' => 'fa-solid',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'ekit_yf_video_play_button',
                            'operator' => '===',
                            'value'    => 'yes',
                        ],
                        [
                            'name'     => 'ekit_yf_video_popup',
                            'operator' => '!==',
                            'value'    => 'inline',
                        ],
                    ], 
                ],
                'separator' => 'after'
            ]
        );
    
        $ekit_youtube_feed = new Repeater();
    
        // set icon
        $ekit_youtube_feed->add_control(
            'ekit_yf_statistics_icon',
            [
                'label' => esc_html__( 'Icon', 'elementskit' ),
                'label_block' => true,
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'icon icon-facebook',
                    'library' => 'ekiticons',
                ],
            ]
        );

        // set statistics type
        $ekit_youtube_feed->add_control(
            'ekit_yf_statistics_type',
            [
                'label' => esc_html__( 'Type', 'elementskit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'views',
                'options' => [
                    'viewCount' => esc_html__( 'Views', 'elementskit' ),
                    'likeCount' => esc_html__( 'Likes', 'elementskit' ),
                    'favoriteCount' => esc_html__( 'Favourite', 'elementskit' ),
                    'commentCount' => esc_html__( 'Comments', 'elementskit' ),
                ],
            ]
        );
    
        // set statistics label
        $ekit_youtube_feed->add_control(
            'ekit_yf_statistics_label',
            [
                'label' => esc_html__( 'Label', 'elementskit' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'ekit_yf_statistics',
            [
                'label' => esc_html__('Statistics', 'elementskit'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $ekit_youtube_feed->get_controls(),
                'prevent_empty' => false,
                'condition' => [
                    'ekit_yf_statistics_show' => 'yes',
                ],
                'defult' => [
                    'ekit_yf_statistics_text' => esc_html__('Views', 'elementskit'),
                ],
                'default' => [
                    [
                        'ekit_yf_statistics_icon' => [
                            'value' => 'icon icon-eye',
                            'library'   => 'ekiticons'
                        ],
                        'ekit_yf_statistics_type' => 'viewCount',
                    ],
                    [
                        'ekit_yf_statistics_icon' => [
                            'value' => 'icon icon-like1',
                            'library'   => 'ekiticons'
                        ],
                        'ekit_yf_statistics_type' => 'likeCount',
                    ],
                    [
                        'ekit_yf_statistics_icon' => [
                            'value' => 'icon icon-heart1',
                            'library'   => 'ekiticons'
                        ],
                        'ekit_yf_statistics_type' => 'favoriteCount',
                    ],
                    [
                        'ekit_yf_statistics_icon' => [
                            'value' => 'icon icon-comment',
                            'library'   => 'ekiticons'
                        ],
                        'ekit_yf_statistics_type' => 'commentCount',
                    ],
                ],
                'title_field' => '{{{ ekit_yf_statistics_type }}}',
            ]
        );
    
        $this->end_controls_section();

        // settings
        $this->start_controls_section(
            'ekit_yf_setting_section',
            [
                'label' => esc_html__('Settings', 'elementskit'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'ekit_yf_video_title',
            [
                'label' => esc_html__( 'Video Title', 'elementskit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'elementskit' ),
                'label_off' => esc_html__( 'Hide', 'elementskit' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
			'ekit_yf_video_play_button',
			[
				'label' => esc_html__( 'Play Button', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementskit' ),
				'label_off' => esc_html__( 'Hide', 'elementskit' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

        $this->add_control(
            'ekit_yf_statistics_show',
            [
                'label' => esc_html__( 'Statistics', 'elementskit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'elementskit' ),
                'label_off' => esc_html__( 'Hide', 'elementskit' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ekit_yf_statistics_position',
            [
                'label' => esc_html__( 'Statistics Position', 'elementskit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'separate',
                'options' => [
                    'separate' => esc_html__( 'Separate', 'elementskit' ),
                    'in_thumb' => esc_html__( 'In Channel Header', 'elementskit' ),
                    'after_thumb' => esc_html__( 'After Channel Header', 'elementskit' ),
                ],
                'condition' => [
                    'ekit_yf_statistics_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
			'ekit_yf_video_description_show',
			[
				'label' => esc_html__( 'Description', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementskit' ),
				'label_off' => esc_html__( 'Hide', 'elementskit' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

        $this->add_control(
            'ekit_yf_thumb_image_position',
            [
                'label' => esc_html__( 'Thumb Position', 'elementskit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'center',
                'prefix_class'  => 'ekit-youtube-feed-video-position-',
                'options' => [
                    'top' => esc_html__( 'Top', 'elementskit' ),
                    'center' => esc_html__( 'Before Statistics', 'elementskit' ),
                    'middle' => esc_html__( 'Before Description', 'elementskit' ),
                    'bottom' => esc_html__( 'Bottom', 'elementskit' ),
                    'left' => esc_html__( 'Left', 'elementskit' ),
                    'right' => esc_html__( 'Right', 'elementskit' ),
                ],
                'render_type' => 'template'
            ]
        );

        $this->add_control(
			'ekit_yf_video_title_heading',
			[
				'label' => esc_html__( 'Video Title', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
                'condition' => [
					'ekit_yf_video_title' => 'yes',
				],
			]
		);

        $this->add_control(
            'ekit_yf_video_title_limit_enable',
            [
				'label' => esc_html__( 'Letter Limit', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'elementskit' ),
				'label_off' => esc_html__( 'Disable', 'elementskit' ),
                'return_value' => 'yes',
				'default' => 'no',
                'condition' => [
					'ekit_yf_video_title' => 'yes',
				],
			]
        );

        $this->add_control(
			'ekit_yf_title_letter_count',
			[
				'label' => esc_html__( 'Letter Count', 'elementskit' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 300,
				'default' => 50,
                'condition' => [
                    'ekit_yf_video_title' => 'yes',
					'ekit_yf_video_title_limit_enable' => 'yes',
				],
			]
		);

        $this->add_control(
			'ekit_yf_video_description_heading',
			[
				'label' => esc_html__( 'Video Description', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
                'condition' => [
					'ekit_yf_video_description_show' => 'yes',
				],
			]
		);

        $this->add_control(
            'ekit_yf_video_description_limit_enable',
            [
				'label' => esc_html__( 'Letter Limit', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Enable', 'elementskit' ),
				'label_off' => esc_html__( 'Disable', 'elementskit' ),
                'return_value' => 'yes',
				'default' => 'yes',
                'condition' => [
					'ekit_yf_video_description_show' => 'yes',
				],
			]
        );

        $this->add_control(
			'ekit_yf_description_letter_count',
			[
				'label' => esc_html__( 'Letter Count', 'elementskit' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 300,
				'default' => 50,
                'condition' => [
                    'ekit_yf_video_description_show' => 'yes',
					'ekit_yf_video_description_limit_enable' => 'yes',
				],
			]
		);

        $this->add_control(
			'ekit_yf_video_description_see_more',
			[
				'label' => esc_html__( 'See More Button', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementskit' ),
				'label_off' => esc_html__( 'Hide', 'elementskit' ),
				'return_value' => 'yes',
				'default' => 'no',
                'description' => esc_html__( 'Appended to the trimmed description. Opens the video on Youtube in a new tab.', 'elementskit' ),
                'condition' => [
                    'ekit_yf_video_description_show' => 'yes',
					'ekit_yf_video_description_limit_enable' => 'yes',
				],
			]
		);

        $this->add_control(
			'ekit_yf_video_description_see_more_text',
			[
				'label' => esc_html__( 'See More Text', 'elementskit' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'See More', 'elementskit' ),
                'dynamic' => [
					'active' => true,
				],
                'condition' => [
                    'ekit_yf_video_description_show' => 'yes',
					'ekit_yf_video_description_limit_enable' => 'yes',
                    'ekit_yf_video_description_see_more' => 'yes',
				],
			]
		);

        // Load More
        $this->add_control(
            'ekit_yf_load_more_heading',
            [
                'label' => esc_html__( 'Load More', 'elementskit' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'ekit_yf_layout_style!' => 'youtube_carousel',
                ],
            ]
        );

        $this->add_control(
            'ekit_yf_load_more_enable',
            [
                'label' => esc_html__( 'Enable Load More', 'elementskit' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'elementskit' ),
                'label_off' => esc_html__( 'No', 'elementskit' ),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__( 'Show a few videos first and reveal more on click (works within the "Max Results" count).', 'elementskit' ),
                'condition' => [
                    'ekit_yf_layout_style!' => 'youtube_carousel',
                ],
            ]
        );

        $this->add_control(
            'ekit_yf_load_more_initial',
            [
                'label' => esc_html__( 'Initially Show', 'elementskit' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'default' => 6,
                'description' => esc_html__( 'Number of videos shown before clicking Load More. Keep it below Max Results, otherwise every video is visible already and no button is shown.', 'elementskit' ),
                'condition' => [
                    'ekit_yf_load_more_enable' => 'yes',
                    'ekit_yf_layout_style!' => 'youtube_carousel',
                ],
            ]
        );

        $this->add_control(
            'ekit_yf_load_more_count',
            [
                'label' => esc_html__( 'Load More Count', 'elementskit' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'default' => 3,
                'description' => esc_html__( 'Number of additional videos revealed per click.', 'elementskit' ),
                'condition' => [
                    'ekit_yf_load_more_enable' => 'yes',
                    'ekit_yf_layout_style!' => 'youtube_carousel',
                ],
            ]
        );

        $this->add_control(
            'ekit_yf_load_more_text',
            [
                'label' => esc_html__( 'Button Text', 'elementskit' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Load More', 'elementskit' ),
                'condition' => [
                    'ekit_yf_load_more_enable' => 'yes',
                    'ekit_yf_layout_style!' => 'youtube_carousel',
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->end_controls_section();

        // slider settings
        $this->start_controls_section(
			'ekit_yf_slider_setting_section',
			[
				'label' => esc_html__('Slider Settings', 'elementskit'),
				'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
					'ekit_yf_layout_style' => 'youtube_carousel',
				],
			]
		);

        $this->add_responsive_control(
			'ekit_yf_slider_spacing',
			[
				'label' => esc_html__( 'Spacing (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 15,
					'unit' => 'px',
				]
			]
		);

		$this->add_responsive_control(
			'ekit_yf_slider_slidetoshow',
			[
				'label' => esc_html__( 'Slides To Show', 'elementskit' ),
				'type' =>  Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 12,
					],
				],
				'default' => [
					'size' => 3,
					'unit' => 'px',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_yf_slider_slide_scrroll',
			[
				'label' => esc_html__( 'Slides To Scroll', 'elementskit' ),
				'type' =>  Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 4,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 1,
					'unit' => 'px',
				],
			]
		);

		$this->add_control(
			'ekit_yf_slider_speed',
			[
				'label' => esc_html__( 'Speed (ms)', 'elementskit' ),
				'type' =>  Controls_Manager::NUMBER,
				'min' => 500,
				'max' => 15000,
				'default' => 1000,
			]
		);

        $this->add_control(
			'ekit_yf_slider_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'elementskit' ),
				'type' =>  Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elementskit' ),
				'label_off' => esc_html__( 'No', 'elementskit' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

        $this->add_control(
			'ekit_yf_slider_show_arrow',
			[
				'label' => esc_html__( 'Show Arrow', 'elementskit' ),
				'type' =>   Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elementskit' ),
				'label_off' => esc_html__( 'No', 'elementskit' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

        $this->add_control(
			'ekit_yf_slider_scrollbar',
			[
				'label' => esc_html__( 'Show Scrollbar', 'elementskit' ),
				'type' =>   Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elementskit' ),
				'label_off' => esc_html__( 'No', 'elementskit' ),
				'return_value' => 'yes',
			]
		);

        $this->add_control(
			'ekit_yf_slider_loop',
			[
				'label' => esc_html__( 'Enable Loop?', 'elementskit' ),
				'type' =>   Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elementskit' ),
				'label_off' => esc_html__( 'No', 'elementskit' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

        $this->add_control(
			'ekit_yf_slider_show_dot',
			[
				'label' => esc_html__( 'Show Dots', 'elementskit' ),
				'type' =>   Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'elementskit' ),
				'label_off' => esc_html__( 'No', 'elementskit' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

        $this->add_control(
			'ekit_yf_slider_right_arrow_icon',
			[
				'label' => esc_html__( 'Right Arrow Icon', 'elementskit' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'youtube_feed_right_arrow',
                'skin' => 'inline',
				'default' => [
					'value' => 'icon icon-right-arrow1',
					'library' => 'ekiticons',
				],
                'condition' => [
					'ekit_yf_slider_show_arrow' => 'yes',
				]
			]
		);

        $this->add_control(
			'ekit_yf_slider_left_arrow_icon',
			[
				'label' => esc_html__( 'Left Arrow Icon', 'elementskit' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'youtube_feed_left_arrow',
                'skin' => 'inline',
				'default' => [
					'value' => 'icon icon-left-arrows',
					'library' => 'ekiticons',
				],
                'condition' => [
					'ekit_yf_slider_show_arrow' => 'yes',
				]
			]
		);

        $this->end_controls_section();

        // wrapper style
		$this->start_controls_section(
			'ekit_yf_wrapper_section',
			[
				'label' => esc_html__( 'Wrapper', 'elementskit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs('ekit_yf_tab');

        $this->start_controls_tab(
            'ekit_yf_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'elementskit' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'ekit_yf_bg_group',
                'label' => esc_html__( 'Background', 'elementskit' ),
                'types' => [ 'classic', 'gradient'],
                'selector' => '{{WRAPPER}} .youtube-wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ekit_yf_shadow_group',
                'label' => esc_html__( 'Box Shadow', 'elementskit' ),
                'selector' => '{{WRAPPER}} .youtube-wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ekit_yf_border_group',
                'label' => esc_html__( 'Border', 'elementskit' ),
                'selector' => '{{WRAPPER}} .youtube-wrap',
                'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
                    'size_units'     => ['px'],
					'width'  => [
						'default' => [
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
						],
					],
					'color'  => [
						'default' => '#E3E4E8',
                    ]
                ]    
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'ekit_yf_hv_tab',
            [
                'label' => esc_html__( 'Hover', 'elementskit' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'ekit_yf_bg_hover_group',
                'label' => esc_html__( 'Background', 'elementskit' ),
                'types' => [ 'classic', 'gradient'],
                'selector' => '{{WRAPPER}} .youtube-wrap:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ekit_yf_shadow_hv_group',
                'label' => esc_html__( 'Box Shadow', 'elementskit' ),
                'selector' => '{{WRAPPER}} .youtube-wrap:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ekit_yf_border_hv_group',
                'label' => esc_html__( 'Border', 'elementskit' ),
                'selector' => '{{WRAPPER}} .youtube-wrap:hover',   
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

		$this->add_responsive_control(
            'ekit_yf_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top'      => '5',
                    'right'    => '5',
                    'bottom'   => '5',
                    'left'     => '5',
                    'unit'     => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'separator' => 'before',
            ]
        );

		$this->add_responsive_control(
            'ekit_yf_padding',
            [
                'label' => esc_html__( 'Padding', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' =>     [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'ekit_yf_margin',
            [
                'label' => esc_html__( 'Margin', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' =>     [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_section();

        //video thumb
		$this->start_controls_section(
			'ekit_yf_video_thumb',
			[
				'label' => esc_html__( 'Video Thumb', 'elementskit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'ekit_yf_video_thumb_width',
			[
				'label' => esc_html__( 'Video Thumb Width', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 800,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .youtube-video-thumb img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'ekit_yf_video_thumb_height',
			[
				'label' => esc_html__( 'Video Thumb Height', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 800,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 220,
				],
				'selectors' => [
					'{{WRAPPER}} .youtube-video-thumb img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'ekit_yf_viedo_thumb_overlay',
			[
				'label' => esc_html__( 'Overlay Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .youtube-video-thumb::before' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'ekit_yf_viedo_thumb_hv_overlay',
			[
				'label' => esc_html__( 'Hover Overlay Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .youtube-content:hover .youtube-video-thumb::after' => 'background-color: {{VALUE}}; top: 0%;',
				],
			]
		);

		$this->add_responsive_control(
            'ekit_yf_video_thumb_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top'      => '0',
                    'right'    => '0',
                    'bottom'   => '0',
                    'left'     => '0',
                    'unit'     => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-video-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .youtube-video-thumb:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .youtube-video-thumb::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
			'ekit_yf_video_thumb_margin',
			[
				'label'      => esc_html__('Margin', 'elementskit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'default'    => [
					'unit'     => 'px',
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .youtube-video-thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        // youtube thumb style
        $this->start_controls_section(
			'ekit_yf_youtube_thumb',
			[
				'label' => esc_html__( 'Youtube Thumb', 'elementskit' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'ekit_yf_channel_heading',
			[
				'label' => esc_html__( 'Channel Thumb', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
			]
		);

        $this->add_control(
			'ekit_yf_channel_thumb_width',
			[
				'label' => esc_html__( 'Width', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .youtube-channel-thumb img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'ekit_yf_channel_thumb_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default' => [
                    'top'      => '50',
                    'right'    => '50',
                    'bottom'   => '50',
                    'left'     => '50',
                    'unit'     => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-channel-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ekit_yf_channel_thumb_margin',
            [
                'label' =>esc_html__( 'Margin', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-channel-thumb img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '10',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => '',
                ]
            ]
        );

        $this->add_control(
			'ekit_yf_thumb_icon_heading',
			[
				'label' => esc_html__( 'Thumb Icon', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
                'separator' => 'before',
			]
		);
        
        $this->add_control(
			'ekit_yf_icon_size',
			[
				'label' => esc_html__( 'Font Size', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .youtube-thumb i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .youtube-thumb svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'ekit_yf_icon_color',
			[
				'label' => esc_html__( 'Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .youtube-thumb i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .youtube-thumb svg' => 'fill: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'ekit_yf_content_thumb_bg',
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .youtube-thumb i, {{WRAPPER}} .youtube-thumb svg',
                'exclude'  => ['image'],
            ],
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_yf_content_thumb_border',
				'selector' => '{{WRAPPER}} .youtube-thumb i, {{WRAPPER}} .youtube-thumb svg',
            ],
		);

        $this->add_responsive_control(
            'ekit_yf_content_thumb_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%'],
                'default' => [
                    'top'      => '0',
                    'right'    => '0',
                    'bottom'   => '0',
                    'left'     => '0',
                    'unit'     => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-thumb i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .youtube-thumb svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'youtube_content_thumb_padding',
            [
                'label' =>esc_html__( 'Padding', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-thumb i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .youtube-thumb svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'ekit_yf_content_thumb_margin',
            [
                'label' =>esc_html__( 'Margin', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'separator'  => 'before',
                'selectors' => [
                    '{{WRAPPER}} .youtube-content-thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '20',
                    'right' => '30',
                    'bottom' => '15',
                    'left' => '30',
                    'unit' => 'px',
                    'isLinked' => '',
                ],
            ]
        );

        $this->end_controls_section();

        // youtube thumb style
        $this->start_controls_section(
            'ekit_yf_play_button',
            [
                'label' => esc_html__( 'Play Button', 'elementskit' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms'    => [
                        [
                            'name'     => 'ekit_yf_video_play_button',
                            'operator' => '===',
                            'value'    => 'yes',
                        ],
                        [
                            'name'     => 'ekit_yf_video_popup',
                            'operator' => '!==',
                            'value'    => 'inline',
                        ],
                    ], 
                ],
            ]
        );

        $this->add_control(
			'ekit_yf_play_button_font_size',
			[
				'label' => esc_html__( 'Font Size', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn :is(i)' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn :is(svg)' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'ekit_yf_play_button_width',
			[
				'label' => esc_html__( 'Button Width', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn ' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->start_controls_tabs(
			'ekit_yf_play_button_tabs'
		);

        $this->start_controls_tab(
			'ekit_yf_play_button_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'elementskit' ),
			]
		);

        $this->add_control(
			'ekit_yf_play_button_color',
			[
				'label' => esc_html__( 'Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn :is(i)' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn :is(svg)' => 'fill: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'ekit_yf_play_button_bg',
			[
				'label' => esc_html__( 'Background Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn' => 'background: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ekit_yf_play_button_box_shadow',
				'selector' => '{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn',
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_yf_play_button_border',
				'selector' => '{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn',
			]
		);

        $this->add_responsive_control(
            'ekit_yf_play_button_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                '{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
			'ekit_yf_play_button_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'elementskit' ),
			]
		);

        $this->add_control(
			'ekit_yf_play_button_hover_color',
			[
				'label' => esc_html__( 'Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
                    '{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn:hover :is(i)' => 'color: {{VALUE}}; transition: 0.3s;',
                    '{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn:hover :is(svg)' => 'fill: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'ekit_yf_play_button_hover_bg',
			[
				'label' => esc_html__( 'Background Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
                    '{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn:hover' => 'background: {{VALUE}}; transition: 0.3s;',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_yf_play_button_hv_border',
				'selector' => '{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn:hover',
			]
		);

        $this->add_responsive_control(
            'ekit_yf_play_button_hv_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                    'unit' => 'px',
                    'isLinked' => '',
                ]
            ]
        );

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
                'name' => 'ekit_yf_play_button_hv_box_shadow',
                'selector' => '{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn:hover',
			]
		);

        $this->end_controls_tab();

		$this->end_controls_tabs();

        $this->add_responsive_control(
            'ekit_yf_play_button_padding',
            [
                'label' => esc_html__( 'Padding', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'separator'  => 'before',
                'selectors' => [
                    '{{WRAPPER}} .ekit-wid-con .youtube-feedback .youtube-video-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                    'unit' => 'px',
                    'isLinked' => '',
                ]
            ]
        );

        $this->end_controls_section();

        // content style
		$this->start_controls_section(
            'ekit_yf_style_content_section',
            [
                'label' => esc_html__( 'Content', 'elementskit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
            'ekit_yf_channel_name',
            [
                'label' => esc_html__( 'Channel Name', 'elementskit' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ekit_yf_channel_name_typography_group',
                'selector' => '{{WRAPPER}} .youtube-channel-name',
            ]
        );

		$this->add_control(
            'ekit_yf_channel_name_color',
            [
                'label' => esc_html__( 'Color', 'elementskit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .youtube-channel-name' => 'color: {{VALUE}};',
                ],
            ]
        );
		
        $this->add_control(
            'ekit_yf_channel_name_color_hover',
            [
                'label' => esc_html__( 'Color Hover', 'elementskit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .youtube-channel-name:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ekit_yf_channel_name_bottom_space',
            [
                'label' => esc_html__( 'Margin', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-channel-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '10',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                    'unit' => 'px',
                    'isLinked' => '',
                ]
            ]
        );

        $this->add_responsive_control(
            'ekit_yf_channel_name_padding',
            [
                'label' => esc_html__( 'Padding', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-channel-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default'    => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => '',
                ],
            ]
        );

		$this->add_control(
            'ekit_yf_video_title_style_heading',
            [
                'label' => esc_html__( 'Title', 'elementskit' ),
                'type' => Controls_Manager::HEADING,
				'separator' => 'before',
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ekit_yf_video_title_typography_group',
                'selector' => '{{WRAPPER}} .youtube-video-title',
            ]
        );

		$this->add_control(
            'ekit_yf_video_title_color',
            [
                'label' => esc_html__( 'Color', 'elementskit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .youtube-video-title' => 'color: {{VALUE}};',
                ],
            ]
        );
		
        $this->add_control(
            'ekit_yf_video_title_color_hover',
            [
                'label' => esc_html__( 'Color Hover', 'elementskit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .youtube-video-title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ekit_yf_video_title_bottom_space',
            [
                'label' => esc_html__( 'Margin', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-video-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '30',
                    'bottom' => '10',
                    'left' => '30',
                    'unit' => 'px',
                    'isLinked' => 'false',
                ]
            ]
        );

		$this->add_control(
            'ekit_yf_video_description',
            [
                'label' => esc_html__( 'Description', 'elementskit' ),
                'type' => Controls_Manager::HEADING,
				'separator' => 'before',
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ekit_yf_video_description_typography_group',
                'selector' => '{{WRAPPER}} .youtube-video-description',
            ]
        );

		$this->add_control(
            'ekit_yf_video_description_color',
            [
                'label' => esc_html__( 'Color', 'elementskit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .youtube-video-description' => 'color: {{VALUE}};',
                ],
            ]
        );
		
        $this->add_responsive_control(
            'ekit_yf_video_description_bottom_space',
            [
                'label' => esc_html__( 'Margin', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-video-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '30',
                    'bottom' => '20',
                    'left' => '30',
                    'unit' => 'px',
                    'isLinked' => '',
                ]
            ]
        );

        $this->add_control(
            'ekit_yf_video_see_more_style_heading',
            [
                'label' => esc_html__( 'See More Button', 'elementskit' ),
                'type' => Controls_Manager::HEADING,
				'separator' => 'before',
                'condition' => [
                    'ekit_yf_video_description_see_more' => 'yes',
				],
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ekit_yf_video_see_more_typography_group',
                'selector' => '{{WRAPPER}} .youtube-video-description .show-more-desc',
                'condition' => [
                    'ekit_yf_video_description_see_more' => 'yes',
				],
            ]
        );

		$this->add_control(
            'ekit_yf_video_see_more_color',
            [
                'label' => esc_html__( 'Color', 'elementskit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .youtube-video-description .show-more-desc' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'ekit_yf_video_description_see_more' => 'yes',
				],
            ]
        );

        $this->add_control(
            'ekit_yf_video_see_more_color_hover',
            [
                'label' => esc_html__( 'Color Hover', 'elementskit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .youtube-video-description .show-more-desc:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'ekit_yf_video_description_see_more' => 'yes',
				],
            ]
        );

        $this->add_control(
            'ekit_yf_video_pb_time',
            [
                'label' => esc_html__( 'Video Published Time', 'elementskit' ),
                'type' => Controls_Manager::HEADING,
				'separator' => 'before',
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ekit_yf_video_pb_time_typography_group',
                'selector' => '{{WRAPPER}} .video-plublied-time',
            ]
        );

		$this->add_control(
            'ekit_yf_video_pb_time_color',
            [
                'label' => esc_html__( 'Color', 'elementskit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .video-plublied-time' => 'color: {{VALUE}};',
                ],
            ]
        );
		
        $this->add_control(
            'ekit_yf_video_pb_time_color_hover',
            [
                'label' => esc_html__( 'Color Hover', 'elementskit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .video-plublied-time:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ekit_yf_video_pb_time_bottom_space',
            [
                'label' => esc_html__( 'Margin', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .video-plublied-time' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '10',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                    'unit' => 'px',
                    'isLinked' => '',
                ]
            ]
        );

		$this->end_controls_section();

        $this->start_controls_section(
            'ekit_yf_video_details',
            [
                'label' => esc_html__( 'Statistics', 'elementskit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ekit_yf_statistics_show' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ekit_yf_video_list_typography_group',
                'selector' => '{{WRAPPER}} .ekit-yf-statistics-text',
            ]
        );

		$this->add_control(
            'ekit_yf_video_list_color',
            [
                'label' => esc_html__( 'Color', 'elementskit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ekit-yf-statistics-text' => 'color: {{VALUE}};',
                ],
            ]
        );
		
        $this->add_control(
            'ekit_yf_video_list_color_hover',
            [
                'label' => esc_html__( 'Color Hover', 'elementskit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ekit-yf-statistics-text:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ekit_yf_video_list_padding',
            [
                'label' => esc_html__( 'Padding', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-video-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '10',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                    'unit' => 'px',
                    'isLinked' => '',
                ]
            ]
        );

        $this->add_control(
			'ekit_yf_video_statistics_icon',
			[
				'label' => esc_html__( 'Statistics Icon', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'ekit_yf_video_statistics_icon_size',
			[
				'label' => esc_html__( 'Font Size', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .youtube-video-list i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .youtube-video-statistics-count' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .youtube-video-list svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'ekit_yf_video_statistics_icon_color',
			[
				'label' => esc_html__( 'Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .youtube-video-list i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .youtube-video-list svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .youtube-video-statistics-count' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'ekit_yf_video_statistics_icon_hv_color',
			[
				'label' => esc_html__( 'Hover Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .youtube-video-list i:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .youtube-video-list svg:hover' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .youtube-video-statistics-count' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_responsive_control(
            'ekit_yf_video_list_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px'],
                'selectors' => [
                    '{{WRAPPER}} .youtube-video-list i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .youtube-video-list svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .youtube-video-statistics-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '6',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => '',
                ]
            ]
        );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_yf_video_statistics_border',
				'selector' => '{{WRAPPER}} .youtube-video-details',
                'separator'  => 'before',
			]
		);

        $this->add_responsive_control(
            'ekit_yf_video_statistics_padding',
            [
                'label' => esc_html__( 'Padding', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-video-details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '',
                    'unit' => 'px',
                    'isLinked' => '',
                ]
            ]
        );

        $this->add_responsive_control(
            'ekit_yf_video_statistics_margin',
            [
                'label' => esc_html__( 'Margin', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-video-details' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '15',
                    'right' => '30',
                    'bottom' => '15',
                    'left' => '30',
                    'unit' => 'px',
                    'isLinked' => '',
                ]
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'ekit_yf_slider_arrow_dot_style',
            [
                'label' => esc_html__( 'Arrow & Dot', 'elementskit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name'     => 'ekit_yf_layout_style',
                            'operator' => '===',
                            'value'    => 'youtube_carousel',
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name'     => 'ekit_yf_slider_show_arrow',
                                    'operator' => '===',
                                    'value'    => 'yes',
                                ],
                                [
                                    'name'     => 'ekit_yf_slider_show_dot',
                                    'operator' => '===',
                                    'value'    => 'yes',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
			'ekit_yf_slider_arrow',
			[
				'label' => esc_html__( 'Slider Arrow', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
                'condition' => [
                    'ekit_yf_slider_show_arrow' => 'yes',
                ],
			]
		);

        $this->add_responsive_control(
            'ekit_yf_slider_arrow_size',
            [
                'label' => esc_html__( 'Arrow Size', 'elementskit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-feed-slider-button-prev, .youtube-feed-slider-button-next i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .youtube-feed-slider-button-prev, .youtube-feed-slider-button-next svg' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'ekit_yf_slider_show_arrow' => 'yes',
                ],
            ]
        );

        $this->add_control(
			'ekit_yf_slider_nav_color',
			[
				'label' => esc_html__( 'Nav Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .youtube-feed-slider-button-prev, .youtube-feed-slider-button-next i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .youtube-feed-slider-button-prev, .youtube-feed-slider-button-next svg' => 'fill: {{VALUE}}',
				],
                'condition' => [
                    'ekit_yf_slider_show_arrow' => 'yes',
                ],
			]
		);

        $this->add_control(
			'ekit_yf_slider_nav_hover_color',
			[
				'label' => esc_html__( 'Nav Hover Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
                'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .youtube-feed-slider-button-prev:hover, .youtube-feed-slider-button-next:hover i' => 'color: {{VALUE}}',
				],
                'condition' => [
                    'ekit_yf_slider_show_arrow' => 'yes',
                ],
			]
		);

        $this->add_control(
			'ekit_yf_slider_dot',
			[
				'label' => esc_html__( 'Slider Dot', 'elementskit' ),
				'type' => Controls_Manager::HEADING,
                'condition' => [
                    'ekit_yf_slider_show_dot' => 'yes',
                ],
			]
		);

        $this->add_responsive_control(
            'ekit_yf_slider_dot_size',
            [
                'label' => esc_html__( 'Dot Size', 'elementskit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .youtube-main-wrapper .swiper-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'ekit_yf_slider_show_dot' => 'yes',
                ],
            ]
        );

        $this->add_control(
			'ekit_yf_slider_arrow_space',
			[
				'label' => esc_html__( 'Space Between', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .youtube-main-wrapper .swiper-pagination .swiper-pagination-bullet' => 'margin: 0px {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'ekit_yf_slider_show_dot' => 'yes',
                ],
			]
		);

        $this->add_control(
			'ekit_yf_slider_dot_color',
			[
				'label' => esc_html__( 'Dot Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .youtube-main-wrapper .swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
				],
                'condition' => [
                    'ekit_yf_slider_show_dot' => 'yes',
                ],
			]
		);

        $this->add_control(
			'ekit_yf_slider_dot_active_color',
			[
				'label' => esc_html__( 'Dot Active Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .youtube-main-wrapper .swiper-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
				],
                'condition' => [
                    'ekit_yf_slider_show_dot' => 'yes',
                ],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'ekit_yf_slider_scrollbar_style',
            [
                'label' => esc_html__( 'Scroll Bar', 'elementskit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ekit_yf_slider_scrollbar' => 'yes',
                ],
            ],
        );

        $this->add_control(
			'ekit_yf_slider_scrollbar_height',
			[
				'label' => esc_html__( 'Scroll Bar Height', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .ekit-yf-slider-scroll-bar' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_yf_slider_scrollbar_border',
				'selector' => '{{WRAPPER}} .ekit-wid-con .ekit-yf-slider-scroll-bar',
			]
		);

        $this->add_responsive_control(
			'ekit_yf_slider_scroll_bar_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'elementskit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ekit-wid-con .ekit-yf-slider-scroll-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ekit-wid-con .swiper-scrollbar-drag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'ekit_yf_slider_ative_bar',
			[
				'label' => esc_html__( 'Active Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .swiper-scrollbar-drag' => 'background: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'ekit_yf_slider_scroll_bar',
			[
				'label' => esc_html__( 'Background', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .ekit-yf-slider-scroll-bar' => 'background: {{VALUE}}',
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'ekit_yf_loadmore_button',
            [
                'label' => esc_html__( 'Load More Button', 'elementskit' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
					'ekit_yf_load_more_enable' => 'yes',
				],  
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ekit_yf_channel_loadmore_typography_group',
                'selector' => '{{WRAPPER}} .ekit-yf-load-more-btn',
            ]
        );

        $this->start_controls_tabs(
			'ekit_yf_loadmore_button_tabs'
		);

        $this->start_controls_tab(
			'ekit_yf_loadmore_button_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'elementskit' ),
			]
		);

        $this->add_control(
			'ekit_yf_loadmore_button_color',
			[
				'label' => esc_html__( 'Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .youtube-main-wrapper .ekit-yf-load-more-btn' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'ekit_yf_loadmore_button_bg',
			[
				'label' => esc_html__( 'Background Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .youtube-main-wrapper .ekit-yf-load-more-btn' => 'background: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ekit_yf_loadmore_button_box_shadow',
				'selector' => '{{WRAPPER}} .ekit-wid-con .youtube-main-wrapper .ekit-yf-load-more-btn',
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_yf_loadmore_button_border',
				'selector' => '{{WRAPPER}} .ekit-wid-con .youtube-main-wrapper .ekit-yf-load-more-btn',
			]
		);

        $this->add_responsive_control(
            'ekit_yf_loadmore_button_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                '{{WRAPPER}} .ekit-wid-con .youtube-main-wrapper .ekit-yf-load-more-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => '',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
			'ekit_yf_loadmore_button_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'elementskit' ),
			]
		);

        $this->add_control(
			'ekit_yf_loadmore_button_hover_color',
			[
				'label' => esc_html__( 'Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
                    '{{WRAPPER}} .ekit-wid-con .youtube-main-wrapper .ekit-yf-load-more-btn:hover' => 'color: {{VALUE}}; transition: 0.3s;',
				],
			]
		);

        $this->add_control(
			'ekit_yf_loadmore_button_hover_bg',
			[
				'label' => esc_html__( 'Background Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
                    '{{WRAPPER}} .ekit-wid-con .youtube-main-wrapper .ekit-yf-load-more-btn:hover' => 'background: {{VALUE}}; transition: 0.3s;',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_yf_loadmore_button_hv_border',
				'selector' => '{{WRAPPER}} .ekit-wid-con .youtube-main-wrapper .ekit-yf-load-more-btn:hover',
			]
		);

        $this->add_responsive_control(
            'ekit_yf_loadmore_button_hv_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ekit-wid-con .youtube-main-wrapper .ekit-yf-load-more-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                    'unit' => 'px',
                    'isLinked' => '',
                ]
            ]
        );

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
                'name' => 'ekit_yf_loadmore_button_hv_box_shadow',
                'selector' => '{{WRAPPER}} .ekit-wid-con .youtube-main-wrapper .ekit-yf-load-more-btn:hover',
			]
		);

        $this->end_controls_tab();

		$this->end_controls_tabs();

        $this->add_responsive_control(
            'ekit_yf_loadmore_button_padding',
            [
                'label' => esc_html__( 'Padding', 'elementskit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'separator'  => 'before',
                'selectors' => [
                    '{{WRAPPER}} .ekit-wid-con .youtube-main-wrapper .ekit-yf-load-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '0',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                    'unit' => 'px',
                    'isLinked' => '',
                ]
            ]
        );

        $this->end_controls_section();

		$this->insert_pro_message();
	}

	protected function render() {
        echo '<div class="ekit-wid-con">';
            $this->render_raw();
        echo '</div>';
    }

    // Converting timestamp to time ago
	protected function time_elapsed_string($datetime, $full = false) {
	    $now = new \DateTime;
	    $ago = new \DateTime($datetime);
	    $distance = $now->diff($ago);	 
	    $distance->f = floor($distance->d / 7);
	    $distance->d -= $distance->f * 7; 

	    $time = array(
	        'y' => 'year',
	        'm' => 'month',
	        'f' => 'week',
	        'd' => 'day',
	        'h' => 'hour',
	        'i' => 'minute',
	        's' => 'second',
	    );

	    foreach ($time as $k => &$v) {
	        if ($distance->$k) {
	            $v = $distance->$k . ' ' . $v . ($distance->$k > 1 ? 's' : '');
	        } else {
	            unset($time[$k]);
	        }
	    }	

	    if (!$full) $string = array_slice($time, 0, 1);

		return $string ? sprintf('%1$s %2$s', implode(', ', $string), esc_html__('ago', 'elementskit')) : esc_html__('just now', 'elementskit');
	}

    // converting statistics digit to K, M, B
    protected function format_number($number) {
        if ($number >= 1000 && $number < 1000000) {
            return round($number / 1000, 1) . 'K';
        } elseif ($number >= 1000000 && $number < 1000000000) {
            return round($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000000000) {
            return round($number / 1000000000, 1) . 'B';
        } else {
            return $number;
        }
    }

	protected function render_raw() {
        $settings = $this->get_settings_for_display();
		extract($settings);

        $config = [
            'rtl'				=> is_rtl(),
            'arrows'			=> !empty($ekit_yf_slider_show_arrow),
            'dots'				=> !empty($ekit_yf_slider_show_dot),
            'scrollbar'			=> !empty($ekit_yf_slider_scrollbar),
            'autoplay'			=> !empty($ekit_yf_slider_autoplay),
            'speed'				=> $ekit_yf_slider_speed,
            'slidesPerView'		=> $ekit_yf_slider_slidetoshow['size'] ?? 4,
            'slidesPerGroup'	=> $ekit_yf_slider_slide_scrroll['size'] ?? 1,
            'spaceBetween' 		=> $ekit_yf_slider_spacing['size'] ?? 30,
            'pauseOnHover'	    => !empty($youtube_feed_pause_on_hover),
            'loop'  			=> !empty($ekit_yf_slider_loop),
            'breakpoints'		=> [
                360 => [
                    'slidesPerView'      => $ekit_yf_slider_slidetoshow_mobile['size'] ?? 1,
                    'slidesPerGroup'    => $ekit_yf_slider_slide_scrroll_mobile['size'] ?? 1
                ],
                767 => [
                    'slidesPerView'      => $ekit_yf_slider_slidetoshow_tablet['size'] ?? 2,
                    'slidesPerGroup'    => $ekit_yf_slider_slide_scrroll_tablet['size'] ?? 1,
                ],
                1024 => [
                    'slidesPerView'      => $ekit_yf_slider_slidetoshow['size'] ?? 2,
                    'slidesPerGroup'    => $ekit_yf_slider_slide_scrroll['size'] ?? 1,
                ]
            ],
        ];

        $slider_container_class = $ekit_yf_layout_style == 'youtube_carousel' ? (method_exists('\ElementsKit_Lite\Utils', 'swiper_class') ? \ElementsKit_Lite\Utils::swiper_class() : 'swiper') : '';
        $wrapper_class = $ekit_yf_layout_style == 'youtube_carousel' ?  'swiper-wrapper' : '';
        $slide_class =  $ekit_yf_layout_style == 'youtube_carousel' ?  'swiper-slide' : '';

        // The control values stay underscored (they are stored in saved pages), the
        // markup uses the hyphenated class names.
        $layout_classes = [
            'youtube_grid'     => 'youtube-grid',
            'youtube_masonary' => 'youtube-masonary',
            'youtube_carousel' => 'youtube-carousel',
        ];
        $layout_class = $layout_classes[$ekit_yf_layout_style] ?? 'youtube-grid';

        // youtube feed data (api calls and caching live in the widget handler)
        $feed_response = Handler::get_video_feeds($settings);
        $video_feeds   = $feed_response['feeds'];
        $feed_error    = $feed_response['is_error'];

        // Grid and Masonary columns
        $columns = '';
        if($ekit_yf_layout_style == 'youtube_masonary'){
            $columns = 'ekit-column-' . $ekit_yf_columns;
        }

        // Masonary is handled by the shared GalleryFilter plugin, same as the Gallery widget.
        $masonry_columns = !empty($ekit_yf_columns) ? (int) $ekit_yf_columns : 3;
        $masonry_gap     = isset($ekit_yf_grid_inner_spacing['size']) && $ekit_yf_grid_inner_spacing['size'] !== '' ? (int) $ekit_yf_grid_inner_spacing['size'] : 15;

        $gallery_config = [
            'layout'      => 'masonry',
            'showFilters' => false,
            'columns'     => $masonry_columns,
            'colGap'      => $masonry_gap,
            'rowGap'      => $masonry_gap,
            'responsive'  => [
                [ 'maxWidth' => 480,  'columns' => !empty($ekit_yf_columns_mobile) ? (int) $ekit_yf_columns_mobile : 1 ],
                [ 'maxWidth' => 768,  'columns' => !empty($ekit_yf_columns_tablet) ? (int) $ekit_yf_columns_tablet : 2 ],
                [ 'maxWidth' => 1024, 'columns' => $masonry_columns ],
            ],
        ];

        // Load more (client side reveal within fetched results, not for carousel)
        $load_more_enabled = !empty($ekit_yf_load_more_enable) && $ekit_yf_load_more_enable === 'yes' && $ekit_yf_layout_style !== 'youtube_carousel';
        $load_more_initial = !empty($ekit_yf_load_more_initial) ? max(1, (int) $ekit_yf_load_more_initial) : 6;
        $load_more_count   = !empty($ekit_yf_load_more_count) ? max(1, (int) $ekit_yf_load_more_count) : 3;
        $load_more_text    = !empty($ekit_yf_load_more_text) ? $ekit_yf_load_more_text : esc_html__('Load More', 'elementskit');
        $total_feeds       = !empty($video_feeds['data']) ? count($video_feeds['data']) : 0;

        ?>
            <div class="youtube-main-wrapper">
                <?php if(!empty($video_feeds['data']) && $video_feeds['is_error'] === false) : ?>
                    <div class="youtube-container <?php echo esc_attr($slider_container_class) ?>">
                        <div class="youtube-wrapper <?php echo esc_attr($wrapper_class . ' '.$layout_class . ' ' . $columns) ?>" data-config="<?php echo esc_attr(json_encode($config)); ?>"<?php if($load_more_enabled) : ?> data-load-more="yes" data-initial="<?php echo esc_attr($load_more_initial); ?>" data-increment="<?php echo esc_attr($load_more_count); ?>"<?php endif; ?><?php if($ekit_yf_layout_style === 'youtube_masonary') : ?> data-gallery-config="<?php echo esc_attr(wp_json_encode($gallery_config)); ?>"<?php endif; ?>>
                            <?php foreach($video_feeds['data'] as $index => $item) :
                                include Handler::get_dir() . 'parts/style.php';
                            endforeach; ?>
                        </div>
                    </div>

                    <?php if($ekit_yf_slider_scrollbar == 'yes') : ?>
                        <div class="swiper-scrollbar ekit-yf-slider-scroll-bar"></div>
                    <?php endif; ?>

                    <?php if($ekit_yf_slider_show_dot == 'yes') : ?>
                        <div class="slick-dots swiper-pagination"></div>
                    <?php endif; ?>	

                    <?php if($ekit_yf_slider_show_arrow == 'yes') : ?>
                        <div class="elementor-swiper-button youtube-feed-slider-button-prev">
                            <?php \Elementor\Icons_Manager::render_icon( $ekit_yf_slider_left_arrow_icon, [ 'aria-hidden' => 'true' ]); ?>
                        </div>
                        <div class="elementor-swiper-button youtube-feed-slider-button-next">
                            <?php \Elementor\Icons_Manager::render_icon( $ekit_yf_slider_right_arrow_icon, [ 'aria-hidden' => 'true' ]); ?>
                        </div>
                    <?php endif; ?>

                    <?php if($load_more_enabled && $total_feeds > $load_more_initial) : ?>
                        <div class="ekit-yf-load-more-wrap">
                            <button type="button" class="ekit-yf-load-more-btn"><?php echo esc_html($load_more_text); ?></button>
                        </div>
                    <?php endif; ?>

                <?php else :
                    echo sprintf('<div class="elemenetskit-alert-info">%1$s</div>', esc_html($feed_error));
                endif; ?>
            </div>
        <?php
	}
}