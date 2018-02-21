<?php
/**
 * Single Product tabs
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );
global $post, $halena_options;

$page_layout = esc_attr( get_post_meta( $post->ID, 'page_layout', true ) );
$product_layout_style = esc_attr( get_post_meta( $post->ID, 'product_layout_style', true ) );

if( $page_layout == '' ){
	$page_layout = isset($halena_options['shop-single-description-stretch'])?esc_attr( $halena_options['shop-single-description-stretch'] ):'container';
}
if( $product_layout_style == '' ){
	$product_layout_style = isset($halena_options['shop-single-layout-style'])?esc_attr( $halena_options['shop-single-layout-style'] ):'1';
}

if ( ! empty( $tabs ) ) : ?>
<div class="single-product-tabs">
	<div class="single-product-tabs-container">
		<?php if( $product_layout_style != '3' ){ ?>
			<div class="woocommerce-tabs wc-tabs-wrapper">
				<ul class="tabs wc-tabs nav nav-tabs list-inline">
					<?php foreach ( $tabs as $key => $tab ) : ?>
						<li class="<?php echo esc_attr( $key ); ?>_tab">
							<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php foreach ( $tabs as $key => $tab ) : 
					if( $key != 'description' ){ 
						$page_layout = 'container';
					} ?>
					<div class="panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>">
						<div class="single-product-tab-<?php echo esc_attr( $key ); ?> single-product-tab-container <?php echo esc_html( $page_layout ); ?>">
							<div class="single-product-tab-content">
								<?php call_user_func( $tab['callback'], $key, $tab ); ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php }
		else{ 
			$output = $title = $el_class = '';

			$acc_id = 'accordion-'.rand();
			$output = '<div id="'.$acc_id.'" class="accordion panel-group accordion-style-1 text-left">';

			$title = 'Hello'; $collapsed = '';
			$active = '';
			$collapsed = 'collapsed';
			foreach ( $tabs as $key => $tab ){

				ob_start(); 
				call_user_func( $tab['callback'], $key, $tab ); 
				$tab_content = ob_get_clean(); 

				$output .= '<div class="panel '.esc_attr( $key ).'_tab">';
			    $output .= '<a class="panel-title '.$collapsed.'" data-toggle="collapse" data-parent="#'.$acc_id.'" href="#tab-'.esc_attr( $key ).'"><h6>'.apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ).'</h6><span class="panel-icon"></span></a>';
			    $output .= '<div id="tab-'.esc_attr( $key ).'" class="panel-body entry-content wc-tab collapse '.$active.'">';
			    $output .= $tab_content;
			    $output .= '</div>';
			    $output .= '</div> ';

			    $active = '';
			    $collapsed = 'collapsed';
			}

			$output .= '</div>';
			echo $output;

		 } ?>
	</div>
</div>
<?php endif; ?>