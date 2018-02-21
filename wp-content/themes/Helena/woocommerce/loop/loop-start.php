<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
?>
<?php global $halena_options, $woocommerce_loop; 

$shop_gutter_value = esc_attr( $halena_options['shop-gutter-value'] );
$shop_col_query = get_query_var( 'agnishopcol' );

$product_column_count = isset( $halena_options['shop-column-layout'] )?$halena_options['shop-column-layout']:'4';
$product_column_count = max( 1, !empty( $woocommerce_loop['columns'] ) ? $woocommerce_loop['columns'] : $product_column_count );

$shop_grid = isset($halena_options['shop-grid-layout'])?esc_attr( $halena_options['shop-grid-layout'] ):'fitRows';

if( $shop_grid == 'masonry' ){
	wp_enqueue_script( 'halena-isotope-script' );
}

if( !empty($shop_col_query) ){
    $product_column_count = $shop_col_query;
}
?>
<ul class="products agni-products agni-products-<?php echo esc_attr( $product_column_count ); ?>-column" style="<?php if( !empty($shop_gutter_value) ){ echo 'margin: 0 -'.intval($shop_gutter_value/2).'px;'; } ?>" data-shop-grid="<?php echo $shop_grid; ?>" <?php if( !empty($shop_gutter_value) ){ echo 'data-gutter="'.esc_attr( $shop_gutter_value ).'"'; } ?> <?php // echo wp_kses_post( $column ); ?>>