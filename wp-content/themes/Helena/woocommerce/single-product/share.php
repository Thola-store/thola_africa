<?php
/**
 * Single Product Share
 *
 * Sharing plugins can hook into here or you can add your own code directly.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $halena_options;

if( $halena_options['shop-sharing-panel'] == '1' ){
	function woocommerce_shop_sharing_panel(){ 
		global $halena_options; ?>
        <div class="shop-sharing">
            <div class="shop-sharing-btn"><a href="#"><i class="pe-7s-share"></i></a></div>
            <ul class="shop-sharing-content list-inline">
                <?php  if($halena_options['shop-sharing-icons'][1] == '1'){ ?>
                    <li><a href="http://www.facebook.com/sharer.php?u=<?php esc_url( the_permalink() );?>/&amp;t=<?php echo esc_html( str_replace( ' ', '%20', the_title('', '', false) ) ); ?>"><i class="fa fa-facebook"></i></a></li>
                <?php  }?>
                <?php  if($halena_options['shop-sharing-icons'][2] == '1'){ ?>
                    <li><a href="https://twitter.com/intent/tweet?text=<?php echo esc_html( str_replace( ' ', '%20', the_title('', '', false) ) ); ?>-<?php esc_url( the_permalink() ); ?>"><i class="fa fa-twitter"></i></a></li>
                <?php  }?>
                <?php  if($halena_options['shop-sharing-icons'][3] == '1'){ ?>             
                    <li><a href="https://plus.google.com/share?url=<?php esc_url( the_permalink() );?>"><i class="fa fa-google-plus"></i></a></li>
                <?php  }?>
                <?php  if($halena_options['shop-sharing-icons'][4] == '1'){ ?>             
                    <li><a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php esc_url( the_permalink() );?>&title=<?php echo esc_html( str_replace( ' ', '%20', the_title('', '', false) ) ); ?>"><i class="fa fa-linkedin"></i></a></li>
                <?php  }?>
            </ul>
        </div>
	<?php }
	add_action( 'woocommerce_share', 'woocommerce_shop_sharing_panel' );
}
?>
<?php do_action( 'woocommerce_share' ); // Sharing plugins can hook into here ?>
