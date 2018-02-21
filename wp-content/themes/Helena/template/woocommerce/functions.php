<?php
/**
 * Loading Custom theme functions.
 */
function halena_woocommerce_setup() {

	// Removing Wocommerce CSS
	add_filter( 'woocommerce_enqueue_styles', '__return_false' );

}
add_action( 'woocommerce_init', 'halena_woocommerce_setup', 3 );

function halena_woocommerce_scripts(){
	wp_enqueue_style( 'halena-woocommerce-style', AGNI_FRAMEWORK_URL .'/template/woocommerce/css/woocommerce-style.css', array(), wp_get_theme()->get('Version')  );
    if( is_rtl() ){
        wp_enqueue_style( 'halena-woocommerce-rtl-style', AGNI_FRAMEWORK_URL .'/template/woocommerce/css/woocommerce-style-rtl.css', array(), wp_get_theme()->get('Version') );
    }
    
	wp_enqueue_script( 'halena-woocommerce-script', get_template_directory_uri() .'/template/woocommerce/js/woocommerce-script.js', array(), wp_get_theme()->get('Version'), true );
	wp_localize_script( 'halena-woocommerce-script', 'agni_quick_view', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
 		'security' => wp_create_nonce( 'agni_quick_view_nonce' ),
		//'col_count' => 
	) );
	wp_localize_script( 'halena-woocommerce-script','xoo_wsc_localize',array(
		'adminurl'		=> admin_url().'admin-ajax.php',
		'wc_ajax_url' 	=> WC_AJAX::get_endpoint( "%%endpoint%%" ),
		'ajax_atc'		=> 1,
		'added_to_cart' => 0,
		'auto_open_cart'=> 1,
		'atc_icons'  	=> 0
		)
	);

    wp_register_script( 'halena-woocommerce-easyzoom', get_template_directory_uri() .'/template/woocommerce/js/easyzoom.min.js', array(), wp_get_theme()->get('Version'), true );
}
add_action( 'wp_enqueue_scripts', 'halena_woocommerce_scripts', 12 );

function halena_woocommerce_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Woocommerce Sidebar', 'halena' ),
		'id'            => 'halena-sidebar-2',
		'description'   => 'Additional Widget location that could appear on the left/right of shop pages.',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );
}
add_action( 'widgets_init', 'halena_woocommerce_widgets_init' );

function halena_woocommerce_theme_options_processing(){

	$halena_options = get_option('halena_options');

	$single_breadcrumb = isset($halena_options['shop-single-breadcrumb'])?$halena_options['shop-single-breadcrumb']:'1';
	$single_rating = isset($halena_options['shop-single-rating'])?$halena_options['shop-single-rating']:'1';
	$single_navigation = isset($halena_options['shop-single-navigation'])?$halena_options['shop-single-navigation']:'1';
	$single_meta = isset($halena_options['shop-single-meta'])?$halena_options['shop-single-meta']:'1';
	$single_desc = isset($halena_options['shop-single-short-desc'])?$halena_options['shop-single-short-desc']:'1';
	$single_sku = isset($halena_options['shop-single-sku'])?$halena_options['shop-single-sku']:'1';
	$single_related = isset($halena_options['shop-single-related'])?$halena_options['shop-single-related']:'1';
	$single_upsell = isset($halena_options['shop-single-upsell'])?$halena_options['shop-single-upsell']:'1';

	$cart_crosssell = isset($halena_options['shop-cart-crosssell'])?$halena_options['shop-cart-crosssell']:'1';

	if( $single_breadcrumb != '1' ){
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 3 );
	}
	if( $single_rating != '1' ){
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 4 );
	}
	if( $single_desc != '1' ){
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	}
	if( $single_meta != '1' ){
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	}
	if( $single_navigation != '1' ){
		remove_action( 'woocommerce_single_product_summary', 'agni_framework_product_nav', 51 );
	}
	if( $single_sku != '1' ){
		function agni_woocommerce_remove_product_sku( $args ) {
		    if ( ! is_admin() && is_product() ) {
		        return false;
		    }
		    return $args;
		}
		add_filter( 'wc_product_sku_enabled', 'agni_woocommerce_remove_product_sku' );
	}

	if( $single_related != '1' ){
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	}

	if( $single_upsell != '1' ){
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	}

	if( $cart_crosssell != '1' ){
		//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
	}

}
add_action( 'woocommerce_init', 'halena_woocommerce_theme_options_processing' );

// Ensure cart contents update when products are added to the cart via AJAX.
add_filter( 'woocommerce_add_to_cart_fragments', 'agni_woocommerce_header_add_to_cart_fragment' );
function agni_woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $halena_options;
	ob_start();
	?>
        <span class="header-cart-details">
			<?php //if( WC()->cart->cart_contents_count != '0' ){ ?>
				<span class="product-count"><?php echo sprintf (_n( '%d', '%d', WC()->cart->cart_contents_count, 'halena' ), WC()->cart->cart_contents_count ); ?></span>
			<?php //} ?>
			<?php if($halena_options['header-cart-amount'] == '1'){ 
				echo WC()->cart->get_cart_total(); 
			} ?>
		</span>
	<?php
	
	$fragments['span.header-cart-details'] = ob_get_contents(); 
	ob_end_clean();
	return $fragments;
}

// Woocommerce product title override
function woocommerce_template_loop_product_title() {
	?><h5 class="product-title"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h5><?php
	
}

// Woocommerce Category title override
function woocommerce_template_loop_category_title( $category ) {
	echo '<h5 class="product-category-title">'.$category->name;
	if ( $category->count > 0 )
		echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . $category->count . ')</mark>', $category );
	echo '</h5>';
} 

// Woocommerce product thumbnail override 
function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $deprecated1 = 0, $deprecated2 = 0 ) {
	global $post, $halena_options;
	$shop_thumbnail_hardcrop = esc_attr( $halena_options['shop-thumbnail-hardcrop'] );
	$shop_thumbnail_dimension_custom = esc_attr( $halena_options['shop-thumbnail-dimension-custom'] );

	$product = wc_get_product( $post->ID );
	$attachment_ids = array_filter($product->get_gallery_image_ids());
	
	$image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );

	if ( has_post_thumbnail() ) {
		if( $shop_thumbnail_hardcrop == '1'){
            $shop_thumbnail_customcrop_dimension = explode( 'x', $shop_thumbnail_dimension_custom );
            $shop_thumbnail = agni_thumbnail_customcrop( get_post_thumbnail_id(), $shop_thumbnail_customcrop_dimension[0].'x'.$shop_thumbnail_customcrop_dimension[1], 'shop-thumbnail-attachment-image' );
            if( !empty($attachment_ids[0]) ){
				$addtional_attr = array(
					'class' => $image_size.' halena-product-additional-thumbnail',
				);
				$shop_thumbnail .= '<div class="product-additional-thumbnail-container">'.agni_thumbnail_customcrop($attachment_ids[0], $shop_thumbnail_customcrop_dimension[0].'x'.$shop_thumbnail_customcrop_dimension[1], 'shop-thumbnail-attachment-image' ).'</div>';
			}
        }
        else{
        	$props = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
			$shop_thumbnail = get_the_post_thumbnail( $post->ID, $image_size, array(
				'title'	 => $props['title'],
				'alt'    => $props['alt'],
			) );

			if( !empty($attachment_ids[0]) ){
				$addtional_attr = array(
					'class' => $image_size.' halena-product-additional-thumbnail',
				);
				$shop_thumbnail .= '<div class="product-additional-thumbnail-container">'.wp_get_attachment_image($attachment_ids[0], $image_size, false, $addtional_attr ).'</div>';
			}
        }
        return $shop_thumbnail;
	} elseif ( wc_placeholder_img_src() ) {
		return wc_placeholder_img( $image_size );
	}
}

// Removing & adding breadcrumb
//remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 3, 0 );

// Removing & adding rating
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10, 0 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 4, 0 );


// Removing & adding product link from loop
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
add_action( 'agni_template_loop_product_link_close', 'woocommerce_template_loop_product_link_close', 5 );

// Removing & Showing sidebar as per the option panel value
function halena_woocommerce_remove_sidebar() {
	$halena_options = get_option('halena_options');
	if( $halena_options['shop-single-sidebar'] == 'no-sidebar' ){ 

	    if ( is_singular('product') ) {
	        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar');
	    }
	}
}
add_action('template_redirect', 'halena_woocommerce_remove_sidebar');

// Variable Single Product price override
function woocommerce_single_variation() {
	echo '<h4 class="single_variation"></h4>';
}

// Removing & Adding cross_sell_display (you may know this items) from collaterals
//remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
//add_action( 'agni_woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

// Removing & Adding product data tabs (description, additional info, review)
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'agni_woocommerce_single_product_data_tabs', 'woocommerce_output_product_data_tabs', 10 );

// Setting default product quantity to 1
add_filter( 'woocommerce_quantity_input_args', 'agni_woocommerce_custom_quantity', 10, 2 );
function agni_woocommerce_custom_quantity( $args, $product ) {
    $args['input_value'] = 1;
    return $args;
}

// Adding add to cart additionally
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 11 );

// Definig custom image sizes for the products on activation
if( !function_exists('halena_woocommerce_image_dimensions') ){
	function halena_woocommerce_image_dimensions() {
		global $pagenow;
	 
		if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
			return;
		}

	  	$catalog = array(
			'width' 	=> '384',	// px
			'height'	=> '472',	// px
			'crop'		=> 1 		// true
		);

		$single = array(
			'width' 	=> '640',	// px
			'height'	=> '760',	// px
			'crop'		=> 1 		// true
		);

		$thumbnail = array(
			'width' 	=> '90',	// px
			'height'	=> '110',	// px
			'crop'		=> 1 		// true
		);

		// Image sizes
		update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
		update_option( 'shop_single_image_size', $single ); 		// Single product image
		update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
	}
}

add_action( 'after_switch_theme', 'halena_woocommerce_image_dimensions', 1 );

if ( ! function_exists( 'agni_woocommerce_products_per_page' ) ){
	function agni_woocommerce_products_per_page( $cols ) {
	  global $halena_options;
	  
	  $cols = !empty($halena_options['shop-products-per-page'])?$halena_options['shop-products-per-page']:$cols;
	  return $cols;
	}
	add_filter( 'loop_shop_per_page', 'agni_woocommerce_products_per_page', 20 );
}

/**
 * Display navigation to next/previous post when applicable.
 */
if ( ! function_exists( 'agni_framework_product_nav' ) ){
	function agni_framework_product_nav() {
		global $halena_options;
		
		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		} ?>
		<nav class="product-navigation navigation" role="navigation">
			<div id="product-nav-links" class="nav-links">
				<?php 
                    $previous_product = get_previous_post();
                    if( !empty($previous_product) ){
                    	$previous_product_data = new WC_Product( $previous_product->ID ); 
                    	$previous_product_thumbnail = get_the_post_thumbnail( $previous_product->ID, 'shop_thumbnail' );
						$previous_product_title = $previous_product_data->get_title();
                    	$previous_product_price = $previous_product_data->get_price_html();

                    	$previous_product_content = '<div class="nav-product-thumbnail nav-prev-product-thumbnail">
                    		'.$previous_product_thumbnail.'
                    	</div>
                    	<div class="nav-product-details nav-prev-product-details">
                    		<h6 class="nav-product-title nav-prev-product-title">'.$previous_product_title.'</h6>
                    		<span class="nav-product-price nav-prev-product-price">'.$previous_product_price.'</span>
                    	</div>';
	                    $previous_product_link = get_previous_post_link( '%link', '<i class="pe-7s-angle-left"></i>' );

	                    echo '<div class="product-nav-prev">
							<div class="product-nav-btn product-nav-prev-btn">'.$previous_product_link.'</div>
							<div class="product-nav-content product-nav-prev-content">'.$previous_product_content.'</div>
						</div>';
	                }

	                $next_product = get_next_post();
                    if( !empty($next_product) ){
                    	$next_product_data = new WC_Product( $next_product->ID ); 
                    	$next_product_thumbnail = get_the_post_thumbnail( $next_product->ID, 'shop_thumbnail' );
						$next_product_title = $next_product_data->get_title();
                    	$next_product_price = $next_product_data->get_price_html();

                    	$next_product_content = '<div class="nav-product-thumbnail nav-next-product-thumbnail">
                    		'.$next_product_thumbnail.'
                    	</div>
                    	<div class="nav-product-details nav-next-product-details">
                    		<h6 class="nav-product-title nav-next-product-title">'.$next_product_title.'</h6>
                    		<span class="nav-product-price nav-next-product-price">'.$next_product_price.'</span>
                    	</div>';
	                    $next_product_link = get_next_post_link( '%link', '<i class="pe-7s-angle-right"></i>' );

	                    echo '<div class="product-nav-next">
							<div class="product-nav-btn product-nav-next-btn">'.$next_product_link.'</div>
							<div class="product-nav-content product-nav-next-content">'.$next_product_content.'</div>
						</div>';
	                }
				?>
			</div>
		</nav>
		<?php 
	}
	add_action( 'woocommerce_single_product_summary', 'agni_framework_product_nav', 51 );
}

// Adding Column switch at shop page
if( !function_exists( 'agni_woocommerce_column_switcher' ) ){
	function agni_woocommerce_column_switcher(){
		$col_query_link = $active = '';
		$col_query_str = array( '3', '4' );
		foreach ($col_query_str as $key => $value) {
			$col_html = '';
			if( $value == '4' ){
				$active = 'active';
			}
			$string = '//'.$_SERVER['HTTP_HOST'].add_query_arg( 'agnishopcol='.$value, '' );


			if( get_query_var( 'agnishopcol' ) ){
				$string = preg_replace( '/agnishopcol=\d&/', '', $string );
			}
			$url = preg_replace( '/agnishopcol=\d&agnishopcol=/', 'agnishopcol=', $string );
			for( $count = 0; $count < $value; $count++ ){
				$col_html .= '<span></span>';
			}
			$col_query_link .= '<a class="col-query-string-btn '.$active.'" href="'.esc_url( $url ).'" data-col-class="agni-products-'.$value.'-column">'.$col_html.'</a>';
		}
		echo '<div class="agni-col-switch">'.$col_query_link.'</div>';

	}
}
if( !function_exists('agni_woocommerce_sidebar_toggle') ){
	function agni_woocommerce_sidebar_toggle(){
		echo '<div class="agni-woocommerce-sidebar-toggle"><span class="agni-woocommerce-sidebar-toggle-burg"></span><span>Filter</span></div>';
	}
}

if( !function_exists('agni_woocommerce_before_shop_loop_wrapper_start') ){
	function agni_woocommerce_before_shop_loop_wrapper_start(){
		echo '<div class="agni-woocommerce-before-shop-loop">';
	}
}
if( !function_exists('agni_woocommerce_before_shop_loop_wrapper_end') ){
	function agni_woocommerce_before_shop_loop_wrapper_end(){
		echo '</div>';
	}
}
add_action( 'woocommerce_before_shop_loop', 'agni_woocommerce_sidebar_toggle', 33 );
add_action( 'woocommerce_before_shop_loop', 'agni_woocommerce_column_switcher', 32 );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_pagination', 31 ); // Add pagination to the top
add_action( 'woocommerce_before_shop_loop', 'agni_woocommerce_before_shop_loop_wrapper_start', 9 );
add_action( 'woocommerce_before_shop_loop', 'agni_woocommerce_before_shop_loop_wrapper_end', 35 );

// Add load more button to pagination
if( !function_exists( 'agni_woocommerce_load_more_nav' ) ){
	function agni_woocommerce_load_more_nav(){
		global $halena_options;

		$shop_navigation = esc_attr( $halena_options['shop-navigation-choice'] );
		$shop_navigation_ifs_btn_text = esc_attr( $halena_options['shop-navigation-ifs-btn-text'] );
		$shop_navigation_ifs_load_text = esc_attr( $halena_options['shop-navigation-ifs-load-text'] );
		$shop_navigation_ifs_finish_text = esc_attr( $halena_options['shop-navigation-ifs-finish-text'] );
		
		if( $shop_navigation == '2' || $shop_navigation == '3' ){ 
			wp_enqueue_script( 'halena-infinitescroll-script' );
		    $load_more_button = ( $shop_navigation == '3' )?'<span class="load-more-btn">'.$shop_navigation_ifs_btn_text.'</span>':'';
		    echo '<div class="load-more-container">
			    <div class="load-more-status">
					<p class="infinite-scroll-request">'.$shop_navigation_ifs_load_text.'</p>
					<p class="infinite-scroll-error">'.$shop_navigation_ifs_finish_text.'</p>
				</div>
				<div class="load-more">'.$load_more_button.'</div>
			</div>';
		}

	}
	add_action( 'agni_woocommerce_pagination', 'agni_woocommerce_load_more_nav' );
}

// removing and adding shop subcategory thumbnails.
remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
add_action( 'woocommerce_before_subcategory_title', 'agni_woocommerce_subcategory_thumbnail', 10, '2' );

// Show subcategory thumbnails.
function agni_woocommerce_subcategory_thumbnail( $category, $thumb_args = null ) {
	$shop_cat_thumbnail = $shop_cat_thumbnail_class = $shop_cat_thumbnail_attr = $image = '';
	
	$shop_cat_thumbnail_dimension_custom = $thumb_args['category_thumbnail_dimension'];
	$shop_cat_thumbnail_individual_settings = $thumb_args['category_thumbnail_individual_settings'];
	$shop_cat_thumbnail_size = $thumb_args['category_thumbnail_size'];

	$small_thumbnail_size  	= apply_filters( 'subcategory_archive_thumbnail_size', $shop_cat_thumbnail_size );
	$dimensions    			= wc_get_image_size( $small_thumbnail_size );
	$thumbnail_id  			= get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );

	if( $shop_cat_thumbnail_size == 'custom' ){
	    $shop_cat_thumbnail_customcrop_dimension = explode( 'x', $shop_cat_thumbnail_dimension_custom );
	    
	    if( $shop_cat_thumbnail_individual_settings != '1' ){

			$term_id = $category->term_id; 
			$shop_cat_thumbnail_width = esc_html( get_term_meta( $term_id, 'terms_thumbnail_width', true ) );
			$shop_cat_thumbnail_height = esc_html( get_term_meta( $term_id, 'terms_thumbnail_height', true ) );

	        if( $shop_cat_thumbnail_width == 'width2x' && $shop_cat_thumbnail_height == 'height1x' ){
	            $shop_cat_thumbnail_customcrop_dimension[0] = ($shop_cat_thumbnail_customcrop_dimension[0]*2);
	        }
	        else if( $shop_cat_thumbnail_width == 'width1x' && $shop_cat_thumbnail_height == 'height2x' ){
	            $shop_cat_thumbnail_customcrop_dimension[1] = ($shop_cat_thumbnail_customcrop_dimension[1]*2);
	        }
	        else if( $shop_cat_thumbnail_width == 'width1x' && $shop_cat_thumbnail_height == 'height3x' ){
	            $shop_cat_thumbnail_customcrop_dimension[1] = ($shop_cat_thumbnail_customcrop_dimension[1]*3);
	        }
	        else if( $shop_cat_thumbnail_width == 'width3x' && $shop_cat_thumbnail_height == 'height1x' ){
	            $shop_cat_thumbnail_customcrop_dimension[0] = ($shop_cat_thumbnail_customcrop_dimension[0]*3);
	        }
	        else if( $shop_cat_thumbnail_width == 'width3x' && $shop_cat_thumbnail_height == 'height2x' ){
	            $shop_cat_thumbnail_customcrop_dimension[0] = ($shop_cat_thumbnail_customcrop_dimension[0]*3);
	            $shop_cat_thumbnail_customcrop_dimension[1] = ($shop_cat_thumbnail_customcrop_dimension[1]*2);
	        }
	        else if( $shop_cat_thumbnail_width == 'width2x' && $shop_cat_thumbnail_height == 'height3x' ){
	            $shop_cat_thumbnail_customcrop_dimension[1] = ($shop_cat_thumbnail_customcrop_dimension[1]*3);
	            $shop_cat_thumbnail_customcrop_dimension[0] = ($shop_cat_thumbnail_customcrop_dimension[0]*2);
	        }
	        else if( $shop_cat_thumbnail_width == 'width2x' && $shop_cat_thumbnail_height == 'height2x' ){
	            $shop_cat_thumbnail_customcrop_dimension[1] = ($shop_cat_thumbnail_customcrop_dimension[1]*2);
	            $shop_cat_thumbnail_customcrop_dimension[0] = ($shop_cat_thumbnail_customcrop_dimension[0]*2);
	        }
	        else if( $shop_cat_thumbnail_width == 'width3x' && $shop_cat_thumbnail_height == 'height3x' ){
	            $shop_cat_thumbnail_customcrop_dimension[1] = ($shop_cat_thumbnail_customcrop_dimension[1]*3);
	            $shop_cat_thumbnail_customcrop_dimension[0] = ($shop_cat_thumbnail_customcrop_dimension[0]*3);
	        }
	    }

	    $shop_cat_thumbnail = agni_thumbnail_customcrop( $thumbnail_id, $shop_cat_thumbnail_customcrop_dimension[0].'x'.$shop_cat_thumbnail_customcrop_dimension[1], 'shop-cat-thumbnail-attachment-image' );

	    if( $shop_cat_thumbnail_individual_settings != '1' && !empty($shop_cat_thumbnail) ){
            $xpath = new DOMXPath(@DOMDocument::loadHTML($shop_cat_thumbnail));
            $src = $xpath->evaluate("string(//img/@src)");
            $shop_cat_thumbnail .= '<div class="shop-cat-thumbnail-bg" style="background-image:url('.$src.')"></div>';
        }
        $shop_cat_thumbnail_class = 'agni-custom-cropped-thumbnail';
        $shop_cat_thumbnail_attr = ' data-hardcrop="true" data-thumbnail-width="'.$shop_cat_thumbnail_customcrop_dimension[0].'" data-thumbnail-height="'.$shop_cat_thumbnail_customcrop_dimension[1].'"';
	}
	else if ( $thumbnail_id && $shop_cat_thumbnail_size != 'custom' ) {

		$image        = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size );
		$image        = $image[0];
		$image_srcset = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $thumbnail_id, $small_thumbnail_size ) : false;
		$image_sizes  = function_exists( 'wp_get_attachment_image_sizes' ) ? wp_get_attachment_image_sizes( $thumbnail_id, $small_thumbnail_size ) : false;
	} 
	else {
		$image        = wc_placeholder_img_src();
		$image_srcset = $image_sizes = false;
	}

	if ( $image ) {
		// Prevent esc_url from breaking spaces in urls for image embeds.
		// Ref: https://core.trac.wordpress.org/ticket/23605.
		$image = str_replace( ' ', '%20', $image );

		// Add responsive image markup if available.
		if ( $image_srcset && $image_sizes ) {
			$shop_cat_thumbnail = '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" srcset="' . esc_attr( $image_srcset ) . '" sizes="' . esc_attr( $image_sizes ) . '" />';
		} else {
			$shop_cat_thumbnail = '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
		}
	}


	echo '<div class="shop-cat-thumbnail '.$shop_cat_thumbnail_class.'" '.$shop_cat_thumbnail_attr.'>'.$shop_cat_thumbnail.'</div>';
	
}


// Adding Query Variables
if( !function_exists('agni_woocommerce_custom_query_variables') ){
	function agni_woocommerce_custom_query_variables($vars) {
	  $vars[] = 'agnishopcol';
	  return $vars;
	}
	add_filter( 'query_vars', 'agni_woocommerce_custom_query_variables' );
}

// Sale Flash filter
if( !function_exists('agni_woocommerce_custom_sale_flash') ){
	function agni_woocommerce_custom_sale_flash(){
		global $product, $halena_options;

		$product_regular_price = $product->get_regular_price();
		$product_sale_price = $product->get_sale_price();
		
		$sale_flash_choice = !empty($halena_options['shop-sale-flash'])?$halena_options['shop-sale-flash']:''; 

		if( $sale_flash_choice == '2' && !$product->is_type( 'variable' ) ){
			$sale_flash_off_text = !empty($halena_options['shop-sale-flash-off-text'])?$halena_options['shop-sale-flash-off-text']:'';
			$sale_flash = (round((($product_regular_price - $product_sale_price)*100)/$product_regular_price)).'%'.$sale_flash_off_text;
		}
		else if( $sale_flash_choice == '3' && !$product->is_type( 'variable' ) ){
			$sale_flash_discount_text = !empty($halena_options['shop-sale-flash-discount-text'])?$halena_options['shop-sale-flash-discount-text']:'';
			$sale_flash = $sale_flash_discount_text.get_woocommerce_currency_symbol().round($product_regular_price - $product_sale_price);
		}
		else{
			$sale_flash = esc_html__( 'Sale!', 'halena' );
		}

		return '<span class="onsale">' . $sale_flash . '</span>';
	}
	add_filter( 'woocommerce_sale_flash', 'agni_woocommerce_custom_sale_flash' );
}

if( !function_exists('agni_woocommerce_cart_page_continue_shopping') ){
	function agni_woocommerce_cart_page_continue_shopping(){
		echo '<a href="'.esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ).'" class="btn btn-alt btn-primary cart-continue-shopping-btn">'.esc_html__( 'Continue Shopping', 'halena' ).'</a>';
	}
	add_action( 'agni_woocommerce_cart_page_continue_shopping', 'agni_woocommerce_cart_page_continue_shopping' );
}

function agni_woocommerce_checkout_custom_paypal_img() {
    return AGNI_FRAMEWORK_URL . '/img/paypal.png';
}
  
add_filter('woocommerce_paypal_icon', 'agni_woocommerce_checkout_custom_paypal_img');

// Agni Quick view
function agni_woocommerce_processing_quick_view(){

	if( !check_ajax_referer( 'agni_quick_view_nonce', 'security' ) ){
		return 'Invalid Nonce';
	} 

	global $product;
	$product_thumbnails = '';
	$product_id = $_REQUEST['agni_quick_view_product_id'];

	$product = wc_get_product( $product_id );
	//$product_thumbnail = get_post_thumbnail_id( $product_id );
	$product_thumbnails .= get_the_post_thumbnail( $product_id, 'shop_single' ); //wp_get_attachment_image( $product_id, 'shop_single' );
	$attachment_ids = array_filter($product->get_gallery_image_ids());
	//print_r($attachment_ids);
	if ( !empty($attachment_ids) ) {
		foreach ( $attachment_ids as $attachment_id ) {
			$product_thumbnails .= wp_get_attachment_image( $attachment_id, 'shop_single' );
		}
	}

	add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array( 'TA_WC_Variation_Swatches_Frontend', 'get_swatch_html' ), 100, 2 );
	add_filter( 'tawcvs_swatch_html', array( 'TA_WC_Variation_Swatches_Frontend', 'swatch_html' ), 5, 4 );
	 
	?>
	<div class="agni-quick-view-container woocommerce single-product-page product-type-<?php echo esc_html( $product->get_type() ); ?>">
		<div class="agni-quick-view-product-thumbnails">
			<?php echo wp_kses_post( $product_thumbnails ); ?>
		</div>
		<div class="agni-quick-view-product-details single-product-description single-product-cart-style-2">
			<h4 itemprop="name" class="product_title"><?php echo esc_html( $product->get_name() ); ?></h4>
			<h5 class="price"><?php echo wp_kses( $product->get_price_html(), array( 'span' => array( 'class' => array() ), 'ins' => array( 'class' => array() ), 'del' => array( 'class' => array() ) ) ); ?></h5>
			<?php if( !empty($product->get_short_description()) ){ ?>
				<div class="woocommerce-product-details__short-description">
					<?php echo wp_kses_post( $product->get_short_description() ); ?>
				</div>
			<?php } ?>

			<?php do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' ); ?>

			<div class="agni-quick-view-product-single-btn">
				<?php echo '<a href="'.esc_url( get_permalink( $product_id ) ).'">Product Details</a>'; ?>
			</div>

			<div class="product_meta">

				<?php do_action( 'woocommerce_product_meta_start' ); ?>

				<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

					<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'halena' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'halena' ); ?></span></span>

				<?php endif; ?>

				<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'halena' ) . ' ', '</span>' ); ?>

				<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'halena' ) . ' ', '</span>' ); ?>

				<?php do_action( 'woocommerce_product_meta_end' ); ?>

			</div>

		</div>
	</div>

	<?php 
    die();
}
add_action( 'wp_ajax_agni_quick_view', 'agni_woocommerce_processing_quick_view' );
add_action( 'wp_ajax_nopriv_agni_quick_view', 'agni_woocommerce_processing_quick_view' );
?>