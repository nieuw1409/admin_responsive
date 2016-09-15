<?php
/*
  Module: Information Pages Unlimited
          File date: 2007/02/17
          Based on the FAQ script of adgrafics
          Adjusted by Joeri Stegeman (joeri210 at yahoo.com), The Netherlands
          Modified by SLiCK_303@hotmail.com for OSC v2.3.1

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/
?>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo $title; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
			                    
		  <div class="panel panel-primary"> 
              <div class="panel-heading"><?php echo  $title ; ?></div>		  
			  <div class="panel-body">  

<?php
                 if (!strstr($info_group['locked'], 'visible')) {
?>
	                <br />
					<label class"=label control-label col-xs-3"><?php echo ENTRY_STATUS ; ?></label>
 	                <div class="form-group">                      
        			   <?php echo tep_bs_radio_field('visible', '1', STATUS_ACTIVE  ,   'input_info_active', (($edit['visible'] == 1) ? true : false ), 'radio radio-success radio-inline', '', '', 'right'  ) ; ?>
        			   <?php echo tep_bs_radio_field('visible', '0', STATUS_INACTIVE, 'input_info_inactive', (($edit['visible'] == 1) ? false : true ), 'radio radio-success radio-inline', '', '', 'right' ) ; ?>
                    </div>	
	                <br />					
<?php
                 }

                 if (!strstr($info_group['locked'], 'parent_id')) {
                    if ((sizeof($data) > 0)) { 	            
				      $Select_Info_Pages = array(array('id' => '', 'text' => ENTRY_TEXT_TOP_PAGE));		
                      while (list($key, $val) = each($data)) {
	                     if ($val['parent_id'] == $edit['parent_id']){
	                        $Selected_Info_Page = $val['parent_id'];
	                     }		  
                         $Select_Info_Pages[] = array('id'   => $val['information_id'] ,
                                                      'text' => $val['information_title']  );
                      }					 
					}
                 } else {
                     echo '<span class="messageStackError">' . WARNING_PARENT_PAGE .'</span>';
                 }					
?>
                 <div class="form-group"> 
					   <?php echo tep_draw_bs_pull_down_menu( 'parent_id', $Select_Info_Pages,     $Selected_Info_Page, ENTRY_PARENT_PAGE, 'id_info_page', 'col-md-9', ' selectpicker show-tick', 'control-label col-xs-3', 'left' ) ; ?>
 				 </div>
<?php

                 if (!strstr($info_group['locked'], 'sort_order')) {
?>
                    <div class="form-group"> 									   
						<?php echo  tep_draw_bs_input_field('sort_order',  $edit['sort_order'],   ENTRY_SORT_ORDER, 'id_input_sort_order' ,    'col-xs-3', 'col-xs-9', 'left', ENTRY_SORT_ORDER,  '', true ) . PHP_EOL; ?>
                    </div>
					<br />
<?php
                 }				 
                 if (!strstr($info_group['locked'], 'information_description')) {				 
				 
				    $contents_edit_location_tab1 = '' ;
					include( DIR_WS_MODULES . 'info_pages_edit_tab_1.php' ) ;	
					echo $contents_edit_location_tab1 ; 
				 
				 }
				 
?>
                 <div>	
	               <div class="col-md-12">
<?php
                     // Decide when to show the buttons (Determine or 'locked' is active)
                     if ((empty($info_group['locked'])) || ($_GET['information_action'] == 'Edit')) {				 
                        echo tep_draw_bs_button(IMAGE_SAVE, "ok", null)  . PHP_EOL;
                     } else {						
                        echo tep_draw_bs_button(IMAGE_INSERT, "ok", null)  . PHP_EOL;	                    
				     }
					 echo tep_draw_bs_button(IMAGE_CANCEL, 'remove', tep_href_link(FILENAME_INFORMATION_MANAGER, 'gID=' . $gID)). PHP_EOL;
?>
                   </div> 					 
	             </div> 		
                 </form> 	  
			  </div> <!-- end panel body -->
		  </div> <!-- end panel  -->		  
		  
    </table>
	