<?php
    $avatar = get_avatar($post->post_author, 25);
    $display_name = "<span class='display_name'>" . get_the_author_meta('display_name', $post->post_author) . "</span>";
    $star_count =  get_post_meta($post->ID, 'WPWVT_total_stars', true);
    $star_count = ($star_count) ? intval($star_count) : 0;
?>
<li>
    <h3 class="wpm_dynamic_post_title">
        <a href="<?php echo get_permalink($post->ID) ?>"><?php echo $post->post_title ?>
        </a>
    </h3>
    <div class="avatar_post">
        <div class="wpm_trending_block_fav">
            <div class="wpm_trending_block_fav_item wpmTooltip">
                <span class="wpmTooltipText">This post has been clapped <?php echo $star_count; ?> times<br/>view post to react</span>
                <span class="dashicons"><?php echo $iconSvg ?></span>
                <span class="star_count"><?php echo $star_count ?></span>
            </div>
            <div class="wpm_trending_block_fav_item">
                <span class="dashicons"><?php echo $commentSvg ?></span>
                <span class="star_count"><?php echo $post->comment_count; ?></span>
            </div>
            <?php echo $avatar . $display_name ?><br/>
        </div>
    </div>
</li>