<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
 
 // check if there more then 1 store active
  $stores_query = tep_db_query( "select stores_id from " . TABLE_STORES ) ;
  if ( tep_db_num_rows( $stores_query ) > 1 ) $stores_multi_present = 'true' ;  
// bof multi stores

  $languages_to_stores = '@,';  
  if ( $HTTP_POST_VARS['stores_languages'] ) { // if any of the checkboxes are checked
    $_stores = array_keys( $HTTP_POST_VARS['stores_languages'] ) ;    
    foreach($_stores as $val) {
        $languages_to_stores .= tep_db_prepare_input($val).','; 
    } // end foreach
  }
  $languages_to_stores = substr($languages_to_stores,0,strlen($languages_to_stores)-1); // remove last comma
  if ( $stores_multi_present != 'true' ) $languages_to_stores = '@,1' ; // 1 store automatic activ  
// eof multi stores  

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
        $name = tep_db_prepare_input($HTTP_POST_VARS['name']);
        $code = tep_db_prepare_input(substr($HTTP_POST_VARS['code'], 0, 2));
        $image = tep_db_prepare_input($HTTP_POST_VARS['image']);
        $directory = tep_db_prepare_input($HTTP_POST_VARS['directory']);
        $sort_order = (int)tep_db_prepare_input($HTTP_POST_VARS['sort_order']);

        tep_db_query("insert into " . TABLE_LANGUAGES . " (name, code, image, directory, sort_order, languages_to_stores) values ('" . tep_db_input($name) . "', '" . tep_db_input($code) . "', '" . tep_db_input($image) . "', '" . tep_db_input($directory) . "', '" . tep_db_input($sort_order) . "', '" . tep_db_input($languages_to_stores) . "')");
        $insert_id = tep_db_insert_id();

// create additional categories_description records
        $categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd on c.categories_id = cd.categories_id where cd.language_id = '" . (int)$languages_id . "'");
        while ($categories = tep_db_fetch_array($categories_query)) {
          tep_db_query("insert into " . TABLE_CATEGORIES_DESCRIPTION . " (categories_id, language_id, categories_name) values ('" . (int)$categories['categories_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($categories['categories_name']) . "')");
        }

// create additional products_description records
        $products_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, pd.products_url from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where pd.language_id = '" . (int)$languages_id . "'");
        while ($products = tep_db_fetch_array($products_query)) {
          tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_url) values ('" . (int)$products['products_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($products['products_name']) . "', '" . tep_db_input($products['products_description']) . "', '" . tep_db_input($products['products_url']) . "')");
        }

// create additional products_options records
        $products_options_query = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "'");
        while ($products_options = tep_db_fetch_array($products_options_query)) {
          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, language_id, products_options_name) values ('" . (int)$products_options['products_options_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($products_options['products_options_name']) . "')");
        }

// create additional products_options_values records
        $products_options_values_query = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . (int)$languages_id . "'");
        while ($products_options_values = tep_db_fetch_array($products_options_values_query)) {
          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$products_options_values['products_options_values_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($products_options_values['products_options_values_name']) . "')");
        }

// create additional manufacturers_info records
        $manufacturers_query = tep_db_query("select m.manufacturers_id, mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id where mi.languages_id = '" . (int)$languages_id . "'");
        while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
          tep_db_query("insert into " . TABLE_MANUFACTURERS_INFO . " (manufacturers_id, languages_id, manufacturers_url) values ('" . $manufacturers['manufacturers_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($manufacturers['manufacturers_url']) . "')");
        }
// create additional FAQ language records
        $faq_query = tep_db_query("select f.faq_id, fd.faq_question, fd.faq_answer from " . TABLE_FAQ . " f left join " . TABLE_FAQ_DESCRIPTION . " fd on f.faq_id = fd.faq_id where fd.language_id = '" . (int)$languages_id . "'");
        while ($faq = tep_db_fetch_array($faq_query)) {
          tep_db_query("insert into " . TABLE_FAQ_DESCRIPTION . " (faq_id, language_id, faq_question, faq_answer) values ('" . (int)$faq['faq_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($faq['faq_question']) . "', '" . tep_db_input($faq['faq_answer']) . "')");
        }

// create additional orders_status records
        $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
        while ($orders_status = tep_db_fetch_array($orders_status_query)) {
          tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . (int)$orders_status['orders_status_id'] . "', '" . (int)$insert_id . "', '" . tep_db_input($orders_status['orders_status_name']) . "')");
        }
// create additional email text records
        $stores_group_query = tep_db_query("select stores_id from " . TABLE_STORES . " order by stores_id");
        while ($stores_stores = tep_db_fetch_array($stores_group_query)) {
             $email_to_stores = $stores_stores['stores_id'] ;
        
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_NORMAL_NEWCUSTOMER                       . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_HTML_NEWCUSTOMER                         . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_NORMAL_NEWCUSTOMER_ADMIN                 . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_HTML_NEWCUSTOMER_ADMIN                   . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_NORMAL_NEWORDER                          . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_HTML_NEWORDER                            . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_NORMAL_NEWORDER_ADMIN                    . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')"); 
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_HTML_NEWORDER_ADMIN                      . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_NORMAL_ORDER_UPDATE                      . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_HTML_ORDER_UPDATE                        . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_NORMAL_PASSWORDFORGOTTEN                 . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_HTML_PASSWORDFORGOTTEN                   . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_NORMAL_CREATE_REVIEW                     . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_HTML_CREATE_REVIEW		                 . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_NORMAL_CONTACT_US                        . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_HTML_CONTACT_US   		                 . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");		      

             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_NORMAL_WISHLIST                          . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_HTML_WISHLIST                            . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_NORMAL_SUBSCRIBE_NEWSLETTER              . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')"); 
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_HTML_SUBSCRIBE_NEWSLETTER                . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER            . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER              . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_NORMAL_WISHLIST_ADMIN                    . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_HTML_WISHLIST_ADMIN                      . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_NORMAL_SUBSCRIBE_NEWSLETTER_ADMIN        . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_HTML_SUBSCRIBE_NEWSLETTER_ADMIN		     . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_NORMAL_UNSUBSCRIBE_NEWSLETTER_ADMIN      . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");
             tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" . _SYS_EMAIL_HTML_UNSUBSCRIBE_NEWSLETTER_ADMIN   	 . "', '" . (int)$insert_id . "', '" . (int)$email_to_stores . "')");				 			 
        }			 

        if (isset($HTTP_POST_VARS['default']) && ($HTTP_POST_VARS['default'] == 'on')) {
//          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($code) . "' where configuration_key = 'DEFAULT_LANGUAGE'");
          tep_db_query("update " . $multi_stores_config . " set configuration_value = '" . tep_db_input($code) . "' where configuration_key = 'DEFAULT_LANGUAGE'");
// Configuration Cache modification start
          require('includes/configuration_cache.php');
// Configuration Cache modification end				  
        }

        tep_redirect(tep_href_link(FILENAME_LANGUAGES, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'lID=' . $insert_id));
        break;
      case 'save':
        $lID = tep_db_prepare_input($HTTP_GET_VARS['lID']);
        $name = tep_db_prepare_input($HTTP_POST_VARS['name']);
        $code = tep_db_prepare_input(substr($HTTP_POST_VARS['code'], 0, 2));
        $image = tep_db_prepare_input($HTTP_POST_VARS['image']);
        $directory = tep_db_prepare_input($HTTP_POST_VARS['directory']);
        $sort_order = (int)tep_db_prepare_input($HTTP_POST_VARS['sort_order']);

        tep_db_query("update " . TABLE_LANGUAGES . " set name = '" . tep_db_input($name) . "', code = '" . tep_db_input($code) . "', image = '" . tep_db_input($image) . "', directory = '" . tep_db_input($directory) . "', sort_order = '" . tep_db_input($sort_order) . "' , languages_to_stores = '" . $languages_to_stores . "' where languages_id = '" . (int)$lID . "'");
        // create additional email text if store is activated		
        $stores_group_query = tep_db_query("select stores_id from " . TABLE_STORES . " order by stores_id");
        while ($stores_stores = tep_db_fetch_array($stores_group_query)) {
             $email_to_stores = $stores_stores['stores_id'] ;
			 
		     // check if store is already defined
			 $query = tep_db_query("SELECT stores_id FROM ". TABLE_EMAIL_ORDER_TEXT ." WHERE stores_id = '" . $email_to_stores . "' and language_id = '" . (int)$lID . "'");
             if ( tep_db_num_rows( $query ) > 0 ) {
			   // present
			 } else { // add email text
               //$email_to_stores = tep_db_prepare_input($val); 

               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_NEWCUSTOMER           . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_NEWCUSTOMER             . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_NEWCUSTOMER_ADMIN     . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_NEWCUSTOMER_ADMIN       . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_NEWORDER              . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_NEWORDER                . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_NEWORDER_ADMIN        . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')"); 
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_NEWORDER_ADMIN          . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_ORDER_UPDATE          . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_ORDER_UPDATE            . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_PASSWORDFORGOTTEN     . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_PASSWORDFORGOTTEN       . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_CREATE_REVIEW         . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_CREATE_REVIEW		      . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_NORMAL_CONTACT_US            . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");
               tep_db_query("insert into " . TABLE_EMAIL_ORDER_TEXT . " (eorder_text_id, language_id, stores_id ) values ('" ._SYS_EMAIL_HTML_CONTACT_US   		      . "', '" . (int)$lID . "', '" . (int)$email_to_stores . "')");		
              }			   
        }		

        if ($HTTP_POST_VARS['default'] == 'on') {
//          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($code) . "' where configuration_key = 'DEFAULT_LANGUAGE'");
          tep_db_query("update " . $multi_stores_config . " set configuration_value = '" . tep_db_input($code) . "' where configuration_key = 'DEFAULT_LANGUAGE'");
		  // update Configuration Cache modification start
          require ('includes/configuration_cache.php');
        // Configuration Cache modification end
        }

        tep_redirect(tep_href_link(FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $HTTP_GET_VARS['lID']));
        break;
      case 'deleteconfirm':
        $lID = tep_db_prepare_input($HTTP_GET_VARS['lID']);

        $lng_query = tep_db_query("select languages_id from " . TABLE_LANGUAGES . " where code = '" . DEFAULT_CURRENCY . "'");
        $lng = tep_db_fetch_array($lng_query);
        if ($lng['languages_id'] == $lID) {
//          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '' where configuration_key = 'DEFAULT_CURRENCY'");
          tep_db_query("update " . $multi_stores_config . " set configuration_value = '' where configuration_key = 'DEFAULT_CURRENCY'");
// Configuration Cache modification start
          require('includes/configuration_cache.php');
// Configuration Cache modification end				  
        }

        tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where language_id = '" . (int)$lID . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . (int)$lID . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$lID . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . (int)$lID . "'");
        tep_db_query("delete from " . TABLE_MANUFACTURERS_INFO . " where languages_id = '" . (int)$lID . "'");
        tep_db_query("delete from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$lID . "'");
		// delete email text
        tep_db_query("delete from " . TABLE_EMAIL_ORDER_TEXT . " where language_id = '" . (int)$lID . "'");		
        // delete configuration group languages descriptions
        tep_db_query("delete from " . TABLE_CONFIGURATION_GROUP . " where languages_id = '" . (int)$lID . "'");			
        // delete configuration languages descriptions
        tep_db_query("delete from " . TABLE_CONFIGURATION_LANGUAGES . " where languages_id = '" . (int)$lID . "'");		
        tep_db_query("delete from " . TABLE_LANGUAGES . " where languages_id = '" . (int)$lID . "'");		
		// bof faq 2.3
		tep_db_query("delete from " . TABLE_FAQ_DESCRIPTION . " where language_id = '" . (int)$lID . "'");

        tep_redirect(tep_href_link(FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'delete':
        $lID = tep_db_prepare_input($HTTP_GET_VARS['lID']);

        $lng_query = tep_db_query("select code from " . TABLE_LANGUAGES . " where languages_id = '" . (int)$lID . "'");
        $lng = tep_db_fetch_array($lng_query);

        $remove_language = true;
        if ($lng['code'] == DEFAULT_LANGUAGE) {
          $remove_language = false;
          $messageStack->add(ERROR_REMOVE_DEFAULT_LANGUAGE, 'error');
        }
        break;
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
// BOF multi stores
    $stores_group_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
    $header = false;
    if (!tep_db_num_rows($stores_group_query) > 0) {
      $messageStack->add_session(ERROR_ALL_STORES_DELETED, 'error');
   } else {
     while ($stores_stores = tep_db_fetch_array($stores_group_query)) {
       $_stores_name[] = $stores_stores;
     }
   }  
?>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th><?php echo TABLE_HEADING_LANGUAGE_NAME; ?></th>
                   <th><?php echo TABLE_HEADING_LANGUAGE_IMAGE; ?></th>				   
                   <th><?php echo TABLE_HEADING_LANGUAGE_CODE; ?></th>				   
                   <th><?php echo TABLE_HEADING_ACTION; ?></th>				   

                </tr>
              </thead>
              <tbody>
<?php
                  $languages_query_raw = "select languages_id, name, code, image, directory, sort_order, languages_to_stores from " . TABLE_LANGUAGES . " order by sort_order";
                  $languages_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $languages_query_raw, $languages_query_numrows);
                  $languages_query = tep_db_query($languages_query_raw);
  

                  while ($languages = tep_db_fetch_array($languages_query)) {
                     if ((!isset($HTTP_GET_VARS['lID']) || (isset($HTTP_GET_VARS['lID']) && ($HTTP_GET_VARS['lID'] == $languages['languages_id']))) && !isset($lInfo) && (substr($action, 0, 3) != 'new')) {
                        $lInfo = new objectInfo($languages);
                     }

                     if (isset($lInfo) && is_object($lInfo) && ($languages['languages_id'] == $lInfo->languages_id) ) {
                        echo '              <tr class=äctive" onclick="document.location.href=\'' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->languages_id . '&action=edit') . '\'">' . PHP_EOL;
                     } else {
                        echo '              <tr  onclick="document.location.href=\'' . tep_href_link(FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $languages['languages_id']) . '\'">' . PHP_EOL;
                     }

                     if (DEFAULT_LANGUAGE == $languages['code']) {
                       echo '                <td class="text-warning small_text"><strong>' . $languages['name'] . ' (' . TEXT_DEFAULT . ')</strong></td>' . PHP_EOL;
                     } else {
                       echo '                <td>' . $languages['name'] . '</td>' . PHP_EOL ;
                     }
?>
                              <td><?php echo tep_image(tep_catalog_href_link(DIR_WS_LANGUAGES . $languages['directory'] . '/images/' . $languages['image'], '', 'SSL'), $languages['name'], null, null, null, false) ; ?></td>
                              <td><?php echo $languages['code']; ?></td>
                                           
                              <td class="text-right">
                                   <div class="btn-toolbar" role="toolbar">                  
<?php
           echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $languages['languages_id']. '&action=info'),    null, 'info')    . '</div>' . PHP_EOL .
		        '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $languages['languages_id'] . '&action=edit'),    null, 'warning') . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $languages['languages_id'] . '&action=confirm'), null, 'danger')  . '</div>' . PHP_EOL .
                '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_ICON_INFO,       'info-sign',     tep_href_link(FILENAME_DEFINE_LANGUAGE, 'lngdir=' . $lInfo->directory),                                                   null, 'info')    . '</div>' . PHP_EOL ;
?>
                                   </div> 
				              </td>											
                     </tr>
<?php 
                
					  
                  if (isset($lInfo) && is_object($lInfo) && ($languages['languages_id'] == $lInfo->languages_id) && isset($HTTP_GET_VARS['action'])) { 
// BOF multi stores
                                 $stores_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
                                 while ($stores_stores = tep_db_fetch_array($stores_query)) {
                                     $stores_array[] = array('id' => $stores_stores['stores_id'], 'text' => $stores_stores['stores_name']);  
                                 }	
                                 $lang_to_stores_array = explode(',', $lInfo->languages_to_stores); // multi stores
                                 $lang_to_stores_array = array_slice($lang_to_stores_array, 1); // remove "@" from the array	 // multi stores	
// EOF multi stores 					  
 	                             $alertClass = '';
                                 switch ($action) {			 
		                            case 'confirm':
			                           $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents .= '          <div class="panel-heading">' . TEXT_INFO_HEADING_DELETE_LANGUAGE . '</div>' . PHP_EOL;
			                           $contents .= '          <div class="panel-body">' . PHP_EOL;											
		                               $alertClass .= ' alert alert-danger';
		                               $contents .= '                      ' . tep_draw_form('customers', FILENAME_LANGUAGES, tep_get_all_get_params(array('lID', 'action')) . 'lID=' . $languages['languages_id'] . '&action=deleteconfirm') . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-8 col-md-8">' . PHP_EOL;
                                       $contents .= '                          <p>' . TEXT_INFO_DELETE_INTRO . '<br />' . $languages['name']   . '</p>' . PHP_EOL;
										
                                       $contents .= '                        </div>' . PHP_EOL;
                                       $contents .= '                        <div class="col-xs-12 col-sm-4 col-md-4 text-right">' . PHP_EOL;
                                       $contents .= '                          ' . tep_draw_bs_button(IMAGE_DELETE, 'ban-circle', null, null, null, 'btn-danger') . '<br>' . 
									                                               tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->languages_id), null, null, 'btn-default text-danger') . PHP_EOL;
                                       $contents .= '                        </div>' . PHP_EOL;
		                               $contents .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '    </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= ' </div>' . PHP_EOL; // end div 	panel										   
                                      break;									 		  

                                    case 'edit':
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_EDIT_INTRO . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' . tep_draw_bs_form('languages', FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->languages_id . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_languages') . PHP_EOL;													   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                           ' . tep_draw_bs_input_field('name',       $lInfo->name,        TEXT_INFO_LANGUAGE_NAME,       'id_input_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_LANGUAGE_NAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;	
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('code',       $lInfo->code,        TEXT_INFO_LANGUAGE_CODE,       'id_input_code' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_LANGUAGE_CODE,       '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('image',      $lInfo->image,       TEXT_INFO_LANGUAGE_IMAGE,      'id_input_image' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_LANGUAGE_IMAGE,      '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('directory',  $lInfo->directory,   TEXT_INFO_LANGUAGE_DIRECTORY, 'id_input_directory' ,    'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_LANGUAGE_DIRECTORY,  '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
                                       $contents            .= '                       <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                           ' . tep_draw_bs_input_field('sort_order', $lInfo->sort_order,  TEXT_INFO_LANGUAGE_SORT_ORDER, 'id_input_sort_order' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_LANGUAGE_SORT_ORDER, '', true ) . PHP_EOL;	
                                       $contents            .= '                       </div>' . PHP_EOL ;									   
									   $contents            .= '                           <br />' . PHP_EOL;	
                                       
									   if (DEFAULT_LANGUAGE != $lInfo->code) {
			                              $contents         .= '                       <div class="panel panel-primary">' . PHP_EOL ;
			                              $contents         .= '                         <div class="panel-heading">' . TEXT_SET_DEFAULT . '</div>' . PHP_EOL;
			                              $contents         .= '                         <div class="panel-body">' . PHP_EOL;										   
                                          $contents         .= '                           <div class="form-group">' . PHP_EOL ;											   
										  $contents         .=                                 tep_bs_checkbox_field('default', '', TEXT_SET_DEFAULT, 'id_set_standard', null, 'checkbox checkbox-success' ) . ' '  ;									   
                                          $contents         .= '                           </div>' . PHP_EOL ;										  
		                                  $contents .= '                                 </div>' . PHP_EOL; // end div default stores	panel body
		                                  $contents .= '                                </div>' . PHP_EOL; // end div default stores 	panel										  										  
									   }
// BOF multi stores
									   if ( $stores_multi_present == 'true' ) { // 1 store automatic active  
			                              $contents            .= '                        <div class="panel panel-primary">' . PHP_EOL ;
			                              $contents            .= '                           <div class="panel-heading">' . TEXT_LANGUAGES_TO_STORES . '</div>' . PHP_EOL;
			                              $contents            .= '                           <div class="panel-body">' . PHP_EOL;										       
                                          for ($i = 0; $i < count($stores_array); $i++) {
	                                         $contents .= '                                        <div class="form-group">' . PHP_EOL ;
                                             $contents .= '                                              ' .  tep_bs_checkbox_field('stores_languages[' . $stores_array[$i]['id'] . ']',  $stores_array[$i]['id'], $stores_array[$i]['text'], 'input_stores_'.$stores_array[$i]['id'], ((in_array($stores_array[$i]['id'], $lang_to_stores_array)) ? 1: 0) ,  'checkbox checkbox-success') . PHP_EOL ;
                                             $contents .= '                                        </div>'. PHP_EOL  ;					 	
                                          } 
		                                  $contents .= '                                      </div>' . PHP_EOL; // end div stores	panel body
		                                  $contents .= '                                   </div>' . PHP_EOL; // end div stores 	panel										  
                                       }										  
// EOF multi stores	 									   
		                               
 
                                       $contents_footer .= '                          ' . tep_draw_bs_button(IMAGE_SAVE, 'ok', null) .  
									                                                      tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->languages_id), null, null, 'btn-default text-danger') . PHP_EOL;			
		                               $contents_footer .= '                      </form>' . PHP_EOL;
		                               $contents_footer .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents_footer .= '           </div>' . PHP_EOL; // end div 	panel									   
                                      break;										  
		                            default: 
// bof multi stores		
                                        $products_to_stores_array = explode(',', $stores_languages['stores_id']); // multi stores
                                        $products_to_stores_array = array_slice($products_to_stores_array, 1); // remove "@" from the array	 // multi stores
                                        $product_to_stores =  '';

										//foreach ($_stores_name as $key => $stores_languages) { //hide_customers_group
										
                                        for ($i = 0; $i < count($stores_array); $i++) {
                                           if (in_array($stores_array[$i]['id'], $lang_to_stores_array)) {  
                                              $product_to_stores .= $stores_array[$i]['text'] . '<br />'; 
                                           }
                                        } // end for ($i = 0; $i < count($stores_array); $i++)
											
									    if ( !tep_not_null( $product_to_stores ) ) $product_to_stores = TEXT_STORES_NONE ;
			                            $contents .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                            $contents .= '          <div class="panel-heading">' . TEXT_INFO_LANGUAGE_NAME . '</div>' . PHP_EOL;
			                            $contents .= '          <div class="panel-body">' . PHP_EOL;		
			                            $contents .= '                      <div class="col-xs-12 col-sm-6 col-md-6">' . PHP_EOL;
			                            $contents .= '                        <ul class="list-group">' . PHP_EOL;
			                            $contents .= '                          <li class="list-group-item">' . PHP_EOL; 		
			                            $contents .= '                              ' . TEXT_LANGUAGES_TO_STORES . ' <br />' . $product_to_stores . PHP_EOL;
			                            $contents .= '                          </li>' . PHP_EOL;
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_NUMBER_OF_REVIEWS . ' ' . $info['number_of_reviews'] . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;					
//                                        $contents .= '                          <li class="list-group-item">' . PHP_EOL;		
//			                            $contents .= '                              ' . TEXT_INFO_STORE_ACTIVATED_NAME . ' : ' . $stores_customers[ 'stores_name' ]  . PHP_EOL;
//			                            $contents .= '                          </li>' . PHP_EOL;						                          
			                            $contents .= '                        </ul>' . PHP_EOL;
			                            $contents .= '                      </div>' . PHP_EOL;
                                        
										$contents_footer .= '                          ' .  tep_draw_bs_button(IMAGE_CLOSE, 'remove', tep_href_link(FILENAME_LANGUAGES ), null, null, 'btn-default text-danger') . PHP_EOL;										
 									
                                    break;
							
                                 }
	

		
		                         $contents .=  '</div>' . PHP_EOL ;
		                         $contents .=  $contents_sv_cncl  . PHP_EOL;
                                 $contents .=  $contents_footer  . PHP_EOL;			

                                 echo '                <tr class="content-row">' . PHP_EOL .
                                      '                  <td colspan="6">' . PHP_EOL .
                                      '                    <div class="row' . $alertClass . '">' . PHP_EOL .
                                                               $contents . 
                                      '                    </div>' . PHP_EOL .
                                      '                  </td>' . PHP_EOL .
                                      '                </tr>' . PHP_EOL;
								  
                              }		// end if assets						  						 
				} // end while ($customers = tep_db_fetch_array($cus				
?>			  
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $languages_split->display_count($languages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_LANGUAGES); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $languages_split->display_links($languages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
             </div>
		  
<?php
          if (empty($action)) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_LANGUAGE, 'plus', null,'data-toggle="modal" data-target="#new_language"') ; ?>
 			 </div>
            </div>
<?php
          }
?>			  

    </table>
        <div class="modal fade"  id="new_language" role="dialog" aria-labelledby="new_language" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <?php echo tep_draw_form('languages', FILENAME_LANGUAGES, 'action=insert')
				//tep_draw_bs_form('languages', FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->languages_id . '&action=save', 'post', 'class="form-horizontal" role="form"', 'id_edit_languages'); ?>
                
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo TEXT_INFO_HEADING_NEW_LANGUAGE; ?></h4>
                  </div>
                  <div class="modal-body">					
<?php 
// BOF multi stores
                                       $stores_query = tep_db_query("select stores_id, stores_name from " . TABLE_STORES . " order by stores_id");
                                       while ($stores_stores = tep_db_fetch_array($stores_query)) {
                                           $stores_array[] = array('id' => $stores_stores['stores_id'], 'text' => $stores_stores['stores_name']);  
                                       }	 
// EOF multi stores 
			                           $contents            .= '       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '          <div class="panel-heading">' . TEXT_INFO_INSERT_INTRO . '</div>' . PHP_EOL;
			                           $contents            .= '          <div class="panel-body">' . PHP_EOL;			
			                           $contents            .= '               ' .  PHP_EOL;	
 	                                   
									   $contents            .= '                       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '                         <div class="panel-body">' . PHP_EOL;									   
                                       $contents            .= '                               <div class="form-group">' . PHP_EOL ;									   
									   $contents            .= '                              ' . tep_draw_bs_input_field('name',       null,        TEXT_INFO_LANGUAGE_NAME,       'id_input_name' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_LANGUAGE_NAME,       '', true ) . PHP_EOL;	
                                       $contents            .= '                               </div>' . PHP_EOL ;	
                                       $contents            .= '                               <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                              ' . tep_draw_bs_input_field('code',       null,        TEXT_INFO_LANGUAGE_CODE,       'id_input_code' ,        'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_LANGUAGE_CODE,       '', true ) . PHP_EOL;	
                                       $contents            .= '                               </div>' . PHP_EOL ;									   
                                       $contents            .= '                               <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                              ' . tep_draw_bs_input_field('image',      null,       TEXT_INFO_LANGUAGE_IMAGE,      'id_input_image' ,       'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_LANGUAGE_IMAGE,      '', true ) . PHP_EOL;	
                                       $contents            .= '                               </div>' . PHP_EOL ;									   
                                       $contents            .= '                               <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                              ' . tep_draw_bs_input_field('directory',  null,   TEXT_INFO_LANGUAGE_DIRECTORY, 'id_input_directory' ,    'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_LANGUAGE_DIRECTORY,  '', true ) . PHP_EOL;	
                                       $contents            .= '                               </div>' . PHP_EOL ;									   
                                       $contents            .= '                               <div class="form-group">' . PHP_EOL ;										   
									   $contents            .= '                              ' . tep_draw_bs_input_field('sort_order', null,  TEXT_INFO_LANGUAGE_SORT_ORDER, 'id_input_sort_order' ,  'col-xs-3', 'col-xs-9', 'left', TEXT_INFO_LANGUAGE_SORT_ORDER, '', true ) . PHP_EOL;	
                                       $contents            .= '                               </div> <!-- input sort order -->' . PHP_EOL ;									   
									   $contents            .= '                               <br />' . PHP_EOL;	
		                               $contents            .= '                         </div>' . PHP_EOL; // end div std 	panel body
		                               $contents            .= '                       </div>' . PHP_EOL; // end div std 	panel									   
                                       
 	                                   $contents            .= '                       <div class="panel panel-primary">' . PHP_EOL ;
			                           $contents            .= '                         <div class="panel-heading">' . TEXT_SET_DEFAULT . '</div>' . PHP_EOL;
			                           $contents            .= '                         <div class="panel-body">' . PHP_EOL;										   
                                       $contents            .= '                           <div class="form-group">' . PHP_EOL ;											   
									   $contents            .=                                 tep_bs_checkbox_field('default', '', TEXT_SET_DEFAULT, 'id_set_standard', null, 'checkbox checkbox-success' ) . ' '  ;									   
                                       $contents            .= '                           </div>' . PHP_EOL ;										  
		                               $contents            .= '                         </div>' . PHP_EOL; // end div default stores	panel body
		                               $contents            .= '                       </div>' . PHP_EOL; // end div default stores 	panel										  										  
 
// BOF multi stores
									  if ( $stores_multi_present == 'true' ) { // 1 store automatic active  
			                              $contents            .= '                        <div class="panel panel-primary">' . PHP_EOL ;
			                              $contents            .= '                           <div class="panel-heading">' . TEXT_LANGUAGES_TO_STORES . '</div>' . PHP_EOL;
			                              $contents            .= '                           <div class="panel-body">' . PHP_EOL;										       
                                          for ($i = 0; $i < count($stores_array); $i++) {
	                                         $contents .= '                                        <div class="form-group">' . PHP_EOL ;
                                             $contents .= '                                              ' .  tep_bs_checkbox_field('stores_languages[' . $stores_array[$i]['id'] . ']',  null, $stores_array[$i]['text'], 'input_stores_'.$stores_array[$i]['id'], false,  'checkbox checkbox-success') . PHP_EOL ;
                                             $contents .= '                                        </div>'. PHP_EOL  ;					 	
                                          } 
		                                  $contents .= '                                      </div>' . PHP_EOL; // end div stores	panel body
		                                  $contents .= '                                   </div>' . PHP_EOL; // end div stores 	panel										  
									  }
// EOF multi stores	 									   
		                               

//		                               $contents        .= '                      </form>' . PHP_EOL;
		                               $contents        .= '              </div>' . PHP_EOL; // end div 	panel body
		                               $contents        .= '           </div>' . PHP_EOL; // end div 	panel	
									   $contents_footer .=  tep_draw_bs_button(IMAGE_SAVE, 'ok', null) . '&nbsp;' . tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_LANGUAGES, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $HTTP_GET_VARS['lID']));

?>
            <div class="full-iframe" width="100%"> 
                  <?php echo $contents . $contents_footer ; ?>
            </div> 
   
                   
                  </div> <!-- end div modal-body -->
            
                </form>
              </div> <!-- end div modal-content -->
            </div> <!-- end div modal -->
          </div><!-- modal #new_language -->

<?php

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>