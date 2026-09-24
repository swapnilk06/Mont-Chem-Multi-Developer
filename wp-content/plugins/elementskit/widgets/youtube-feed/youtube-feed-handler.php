<?php

namespace Elementor;

use ElementsKit_Lite\Libs\Framework\Attr;

class ElementsKit_Widget_Youtube_Feed_Handler extends \ElementsKit_Lite\Core\Handler_Widget {

	static function get_name() {
		return 'elementskit-youtube-feed';
	}

	static function get_title() {
		return esc_html__('Youtube Feed', 'elementskit');
	}

	static function get_icon() {
		return 'ekit ekit-youtube-feed ekit-widget-icon';
	}

	static function get_categories() {
		return ['elementskit'];
	}

	static function get_keywords() {
		return ['ekit', 'youtube', 'feed', 'playlist', 'video', 'channel'];
	}

	static function get_dir() {
		return \ElementsKit::widget_dir() . 'youtube-feed/';
	}

	static function get_url() {
		return \ElementsKit::widget_url() . 'youtube-feed/';
	}

	public function wp_init() {
		new \ElementsKit\Widgets\Youtube_Feed\Youtube_Feed_Api();
	}

	public static $transient_prefix = '__youtube_feed_';

	/**
	 * Builds the transient key for a feed from the settings that shape the api request.
	 *
	 * @param array $settings widget settings.
	 * @return string transient key, or empty string if settings can't address a feed.
	 */
	public static function get_transient_key($settings) {
		$access_token = $settings['ekit_yf_access_token'] ?? '';
		$type         = $settings['ekit_yf_type'] ?? '';
		$channel_id   = $settings['ekit_yf_channel_id'] ?? '';
		$playlist_id  = $settings['ekit_yf_playlist_id'] ?? '';
		$video_search = $settings['ekit_yf_video_search'] ?? '';
		$video_order  = $settings['ekit_yf_video_order'] ?? '';
		$max_result   = !empty($settings['ekit_yf_video_max_result']) ? $settings['ekit_yf_video_max_result'] : 10;

		if (empty($access_token)) {
			return '';
		}

		if ($type === 'channel' && !empty($channel_id)) {
			$key = $type . '_' . $video_order . '_' . $max_result . '_' . $channel_id;
		} elseif ($type === 'playlist' && !empty($playlist_id) && !empty($channel_id)) {
			$key = $type . '_' . $max_result . '_' . $playlist_id . '_' . $channel_id;
		} elseif ($type === 'search' && !empty($video_search)) {
			$key = $type . '_' . $video_order . '_' . $max_result . '_' . $video_search;
		} else {
			return '';
		}

		return self::$transient_prefix . md5($access_token . '_' . $key);
	}

	/**
	 * Fetches Youtube videos (+ channel info + statistics) and caches the result in a transient.
	 *
	 * Returns cached data when available, otherwise requests videos from the Youtube
	 * Data API, stores the decoded result, and returns it.
	 *
	 * @param array $settings widget settings.
	 * @return array { @type array|false $feeds, @type string|false $is_error }
	 */
	public static function get_video_feeds($settings) {
		$access_token = $settings['ekit_yf_access_token'] ?? '';
		$type         = $settings['ekit_yf_type'] ?? '';
		$channel_id   = $settings['ekit_yf_channel_id'] ?? '';
		$playlist_id  = $settings['ekit_yf_playlist_id'] ?? '';
		$video_search = $settings['ekit_yf_video_search'] ?? '';
		$video_order  = $settings['ekit_yf_video_order'] ?? '';
		$max_result   = !empty($settings['ekit_yf_video_max_result']) ? $settings['ekit_yf_video_max_result'] : 10;
		$expiration_time = !empty($settings['expiration_time']) ? $settings['expiration_time'] : DAY_IN_SECONDS;

		if (empty($access_token)) {
			return ['feeds' => false, 'is_error' => esc_html__('Access token field is empty', 'elementskit')];
		}

		$transient_name  = self::get_transient_key($settings);
		$transient_value = empty($transient_name) ? false : get_transient($transient_name);
		if (false !== $transient_value) {
			return ['feeds' => $transient_value, 'is_error' => false];
		}

		$main_url = 'https://www.googleapis.com/youtube/v3';

		if ($type === 'channel' && !empty($channel_id)) {
			$api_url = add_query_arg([
				'part'       => 'snippet',
				'type'       => 'video',
				'order'      => $video_order,
				'maxResults' => $max_result,
				'channelId'  => $channel_id,
				'key'        => $access_token,
			], $main_url . '/search');
		} elseif ($type === 'playlist' && !empty($playlist_id) && !empty($channel_id)) {
			$api_url = add_query_arg([
				'part'       => 'snippet',
				'playlistId' => $playlist_id,
				'maxResults' => $max_result,
				'key'        => $access_token,
			], $main_url . '/playlistItems');
		} elseif ($type === 'search' && !empty($video_search)) {
			$api_url = add_query_arg([
				'part'       => 'id,snippet',
				'q'          => $video_search,
				'type'       => 'video',
				'order'      => $video_order,
				'maxResults' => $max_result,
				'key'        => $access_token,
			], $main_url . '/search');
		} else {
			return ['feeds' => false, 'is_error' => esc_html__('Your Channel_ID/Playlist_ID Invalid', 'elementskit')];
		}

		$request = wp_remote_get($api_url);
		if (is_wp_error($request)) {
			return ['feeds' => false, 'is_error' => esc_html__('Invalid channel/playlist/search', 'elementskit')];
		}

		$body  = json_decode(wp_remote_retrieve_body($request), true);
		$items = $body['items'] ?? [];

		if (empty($items)) {
			return ['feeds' => false, 'is_error' => esc_html__('No video found', 'elementskit')];
		}

		$data = [];
		foreach ($items as $key => $item) {
			$video_id = $item['id']['videoId'] ?? ($item['snippet']['resourceId']['videoId'] ?? '');

			$data[$key] = [
				'video_id'    => $video_id,
				'title'       => $item['snippet']['title'],
				'description' => $item['snippet']['description'],
				'published'   => $item['snippet']['publishedAt'],
				'thumbnails'  => $item['snippet']['thumbnails'],
				'channel_id'  => $item['snippet']['channelId'] ?? '',
			];
		}

		// attach channel info
		foreach ($data as $key => $row) {
			$info = self::get_channel_info($access_token, $type !== 'search' ? $channel_id : $row['channel_id']);
			if (empty($info)) {
				return ['feeds' => false, 'is_error' => esc_html__('Channel not found', 'elementskit')];
			}
			$data[$key]['channel_info'] = $info;
		}

		// attach statistics
		foreach ($data as $key => $row) {
			$stats = self::get_video_statistics($access_token, $row['video_id']);
			if (empty($stats)) {
				return ['feeds' => false, 'is_error' => esc_html__('Video statistics not found', 'elementskit')];
			}
			$data[$key]['statistics'] = $stats;
		}

		$result = ['is_error' => false, 'data' => $data];

		if (!empty($transient_name)) {
			set_transient($transient_name, $result, $expiration_time);
		}

		return ['feeds' => $result, 'is_error' => false];
	}

	/**
	 * Fetches the snippet of a single channel.
	 *
	 * @param string $access_token youtube api key.
	 * @param string $channel_id channel to look up.
	 * @return array|false channel snippet, or false on failure.
	 */
	public static function get_channel_info($access_token, $channel_id) {
		if (empty($channel_id)) {
			return false;
		}

		$api_url = add_query_arg([
			'part' => 'snippet',
			'id'   => $channel_id,
			'key'  => $access_token,
		], 'https://www.googleapis.com/youtube/v3/channels');

		$request = wp_remote_get($api_url);
		if (is_wp_error($request)) {
			return false;
		}

		$body  = json_decode(wp_remote_retrieve_body($request), true);
		$items = $body['items'] ?? [];

		return !empty($items[0]['snippet']) ? $items[0]['snippet'] : false;
	}

	/**
	 * Fetches statistics for a single video.
	 *
	 * @param string $access_token youtube api key.
	 * @param string $video_id video to look up.
	 * @return array|false statistics, or false on failure.
	 */
	public static function get_video_statistics($access_token, $video_id) {
		if (empty($video_id)) {
			return false;
		}

		$api_url = add_query_arg([
			'part' => 'statistics',
			'id'   => $video_id,
			'key'  => $access_token,
		], 'https://www.googleapis.com/youtube/v3/videos');

		$request = wp_remote_get($api_url);
		if (is_wp_error($request)) {
			return false;
		}

		$body  = json_decode(wp_remote_retrieve_body($request), true);
		$items = $body['items'] ?? [];

		return !empty($items[0]['statistics']) ? $items[0]['statistics'] : false;
	}

	public static function reset_cache($settings) {
		$transient_name = self::get_transient_key($settings);

		if (empty($transient_name)) {
			return false;
		}

		return delete_transient($transient_name);
	}
}