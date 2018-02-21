<?php
/**
 * Plugin Name: WooCommerce Variation Swatches
 * Plugin URI: http://themealien.com/wordpress-plugin/woocommerce-variation-swatches
 * Description: An extension of WooCommerce to make variable products be more beauty and friendly with users.
 * Version: 1.0.3
 * Author: ThemeAlien
 * Author URI: http://themealien.com/
 * Requires at least: 4.5
 * Tested up to: 4.8
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * The main plugin class
 */
final class TA_WC_Variation_Swatches {
	/**
	 * The single instance of the class
	 *
	 * @var TA_WC_Variation_Swatches
	 */
	protected static $instance = null;

	/**
	 * Extra attribute types
	 *
	 * @var array
	 */
	public $types = array();

	/**
	 * Main instance
	 *
	 * @return TA_WC_Variation_Swatches
	 */
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->types = array(
			'color' => esc_html__( 'Color', 'halena' ),
			'image' => esc_html__( 'Image', 'halena' ),
			'label' => esc_html__( 'Label', 'halena' ),
		);

		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		require_once AGNI_THEME_FILES_DIR . '/variation-swatches-for-woocommerce/includes/class-admin.php';
		require_once AGNI_THEME_FILES_DIR . '/variation-swatches-for-woocommerce/includes/class-frontend.php';
	}

	/**
	 * Initialize hooks
	 */
	public function init_hooks() {
		
		add_filter( 'product_attributes_type_selector', array( $this, 'add_attribute_types' ) );

		if ( is_admin() ) {
			add_action( 'init', array( 'TA_WC_Variation_Swatches_Admin', 'instance' ) );
		} else {
			add_action( 'init', array( 'TA_WC_Variation_Swatches_Frontend', 'instance' ) );
		}
	}


	/**
	 * Add extra attribute types
	 * Add color, image and label type
	 *
	 * @param array $types
	 *
	 * @return array
	 */
	public function add_attribute_types( $types ) {
		
		$types = array_merge( $types, $this->types );

		return $types;
	}

	/**
	 * Get attribute's properties
	 *
	 * @param string $taxonomy
	 *
	 * @return object
	 */
	public function get_tax_attribute( $taxonomy ) {
		global $wpdb;

		$attr = esc_sql( substr( $taxonomy, 3 ) );
		$attr = $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = %s", 
			$attr

		) );

		return $attr;
	}

	/**
	 * Instance of admin
	 *
	 * @return TA_WC_Variation_Swatches_Admin
	 */
	public function admin() {
		return TA_WC_Variation_Swatches_Admin::instance();
	}

	/**
	 * Instance of frontend
	 *
	 * @return TA_WC_Variation_Swatches_Frontend
	 */
	public function frontend() {
		return TA_WC_Variation_Swatches_Frontend::instance();
	}
}

/**
 * Main instance of plugin
 *
 * @return TA_WC_Variation_Swatches
 */
function TA_WCVS() {
	return TA_WC_Variation_Swatches::instance();
}

/**
 * Display notice in case of WooCommerce plugin is not activated
 */
function ta_wc_variation_swatches_wc_notice() {
	?>

	<div class="error">
		<p><?php esc_html_e( 'Soo Product Attribute Swatches is enabled but not effective. It requires WooCommerce in order to work.', 'halena' ); ?></p>
	</div>

	<?php
}

/**
 * Construct plugin when plugins loaded in order to make sure WooCommerce API is fully loaded
 * Check if WooCommerce is not activated then show an admin notice
 * or create the main instance of plugin
 */
function ta_wc_variation_swatches_constructor() {
	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'ta_wc_variation_swatches_wc_notice' );
	} else {
		TA_WCVS();
	}
}

add_action( 'woocommerce_init', 'ta_wc_variation_swatches_constructor' );

