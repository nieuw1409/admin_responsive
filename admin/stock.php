<?php
/*
      QT Pro Version 4.1
  
      stock.php
  
      Contribution extension to:
        osCommerce, Open Source E-Commerce Solutions
        http://www.oscommerce.com
     
      Copyright (c) 2004, 2005 Ralph Day
      Released under the GNU General Public License
  
      Based on prior works released under the GNU General Public License:
        QT Pro prior versions
          Ralph Day, October 2004
          Tom Wojcik aka TomThumb 2004/07/03 based on work by Michael Coffman aka coffman
          FREEZEHELL - 08/11/2003 freezehell@hotmail.com Copyright (c) 2003 IBWO
          Joseph Shain, January 2003
        osCommerce MS2
          Copyright (c) 2003 osCommerce
          
      Modifications made:
        11/2004 - Add input validation
                  clean up register globals off problems
                  use table name constant for products_stock instead of hard coded table name
        03/2005 - Change $_SERVER to $HTTP_SERVER_VARS for compatibility with older php versions
        
*******************************************************************************************
  
      QT Pro Stock Add/Update
  
      This is a page to that is linked from the osCommerce admin categories page when an
      item is selected.  It displays a products attributes stock and allows it to be updated.

*******************************************************************************************

  $Id: stock.php,v 1.00 2003/08/11 14:40:27 IBWO Exp $

  Enhancement module for osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  
  Credit goes to original QTPRO developer.
  Attributes Inventory - FREEZEHELL - 08/11/2003 freezehell@hotmail.com
  Copyright (c) 2003 IBWO

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require(DIR_WS_INCLUDES . 'template_top.php');
  
  $product_investigation = qtpro_doctor_investigate_product($HTTP_GET_VARS['product_id']);
?>



   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
		  
		  <div class="panel panel-primary">
		     <div class="panel-body">
			 
			   <?php echo qtpro_doctor_formulate_product_investigation($product_investigation, 'detailed'); ?>
			 
			 </div>
		  </div>
<?php 
	
	
	      echo '<br />' . PHP_EOL ;
		  echo '<div class="btn-toolbar" role="toolbar">' . PHP_EOL ;
		  echo     tep_draw_bs_button(TEXT_BUTTON_EDIT_PROD, 'pencil', tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['product_id'] . '&action=new_product'), null, null, 'btn-warning text-warning') . PHP_EOL ;
		  //<ul><li><a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID=' . $HTTP_GET_VARS['product_id'] . '&action=new_product') . '" class="menuBoxContentLink">Edit this product</a></li>';
	      echo     tep_draw_bs_button(TEXT_BUTTON_LOW_STOCK, 'info', tep_href_link(FILENAME_STATS_LOW_STOCK_ATTRIB, '', 'NONSSL'), null, null, 'btn-info text-info') . PHP_EOL ;
		  //<a href="' . tep_href_link(FILENAME_STATS_LOW_STOCK_ATTRIB, '', 'NONSSL') . '" class="menuBoxContentLink">Go to the low stock report</a><br></li>';
	 
		  //Generate both the text (in $path_array) and the parameter (in $cpath_string_array)
		  $raw_path_array =tep_generate_category_path($HTTP_GET_VARS['product_id'], 'product');
		  $path_array = array();
		  $cpath_string_array = array();
		  foreach($raw_path_array as $raw_path){
			$path_in_progress='';
			$cpath_string_in_progress='';
			foreach($raw_path as $raw_path_piece){
			  $path_in_progress .= $raw_path_piece['text'].' >> ';
			  $cpath_string_in_progress .= $raw_path_piece['id'].'_';
			}
			$path_array[]= substr($path_in_progress, 0, -4);
			$cpath_string_array[] = substr($cpath_string_in_progress, 0, -1);
		  }
		  
		  if (sizeof($raw_path_array)>0) {


			$curr_pos = 0;
			foreach($path_array as $neverusedvariable) {
	          echo tep_draw_bs_button(TEXT_BUTTON_GOTO_PROD . $path_array[$curr_pos], 'pencil', tep_href_link(FILENAME_CATEGORIES, 'pID='.$HTTP_GET_VARS['product_id'].'&cPath='. $cpath_string_array[$curr_pos] , 'NONSSL'), null, null, 'btn-warning text-warning') . PHP_EOL ;
				
//			  print '<li><a href="' . tep_href_link(FILENAME_CATEGORIES, 'pID='.$HTTP_GET_VARS['product_id'].'&cPath='. $cpath_string_array[$curr_pos] , 'NONSSL') . '" class="menuBoxContentLink">Go to this product in '.$path_array[$curr_pos].'</a></li>';
			  $curr_pos++;
			}
		 }else{
			echo '<span class="text-danger">' . TEXT_WARNING_PROD_NOT_FOUND . '</span>';
		 }
		 
		 echo '<div class="btn-toolbar" role="toolbar">' 		 
?>		  
  </table>		  
  
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>