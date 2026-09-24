<?php

namespace ElementsKit\Widgets\Youtube_Feed;

defined('ABSPATH') || exit;

use Elementor\ElementsKit_Widget_Youtube_Feed_Handler as Youtube_Feed_Handler;
use ElementsKit_Lite\Core\Handler_Api;

class Youtube_Feed_Api extends Handler_Api {

    public function __construct() {
        parent::__construct();
    }

    public function config() {
        $this->prefix = 'widget/youtube-feed';
        $this->param  = "";
    }

    public function post_remove_cache() {
        if (!current_user_can('edit_posts')) {
            return [
                'success' => false,
                'msg'     => esc_html__('You are not allowed to clear this cache.', 'elementskit'),
            ];
        }

        $params = $this->request->get_params();

        $settings = [
            'ekit_yf_access_token'    => isset($params['accessToken']) ? sanitize_text_field($params['accessToken']) : '',
            'ekit_yf_type'            => isset($params['type']) ? sanitize_text_field($params['type']) : '',
            'ekit_yf_channel_id'      => isset($params['channelId']) ? sanitize_text_field($params['channelId']) : '',
            'ekit_yf_playlist_id'     => isset($params['playlistId']) ? sanitize_text_field($params['playlistId']) : '',
            'ekit_yf_video_search'    => isset($params['videoSearch']) ? sanitize_text_field($params['videoSearch']) : '',
            'ekit_yf_video_order'     => isset($params['videoOrder']) ? sanitize_text_field($params['videoOrder']) : '',
            'ekit_yf_video_max_result'=> isset($params['maxResult']) ? sanitize_text_field($params['maxResult']) : '',
        ];

        $is_remove = Youtube_Feed_Handler::reset_cache($settings);

        if ($is_remove) {
            return [
                'success' => true,
                'msg'     => esc_html__('Cache Successfully Deleted', 'elementskit'),
            ];
        } else {
            return [
                'success' => false,
                'msg'     => esc_html__('Data Not Found in Server', 'elementskit'),
            ];
        }
    }
}