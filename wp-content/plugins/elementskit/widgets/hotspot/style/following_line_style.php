<?php
$line_directions = [];

if(!empty($location['ekit_hotspot_follow_line_direction'])) {
	$line_directions['desktop'] = $location['ekit_hotspot_follow_line_direction'];
}

foreach($active_breakpoints as $key => $breakpoint) {
	if(!empty($location['ekit_hotspot_follow_line_direction_' . $key])) {
		$line_directions[$key] = $location['ekit_hotspot_follow_line_direction_' . $key];
	}
}

$ekit_hotspot_is_active   = ( $ekit_hotspot_all_time_show_hide == 'yes' ) || ( isset($location['ekit_hotspot_active']) && $location['ekit_hotspot_active'] == 'yes' );
$ekit_hotspot_content_id  = 'ekit-hotspot-content-' . $location['_id'];
$ekit_hotspot_label       = ( isset($location['ekit_hotspot_title']) && $location['ekit_hotspot_title'] !== '' ) ? $location['ekit_hotspot_title'] : esc_html__( 'Hotspot', 'elementskit' );
?>

<div class="ekit-location elementor-repeater-item-<?php echo esc_attr( $location[ '_id' ] ); ?> ekit-location-on-click <?php echo esc_attr($hotspot_line_class . ' ' . $location['ekit_hotspot_follow_line_direction']);?> <?php echo esc_attr($ekit_hotspot_all_time_show_hide == 'yes' ? 'ekit-all-activated active' : ''); ?> <?php echo ($ekit_hotspot_all_time_show_hide != 'yes' && $location['ekit_hotspot_active'] == 'yes') ? 'active' : ''; ?>" data-directions="<?php echo esc_attr(wp_json_encode($line_directions)) ?>">
    <div class="ekit-location_outer" id="<?php echo esc_attr($ekit_hotspot_content_id); ?>">
        <div class="ekit-hotspot-vertical-line">
            <?php if ($ekit_hotspot_show_caret == 'yes') { ?>
                <div class="ekit_hotspot_arrow"></div>
            <?php } ?>
        </div>
        <div class="<?php echo esc_attr($ekit_hotspot_location_wraper_image_position); ?> ekit-location_inner">
            <?php

                if($location['ekit_hotspot_logo']['id'] !='') :
            ?>
                <div class="ekit_hotspot_image">
                    <?php
                        echo \Elementskit_Lite\Utils::get_attachment_image_html($location, 'ekit_hotspot_logo', 'full' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                </div>
            <?php endif;

                if($location['ekit_hotspot_address'] || $location['ekit_hotspot_title']) :
            ?>
                <div class='media-body'>
                    <?php if ($location['ekit_hotspot_title'] != '') : ?>
                    <h3 class="ekit-hotspot-title"><?php echo esc_html($location['ekit_hotspot_title'], 'elementskit-lite')?></h3>
                    <?php endif; ?>
                    <?php if ($location['ekit_hotspot_address'] != '') : ?>
                    <div class='ekit-location-des'>
                        <?php echo do_shortcode( \ElementsKit_Lite\Utils::kses( $location['ekit_hotspot_address'] ) ); ?>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="ekit-location_indicator" role="button" tabindex="0" aria-expanded="<?php echo $ekit_hotspot_is_active ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr($ekit_hotspot_content_id); ?>" aria-label="<?php echo esc_attr($ekit_hotspot_label); ?>">
        <?php if ($ekit_hotspot_show_glow == 'yes') { ?>
            <div class="ekit_hotspot_pulse_1"></div>
            <div class="ekit_hotspot_pulse_2"></div>
        <?php }; ?>
        <div class="ekit-hotspot-horizontal-line">
            <?php if ($ekit_hotspot_show_caret == 'yes') { ?>
                <div class="ekit_hotspot_arrow"></div>
            <?php } ?>
        </div>
    </div>
</div>