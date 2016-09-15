<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  $cl_box_groups[] = array(
	'icon' => BOX_HEADING_EMAIL_ICON,  
    'heading' => BOX_HEADING_EMAILS,
    'apps' => array(
      array(
        'code' => FILENAME_EMAIL_ORDER_TEXT,
        'title' => BOX_EMAIL_TEXT_NEW_ORDER,
        'link' => tep_href_link(FILENAME_EMAIL_ORDER_TEXT)
      ),
      array(
        'code' => FILENAME_ADMIN_EMAIL_ORDER_TEXT,
        'title' => BOX_EMAIL_TEXT_NEW_ORDER_ADMIN,
        'link' => tep_href_link(FILENAME_ADMIN_EMAIL_ORDER_TEXT)
      ),
      array(
        'code' => FILENAME_EMAIL_CREATE_CUST_TEXT,
        'title' => BOX_EMAIL_TEXT_NEW_CUSTOMER,
        'link' => tep_href_link(FILENAME_EMAIL_CREATE_CUST_TEXT)
      ),
      array(
        'code' => FILENAME_ADMIN_EMAIL_CREATE_CUST_TEXT,
        'title' => BOX_EMAIL_TEXT_NEW_CUSTOMER_ADMIN,
        'link' => tep_href_link(FILENAME_ADMIN_EMAIL_CREATE_CUST_TEXT)
      ),
      array(
        'code' => FILENAME_ADMIN_EMAIL_UPD_ORDER_TEXT,
        'title' => BOX_EMAIL_TEXT_UPD_ORDER_ADMIN,
        'link' => tep_href_link(FILENAME_ADMIN_EMAIL_UPD_ORDER_TEXT)
      ),
      array(
        'code' => FILENAME_EMAIL_PASSWORD_FORGOTTEN_TEXT,
        'title' => BOX_EMAIL_TEXT_PASSWORD_FORGOTTEN,
        'link' => tep_href_link(FILENAME_EMAIL_PASSWORD_FORGOTTEN_TEXT)
      ),
      array(
        'code' => FILENAME_EMAIL_CONTACT_US,
        'title' => BOX_EMAIL_TEXT_CONTACT_US,
        'link' => tep_href_link(FILENAME_EMAIL_CONTACT_US )
      ),	  
      array(
        'code' => FILENAME_EMAIL_CUSTOMER_REVIEW_TEXT,
        'title' => BOX_EMAIL_TEXT_CUSTOMER_REVIEW,
        'link' => tep_href_link(FILENAME_EMAIL_CUSTOMER_REVIEW_TEXT)
      ),
      array(
        'code' => FILENAME_EMAIL_WISHLIST_TEXT,
        'title' => BOX_EMAIL_TEXT_WISHLIST,
        'link' => tep_href_link(FILENAME_EMAIL_WISHLIST_TEXT)
      ),	
      array( 	  
       'code' => FILENAME_EMAIL_ADMIN_WISHLIST_TEXT,
        'title' => BOX_EMAIL_TEXT_WISHLIST_ADMIN,
        'link' => tep_href_link(FILENAME_EMAIL_ADMIN_WISHLIST_TEXT)
      ),	 	  
      array(
        'code' => FILENAME_EMAIL_NEWSLETTER_SUBSCRIBE_TEXT,
        'title' => BOX_EMAIL_TEXT_NEWSLETTER_SUBSCRIBE,
        'link' => tep_href_link(FILENAME_EMAIL_NEWSLETTER_SUBSCRIBE_TEXT)
      ),
      array(
        'code' => FILENAME_EMAIL_NEWSLETTER_UNSUBSCRIBE_TEXT,
        'title' => BOX_EMAIL_TEXT_NEWSLETTER_UNSUBSCRIBE,
        'link' => tep_href_link(FILENAME_EMAIL_NEWSLETTER_UNSUBSCRIBE_TEXT)
      ),	 		  
      array(
        'code' => FILENAME_EMAIL_ADMIN_NEWSLETTER_SUBSCRIBE_TEXT,
        'title' => BOX_EMAIL_TEXT_NEWSLETTER_SUBSCRIBE_ADMIN,
        'link' => tep_href_link(FILENAME_EMAIL_ADMIN_NEWSLETTER_SUBSCRIBE_TEXT)
      ),	 
      array(
        'code' => FILENAME_EMAIL_ADMIN_NEWSLETTER_UNSUBSCRIBE_TEXT,
        'title' => BOX_EMAIL_TEXT_NEWSLETTER_UNSUBSCRIBE_ADMIN,
        'link' => tep_href_link(FILENAME_EMAIL_ADMIN_NEWSLETTER_UNSUBSCRIBE_TEXT)
      )	 	  
    )
  );
?>
