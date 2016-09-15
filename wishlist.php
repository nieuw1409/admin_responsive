<?php
/*
  $Id: wishlist.php,v 3.11  2005/04/20 Dennis Blake
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/
// This version, removes the product when adding to a cart now, replaced all of the  HTTP_POST_VARS for $_POST

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_WISHLIST);

/*******************************************************************
******* ADD PRODUCT TO WISHLIST IF PRODUCT ID IS REGISTERED ********
*******************************************************************/

  if(tep_session_is_registered('wishlist_id')) {
  	$wishList->add_wishlist($wishlist_id, $attributes_id);
/*******************************************************************
******* CREATES COOKIE TO STORE WISHLIST ON LOCAL COMPUTER  ********
********************************************************************
******* TO CHANGE THE LENGTH OF TIME THE COOKIE IS STORED:  ********
*******                                                     ********
******* EDIT THE "DAYS" VARIABLE BELOW.  THIS VARIABLE IS   ********
******* THE NUMBER OF DAYS THE COOKIE IS STORED.            ********
*******************************************************************/	
	//	$days = 30;																															
	//	$time = time() + (3600 * 24 * $days);
	// 	$cook_id = serialize($wishList->wishID);
	//	tep_setcookie('wish', $cook_id , $time);
/***********************END CHANGE*********************************/
    $product_id = $wishlist_id;
   	tep_session_unregister('wishlist_id');
  	if(tep_session_is_registered('attributes_id')) tep_session_unregister('attributes_id');
  	if(WISHLIST_REDIRECT == 'Yes') {
	  	tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_id));
	  }
  }


/*******************************************************************
****************** ADD PRODUCT TO SHOPPING CART ********************
*******************************************************************/

  if (isset($_POST['add_wishprod'])) {
  	if(isset($_POST['wlaction']) && $_POST['wlaction'] == 'cart') {
	  	foreach ($_POST['add_wishprod'] as $value) {
		  	$product_id = tep_get_prid($value);
			  $cart->add_cart($product_id, $cart->get_quantity(tep_get_uprid($product_id, $_POST['id'][$value]))+1, $_POST['id'][$value]);
			  $wishList->remove($value);
			}
			if (DISPLAY_CART == 'true') tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
		}
	}
  


/*******************************************************************
****************** DELETE PRODUCT FROM WISHLIST ********************
*******************************************************************/

  if (isset($_POST['add_wishprod'])) {
  	if(isset($_POST['wlaction']) && $_POST['wlaction'] == 'delete') {
	  	foreach ($_POST['add_wishprod'] as $value) {
		  	$wishList->remove($value);
		  }
	  }
  }


/*******************************************************************
************* EMAIL THE WISHLIST TO MULTIPLE FRIENDS ***************
*******************************************************************/

  $wishlist_not_empty = false;
  if (is_array($wishList->wishID) && !empty($wishList->wishID)) {
    $wishlist_not_empty = (count($wishList->wishID) > 0);
  }

  if (isset($_POST['wlaction']) && ($_POST['wlaction'] == 'email') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken) && $wishlist_not_empty) {

		$error = false;
		$guest_errors = "";
		$email_errors = "";
		$message_error = "";

  	$message = tep_db_prepare_input($_POST['message']);
		if(strlen($message) < 1) {
			$error = true;
			$message_error .= "<div class=\"messageStackError\"><img src=\"images/icons/error.gif\" /> " . ERROR_MESSAGE . "</div>";
		}			
    // check for links to other web sites, a sign that a spammer is trying to use this site to send spam
    $protocols = array('http://', 'https://', 'file://', 'ftp://', 'news://', 'mailto:', 'telnet://', 'ssh:');
    $check = strtolower($message);
    $thisdomain = HTTP_SERVER;
    $thisdomain = strtolower(substr($thisdomain, strpos($thisdomain, '://') + 3));
    foreach ($protocols as $p ) {
      $x = 0;
      while (strpos($check, $p, $x) !== false) {
        $x = strpos($check, $p, $x) + strlen($p);
        if ((substr($check, $x, strlen($thisdomain)) != $thisdomain) || !preg_match('/\/|\s/', substr($check, $x + strlen($thisdomain), 1))) {
          $error = true;
          $message_error .= "<div class=\"messageStackError\"><img src=\"images/icons/error.gif\" /> " . ERROR_INVALID_LINK . "</div>";
        }
      }
    }

 		if(tep_session_is_registered('customer_id')) { // logged in
			$customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
	  	if (tep_db_num_rows($customer_query) != 1 ) tep_redirect(tep_href_link(FILENAME_LOGOFF, '', 'SSL')); // invalid customer id
	  	$customer = tep_db_fetch_array($customer_query);
	
			$from_name = $customer['customers_firstname'] . ' ' . $customer['customers_lastname'];
			$from_email = $customer['customers_email_address'];
			$subject = $customer['customers_firstname'] . ' ' . WISHLIST_EMAIL_SUBJECT;
			$link = tep_href_link(FILENAME_WISHLIST_PUBLIC, "public_id=" . $customer_id);
	
			$body = $message . sprintf(WISHLIST_EMAIL_LINK, $from_name, $link, $link);
		} else { // guest
			$from_name = tep_db_prepare_input($_POST['your_name']);
			$from_email = tep_db_prepare_input($_POST['your_email']);
			if(strlen($from_name) < 1) {
				$error = true;
				$guest_errors .= "<div class=\"messageStackError\"><img src=\"images/icons/error.gif\" /> " . ERROR_YOUR_NAME . "</div>";
			}
			if(strlen($from_email) < 1) {
				$error = true;
				$guest_errors .= "<div class=\"messageStackError\"><img src=\"images/icons/error.gif\" /> " .ERROR_YOUR_EMAIL . "</div>";
			} elseif(!tep_validate_email($from_email)) {
				$error = true;
				$guest_errors .= "<div class=\"messageStackError\"><img src=\"images/icons/error.gif\" /> " . ERROR_VALID_EMAIL . "</div>";
			}

			$subject = $from_name . ' ' . WISHLIST_EMAIL_SUBJECT;

			$z = 0;
			$prods = "";
			foreach($_POST['prod_name'] as $name) {
				$prods .= '<a href="' . tep_db_prepare_input($_POST['prod_link'][$z]) .'">' . tep_db_prepare_input($name) . "  " . tep_db_prepare_input($_POST['prod_att'][$z]) . "\n" . tep_db_prepare_input($_POST['prod_link'][$z]) . "</a>\n\n";
				$z++;
			}
			$body = $message . "\n\n" . $prods . "\n\n" . $from_name . WISHLIST_EMAIL_GUEST;
	  }

		//Check each posted name => email for errors.
    $email = tep_db_prepare_input($_POST['email']);
    $friend = tep_db_prepare_input($_POST['friend']);
		for ($j=0; $j < sizeof($friend); $j++) {
		  $friend[$j] = $friend[$j];
			if($j == 0) {
				if($friend[0] == '' && $email[0] == '') {
					$error = true;
					$email_errors .= "<div class=\"messageStackError\"><img src=\"images/icons/error.gif\" /> " . ERROR_ONE_EMAIL . "</div>";
				}
			}

			if(isset($friend[$j]) && $friend[$j] != '') {
				if(strlen($email[$j]) < '1') {
					$error = true;
					$email_errors .= "<div class=\"messageStackError\"><img src=\"images/icons/error.gif\" /> " . ERROR_ENTER_EMAIL . "</div>";
				} elseif(!tep_validate_email($email[$j])) {
					$error = true;
					$email_errors .= "<div class=\"messageStackError\"><img src=\"images/icons/error.gif\" /> " . ERROR_VALID_EMAIL . "</div>";
				}
			}

			if(isset($email[$j]) && $email[$j] != '') {
				if(strlen($friend[$j]) < '1') {
					$error = true;
					$email_errors .= "<div class=\"messageStackError\"><img src=\"images/icons/error.gif\" /> " . ERROR_ENTER_NAME . "</div>";
				}
			}
		}

    // check for attempt to send email from another page besides this sites Wish List script
    if (substr($_SERVER['HTTP_REFERER'], 0, strpos($_SERVER['HTTP_REFERER'], '.php') + 4) != tep_href_link(FILENAME_WISHLIST)) {
      if (tep_session_is_registered('customer_id')) {
        $cid = $customer_id;
      } else {
        $cid = TEXT_SPAM_NO_ID;
      }
      $spammsg = sprintf(TEXT_SPAM_MESSAGE, date('l F j, Y  H:i:s'), $cid, $from_name, $from_email, $_SERVER['HTTP_REFERER'], tep_get_ip_address(), $_SERVER['REMOTE_PORT'], $_SERVER['HTTP_USER_AGENT']) . $message;
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, TEXT_SPAM_SUBJECT, $spammsg, $from_name, $from_email_address);
      foreach ($_SESSION as $key => $value) unset($_SESSION[$key]);
      echo ERROR_SPAM_BLOCKED;
      tep_exit();
    }

    $actionRecorder = new actionRecorder('ar_wish_list', (tep_session_is_registered('customer_id') ? $customer_id : null), $from_name);
    if (!$actionRecorder->canPerform()) {
      $error = true;

      $actionRecorder->record(false);

      $messageStack->add('wishlist', sprintf(ERROR_ACTION_RECORDER, (defined('MODULE_ACTION_RECORDER_WISH_LIST_EMAIL_MINUTES') ? (int)MODULE_ACTION_RECORDER_WISH_LIST_EMAIL_MINUTES : 15)));
    }

		if($error == false) {
			for ($j=0; $j < sizeof($friend); $j++) {
				if($friend[$j] != '') {
					tep_mail($friend[$j], $email[$j], $subject, $friend[$j] . ",\n\n" . $body, $from_name, $from_email);
				}
			//Clear Values
				$friend[$j] = "";
				$email[$j] = "";
			}
			$message = "";
			$actionRecorder->record();
     	$messageStack->add('wishlist', WISHLIST_SENT, 'success');
		}
  }


 $breadcrumb->add(NAVBAR_TITLE_WISHLIST, tep_href_link(FILENAME_WISHLIST, '', 'SSL'));
 
 require(DIR_WS_INCLUDES . 'template_top.php');
 if ($messageStack->size('wishlist') > 0) {
    echo '<div>' . $messageStack->output('wishlist') . '</div>';
  }
  
// BOF Separate Pricing per Customer
    $customer_group_id = tep_get_cust_group_id() ;
// EOF Separate Pricing per Customer
?>

<div class="page-header">
   <h1><?php echo HEADING_TITLE; ?></h1>
</div>   
<br />
<div class="contentContainer"> <?php echo tep_draw_form('wishlist_form', tep_href_link(FILENAME_WISHLIST), 'post', 'class="form-inline" role="form"', true);

if (is_array($wishList->wishID) && !empty($wishList->wishID)) {
	reset($wishList->wishID);
?>
  <table class="table">
  
     <thead>
        <tr>
          <th><?php echo BOX_TEXT_IMAGE; ?></th>
          <th><?php echo BOX_TEXT_PRODUCT; ?></th>
          <th><?php echo BOX_TEXT_PRICE; ?></th>
          <th><?php echo BOX_TEXT_SELECT; ?></th>
        </tr>
      </thead>  
	  <tbody>
    <?php
		$i = 0;
		while (list($wishlist_id, ) = each($wishList->wishID)) {

			$product_id = tep_get_prid($wishlist_id);
		
		    $products_query = tep_db_query("select pd.products_id, pd.products_name, pd.products_description, p.products_image, p.products_status, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from ( " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd ) left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where pd.products_id = '" . (int)$product_id . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by products_name");
			$products = tep_db_fetch_array($products_query);
?>
            <tr>
            <td class="text-left">
<?php 
               if($products['products_status'] == 0) {
				  echo tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT); 
		       } else {
?>
                   <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $wishlist_id, 'NONSSL'); ?>"><?php echo tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT); ?></a>
<?php 
               } 
?>
            </td>      
            <td class="text-left">
            <strong>
<?php 
            if($products['products_status'] == 0) {
			   echo $products['products_name']; 
		    } else {
?>
               <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $wishlist_id, 'NONSSL'); ?>"><?php echo $products['products_name']; ?></a>
<?php 
            } 
?>
            </strong>
            <input type="hidden" name="prod_link[]" value="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $wishlist_id, 'NONSSL'); ?>" />
            <input type="hidden" name="prod_name[]" value="<?php echo $products['products_name']; ?>" />
<?php

/*******************************************************************
******** THIS IS THE WISHLIST CODE FOR PRODUCT ATTRIBUTES  *********
*******************************************************************/

                  $attributes_addon_price = 0;

                  // Now get and populate product attributes
					$att_name = "";
					if (isset($wishList->wishID[$wishlist_id]['attributes'])) {
						while (list($option, $value) = each($wishList->wishID[$wishlist_id]['attributes'])) {
                      		echo tep_draw_hidden_field('id[' . $wishlist_id . '][' . $option . ']', $value);

         					$attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)$wishlist_id . "'
                                       and pa.options_id = '" . (int)$option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . (int)$languages_id . "'
                                       and poval.language_id = '" . (int)$languages_id . "'");
							$attributes_values = tep_db_fetch_array($attributes);

                       		if ($attributes_values['price_prefix'] == '+') {
								$attributes_addon_price += $attributes_values['options_values_price'];
                       		} else if($attributes_values['price_prefix'] == '-') {
                         		$attributes_addon_price -= $attributes_values['options_values_price'];
							}

                            // OTF contrib begins
                            if ($value == OPTIONS_VALUE_TEXT_ID) {   
                               $wish_attr_name_sql_raw = 'SELECT po.products_options_name FROM ' .
                                          TABLE_PRODUCTS_OPTIONS . ' po, ' . TABLE_PRODUCTS_ATTRIBUTES . ' pa WHERE ' .
                                                ' pa.products_id="' . (int)$wishlist_id . '" AND ' .              
                                                ' pa.options_id=po.products_options_id AND ' .
                                                ' po.language_id="' . (int)$languages_id . '" ';
			  
                                $wish_attr_name_sql = tep_db_query($wish_attr_name_sql_raw);
                                if ($wish_arr = tep_db_fetch_array($wish_attr_name_sql)) {
                                  $attributes_values['products_options_name']  = $wish_arr['products_options_name'] ;
                                }            
                                $attributes_values['products_options_values_name'] = $value ;          
                            }
							
							$att_name .= " (" . $attributes_values['products_options_name'] . ": " . $attributes_values['products_options_values_name'] . ") ";
                       		echo '<br /><small><em> ' . $attributes_values['products_options_name'] . ': ' . $attributes_values['products_options_values_name'] . '</em></small>';
                    	} // end while attributes for product

					}

					echo '<input type="hidden" name="prod_att[]" value="' . $att_name . '" />';

//                   	if (tep_not_null($products['specials_new_products_price'])) {
//                   		$products_price = '<del>' . $currencies->display_price($products['products_price']+$attributes_addon_price, tep_get_tax_rate($products['products_tax_class_id'])) . '</del> <span class="productSpecialPrice">' . $currencies->display_price($products['specials_new_products_price']+$attributes_addon_price, tep_get_tax_rate($products['products_tax_class_id'])) . '</span>';
//                   	} else {
//                       	$products_price = $currencies->display_price($products['products_price']+$attributes_addon_price, tep_get_tax_rate($products['products_tax_class_id']));
//                    }
                    $pf->loadProduct((int)$wishlist_id, (int)$languages_id);
                    $products_price = '        <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">' . 
			      '<font size="'.PRODUCT_PRICE_SIZE.'"><meta itemprop="priceCurrency" content="' . tep_output_string($currency) . '" />' . 
			       $pf->getPriceStringShort(  )
				   . '</font></span><br />'  ;
					
					
					//$pf->getPriceStringShort( '<span class="ui-state-default ui-corner-all">', '<span class="ui-state-highlight ui-corner-all">' );

/*******************************************************************
******* CHECK TO SEE IF PRODUCT HAS BEEN ADDED TO THEIR CART *******
*******************************************************************/

			if($cart->in_cart($wishlist_id)) {
				echo '<br /><span class="label label-info">' . TEXT_ITEM_IN_CART . '</span>';
			}

/*******************************************************************
********** CHECK TO SEE IF PRODUCT IS NO LONGER AVAILABLE **********
*******************************************************************/

   			if($products['products_status'] == 0) {
   				echo '<br /><strong style="color: red">' . TEXT_ITEM_NOT_AVAILABLE . '</strong>';
  			}
	
			$i++;
?>
      </td>
      <td class="text-left" ><?php echo $products_price; ?></td>
      <td class="text-left"><?php

/*******************************************************************
* PREVENT THE ITEM FROM BEING ADDED TO CART IF NO LONGER AVAILABLE *
*******************************************************************/

			if($products['products_status'] != 0) {
				echo tep_draw_checkbox_field('add_wishprod[]',$wishlist_id);
			}
?>
       </td>
    </tr>
	</tbody>
<?php
		}
?>
  </table>
  <br />
  <div id="mydiv"></div>
  <table class="table">
  <tbody>
  <tr>
     <td align="center">
<?php 
         echo tep_draw_button(BUTTON_TEXT_ADD_CART, 'shopping-cart', null, 'primary', array('params' => 'onclick=\'var input = document.createElement("input"); input.setAttribute("type", "hidden"); input.setAttribute("name", "wlaction"); input.setAttribute("value", "cart"); document.getElementById("mydiv").appendChild(input);\'')) ;
?>
      </td>		 
	  <td align="center">
<?php	  
        echo tep_draw_button(BUTTON_TEXT_DELETE,   'trash', null, 'primary', array('params' => 'onclick=\'var input = document.createElement("input"); input.setAttribute("type", "hidden"); input.setAttribute("name", "wlaction"); input.setAttribute("value", "delete"); document.getElementById("mydiv").appendChild(input);\'')) ;
?>
      </td>
     </tr>
    </tbody>
    </table>	
  <?php
/*******************************************************************
*********** CODE TO SPECIFY HOW MANY EMAILS TO DISPLAY *************
*******************************************************************/


if(!tep_session_is_registered('customer_id')) {
?>
  <div class="contentText">
    <br />
    <div class="well well-info">    
           <?php echo WISHLIST_EMAIL_TEXT_GUEST; ?>
    </div>
  
    
    <table class="table">
	  <tbody>
      <div class="allert alert-error">
         <p><?php echo $guest_errors; ?></p>
	  </div>	  
      <tr>
        <td><?php echo tep_bootstrap_form_input_field($your_name,                       // name input
                                          '',                 // value
										  'txt_yr_name',                    // id
										  TEXT_YOUR_NAME ,          // initial text
										  TEXT_YOUR_NAME,                 // label
										  'col-md-2',         // columns label
										  'col-md-4',               // columns input
										  50          // size of input
										  ) ;    // reinsert value


		//tep_draw_input_field('your_name', $your_name); ?></td>
      
        <td class="main"><?php echo tep_bootstrap_form_input_field($your_email,                       // name input
                                          '',                 // value
										  'txt_yr_email',                    // id
										  TEXT_YOUR_EMAIL ,          // initial text
										  TEXT_YOUR_EMAIL,                 // label
										  'col-md-2',         // columns label
										  'col-md-4',               // columns input
										  50          // size of input										  
										  ) ;    
		
		//tep_draw_input_field('your_email', $your_email); ?></td>
      </tr>
	  </tbody>
    </table>
  </div>
  <?php
	} else {
?>
       <br />
       <div class="well well-info">    
           <?php echo WISHLIST_EMAIL_TEXT; ?>
       </div>
       <br />	   
  <?php
	}
?>
    <div class="allert alert-error">
       <p><?php echo $email_errors; ?></p>
	</div>
  <?php
	$email_counter = 0;
?>
  <table class="table">
     <tbody>
    <?php
	while($email_counter < DISPLAY_WISHLIST_EMAILS) {
?>
    <tr>
        <td>
<?php 
           echo tep_bootstrap_form_input_field('friend[]',                       // name input
                                          $friend[$email_counter],                 // value
										  'frnd_name'.$email_counter,                    // id
										  '' ,          // initial text
										  TEXT_NAME,                 // label
										  'col-md-2',         // columns label
										  'col-md-4',               // columns input
										  50          // size of input										  
										  ) ;    
		
?>
      </td>	
        <td>
<?php 
           echo tep_bootstrap_form_input_field('email[]',                       // name input
                                          $email[$email_counter],                 // value
										  'frnd_email'.$email_counter,                    // id
										  '' ,          // initial text
										  TEXT_EMAIL,                 // label
										  'col-md-2',         // columns label
										  'col-md-4',               // columns input
										  50          // size of input										  
										  ) ;    
		
?>
      </td>		  
<!--      <td class="main"><?php echo TEXT_NAME; ?>&nbsp;&nbsp;<?php echo tep_draw_input_field('friend[]', $friend[$email_counter]); ?></td>
      <td class="main"><?php echo TEXT_EMAIL; ?>&nbsp;&nbsp;<?php echo tep_draw_input_field('email[]', $email[$email_counter]); ?></td>
-->	  
    </tr>
    <?php
	$email_counter++;
	}
?>
   </tbody>	

    <div class="allert alert-error">
       <p><?php echo $message_error; ?></p>
	</div>	
    <tr>

      <td><?php echo tep_bootstrap_form_textarea('message',                       // name input
                                          '',                 // value
										  'mssg_email',                    // id
										  '' ,          // initial text
										  TEXT_MESSAGE,                 // label
										  'col-md-2',         // columns label
										  'col-md-10',               // columns input
                                          45,                 // width of texarea
										  10                // height of texarea
										   ) ;
?>										  
      </td>
    </tr>
    <tr>
      <td colspan="2" align="right"><?php echo tep_draw_button(SMALL_IMAGE_BUTTON_SEND_EMAIL, 'send', null, 'primary', array('params' => 'onclick=\'var input = document.createElement("input"); input.setAttribute("type", "hidden"); input.setAttribute("name", "wlaction"); input.setAttribute("value", "email"); document.getElementById("mydiv").appendChild(input);\'')); ?></td>
    </tr>

  </table>
  </form>
  <?php

} else { // Nothing in the customers wishlist

?>
  <div class="panel panel-default panel-alert">
      <div class="panel-body">
	    <div class="well well-default">
 	       <?php echo BOX_TEXT_NO_ITEMS; ?>
        </div>
		
<!-- Button trigger modal -->
        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
            <?php echo BUTTON_HELP; ?>
        </button>

<!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
           <div class="modal-dialog">
              <div class="modal-content">
                 <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                      <h4 class="modal-title" id="myModalLabel"><?php echo HELP_HEADING_TITLE; ?></h4>
                 </div>
                 <div class="modal-body">
                       <?php echo TEXT_INFORMATION; ?>
                 </div>
                 <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo IMAGE_BUTTON_CLOSE ; ?></button>
                 </div>
               </div>
            </div>
         </div>	<!-- end modal -->
	  </div>
  </div>	  
		
  <?php } ?>
  </form>
</div>
<?php 
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
