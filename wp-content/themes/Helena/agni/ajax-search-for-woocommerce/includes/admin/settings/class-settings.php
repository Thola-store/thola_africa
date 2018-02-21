<?php

/**
 * Settings API data
 */
class AGNIDGWT_WCAS_Settings {
	/*
	 * @var string
	 * Unique settings slug
	 */

	private $setting_slug = AGNIDGWT_WCAS_SETTINGS_KEY;

	/*
	 * @var array
	 * All options values in one array
	 */
	public $opt;

	/*
	 * @var object
	 * Settings API object
	 */
	public $settings_api;

	public function __construct() {
		global $agnidgwt_wcas_settings;

		// Set global variable with settings
		$settings = get_option( $this->setting_slug );
		if ( !isset( $settings ) || empty( $settings ) ) {
			$agnidgwt_wcas_settings = array();
		} else {
			$agnidgwt_wcas_settings = $settings;
		}

		$this->opt = $agnidgwt_wcas_settings;

	}

	/*
	 * Set settings sections
	 * 
	 * @return array settings sections
	 */

	public function settings_sections() {

		$sections = array(
			array(
				'id'	 => 'agnidgwt_wcas_basic',
				'title'	 => __( 'Basic', 'halena' )
			),
			array(
				'id'	 => 'agnidgwt_wcas_advanced',
				'title'	 => __( 'Advanced', 'halena' )
			),
			array(
				'id'	 => 'agnidgwt_wcas_details_box',
				'title'	 => __( 'Extra Details', 'halena' )
			),
			array(
				'id'	 => 'agnidgwt_wcas_style',
				'title'	 => __( 'Style', 'halena' )
			),
//			array(
//				'id'	 => 'agnidgwt_wcas_performance',
//				'title'	 => __( 'Performance', 'halena' )
//			)
		);
		return apply_filters( 'agnidgwt_wcas_settings_sections', $sections );
	}

	/**
	 * Create settings fields
	 *
	 * @return array settings fields
	 */
	function settings_fields() {
		$settings_fields = array(
			'agnidgwt_wcas_basic'		 => apply_filters( 'agnidgwt_wcas_basic_settings', array(
				array(
					'name'		 => 'suggestions_limit',
					'label'		 => __( 'Suggestions limit', 'halena' ),
					'type'		 => 'number',
					'size'		 => 'small',
					'desc'		 => __( 'Maximum number of suggestions rows.', 'halena' ),
					'default'	 => 10,
				),
				array(
					'name'		 => 'min_chars',
					'label'		 => __( 'Minimum characters', 'halena' ),
					'type'		 => 'number',
					'size'		 => 'small',
					'desc'		 => __( 'Minimum number of characters required to trigger autosuggest.', 'halena' ),
					'default'	 => 3,
				),
				array(
					'name'		 => 'show_submit_button',
					'label'		 => __( 'Show submit button', 'halena' ),
					'type'		 => 'checkbox',
					'size'		 => 'small',
					'default'	 => 'off',
				),
				array(
					'name'		 => 'search_submit_text',
					'label'		 => __( 'Search submit button text', 'halena' ),
					'type'		 => 'text',
					'desc'		 => __( 'To display a loupe icon leave this field empty.', 'halena' ),
					'default'	 => __( 'Search', 'halena' ),
				),
				array(
					'name'		 => 'search_placeholder',
					'label'		 => __( 'Search input placeholder', 'halena' ),
					'type'		 => 'text',
					'default'	 => __( 'Search for products...', 'halena' ),
				),
				array(
					'name'		 => 'show_details_box',
					'label'		 => __( 'Show details box', 'halena' ),
					'type'		 => 'checkbox',
					'size'		 => 'small',
					'desc'		 => __( 'The Details box is an additional container for extended information. The details are changed dynamically when you hover the mouse over one of the suggestions.', 'halena' ),
					'default'	 => 'off',
				)
			) ),
			'agnidgwt_wcas_advanced'	 => apply_filters( 'agnidgwt_wcas_advanced_settings', array(
				array(
					'name'	 => 'search_head',
					'label'	 => '<h3>' . __( 'Product search', 'halena' ) . '</h3>',
					'type'	 => 'head',
				),
				array(
					'name'		 => 'search_in_product_content',
					'label'		 => __( 'Search in products content', 'halena' ),
					'type'		 => 'checkbox',
					'default'	 => 'off',
				),
				array(
					'name'		 => 'search_in_product_excerpt',
					'label'		 => __( 'Search in products excerpt', 'halena' ),
					'type'		 => 'checkbox',
					'default'	 => 'off',
				),
				array(
					'name'		 => 'search_in_product_sku',
					'label'		 => __( 'Search in products SKU', 'halena' ),
					'type'		 => 'checkbox',
					'default'	 => 'off',
				),
				array(
					'name'		 => 'exclude_out_of_stock',
					'label'		 => __( "Exclude 'out of stock' products", 'halena' ),
					'type'		 => 'checkbox',
					'default'	 => 'off',
				),
				array(
					'name'	 => 'search_in_taxonomy_head',
					'label'	 => '<h3>' . __( 'Taxonomy search', 'halena' ) . '</h3>',
					'type'	 => 'head',
				),
				array(
					'name'		 => 'show_matching_categories',
					'label'		 => __( 'Show matching categories', 'halena' ),
					'type'		 => 'checkbox',
					'default'	 => 'on',
				),
				array(
					'name'		 => 'show_matching_tags',
					'label'		 => __( 'Show matching tags', 'halena' ),
					'type'		 => 'checkbox',
					'default'	 => 'off',
				),
				array(
					'name'	 => 'product_suggestion_head',
					'label'	 => '<h3>' . __( 'Suggestions output', 'halena' ) . '</h3>',
					'type'	 => 'head',
				),
				array(
					'name'		 => 'show_product_image',
					'label'		 => __( 'Show product image', 'halena' ),
					'type'		 => 'checkbox',
					'default'	 => 'off',
				),
				array(
					'name'		 => 'show_product_price',
					'label'		 => __( 'Show price', 'halena' ),
					'type'		 => 'checkbox',
					'default'	 => 'off',
				),
				array(
					'name'		 => 'show_product_desc',
					'label'		 => __( 'Show product description', 'halena' ),
					'type'		 => 'checkbox',
					'default'	 => 'off',
				),
				array(
					'name'		 => 'show_product_sku',
					'label'		 => __( 'Show SKU', 'halena' ),
					'type'		 => 'checkbox',
					'default'	 => 'off',
				),
//				array(
//					'name'		 => 'show_sale_badge',
//					'label'		 => __( 'Show sale badge', 'halena' ),
//					'type'		 => 'checkbox',
//					'default'	 => 'off',
//				),
//				array(
//					'name'		 => 'show_featured_badge',
//					'label'		 => __( 'Show featured badge', 'halena' ),
//					'type'		 => 'checkbox',
//					'default'	 => 'off',
//				),
			) ),
			'agnidgwt_wcas_details_box'	 => apply_filters( 'agnidgwt_wcas_details_box_settings', array(
				array(
					'name'	 => 'tax_details_tax_head',
					'label'	 => '<h3>' . __( 'Category and tag details:', 'halena' ) . '</h3>',
					'type'	 => 'head',
				),
				array(
					'name'		 => 'show_for_tax',
					'label'		 => __( 'Show', 'halena' ),
					'type'		 => 'select',
					'options'	 => array(
						'all'		 => __( 'All Product', 'halena' ),
						'featured'	 => __( 'Featured Products', 'halena' ),
						'onsale'	 => __( 'On-sale Products', 'halena' ),
					),
					'default'	 => 'on',
				),
				array(
					'name'		 => 'orderby_for_tax',
					'label'		 => __( 'Order by', 'halena' ),
					'type'		 => 'select',
					'options'	 => array(
						'date'	 => __( 'Date', 'halena' ),
						'price'	 => __( 'Price', 'halena' ),
						'rand'	 => __( 'Random', 'halena' ),
						'sales'	 => __( 'Sales', 'halena' ),
					),
					'default'	 => 'on',
				),
				array(
					'name'		 => 'order_for_tax',
					'label'		 => __( 'Order by', 'halena' ),
					'type'		 => 'select',
					'options'	 => array(
						'desc'	 => __( 'DESC', 'halena' ),
						'asc'	 => __( 'ASC', 'halena' ),
					),
					'default'	 => 'desc',
				),
				array(
					'name'	 => 'tax_details_product_other',
					'label'	 => '<h3>' . __( 'Other', 'halena' ) . '</h3>',
					'type'	 => 'head',
				),
				array(
					'name'		 => 'details_box_position',
					'label'		 => __( 'Details box position', 'halena' ),
					'type'		 => 'select',
					'desc'		 => __( 'If your search form is very close to the right window screen, then select left.', 'halena' ),
					'options'	 => array(
						'left'	 => __( 'Left', 'halena' ),
						'right'	 => __( 'Right', 'halena' ),
					),
					'default'	 => 'right',
				)
			) ),
			'agnidgwt_wcas_style'		 => apply_filters( 'agnidgwt_wcas_style_settings', array(
				array(
					'name'	 => 'search_form',
					'label'	 => '<h3>' . __( 'Search form', 'halena' ) . '</h3>',
					'type'	 => 'head',
				),
				array(
					'name'		 => 'bg_input_color',
					'label'		 => __( 'Search input background', 'halena' ),
					'type'		 => 'color',
					'default'	 => '',
				),
				array(
					'name'		 => 'text_input_color',
					'label'		 => __( 'Search input text', 'halena' ),
					'type'		 => 'color',
					'default'	 => '',
				),
				array(
					'name'		 => 'border_input_color',
					'label'		 => __( 'Search input border', 'halena' ),
					'type'		 => 'color',
					'default'	 => '',
				),
				array(
					'name'		 => 'bg_submit_color',
					'label'		 => __( 'Search submit background', 'halena' ),
					'type'		 => 'color',
					'default'	 => '',
				),
				array(
					'name'		 => 'text_submit_color',
					'label'		 => __( 'Search submit text', 'halena' ),
					'type'		 => 'color',
					'default'	 => '',
				),
				array(
					'name'	 => 'syggestions_style_head',
					'label'	 => '<h3>' . __( 'Suggestions', 'halena' ) . '</h3>',
					'type'	 => 'head',
				),
				array(
					'name'		 => 'sug_bg_color',
					'label'		 => __( 'Suggestion background', 'halena' ),
					'type'		 => 'color',
					'default'	 => '',
				),
				array(
					'name'		 => 'sug_hover_color',
					'label'		 => __( 'Suggestion selected', 'halena' ),
					'type'		 => 'color',
					'default'	 => '',
				),
				array(
					'name'		 => 'sug_text_color',
					'label'		 => __( 'Text color', 'halena' ),
					'type'		 => 'color',
					'default'	 => '',
				),
				array(
					'name'		 => 'sug_highlight_color',
					'label'		 => __( 'Highlight color', 'halena' ),
					'type'		 => 'color',
					'default'	 => '',
				),
				array(
					'name'		 => 'sug_border_color',
					'label'		 => __( 'Border color', 'halena' ),
					'type'		 => 'color',
					'default'	 => '',
				),
				array(
					'name'		 => 'sug_width',
					'label'		 => __( 'Suggestions width', 'halena' ),
					'type'		 => 'number',
					'size'		 => 'small',
					'desc'		 => ' px. ' . __( 'Overvrite the suggestions container width. Leave this field empty to adjust the suggestions container width to the search input width.', 'halena' ),
					'default'	 => '',
				),
				array(
					'name'	 => 'preloader',
					'label'	 => '<h3>' . __( 'Preloader', 'halena' ) . '</h3>',
					'type'	 => 'head',
				),
				array(
					'name'		 => 'show_preloader',
					'label'		 => __( 'Show preloader', 'halena' ),
					'type'		 => 'checkbox',
					'default'	 => 'on',
				),
				array(
					'name'		 => 'preloader_url',
					'label'		 => __( 'Upload preloader image', 'halena' ),
					'type'		 => 'file',
					'default'	 => '',
				),
			) )
		);


		return $settings_fields;
	}

	/*
	 * Print optin value
	 * 
	 * @param string $option_key
	 * @param string $default default value if option not exist
	 * 
	 * @return string
	 */

	public function get_opt( $option_key, $default = '' ) {

		$value = '';

		if ( is_string( $option_key ) && !empty( $option_key ) ) {

			$settings = get_option( $this->setting_slug );

			if ( is_array( $settings ) && array_key_exists( $option_key, $settings ) ) {
				$value = $settings[ $option_key ];
			} else {

				// Catch default
				foreach ( $this->settings_fields() as $section ) {
					foreach ( $section as $field ) {
						if ( $field[ 'name' ] === $option_key && isset( $field[ 'default' ] ) ) {
							$value = $field[ 'default' ];
						}
					}
				}
			}
		}

		if ( empty( $value ) && !empty( $default ) ) {
			$value = $default;
		}

		return apply_filters( 'agnidgwt_wcas_return_option_value', $value, $option_key );
	}

	/**
	 * Handles output of the settings
	 */
	public static function output() {

		$settings = AGNIDGWT_WCAS()->settings->settings_api;

		//include_once AGNIDGWT_WCAS_DIR . 'includes/admin/views/settings.php';
	}

}

/*
 * Disable details box setting tab if the option id rutns off
 */
add_filter( 'agnidgwt_wcas_settings_sections', 'agnidgwt_wcas_hide_settings_detials_tab' );

function agnidgwt_wcas_hide_settings_detials_tab( $sections ) {

	if ( AGNIDGWT_WCAS()->settings->get_opt( 'show_details_box' ) !== 'on' && is_array( $sections ) ) {

		$i = 0;
		foreach ( $sections as $section ) {

			if ( isset( $section[ 'id' ] ) && $section[ 'id' ] === 'agnidgwt_wcas_details_box' ) {
				unset( $sections[ $i ] );
			}

			$i++;
		}
	}

	return $sections;
}
