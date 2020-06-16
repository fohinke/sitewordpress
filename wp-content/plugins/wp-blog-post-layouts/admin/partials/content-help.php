<?php
/**
 * Content for help section in admin area.
 */
?>
<div id="cv-help" style="display:none">
    <h2 class="cv-admin-title">
        <?php echo esc_html__( 'Do you need any help related to our plugin ?', 'wp-blog-post-layouts' ); ?>
    </h2>
    <?php
        echo '<div class="cv-admin-img">';
            echo '<img src="'.esc_url( plugins_url( 'includes/assets/images/support-img.jpg', dirname(__DIR__) ) ).'">';
        echo '</div>';

        echo '<div class="cv-admin-box-wrapper">';
            echo '<div class="cv-admin-fields">';
                echo esc_html__( 'Our documentation gives all the necessary detailed information to get you started. It provides an elaborated overview on plugin features, how to use those features and how to troubleshoot errors.', 'wp-blog-post-layouts' );
                echo '<div class="cv-main-btn">';
                    echo '<a class="button-primary" href="'.esc_url( '//docs.codevibrant.com/plugins/blog-post-layouts/' ).'" target="_blank">';
                        echo esc_html__( 'Documentation', 'wp-blog-post-layouts' );
                    echo '</a>';
                echo '</div>';
            echo '</div>';

            echo '<div class="cv-admin-fields">';
                echo esc_html__( 'Our TeamSupport specialists are standing by to better understand your customer support needs and solve your problem for you. We aim to provide professional technical support  24/7 to satisfy your need and wish. We also offer support via email and social media.', 'wp-blog-post-layouts' );
                echo '<div class="cv-main-btn">';
                    echo '<a class="button-primary" href="'.esc_url( '//wordpress.org/support/plugin/wp-blog-post-layouts/' ).'" target="_blank">';
                        echo esc_html__( 'Support', 'wp-blog-post-layouts' );
                    echo '</a>';
                echo '</div>';
            echo '</div>';

            echo '<div class="cv-admin-fields">';
                echo esc_html__( 'Here are our some plugin related latest blogs.', 'wp-blog-post-layouts' );
                echo '<div class="cv-main-btn">';
                    echo '<a class="button-primary" href="'.esc_url( '//wpallresources.com/' ).'" target="_blank">';
                        echo esc_html__( 'Wpallresources', 'wp-blog-post-layouts' );
                    echo '</a>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    ?>
</div><!-- .cv-help -->