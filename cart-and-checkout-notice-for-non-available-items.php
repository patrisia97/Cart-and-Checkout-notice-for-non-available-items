<?php

/**
* Plugin Name: Cart and Checkout Notice For Non-Available Items 
* Description: Shows a notice in the Cart and the Checkout page when the stock of a product is less than the number of items of that product in the cart, so the order's arrival may be delayed.
* Author: Patrisia Kalogianni
* Version: 1.0
**/



class WC_Custom_Non_Available_Items_Notices {

    //  Bootstraps the class and hooks required actions & filters.
     
    public static function init() {
		
		add_action('woocommerce_before_cart',  __CLASS__ . '::check_item_availability');
        add_action('woocommerce_before_checkout_form',  __CLASS__ . '::check_item_availability', 5);
    
	}
	
	/**
    * Shows a notification in the Cart and Checkout pages when the stock is not enough to cover the quantity of 
	* the product that the client has selected to purchase, so there my be a delay in the arrival of the products.
	 */

    public static function check_item_availability(){
		
		global $woocommerce;
		
		$items = $woocommerce->cart->get_cart();
		
		$non_available_items = array();
		
		// Gets the cart items' information.
		
		foreach($items as $item => $values) { 
		
            $product =  wc_get_product( $values['data']->get_id()); 
			
			// Saves the names (or titles) of the products whose stock is not enough to cover the selected quantity for purchase.
			
			if ( $product->get_stock_quantity() < $values['quantity'] && $product->get_stock_quantity() > 0){
					
				$non_available_items[] = $product->get_title();	
			}
		}		
		
		// If there are products with more items in the cart than are currently available.
		
		if ( sizeof($non_available_items) != 0 ){
					
			for ( $i = 0; $i < sizeof($non_available_items) ; $i++ ){
					
				// Only 1 item.
					
				if ( sizeof($non_available_items) == 1 ){
						
					$text = "το προϊόν ".$non_available_items[$i] .".";
					
				} 
				
				// More than 1 items.
					
				else{
					
   					if ( $i == 0 ){  // 1st item.

						$text = "τα προϊόντα " . $non_available_items[$i] ;
						
					}
						
				    else if (  $i == sizeof($non_available_items) -1 ){  // Last item.
						
			        	$text .= " και " .$non_available_items[$i] . ".";
				    	
					}
					    
					else{
							
					    $text .= ", " . $non_available_items[$i] ;
				    
					}
				}	

			}
			
			// Displays notice.
			
			echo '<div class = "woocommerce-info">'; 
			echo '  <img src="'. plugin_dir_url( __FILE__ ).'exclamation-circle-solid.svg" width="16" height="16" style="margin-right: 10px" >';  
			echo '  <p style="margin-bottom: 0px;"> Ο αριθμός των τεμαχίων που επιλέξατε για '.$text.' δεν είναι άμεσα διαθέσιμος. Ενδέχεται να υπάρξει καθυστέρηση στον χρόνο παράδoσης.</p>';  
		    echo '</div>';
        
		}
		
	}
        
}

WC_Custom_Non_Available_Items_Notices::init();