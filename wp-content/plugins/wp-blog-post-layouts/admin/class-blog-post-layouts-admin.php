<?php
/**
 * Defines the admin core plugin class.
 * 
 * Handles the admin-specific hooks and functions.
 * 
 */
if( !class_exists( 'Wpblog_Post_Layouts_Admin' ) ):
    class Wpblog_Post_Layouts_Admin {
        /**
         * The unique identifier of this plugin.
         * @access   protected
         */
        protected $plugin_name;

        /**
         * The current version of the plugin.
         * @access   protected
         */
        protected $version;

        /**
         * Instance
         *
         * @access private
         * @static
         *
         * @var Wpblog_Post_Layouts_Admin The single instance of the class.
         */
        private static $_instance = null;

        /**
         * Ensures only one instance of the class is loaded or can be loaded.
         *
         * @access public
         * @static
         *
         * @return Wpblog_Post_Layouts_Admin An instance of the class.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Set the plugin name and the plugin version that can be used throughout the plugin.
         * Load the dependencies, define the locale, and set the hooks for the admin area of the site.
         */
        public function __construct() {
            if( !is_admin() ) {
                return;
            }

            if ( defined( 'WPBLOG_POST_LAYOUTS_VERSION' ) ) {
                $this->version = WPBLOG_POST_LAYOUTS_VERSION;
            } else {
                $this->version = '1.0.1';
            }
            $this->plugin_name = 'wp-blog-post-layouts';

            add_action( 'admin_menu', array( $this, 'add_admin_menu_register' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'cv_enqueue_scripts' ) );
            add_action( 'admin_init', array( $this, 'review_notice_set_option' ) );
            add_action( 'admin_notices', array( $this, 'review_admin_notice' ) );
            add_action( 'admin_notices', array( $this, 'upgrade_admin_notice' ) );
        }

        /**
         * load scripts.
         */
        public function cv_enqueue_scripts() {

            wp_enqueue_style( 'wpblog-post-layouts-admin-notice-style',
                plugins_url( 'css/admin-notice.css', __FILE__ ),
                array(),
                WPBLOG_POST_LAYOUTS_VERSION,
                'all'
            );

            $current_screen = get_current_screen();
            if( $current_screen->base !== 'toplevel_page_wp-blog-post-layouts' ) {
                return;
            }
            wp_enqueue_style( 'wpblog-post-layouts-icon-style',
                plugins_url( 'includes/assets/cv-icons/style.css', __DIR__ ),
                array(),
                WPBLOG_POST_LAYOUTS_VERSION,
                'all'
            );

            wp_enqueue_style( 'wpblog-post-layouts-admin-style',
                plugins_url( 'css/admin.css', __FILE__ ),
                array(),
                WPBLOG_POST_LAYOUTS_VERSION,
                'all'
            );

            wp_enqueue_script( 'wpblog-post-layouts-admin-script',
                plugins_url( 'js/admin.js', __FILE__ ),
                array( 'jquery' ),
                WPBLOG_POST_LAYOUTS_VERSION,
                true
            );
            
        }

        /**
         * Add admin page for the blog-post-layouts.
         */
        public function add_admin_menu_register() {

            $admin_icon = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><title>blog</title><path fill="#0073aa" d="M29.6 32h-27.2c-1.323 0-2.4-1.077-2.4-2.4v-27.2c0-1.323 1.077-2.4 2.4-2.4h27.2c1.323 0 2.4 1.077 2.4 2.4v27.2c0 1.323-1.077 2.4-2.4 2.4zM2.4 1.067c-0.735 0-1.333 0.598-1.333 1.333v27.2c0 0.735 0.598 1.333 1.333 1.333h27.2c0.735 0 1.333-0.598 1.333-1.333v-27.2c0-0.735-0.598-1.333-1.333-1.333z"></path><path fill="#0073aa" d="M4.8 5.333h-2.133c-0.294 0-0.533-0.238-0.533-0.533v-2.133c0-0.295 0.239-0.533 0.533-0.533h2.133c0.294 0 0.533 0.238 0.533 0.533v2.133c0 0.295-0.239 0.533-0.533 0.533zM3.2 4.267h1.067v-1.067h-1.067z"></path><path fill="#0073aa" d="M9.067 5.333h-2.133c-0.294 0-0.533-0.238-0.533-0.533v-2.133c0-0.295 0.239-0.533 0.533-0.533h2.133c0.294 0 0.533 0.238 0.533 0.533v2.133c0 0.295-0.239 0.533-0.533 0.533zM7.467 4.267h1.067v-1.067h-1.067z"></path><path fill="#0073aa" d="M13.333 5.333h-2.133c-0.294 0-0.533-0.238-0.533-0.533v-2.133c0-0.295 0.239-0.533 0.533-0.533h2.133c0.294 0 0.533 0.238 0.533 0.533v2.133c0 0.295-0.239 0.533-0.533 0.533zM11.733 4.267h1.067v-1.067h-1.067z"></path><path fill="#0073aa" d="M31.467 7.467h-30.933c-0.294 0-0.533-0.238-0.533-0.533v-4.533c0-1.323 1.077-2.4 2.4-2.4h27.2c1.323 0 2.4 1.077 2.4 2.4v4.533c0 0.295-0.239 0.533-0.533 0.533zM1.067 6.4h29.867v-4c0-0.735-0.598-1.333-1.333-1.333h-27.2c-0.735 0-1.333 0.598-1.333 1.333z"></path><path fill="#0073aa" d="M9.6 21.333c-0.137 0-0.273-0.052-0.377-0.156l-2.823-2.823-2.823 2.823c-0.208 0.209-0.546 0.209-0.754 0s-0.209-0.546 0-0.754l3.2-3.2c0.208-0.209 0.546-0.209 0.754 0l3.2 3.2c0.209 0.209 0.209 0.546 0 0.754-0.104 0.104-0.241 0.156-0.377 0.156z"></path><path fill="#0073aa" d="M7.467 19.2c-0.137 0-0.273-0.052-0.377-0.156-0.209-0.209-0.209-0.546 0-0.754l2.133-2.133c0.208-0.209 0.546-0.209 0.754 0s0.209 0.546 0 0.754l-2.133 2.133c-0.104 0.104-0.241 0.156-0.377 0.156z"></path><path fill="#0073aa" d="M13.333 20.8c-0.137 0-0.273-0.052-0.377-0.156l-3.733-3.733c-0.209-0.209-0.209-0.546 0-0.754s0.546-0.209 0.754 0l3.733 3.733c0.209 0.209 0.209 0.546 0 0.754-0.104 0.104-0.241 0.156-0.377 0.156z"></path><path fill="#0073aa" d="M13.333 21.333h-10.667c-0.294 0-0.533-0.238-0.533-0.533v-6.4c0-0.295 0.239-0.533 0.533-0.533h10.667c0.294 0 0.533 0.238 0.533 0.533v6.4c0 0.295-0.239 0.533-0.533 0.533zM3.2 20.267h9.6v-5.333h-9.6z"></path><path fill="#0073aa" d="M9.6 29.867c-0.137 0-0.273-0.052-0.377-0.156l-2.823-2.823-2.823 2.823c-0.208 0.209-0.546 0.209-0.754 0s-0.209-0.546 0-0.754l3.2-3.2c0.208-0.209 0.546-0.209 0.754 0l3.2 3.2c0.209 0.209 0.209 0.546 0 0.754-0.104 0.104-0.241 0.156-0.377 0.156z"></path><path fill="#0073aa" d="M7.467 27.733c-0.137 0-0.273-0.052-0.377-0.156-0.209-0.209-0.209-0.546 0-0.754l2.133-2.133c0.208-0.209 0.546-0.209 0.754 0s0.209 0.546 0 0.754l-2.133 2.133c-0.104 0.104-0.241 0.156-0.377 0.156z"></path><path fill="#0073aa" d="M13.333 29.333c-0.137 0-0.273-0.052-0.377-0.156l-3.733-3.733c-0.209-0.209-0.209-0.546 0-0.754s0.546-0.209 0.754 0l3.733 3.733c0.209 0.209 0.209 0.546 0 0.754-0.104 0.104-0.241 0.156-0.377 0.156z"></path><path fill="#0073aa" d="M13.333 29.867h-10.667c-0.294 0-0.533-0.238-0.533-0.533v-6.4c0-0.295 0.239-0.533 0.533-0.533h10.667c0.294 0 0.533 0.238 0.533 0.533v6.4c0 0.295-0.239 0.533-0.533 0.533zM3.2 28.8h9.6v-5.333h-9.6z"></path><path fill="#0073aa" d="M29.333 12.8h-26.667c-0.294 0-0.533-0.238-0.533-0.533v-3.2c0-0.295 0.239-0.533 0.533-0.533h26.667c0.294 0 0.533 0.238 0.533 0.533v3.2c0 0.295-0.239 0.533-0.533 0.533zM3.2 11.733h25.6v-2.133h-25.6z"></path><path fill="#0073aa" d="M29.333 29.867h-13.867c-0.294 0-0.533-0.238-0.533-0.533v-14.933c0-0.295 0.239-0.533 0.533-0.533h13.867c0.294 0 0.533 0.238 0.533 0.533v14.933c0 0.295-0.239 0.533-0.533 0.533zM16 28.8h12.8v-13.867h-12.8z"></path><path fill="#0073aa" d="M27.2 17.067h-0.533c-0.294 0-0.533-0.238-0.533-0.533s0.239-0.533 0.533-0.533h0.533c0.294 0 0.533 0.238 0.533 0.533s-0.239 0.533-0.533 0.533z"></path><path fill="#0073aa" d="M24.533 17.067h-6.933c-0.294 0-0.533-0.238-0.533-0.533s0.239-0.533 0.533-0.533h6.933c0.294 0 0.533 0.238 0.533 0.533s-0.239 0.533-0.533 0.533z"></path><path fill="#0073aa" d="M18.133 19.2h-0.533c-0.294 0-0.533-0.238-0.533-0.533s0.239-0.533 0.533-0.533h0.533c0.294 0 0.533 0.238 0.533 0.533s-0.239 0.533-0.533 0.533z"></path><path fill="#0073aa" d="M27.2 19.2h-6.933c-0.294 0-0.533-0.238-0.533-0.533s0.239-0.533 0.533-0.533h6.933c0.294 0 0.533 0.238 0.533 0.533s-0.239 0.533-0.533 0.533z"></path><path fill="#0073aa" d="M27.2 21.333h-0.533c-0.294 0-0.533-0.238-0.533-0.533s0.239-0.533 0.533-0.533h0.533c0.294 0 0.533 0.238 0.533 0.533s-0.239 0.533-0.533 0.533z"></path><path fill="#0073aa" d="M24.533 21.333h-6.933c-0.294 0-0.533-0.238-0.533-0.533s0.239-0.533 0.533-0.533h6.933c0.294 0 0.533 0.238 0.533 0.533s-0.239 0.533-0.533 0.533z"></path><path fill="#0073aa" d="M18.133 23.467h-0.533c-0.294 0-0.533-0.238-0.533-0.533s0.239-0.533 0.533-0.533h0.533c0.294 0 0.533 0.238 0.533 0.533s-0.239 0.533-0.533 0.533z"></path><path fill="#0073aa" d="M27.2 23.467h-6.933c-0.294 0-0.533-0.238-0.533-0.533s0.239-0.533 0.533-0.533h6.933c0.294 0 0.533 0.238 0.533 0.533s-0.239 0.533-0.533 0.533z"></path><path fill="#0073aa" d="M27.2 25.6h-0.533c-0.294 0-0.533-0.238-0.533-0.533s0.239-0.533 0.533-0.533h0.533c0.294 0 0.533 0.238 0.533 0.533s-0.239 0.533-0.533 0.533z"></path><path fill="#0073aa" d="M24.533 25.6h-6.933c-0.294 0-0.533-0.238-0.533-0.533s0.239-0.533 0.533-0.533h6.933c0.294 0 0.533 0.238 0.533 0.533s-0.239 0.533-0.533 0.533z"></path><path fill="#0073aa" d="M18.133 27.733h-0.533c-0.294 0-0.533-0.238-0.533-0.533s0.239-0.533 0.533-0.533h0.533c0.294 0 0.533 0.238 0.533 0.533s-0.239 0.533-0.533 0.533z"></path><path fill="#0073aa" d="M27.2 27.733h-6.933c-0.294 0-0.533-0.238-0.533-0.533s0.239-0.533 0.533-0.533h6.933c0.294 0 0.533 0.238 0.533 0.533s-0.239 0.533-0.533 0.533z"></path></svg>';

            add_menu_page(
                'wp-blog-post-layouts',
                esc_html__( 'WP Blog Post Layouts', 'wp-blog-post-layouts' ),
                'manage_options',
                'wp-blog-post-layouts',
                array( $this, 'admin_menu_callback' ),
                'data:image/svg+xml;base64,' . base64_encode( $admin_icon ),
                20
            );
        }

        /**
         * Callback function for blog-post-layouts admin page.
         * 
         */
        public function admin_menu_callback() {
        ?>
            <div id="cv-blog-post-layouts-admin" class="cv-admin-block-wrapper cv-clearfix">
                <header id="cv-main-header" class="cv-tab-block-wrapper">
                    <h1><?php echo esc_html__( 'WP Blog Post Layouts', 'wp-blog-post-layouts' ); ?></h1>
                    <div class="admin-main-menu nav-tab-wrapper cv-nav-tab-wrapper">
                        <ul>
                            <?php
                                $header_titles = array(
                                                        "dashboard" => array( "desc" => "Get started!!", "icon" => "cvicon-dashboard" ),
                                                        "help"      => array( "desc" => "Have an issue?", "icon" => "cvicon-support" ),
                                                        "review"    => array( "desc" => "Review our product", "icon" => "cvicon-review" )
                                                    );
                                foreach( $header_titles as $header_title => $header_title_val ) {
                            ?>
                                    <li class="nav-tab cv-nav-tab <?php echo esc_html( 'cv-'.$header_title ); if( $header_title == 'dashboard' ){ echo esc_html( ' isActive' ); } ?>">
                                        <a href="<?php echo '#cv-'.$header_title; ?>"><?php echo str_replace( '-', ' ', $header_title ); ?>
                                            <span class="cv-nav-sub-title"><?php echo esc_html( $header_title_val['desc'] ); ?></span>
                                            <i class="<?php echo esc_html( $header_title_val['icon'] ); ?>"></i>
                                        </a>
                                    </li>
                            <?php
                                }
                            ?>
                        </ul>
                    </div>
                </header>

                <div id="cv-main-content" class="cv-content-block-wrapper">
                    <?php
                        foreach( $header_titles as $header_title => $header_title_desc ) {
                            include( plugin_dir_path( __FILE__ ) .'partials/content-'.$header_title.'.php' );
                        }
                    ?>
                </div><!-- #main-content -->
                <footer id="cv-main-footer" class="cv-promo-sidebar">
                    <div class="footer-content cv-promo-block">
                        <h2 class="cv-admin-title"><?php echo esc_html__( 'Go Premium', 'wp-blog-post-layouts' ); ?></h2>
                        <div class="cv-admin-sub-title"><?php esc_html_e( 'Features', 'wp-blog-post-layouts' ); ?></div>
                        <ul class="cv-footer-list">
                            <li><?php esc_html_e( '5 layout variations in each layout block', 'wp-blog-post-layouts' ); ?></li>
                            <li><?php esc_html_e( 'Supports Custom Post Type', 'wp-blog-post-layouts' ); ?></li>
                            <li><?php esc_html_e( 'Responsive Design', 'wp-blog-post-layouts' ); ?></li>
                            <li><?php esc_html_e( '5 Block Columns', 'wp-blog-post-layouts' ); ?></li>
                            <li><?php esc_html_e( '5 Block Title Layout', 'wp-blog-post-layouts' ); ?></li>
                            <li><?php esc_html_e( 'Fallback Image Option', 'wp-blog-post-layouts' ); ?></li>
                            <li><?php esc_html_e( 'Element Sorting', 'wp-blog-post-layouts' ); ?></li>
                            <li><?php esc_html_e( 'Numerous Google Fonts', 'wp-blog-post-layouts' ); ?></li>
                            <li><?php esc_html_e( 'Advanced Typography Options', 'wp-blog-post-layouts' ); ?></li>
                            <li><?php esc_html_e( 'Show/Hide meta', 'wp-blog-post-layouts' ); ?></li>
                            <li><?php esc_html_e( 'Show/Hide before/after icons', 'wp-blog-post-layouts' ); ?></li>
                            <li><?php esc_html_e( 'Show/Hide content', 'wp-blog-post-layouts' ); ?></li>
                        </ul>
                        <a href="<?php echo esc_url( '//codecanyon.net/item/blog-post-layouts-for-gutenberg-and-elementor/26362956' ); ?>" target="_blank" class="button button-primary"><?php esc_html_e( 'Upgrade Now', 'wp-blog-post-layouts' ); ?></a>
                    </div><!-- .footer-content -->
                    <div class="footer-content cv-wpall-block">
                        <?php
                            esc_html_e( 'We have completely free online WordPress resources offers genuine and useful content helps to build your WordPress knowledge', 'wp-blog-post-layouts' );
                        ?>
                        <a href="<?php echo esc_url( '//wpallresources.com/' ); ?>" target="_blank"><?php echo esc_url( 'wpallresources.com' ); ?></a>
                    </div><!-- .footer-content -->
                </footer><!-- #cv-main-footer -->
            </div> <!-- #cv-blog-post-layouts-admin.cv-admin-block-wrapper -->
        <?php
        }

        /**
         * Plugin review admin notice after 15 days of plugin activation.
         */
        public function review_admin_notice() {
            global $current_user;
            $user_id = $current_user->ID;

            $wpblog_post_layouts_activated_time = get_option( 'wpblog_post_layouts_activated_time' );
            $wp_blog_post_layouts_ignore_review_notice_partially = get_user_meta( $user_id, 'wp_blog_post_layouts_ignore_review_notice_partially', true );
            $wp_blog_post_layouts_ignore_theme_review_notice = get_user_meta( $user_id, 'wp_blog_post_layouts_ignore_theme_review_notice', true );

            /**
             * if plugin activation time is more than 15 days
             * if plugin review notice is partially ignored and is not 7days.
             * if plugin review is already done.
             * 
             * @return null
             */
            if( ( $wpblog_post_layouts_activated_time > strtotime( '- 15 days' ) ) || ( $wp_blog_post_layouts_ignore_review_notice_partially > strtotime( '- 7 days' ) ) || $wp_blog_post_layouts_ignore_theme_review_notice ) {
                return;
            }
        ?>
            <div id="cv-plugin-admin-notice" class="notice updated is-dismissible">
                <div class="cv-plugin-message">
                    <?php esc_html_e( 'Hey, '.esc_html( $current_user->display_name ).'! Having great experience using WP Blog Post Layouts? We hope you are happy with everything that the plugin has to offer. If you can spare a minute, please help us leaving a 5-star review on wordpress.org. By spreading love, we continue to develop new amazing features in the future, for free!', 'wp-blog-post-layouts' ); ?>
                </div>
                <div class="links">
                    <a href="<?php echo esc_url( '//wordpress.org/support/plugin/wp-blog-post-layouts/reviews/#new-post' ); ?>" class="btn button-primary" target="_blank">
                        <span class="dashicons dashicons-thumbs-up"></span>
                        <span><?php esc_html_e( 'Sure', 'wp-blog-post-layouts' ); ?></span>
                    </a>
                    <a href="<?php echo wp_nonce_url( add_query_arg( 'wp_blog_post_layouts_ignore_review_notice_partially', true ), 'wpblog_post_layouts_nonce' ); ?>" class="btn button-secondary">
                        <span class="dashicons dashicons-calendar"></span>
                        <span><?php esc_html_e( 'Maybe later', 'wp-blog-post-layouts' ); ?></span>
                    </a>

                    <a href="<?php echo wp_nonce_url( add_query_arg( 'wp_blog_post_layouts_ignore_theme_review_notice', true ), 'wpblog_post_layouts_nonce' ); ?>" class="btn button-secondary">
                        <span class="dashicons dashicons-smiley"></span>
                        <span><?php esc_html_e( 'I already did', 'wp-blog-post-layouts' ); ?></span>
                    </a>

                    <a href="<?php echo esc_url( '//wordpress.org/support/plugin/wp-blog-post-layouts/' ); ?>" class="btn button-secondary" target="_blank">
                        <span class="dashicons dashicons-edit"></span>
                        <span><?php esc_html_e( 'Get plugin support question?', 'wp-blog-post-layouts' ); ?></span>
                    </a>
                </div>
            </div>
        <?php
        }

        /**
         * Plugin upgrade to premium notice
         */
        public function upgrade_admin_notice() {
            $wpblog_post_layouts_upgrade_premium = get_option( 'wpblog_post_layouts_upgrade_premium' );
            if( $wpblog_post_layouts_upgrade_premium > strtotime( '- 7 days' ) ) {
                return;
            }
            ?>
                <div id="cv-plugin-admin-notice" class="notice updated is-dismissible">
                    <div class="cv-plugin-message">
                        <?php esc_html_e( 'Looking for extending more features in WP Blog Post Layouts? Unlock additional features with custom post type support and many other options in premium version.', 'wp-blog-post-layouts' ); ?>
                    </div>
                    <div class="links">
                        <a href="<?php echo esc_url( '//codecanyon.net/item/blog-post-layouts-for-gutenberg-and-elementor/26362956' ); ?>" class="btn button-primary" target="_blank">
                            <span class="dashicons dashicons-upload"></span>
                            <span><?php esc_html_e( 'Upgrade To Premium', 'wp-blog-post-layouts' ); ?></span>
                        </a>
                        <a href="<?php echo wp_nonce_url( add_query_arg( 'wpblog_post_layouts_upgrade_premium', true ), 'wpblog_post_layouts_nonce' ); ?>" class="btn button-secondary">
                            <span class="dashicons dashicons-no"></span>
                            <span><?php esc_html_e( 'Dismiss this notice', 'wp-blog-post-layouts' ); ?></span>
                        </a>
                    </div><!-- .links -->
                </div>
            <?php
        }

        /**
         * Set plugin admin notice values
         */
        public function review_notice_set_option() {
            global $current_user;
            $user_id = $current_user->ID;

            if( isset( $_GET[ 'wpblog_post_layouts_upgrade_premium' ] ) && wp_verify_nonce( $_GET['_wpnonce'], 'wpblog_post_layouts_nonce' ) ) {
                update_option( 'wpblog_post_layouts_upgrade_premium', time() );
            }

            if( isset( $_GET[ 'wp_blog_post_layouts_ignore_review_notice_partially' ] ) && wp_verify_nonce( $_GET['_wpnonce'], 'wpblog_post_layouts_nonce' ) ) {
                update_user_meta( $user_id, 'wp_blog_post_layouts_ignore_review_notice_partially', time() );
            }

            if( isset( $_GET[ 'wp_blog_post_layouts_ignore_theme_review_notice' ] ) && wp_verify_nonce( $_GET['_wpnonce'], 'wpblog_post_layouts_nonce' ) ) {
                update_user_meta( $user_id, 'wp_blog_post_layouts_ignore_theme_review_notice', true );
            }
        }
    }
Wpblog_Post_Layouts_Admin::instance();
endif;