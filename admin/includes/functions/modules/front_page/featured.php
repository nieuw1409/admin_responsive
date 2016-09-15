<?php
/*  $Id featured.php 20101117 Kymation $
  $Loc: catalog/admin/includes/functions/ $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
*/
    function tep_cfg_pull_down_products( $products_id, $key = '' ) {
      global $languages_id;

      $name = ( ( $key ) ? 'configuration[' . $key . ']' : 'configuration_value' );

      $products_array = array( array( 'id' => '0', 'text' => TEXT_NONE ) );
      $products_query_raw = "
        select
          p.products_id,
          pd.products_name
        from
          " . TABLE_PRODUCTS . " p
          join " . TABLE_PRODUCTS_DESCRIPTION . " pd
            on pd.products_id = p.products_id
        where
          p.products_status = '1'
          and pd.language_id = '" . ( int )$languages_id . "'
        order by
          pd.products_name
      ";
      $products_query = tep_db_query( $products_query_raw );

      while( $products_data = tep_db_fetch_array( $products_query ) ) {
        $products_array[] = array( 'id' => $products_data['products_id'],
                                   'text' => $products_data['products_name']
                                 );
      }

      return tep_draw_pull_down_menu( $name, $products_array, $products_id );
    }
?>