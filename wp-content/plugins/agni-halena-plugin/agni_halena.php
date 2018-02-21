<?php
/*
Plugin Name: Agni Halena
Plugin URI: http://themeforest.net/user/AgniHD
Description: This is a core plugin for Halena theme by AgniDesigns.
Version: 1.0.1
Author: AgniDesigns
Author URI: http://themeforest.net/user/AgniHD
Text Domain: agni-halena-plugin
License: GNU General Public License v2 or later
*/

/*
This is custom plugin specifically made for this theme by theme author(AgniDesigns). its strictly an offense to use this with third party author's theme.
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}



if ( ! class_exists( 'AgniHalenaPlugin' ) ) {

    /**
     * Main AgniHalenaPlugin class
     *
     * @since       1.0.0
     */
    class AgniHalenaPlugin {

        /**
         * @const       string VERSION The plugin version, used for cache-busting and script file references
         * @since       1.0.0
         */

        const VERSION = '1.0.0';

        /**
         * @access      protected
         * @var         array $options Array of config options, used to check for demo mode
         * @since       1.0.0
         */
        protected $options = array();

        /**
         * Use this value as the text domain when translating strings from this plugin. It should match
         * the Text Domain field set in the plugin header, as well as the directory name of the plugin.
         * Additionally, text domains should only contain letters, number and hypens, not underscores
         * or spaces.
         *
         * @access      protected
         * @var         string $plugin_slug The unique ID (slug) of this plugin
         * @since       1.0.0
         */
        protected $plugin_slug = 'agni-halena-plugin';
		function __construct() {

            // load language files
            load_plugin_textdomain( dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

            // We safely integrate with theme with this hook
			add_action( 'init', array( $this, 'AgniHalenaCustomFunction' ), 1 );


            add_action( 'admin_enqueue_scripts', array( $this, 'agni_cmb2_admin_scripts' ) );

            add_action('cmb2_render_radio_image', array( $this, 'cmb2_radio_image_callback' ), 10, 5);

            add_filter('cmb2_list_input_attributes', array( $this, 'cmb2_radio_image_attributes' ), 10, 4);

	 
		}
		
		public function AgniHalenaCustomFunction() {		
            /* Custom Redux Framework */
            require_once( 'inc/redux-framework/framework.php' );

			/* Custom Post Types */
			require_once( 'inc/custom-posttypes.php' );

            /* Custom Shortcodes */
            require_once( 'inc/custom-vc-shortcodes.php' );

            /* CMB2  */
            require_once( 'inc/cmb2/init.php' );

            /* CMB2 Conditional */
            require_once( 'inc/cmb2-conditionals.php' );
		}


        /**
         * Enqueue scripts and styles for admin.
         */
        function agni_cmb2_admin_scripts(){
            wp_deregister_style( 'cmb2-styles' );
            wp_enqueue_style( 'agni-cmb2-css', plugins_url( '/inc/assets/css/cmb2.css', __FILE__ ) );
            wp_enqueue_style( 'cmb2-radio-image', plugins_url( '/inc/assets/css/cmb2-radio-image.css', __FILE__ ) );
            if( is_rtl() ){
                wp_enqueue_style( 'agni-cmb2-rtl-css', plugins_url( '/inc/assets/css/cmb2-rtl.css', __FILE__ ) );
            }

            wp_enqueue_script( 'cmb2-conditionals', plugins_url( '/inc/assets/js/cmb2-conditionals.js', __FILE__ ), array('jquery'), CMB2_Conditionals::VERSION, true );
            wp_enqueue_script( 'jw-cmb2-rgba-picker-js', plugins_url( '/inc/assets/js/jw-cmb2-rgba-picker.js', __FILE__ ), array( 'wp-color-picker' ), Agni_Halena_JW_Fancy_Color::VERSION, true );
        }

        /**
         * Load Custom metabox file CMB2 Radio Image 0.1
         */
        function cmb2_radio_image_callback($field, $escaped_value, $object_id, $object_type, $field_type_object) {

            echo  $field_type_object->radio();

        }
        
        function cmb2_radio_image_attributes($args, $defaults, $field, $cmb) {
            if ($field->args['type'] == 'radio_image' && isset($field->args['images'])) {
                foreach ($field->args['images'] as $field_id => $image) {
                    if ($field_id == $args['value']) {
                        $image = trailingslashit($field->args['images_path']) . $image;
                        $args['label'] = '<img src="' . $image . '" alt="' . $args['value'] . '" title="' . $args['label'] . '" />';
                    }
                }
            }
            return $args;
        }
		
	}
	// Finally initialize code
	new AgniHalenaPlugin();
}
if( !class_exists('Agni_Halena_JW_Fancy_Color') ){
    class Agni_Halena_JW_Fancy_Color {
        const VERSION = '0.2.0';
        public function hooks() {
            add_action( 'cmb2_render_rgba_colorpicker', array( $this, 'render_color_picker' ), 10, 5 );
        }
        public function render_color_picker( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {
            echo  $field_type_object->input( array(
                'class'              => 'cmb2-colorpicker color-picker',
                'data-default-color' => $field->args( 'default' ),
                'data-alpha'         => 'true',
            ) );
        }
    }
    $jw_fancy_color = new Agni_Halena_JW_Fancy_Color();
    $jw_fancy_color->hooks();
}
