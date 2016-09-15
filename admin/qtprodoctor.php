<?php
/*
  $Id: server_info.php 1785 2008-01-10 15:07:07Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2008 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  require(DIR_WS_INCLUDES . 'template_top.php');


  if(isset($HTTP_GET_VARS['action'])){
  	$doctor_action = $HTTP_GET_VARS['action'];
  }
  
  if(isset($HTTP_GET_VARS['pID'])){
  	$products_id = $HTTP_GET_VARS['pID'];
  }
  
?>

   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo 'QTPro Doctor';//echo HEADING_TITLE;; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
		  
		<?php 
			switch($doctor_action){
				case 'examine':
					if(qtpro_doctor_product_healthy($products_id)){
			           echo '       <ul class="list-group">'. PHP_EOL ;
			           echo '			       <li class="list-group-item">'. PHP_EOL ;
			           echo '					   <p class="bg-success"><b>Product is healthy</b><br /></p>'. PHP_EOL ;
					   echo '                      <p>The database entries for this products stock as they should.</p>' . PHP_EOL ;
			           echo '                </li>'. PHP_EOL ;						
					   echo '       </ul>' . PHP_EOL ;
//						print '<span style="font-family: Verdana, Arial, sans-serif; font-size: 10px; color: green; font-weight: normal; text-decoration: none;"><b>Product is healthy</b><br /> The database entries for this products stock as they should.</span>';
					}else{
			           echo '       <ul class="list-group">'. PHP_EOL ;
			           echo '			       <li class="list-group-item">'. PHP_EOL ;
			           echo '					   <p class="bg-danger"><b>Product is sick</b><br /></p>'. PHP_EOL ;
					   echo '                      <p>The database entries for this products stock is messed up. This is why the table above looks messed up.</p>' . PHP_EOL ;
			           echo '                </li>'. PHP_EOL ;						
					   echo '       </ul>' . PHP_EOL ;						
//						print '<span style="font-family: Verdana, Arial, sans-serif; font-size: 10px; color: red; font-weight: normal; text-decoration: none;"><b>Product is sick</b><br /> The database entries for this products stock is messed up. This is why the table above looks messed up.</span>';
					}
				break;
				case 'amputate':
					echo qtpro_doctor_amputate_bad_from_product($products_id).' database entries where amputated';
					qtpro_update_summary_stock($products_id);
					echo '<br />' . PHP_EOL ;
					echo tep_draw_bs_button( IMAGE_BACK, 'chevron-left', tep_href_link(FILENAME_QTPRODOCTOR, '', 'SSL') )  . PHP_EOL ;					
				break;
				case 'chuck_trash':
					echo qtpro_chuck_trash().' database entries where identified as trash and deleted.';
					echo '<br />' . PHP_EOL ;
					echo tep_draw_bs_button( IMAGE_BACK, 'chevron-left', tep_href_link(FILENAME_QTPRODOCTOR, '', 'SSL') )  . PHP_EOL ;					
				break;
				case 'update_summary':
					qtpro_update_summary_stock($products_id);
					echo 'The summary stock for the product was updated.' . PHP_EOL ;
					echo '<br />' . PHP_EOL ;
					echo tep_draw_bs_button( IMAGE_BACK, 'chevron-left', tep_href_link(FILENAME_QTPRODOCTOR, '', 'SSL') )  . PHP_EOL ;
				break;
				
				
				
				default:
				
			     echo '       <ul class="list-group">'. PHP_EOL ;
			     echo '			       <li class="list-group-item">'. PHP_EOL ;
			     echo '					   <p>You currently have <b>'. qtpro_normal_product_count(). '</b> products in your store.<br /></p>'. PHP_EOL ;
			     echo '                </li>'. PHP_EOL ;			   

			     echo '			       <li class="list-group-item">'. PHP_EOL ;
			     echo '					   <p>You currently have <b>'. qtpro_normal_product_count(). '</b> products in your store.<br /></p>'. PHP_EOL ;
			     echo '                </li>'. PHP_EOL ;

			     echo '			       <li class="list-group-item">'. PHP_EOL ;
			     echo '					   <p><b>'. qtpro_tracked_product_count() . '</b></b> of them have options with tracked stock.<br /></p>'. PHP_EOL ;
			     echo '                </li>'. PHP_EOL ;

			     echo '			       <li class="list-group-item">'. PHP_EOL ;
			     echo '					   <p>In the database we currently have <b>'. qtpro_number_of_trash_stock_rows(). '</b> trash rows.<br /></p>'. PHP_EOL ;
			     echo '                </li>'. PHP_EOL ;				 

			     echo '			       <li class="list-group-item">'. PHP_EOL ;
			     echo '					   <p>'. qtpro_doctor_formulate_database_investigation(). '</p>'. PHP_EOL ;
			     echo '                </li>'. PHP_EOL ;	
				 
			     echo '       </ul>'. PHP_EOL ;
				 
				break;
			
			}
		?>		  
		  
   </table>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>