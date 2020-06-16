<?php
/**
 * Defines the plugin class exucutes when plugin is activated.
 * 
 * 
 */
if( !class_exists( 'Wpblog_Post_Layouts_Activator' ) ):
    class Wpblog_Post_Layouts_Activator {
        /**
         * @access public static
         */
        public static function activate() {
            $wpblog_post_layouts_activated_time = get_option( 'wpblog_post_layouts_activated_time' );
            if( !$wpblog_post_layouts_activated_time ) {
                update_option( 'wpblog_post_layouts_activated_time', time() );
            }

            $wpblog_post_layouts_upgrade_premium = get_option( 'wpblog_post_layouts_upgrade_premium' );
            if( !$wpblog_post_layouts_upgrade_premium ) {
                update_option( 'wpblog_post_layouts_upgrade_premium', time() );
            }

            $wpblog_post_layouts_premium_prefix = get_option( 'wpblog_post_layouts_premium_prefix' );
            if( !$wpblog_post_layouts_premium_prefix ) {
                update_option( 'wpblog_post_layouts_premium_prefix', 'wp' );
            }
        }
    }
endif;