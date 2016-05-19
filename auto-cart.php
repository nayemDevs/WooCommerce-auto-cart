<?php 

/*
Plugin Name: WooCommerce Auto Cart
Plugin URI: https://nayemdevs.com
Description: Add a product for your customer into the cart automatically.
Version: 1.0
Author: Nayem
Author URI: https://nayemdevs.com
License: GPL2
*/




add_filter( 'woocommerce_general_settings', 'redirect_menu' );
function redirect_menu( $settings ) {
			 $updated_settings = array();
  foreach ( $settings as $section ) {
    // at the bottom of the General Options section
    if ( isset( $section['id'] ) && 'general_options' == $section['id'] &&
       isset( $section['type'] ) && 'sectionend' == $section['type'] ) {
      $updated_settings[] = array(
                    'title'    => __( 'Product ID', 'woocommerce' ),
                    'desc'     => __( 'Insert the product id. This product will be added to the cart automatically when a user will visit your site', 'woocommerce' ),
                    'id'       => 'cart_product_id',
                    'type'     => 'text',
                    'default'  => '',
                    'class'    => 'wc-enhanced-select',
                    'desc_tip' => true
        );               
            
    
    }
    
    $updated_settings[] = $section;
  }
  return $updated_settings;
}


// add item to cart on visit
add_action( 'init', 'add_product_to_cart' );
function add_product_to_cart() {
    if ( ! is_admin() ) {
    global $woocommerce;
    $product_id = get_option( 'cart_product_id') ;
    $found = false;
//check if product already in cart
    if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
    foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
    $_product = $values['data'];
        if ( $_product->id == $product_id )
        $found = true;
    }
// if product not found, add it
    if ( ! $found )
    $woocommerce->cart->add_to_cart( $product_id );
    } else {
// if no products in cart, add it
        $woocommerce->cart->add_to_cart( $product_id );

        }
    }
}

//change the warning 

add_filter('woocommerce_cart_item_removed_title','auto_cart_warning',10,2);

function auto_cart_warning($title, $item){
     // echo "<pre>"; var_dump($title, $item);
     if (get_option( 'cart_product_id') == $item['product_id']) {
         $title = $title. ' can not be' ;
     }
     return $title;

}