<!-- Create popup video url -->
<?php
    $video_url = '';
    if($ekit_yf_video_popup === 'video-redirect'){
       $video_url = "https://www.youtube.com/watch?v={$item['video_id']}&list={$ekit_yf_playlist_id}&ab_channel={$item['channel_info']['customUrl']}";
    }
    else{
        $video_url = "https://www.youtube.com/watch?v={$item['video_id']}";
    }

    $embed_url   = "https://www.youtube.com/embed/{$item['video_id']}?autoplay=1&rel=0";

    // Apply the letter limit to the description. The popup is the expanded view,
    // so it keeps the full text (like the title) while the card gets the trim.
    $ekit_yf_description = !empty($item['description']) ? $item['description'] : '';
    $ekit_yf_popup_description = $ekit_yf_description;
    $ekit_yf_description_trimmed = false;
    if( !empty($ekit_yf_video_description_limit_enable) && $ekit_yf_video_description_limit_enable === 'yes' ) {
        $ekit_yf_letter_count = isset($ekit_yf_description_letter_count) ? absint($ekit_yf_description_letter_count) : 0;

        if( mb_strlen($ekit_yf_description) > $ekit_yf_letter_count ) {
            $ekit_yf_description = rtrim( mb_substr($ekit_yf_description, 0, $ekit_yf_letter_count) ) . '...';
            $ekit_yf_description_trimmed = true;
        }
    }

    // The "See More" link only makes sense once the description is actually cut off.
    $ekit_yf_show_see_more = $ekit_yf_description_trimmed
        && !empty($ekit_yf_video_description_see_more)
        && $ekit_yf_video_description_see_more === 'yes';
    $ekit_yf_see_more_text = !empty($ekit_yf_video_description_see_more_text) ? $ekit_yf_video_description_see_more_text : esc_html__('See More', 'elementskit');

    // Same letter limit treatment for the title.
    $ekit_yf_title = !empty($item['title']) ? $item['title'] : '';
    if( !empty($ekit_yf_video_title_limit_enable) && $ekit_yf_video_title_limit_enable === 'yes' ) {
        $ekit_yf_title_limit = isset($ekit_yf_title_letter_count) ? absint($ekit_yf_title_letter_count) : 0;

        if( mb_strlen($ekit_yf_title) > $ekit_yf_title_limit ) {
            $ekit_yf_title = rtrim( mb_substr($ekit_yf_title, 0, $ekit_yf_title_limit) ) . '...';
        }
    }

    // Statistics can be switched off entirely (feed item and popup).
    $ekit_yf_has_statistics = !empty($ekit_yf_statistics)
        && ( !isset($ekit_yf_statistics_show) || $ekit_yf_statistics_show === 'yes' );

    $channel_info = !empty($item['channel_info']['customUrl']) ? $item['channel_info']['customUrl'] : 'channel/' . $item['channel_id'];
    $channel_url = "https://www.youtube.com/{$channel_info}";
?>

<?php
    // Hide items beyond the initial count when Load More is enabled.
    $ekit_yf_item_hidden_class = ( !empty($load_more_enabled) && $index >= $load_more_initial ) ? ' ekit-yf-hidden' : '';

    // GalleryFilter only picks up items carrying its own item class.
    $ekit_yf_filter_item_class = ( $ekit_yf_layout_style === 'youtube_masonary' ) ? ' filter-item' : '';
?>
<div class="youtube-feedback <?php echo esc_attr($slide_class . $ekit_yf_item_hidden_class . $ekit_yf_filter_item_class);?>">
    <div class="youtube-wrap">
        <div class="youtube-content ekit-yf-popup">
            <div class="youtube-content-wrap">
                <div class="youtube-content-thumb">
                    <div class="youtube-wrapper-main">
                        
                        <!--  youtube Channel thumb  -->
                        <div class="youtube-channel-thumb">
                            <a href="<?php echo esc_url($channel_url); ?>">
                                <img src="<?php echo esc_url($item['channel_info']['thumbnails'][$ekit_yf_thumb_size]['url']); ?>" alt="ty-thumb">
                            </a>
                        </div>

                        <!--  youtube Info wrap  -->
                        <div class="youtube-channel-wrap">
                            <!--  youtube Channel name  -->
                            <a href="<?php echo esc_url($channel_url); ?>">
                                <h4 class="youtube-channel-name"><?php echo esc_html($item['channel_info']['title']); ?></h4>
                            </a>

                            <?php // With statistics hidden the "in thumb" block never renders, so the time falls back here.
                            if(!$ekit_yf_has_statistics || $ekit_yf_statistics_position !== 'in_thumb'): ?>
                                <!-- video published time -->
                                <h6 class="video-plublied-time">
                                    <?php echo esc_html($this->time_elapsed_string( $item['published'])); ?>
                                </h6>
                            <?php endif;

                            if($ekit_yf_has_statistics && $ekit_yf_statistics_position == 'in_thumb'): ?>
                                <div class="youtube-thumb-view">
                                    <!--  youtube video statistics  -->
                                    <div class="youtube-video-details">
                                        <div class="youtube-video-view youtube-video-list">
                                            <i class="<?php echo esc_html($ekit_yf_statistics[0]['ekit_yf_statistics_icon']['value']); ?>"></i>
                                            <div class="youtube-video-statistics-count">
                                                <?php echo !empty($item['statistics']['viewCount']) ? esc_html($this->format_number($item['statistics']['viewCount'])) : 0; ?>
                                            </div>
                                            <span class="ekit-yf-statistics-text"><?php echo esc_html_e(($ekit_yf_statistics[0]['ekit_yf_statistics_label']), 'elementskit') ?></span>
                                        </div>
                                    </div>

                                    <!-- video published time -->
                                    <h6 class="video-plublied-time">
                                        <?php echo esc_html($this->time_elapsed_string( $item['published'])); ?>
                                    </h6>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!--  youtube Icon  -->
                    <div class="youtube-thumb">
                        <a href="<?php echo esc_url($video_url); ?>">
                            <?php \Elementor\Icons_Manager::render_icon( $settings['ekit_yf_youtube_thumb_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </a>
                    </div>
                </div>
                
                <!--  youtube video title  -->
                <?php if($ekit_yf_video_title === 'yes'): ?>
                    <h5 class="youtube-video-title"><?php echo esc_html($ekit_yf_title); ?></h5>
                <?php endif;

                if($ekit_yf_video_description_show === 'yes'): ?>

                    <!--  youtube video description  -->
                    <p class="youtube-video-description">
                        <?php echo esc_html($ekit_yf_description); ?>
                        <?php if($ekit_yf_show_see_more): ?>
                            <a class="show-more-desc" href="<?php echo esc_url($video_url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($ekit_yf_see_more_text); ?></a>
                        <?php endif; ?>
                    </p>
                <?php endif;

                if($ekit_yf_thumb_image_position !== 'right' && $ekit_yf_thumb_image_position !== 'left'): ?>
                    <?php if( $ekit_yf_video_play_button == 'yes' ): ?>
                        <div class="youtube-video-thumb <?php echo esc_attr( $ekit_yf_video_popup ); ?>">
                            <?php if($ekit_yf_video_popup === 'inline'): ?>
                                <iframe src="<?php echo esc_url($embed_url); ?>" allowfullscreen></iframe>
                            <?php endif; ?>

                            <?php if($ekit_yf_video_popup !== 'inline'): ?>
                                <img class="youtube-video-thumb-image" src="<?php echo esc_url($item['thumbnails'][$ekit_yf_thumb_size]['url']); ?>">
                                <a class="youtube-video-btn <?php echo esc_html($ekit_yf_video_popup); ?>" href="<?php echo esc_url($video_url); ?>" data-url="<?php echo esc_url($embed_url); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php \Elementor\Icons_Manager::render_icon( $settings['ekit_yf_video_btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <!--  youtube video thumb  -->
                        <a class="youtube-video-thumb-wrap <?php echo esc_html($ekit_yf_video_popup); ?>" href="<?php echo esc_url($video_url); ?>" data-url="<?php echo esc_url($embed_url); ?>" target="_blank" rel="noopener noreferrer" >
                            <div class="youtube-video-thumb">
                                <img class="youtube-video-thumb-image" src="<?php echo esc_url($item['thumbnails'][$ekit_yf_thumb_size]['url']); ?>">
                            </div>
                        </a>
                    <?php endif; ?>
                <?php endif;

                if($ekit_yf_has_statistics && $ekit_yf_statistics_position !== 'in_thumb' ): ?>
                    <!--  youtube video statistics  -->
                    <div class="youtube-video-details <?php echo esc_attr($ekit_yf_statistics_position == 'after_thumb' ? 'after-thumb' : ''); ?>">
                        <?php foreach($ekit_yf_statistics as $index => $ekit_yf_statistic) :
                            extract($ekit_yf_statistic);
                            ?>
                            <div class="youtube-video-<?php echo esc_attr($ekit_yf_statistics_type); ?> youtube-video-list">
                                <?php \Elementor\Icons_Manager::render_icon( $ekit_yf_statistics_icon, [ 'aria-hidden' => 'true' ] ); ?>
                                <div class="youtube-video-statistics-count">
                                    <?php echo !empty($item['statistics'][$ekit_yf_statistics_type]) ? esc_html($this->format_number($item['statistics'][$ekit_yf_statistics_type])) : 0; ?>
                                </div>
                                <?php if(!empty($ekit_yf_statistics_label)): ?>
                                    <span class="ekit-yf-statistics-text"><?php echo esc_attr($ekit_yf_statistics_label); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if($ekit_yf_thumb_image_position === 'right' || $ekit_yf_thumb_image_position === 'left'): ?>
                <?php if( $ekit_yf_video_play_button == 'yes' ): ?>
                    <div class="youtube-video-thumb">
                        <?php if($ekit_yf_video_popup === 'inline'): ?>
                            <iframe src="<?php echo esc_url($embed_url); ?>" allowfullscreen></iframe>
                        <?php endif; ?>

                        <?php if($ekit_yf_video_popup !== 'inline'): ?>
                            <img class="youtube-video-thumb-image" src="<?php echo esc_url($item['thumbnails'][$ekit_yf_thumb_size]['url']); ?>">
                            <a class="youtube-video-btn <?php echo esc_html($ekit_yf_video_popup); ?>" href="<?php echo esc_url($video_url); ?>" data-url="<?php echo esc_url($embed_url); ?>" target="_blank" rel="noopener noreferrer">
                                <?php \Elementor\Icons_Manager::render_icon( $settings['ekit_yf_video_btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <!--  youtube video thumb  -->
                    <a class="youtube-video-thumb-wrap <?php echo esc_html($ekit_yf_video_popup); ?>" href="<?php echo esc_url($video_url); ?>" data-url="<?php echo esc_url($embed_url); ?>" target="_blank" rel="noopener noreferrer" >
                        <div class="youtube-video-thumb">
                            <img class="youtube-video-thumb-image" src="<?php echo esc_url($item['thumbnails'][$ekit_yf_thumb_size]['url']); ?>">
                        </div>
                    </a>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>

    <?php
        // Popup template — rendered hidden and shown on click for the "Pop Up" mode.
        if ( $ekit_yf_video_popup === 'video-popup' ) :
    ?>
        <div class="ekit-yf-popup-template" style="display:none;">
            <div class="ekit-yf-popup-card">

                <!-- Header: channel avatar, name, published time + youtube icon -->
                <div class="ekit-yf-popup-header">
                    <div class="ekit-yf-popup-channel">
                        <div class="ekit-yf-popup-avatar">
                            <img src="<?php echo esc_url($item['channel_info']['thumbnails'][$ekit_yf_thumb_size]['url']); ?>" alt="<?php echo esc_attr($item['channel_info']['title']); ?>">
                        </div>
                        <div class="ekit-yf-popup-channel-meta">
                            <h4 class="ekit-yf-popup-channel-name"><?php echo esc_html($item['channel_info']['title']); ?></h4>
                            <span class="ekit-yf-popup-time"><?php echo esc_html($this->time_elapsed_string( $item['published'])); ?></span>
                        </div>
                    </div>
                    <div class="ekit-yf-popup-yt-icon">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['ekit_yf_youtube_thumb_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    </div>
                </div>

                <!-- Title -->
                <h3 class="ekit-yf-popup-title"><?php echo esc_html($item['title']); ?></h3>

                <!-- Statistics -->
                <?php if($ekit_yf_has_statistics): ?>
                    <div class="ekit-yf-popup-stats">
                        <?php foreach($ekit_yf_statistics as $ekit_yf_statistic) :
                            $st_type  = $ekit_yf_statistic['ekit_yf_statistics_type'];
                            $st_icon  = $ekit_yf_statistic['ekit_yf_statistics_icon'];
                            $st_label = $ekit_yf_statistic['ekit_yf_statistics_label'];
                        ?>
                            <div class="ekit-yf-popup-stat">
                                <?php \Elementor\Icons_Manager::render_icon( $st_icon, [ 'aria-hidden' => 'true' ] ); ?>
                                <span class="ekit-yf-popup-stat-count"><?php echo !empty($item['statistics'][$st_type]) ? esc_html($this->format_number($item['statistics'][$st_type])) : 0; ?></span>
                                <?php if(!empty($st_label)): ?>
                                    <span class="ekit-yf-popup-stat-label"><?php echo esc_html($st_label); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Video -->
                <div class="ekit-yf-popup-video" data-src="<?php echo esc_url($embed_url); ?>"></div>

                <!-- Description -->
                <?php if(!empty($ekit_yf_popup_description)): ?>
                    <p class="ekit-yf-popup-desc"><?php echo esc_html($ekit_yf_popup_description); ?></p>
                <?php endif; ?>

                <!-- View Post button -->
                <a class="ekit-yf-popup-view-post" href="<?php echo esc_url($video_url); ?>" target="_blank" rel="noopener noreferrer">
                    <?php echo esc_html__('View Post', 'elementskit'); ?>
                </a>

            </div>
        </div>
    <?php endif; ?>
</div>