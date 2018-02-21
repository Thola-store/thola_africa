<?php
/**
 * Content wrappers
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div id="primary" class="content-area <?php echo (is_singular('product'))?'shop-single-content halena-shop-single-content':'shop-content halena-shop-content'; ?>">
	<main id="main" class="site-main clearfix" role="main">
