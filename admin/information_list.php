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
            <h1 class="col-xs-12 col-md-6"><?php echo EDIT_ID_INFORMATION ; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th>                     <?php echo ID_INFORMATION; ?></th>
                   <th class="text-center"><?php echo ENTRY_TITLE; ?></th>				    			   
                   <th class="text-center"><?php echo ENTRY_PARENT_PAGE; ?></th>					   
                   <th class="text-center"><?php echo PUBLIC_INFORMATION; ?></th>	
                   <th class="text-center"><?php echo ENTRY_SORT_ORDER; ?></th>					   
                   <th class="text-right" ><?php echo ACTION_INFORMATION; ?></th>	   
                </tr>
              </thead>
			  <tbody>
<?php			  
                  if (sizeof($data) > 0) {
                     while (list($key, $val) = each($data)) {	 				 
?>
                        <tr>
                           <td class="text-center"><?php echo $val['information_id']; ?></td>	
                           <td class="text-center"><?php echo $val['information_title']; ?></td>
                           <td class="text-center"><?php echo ((!empty($val['parent_id'])) ? $val['parent_id'] : null); ?></td>		
                           <td class="text-center">
                               <?php
							  if ($val['visible'] == 1) {
									echo '                    ' . tep_glyphicon('ok-sign', 'success') ; 
									echo '&nbsp;&nbsp;' . ((!strstr($info_group['locked'], 'visible')) ? '<a href="' . tep_href_link(FILENAME_INFORMATION_MANAGER, "gID=$gID&information_action=Visible&information_id=$val[information_id]&visible=0") . '">' : '')  ;
									echo '                    ' . tep_glyphicon('remove-sign', 'muted') . PHP_EOL ; 
									echo ((!strstr($info_group['locked'], 'visible')) ? '</a>' : null) . PHP_EOL;
								} else {

									echo '                    ' .  ((!strstr($info_group['locked'], 'visible')) ? '<a href="' . tep_href_link(FILENAME_INFORMATION_MANAGER, "gID=$gID&information_action=Visible&information_id=$val[information_id]&visible=1") . '">' : '') ; 
									echo                          tep_glyphicon('ok-sign', 'muted') . PHP_EOL ;
									echo                       ((!strstr($info_group['locked'], 'visible')) ? '</a>' : '') . '&nbsp;&nbsp;' . PHP_EOL ; 
									echo                           tep_glyphicon('remove-sign', 'danger') . PHP_EOL ;
								}
                                ?>

                           </td>
                           <td class="text-center"><?php echo $val['sort_order']; ?></td>
						   <td class="text-right">
                              <div class="btn-toolbar" role="toolbar">                  
<?php  
									echo '             			<div class="btn-group">' . tep_glyphicon_button(IMAGE_EDIT,            'pencil',        tep_href_link(FILENAME_INFORMATION_MANAGER, "gID=$gID&information_action=Edit&information_id=$val[information_id]", 'NONSSL'),    null, 'warning') . '</div>' . PHP_EOL  ;
									if (empty($info_group['locked'])) {			
									  echo  '         			<div class="btn-group">' . tep_glyphicon_button(IMAGE_DELETE,          'remove',        tep_href_link(FILENAME_INFORMATION_MANAGER, "gID=$gID&information_action=Delete&information_id=$val[information_id]", 'NONSSL'), null, 'danger')  . '</div>' . PHP_EOL ;
									}			  
?>
                              </div> 
				           </td>
                        </tr>
<?php
					 }
                  } else {
?>
						<div>
						  <td colspan="7"><?php echo ALERT_INFORMATION; ?></td>
						</div>
<?php						
				  }
?>
              				  
			  </tbody>
		  </table>
		</div>
	</table> 
    <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>

		  
<?php
    if (empty($info_group['locked'])) {
?>
          <hr>
          <div class="row">
             <div class="col-xs-12 col-md-7">		  
                   <?php echo tep_draw_bs_button(IMAGE_NEW_PAGE, 'plus', tep_href_link(FILENAME_INFORMATION_MANAGER, 'gID=' . $gID . '&information_action=Added')) ; ?>
 			 </div>
            </div>
<?php
          }
?>	
    </table>	