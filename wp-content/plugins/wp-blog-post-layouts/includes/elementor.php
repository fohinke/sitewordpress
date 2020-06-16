<?php
/**
 * Handles the internationalization, admin-specific hooks, and
 * public-facing site hooks.
 * 
 */
if( !class_exists( 'Wpblog_Post_Layouts_Elements' ) ):
    class Wpblog_Post_Layouts_Elements {
        /**
         * Instance
         *
         * @access private
         * @static
         *
         * @var Wpblog_Post_Layouts_Elements The single instance of the class.
         */
        private static $_instance = null;

        /**
         * Ensures only one instance of the class is loaded or can be loaded.
         *
         * @access public
         * @static
         *
         * @return Wpblog_Post_Layouts_Elements An instance of the class.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Load the dependencies and set the hooks for the admin area and
         * the public-facing side of the element.
         */
        public function __construct() {
            add_action( 'plugins_loaded', array( $this, 'init' ), 99 );
        }
        
        /**
         * Initialize the dependencies necessary hooks.
         */
        public function init() {
            if( !WPBLOG_POST_LAYOUTS_ELEMENTOR ) {
                return;
            }

            add_action( 'elementor/elements/categories_registered', array( $this, 'add_elements_categories' ) );
            
            // Register elements
            add_action( 'elementor/widgets/widgets_registered', array( $this, 'init_widgets' ) );

            if( ! WPBLOG_POST_LAYOUTS_GUTENBERG ) {
                add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'elementor_enqueue_scripts' ) );
            }

            add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'elementor_editor_scripts' ) );
        }

        /**
         * Initialize the widgets in elementor.
         * 
         */
        public function init_widgets() {
            // Include Widget files
            require_once( __DIR__ . '/src/grid/element.php' );
            require_once( __DIR__ . '/src/list/element.php' );
            require_once( __DIR__ . '/src/masonry/element.php' );

            // Register widget
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpblog_Post_Layouts_Grid_Element() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpblog_Post_Layouts_List_Element() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpblog_Post_Layouts_Masonry_Element() );
        }

        /**
         * Enqueue elements scripts.
         */
        public function elementor_enqueue_scripts() {

            wp_enqueue_style( 'wpblog-post-layouts-elements-style',
                plugins_url( 'assets/css/build.css', __FILE__ ),
                array(),
                WPBLOG_POST_LAYOUTS_VERSION,
                'all'
            );

        }

        /**
         * Enqueue elements admin scripts.
         */
        public function elementor_editor_scripts() {

            wp_enqueue_style( 'wpblog-post-layouts-icon-style',
                plugins_url( 'assets/cv-icons/style.css', __FILE__ ),
                array(),
                WPBLOG_POST_LAYOUTS_VERSION,
                'all'
            );

        }

        /**
         * Init Widgets categories
         *
         * @since 1.0.0
         *
         * @access public
         */
        public function add_elements_categories( $elements_manager ) {
            $elements_manager->add_category(
                'wpblog-post-layouts-elements',
                [
                    'title' => esc_html__( 'WP Blog Post Layouts Elements', 'wp-blog-post-layouts' ),
                    'icon' => 'fa fa-plug',
                ]
            );
        }
    }
    Wpblog_Post_Layouts_Elements::instance();
endif;