<?php
  /*
  $Id: cm_sc_xsell_in_cart.php, v1.0 20160322 Kymation$
  $Loc: catalog/includes/modules/content/shopping_cart/

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 James C Keebaugh

  Released under the GNU General Public License v2.0 or later
*/

  class cm_sc_xsell_in_cart {
    protected $version = '1.0';
    public $code = '';
    public $group = '';
    public $title = '';
    public $description = '';
    public $sort_order = 0;
    public $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_SHOPPING_CART_XSELL_TITLE;
      $this->description = MODULE_CONTENT_SHOPPING_CART_XSELL_DESCRIPTION;
      $this->description .= '<div class="secWarning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';

      if ( defined('MODULE_CONTENT_SHOPPING_CART_XSELL_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_SHOPPING_CART_XSELL_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_SHOPPING_CART_XSELL_STATUS == 'True');
      }
    }

    public function execute() {
      global $oscTemplate, $cart, $currencies, $PHP_SELF;

      if( $cart->count_contents() > 0 ) {
        $ids_in_cart_array = $this->products_in_cart();

        $xsell_contents_list = $this->xsell_products( $ids_in_cart_array );

        $products_data = $this->xcell_product_data_query( $xsell_contents_list );

        if( $products_data !== false && count($products_data) > 0 ) {

          ob_start();
          include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/' . basename(__FILE__));
          $template = ob_get_clean();

          $oscTemplate->addContent($template, $this->group);
        }
      }
    }

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return defined('MODULE_CONTENT_SHOPPING_CART_XSELL_STATUS');
    }

    public function install() {
	  global $multi_stores_config;			
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Module Version', 'MODULE_CONTENT_SHOPPING_CART_XSELL_VERSION', '" . $this->version . "', 'The version of this module that you are running.', '6', '0', 'tep_cfg_disabled(', now() ) ");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Xsell Module', 'MODULE_CONTENT_SHOPPING_CART_XSELL_STATUS', 'True', 'Should the xsell products block be shown on the shopping cart page?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SHOPPING_CART_XSELL_SORT_ORDER', '2000', 'Sort order of display. Lowest is displayed first.', '6', '2', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SHOPPING_CART_XSELL_CONTENT_WIDTH', '12', 'What width container should the content be shown in?', '6', '3', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Product Width', 'MODULE_CONTENT_SHOPPING_CART_XSELL_PRODUCT_WIDTH', '4', 'What width container should the individual products be shown in?', '6', '4', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Max Products', 'MODULE_CONTENT_SHOPPING_CART_XSELL_MAX_DISPLAY_PRODUCTS', '9', 'Maximum number of xsell products to show on the shopping cart page.', '6', '5', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Product Order', 'MODULE_CONTENT_SHOPPING_CART_XSELL_ORDER_BY', 'Popular', 'What order should the xsell products be shown in?', '6', '6', 'tep_cfg_select_option(array(\'Popular\', \'Random\'), ', now())");
    }

    public function remove() {
	  global $multi_stores_config;			
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    public function keys() {
      $keys = array();
      
      $keys[] = 'MODULE_CONTENT_SHOPPING_CART_XSELL_VERSION';
      $keys[] = 'MODULE_CONTENT_SHOPPING_CART_XSELL_STATUS';
      $keys[] = 'MODULE_CONTENT_SHOPPING_CART_XSELL_SORT_ORDER';
      $keys[] = 'MODULE_CONTENT_SHOPPING_CART_XSELL_CONTENT_WIDTH';
      $keys[] = 'MODULE_CONTENT_SHOPPING_CART_XSELL_PRODUCT_WIDTH';
      $keys[] = 'MODULE_CONTENT_SHOPPING_CART_XSELL_MAX_DISPLAY_PRODUCTS';
      $keys[] = 'MODULE_CONTENT_SHOPPING_CART_XSELL_ORDER_BY';
      
      return $keys;
    }
    
    /**
     * Get an array of IDs of the products in the cart.
     * 
     * @global object $cart  Shopping cart class
     * @return array  Product IDs of products in the cart
     */
    protected function products_in_cart() {
      global $cart;
      
      // Get a list of the products in the cart from the cart class
      $ids_in_cart = $cart->get_product_id_list();
      
      // Convert the comma separated list to an array
      $ids_in_cart_array_1 = explode( ',', $ids_in_cart );
      
      // Remove whitespace from the array values
      $ids_in_cart_array_2 = array_map( 'trim', $ids_in_cart_array_1 );
      
      // Convert values to integers to get rid of options/attributes
      $ids_in_cart_array = array_map( 'intval', $ids_in_cart_array_2 );

      return $ids_in_cart_array;
    }
    
    /**
     * Xsell product IDs
     * 
     * Get a comma-separated list of the product IDs of all of the xsell 
     * recommended products related to the products in the cart.
     * 
     * @global object $cart  The shopping cart class
     * @param array $ids_in_cart_array  IDs of products in the cart
     * @return string  Comma-separated list of xsell product IDs
     */
    protected function xsell_products( $ids_in_cart_array ) {
      global $cart;
      
      $xsell_contents_array = array();
      foreach ( $ids_in_cart_array as $product_id_in_cart ) {
        $xsell_query_raw = "
          select
            xsell_id
          from
            " . TABLE_PRODUCTS_XSELL . " 
          where 
            products_id = " . $product_id_in_cart . "
        ";
        //echo $xsell_query_raw;
        $xsell_query = tep_db_query( $xsell_query_raw );

        while( $xsell_products = tep_db_fetch_array( $xsell_query ) ) {
          if( !in_array( $xsell_products['xsell_id'], $xsell_contents_array ) && !$cart->in_cart( $xsell_products['xsell_id'] ) ) {
            $xsell_contents_array[] = (int) $xsell_products['xsell_id'];
          }
        }
      }

      $xsell_contents_list = implode( ',', $xsell_contents_array );
      return $xsell_contents_list;
    }

    /**
     * Xsell data array
     * 
     * Generates a multi-dimensional array of data on all of the products 
     * recommended for cross-sell based on the products in the cart. This array 
     * is used by the template to generate the Xsell In Cart block.
     * 
     * @param string $xsell_contents_list  Comma-separated list of product IDs
     * @return array  Data on the xsell products
     */
    protected function xcell_product_data_query( $xsell_contents_list ) {
      $xsell_contents_array = array();
      
      if( strlen( $xsell_contents_list ) > 0 ) {
        $xsell_query_raw = $this->raw_data_query( $xsell_contents_list );

        $xsell_query = tep_db_query( $xsell_query_raw );
        
        while( $xsell_products = tep_db_fetch_array( $xsell_query ) ) {
          $xsell_contents_array[$xsell_products['products_id']] = $xsell_products;
        }
        
      } else {
        $xsell_contents_array = false;
      }
      
      return $xsell_contents_array;
    }
    
    /**
     * SQL query string for the xsell products
     * 
     * @global integer $languages_id  The current language ID
     * @param string $xsell_contents_list  Comma-separated list of product IDs
     * @return string  SQL query
     */
    protected function raw_data_query( $xsell_contents_list ) {
      global $languages_id ;
	  
      $customer_group_id = tep_get_cust_group_id() ;	  
      
      $xsell_query_raw = "
        select
          p.products_id, 
          pd.products_name, 
          p.products_image, 
          p.products_price, 
          p.products_tax_class_id,
          IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price
        from
          " . TABLE_PRODUCTS . " p 
          join " . TABLE_PRODUCTS_DESCRIPTION . " pd 
            on (pd.products_id = p.products_id
                and p.products_status = '1')
          left join " . TABLE_SPECIALS . " s 
            on (p.products_id = s.products_id)
        where 
          p.products_id in (" . $xsell_contents_list . ")
          and pd.language_id = '" . (int) $languages_id . "' 
          and find_in_set('".$customer_group_id."', products_hide_from_groups) = 0 
	      and find_in_set('" . SYS_STORES_ID . "', p.products_to_stores) != 0 		  
        order by 
          " . $this->xsell_order() . "
        limit
          " . MODULE_CONTENT_SHOPPING_CART_XSELL_MAX_DISPLAY_PRODUCTS . "
      ";
      
      return $xsell_query_raw;
    }
    
    /**
     * SQL Order By string
     * 
     * @return string  The order by string
     */
    protected function xsell_order() {
      If( MODULE_CONTENT_SHOPPING_CART_XSELL_ORDER_BY === 'Popular' ) {
        $xsell_order = 'p.products_ordered';
      } else {
        $xsell_order = 'rand()';
      }
      
      return $xsell_order;
    }
   function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_SHOPPING_CART_XSELL_STATUS'");
            $this->enabled = (MODULE_CONTENT_SHOPPING_CART_XSELL_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_SHOPPING_CART_XSELL_STATUS'");
            $this->enabled = (MODULE_CONTENT_SHOPPING_CART_XSELL_STATUS == 'True');

    }		    
  } // End class


  ////////////////////////////////////////////////////////////////////////////
  //                                                                        //
  //  This is the end of the module class.                                  //
  //  Everything past this point is an independent function, not a method.  //
  //                                                                        //
  ////////////////////////////////////////////////////////////////////////////


  ////
  // Function to show a disabled entry (Value is shown but cannot be changed)
  if( !function_exists( 'tep_cfg_disabled' ) ) {
    function tep_cfg_disabled( $value ) {
      return tep_draw_input_field( 'configuration_value', $value, ' disabled' );
    }
  }

