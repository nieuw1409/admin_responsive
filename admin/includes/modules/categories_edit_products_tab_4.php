<?php
				
      $contents_edit_prod_tab4 .= '                <div class="col-xs-9">' . PHP_EOL;	
      $contents_edit_prod_tab4 .= '                   <br />' . PHP_EOL;

      $contents_edit_prod_tab4 .= '                     <div class="well well-small">' . PHP_EOL;
                
      $contents_edit_prod_tab4 .=                           TEXT_PRODUCTS_IMAGE ;
            
      $contents_edit_prod_tab4 .= '                         <div><strong>' . TEXT_PRODUCTS_MAIN_IMAGE . ' <small>(' . SMALL_IMAGE_WIDTH . ' x ' . SMALL_IMAGE_HEIGHT . 'px)</small></strong><br />' . 
	                                                                  (tep_not_null($pInfo->products_image) ? tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') : '') . 
																	   tep_draw_file_field('products_image') .  '</div>' . PHP_EOL;

      $contents_edit_prod_tab4 .= '                               <ul id="piList">' . PHP_EOL;

      $pi_counter = 0;

      foreach ($pInfo->products_larger_images as $pi) {
        $pi_counter++;

       $contents_edit_prod_tab4 .= '                <li id="piId' . $pi_counter . '"><hr><span class="ui-icon ui-icon-arrowthick-2-n-s" style="float: right;"></span><a href="#" onclick="showPiDelConfirm(' . $pi_counter . ');return false;" class="ui-icon ui-icon-trash" style="float: right;"></a>' .
	                                                        '<strong>' . TEXT_PRODUCTS_LARGE_IMAGE . '</strong><br />' . 
															tep_draw_file_field('products_image_large_' . $pi['id']) . 
															tep_image(DIR_WS_CATALOG_IMAGES . $pi['image'], $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . 
															'<br /><a href="' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $pi['image'] . '" target="_blank">' . $pi['image'] . '</a><br />' .
															'<div><label>' . TEXT_PRODUCTS_LARGE_IMAGE_HTML_CONTENT . '</label></div>' . tep_draw_textarea_field('products_image_htmlcontent_' . $pi['id'], 'soft', '70', '3', $pi['htmlcontent']) . '</li>';
      }

      $contents_edit_prod_tab4 .= '                               </ul>' . PHP_EOL ;
	  
      $contents_edit_prod_tab4 .= '                               <br /><br />' . PHP_EOL ;	  

      $contents_edit_prod_tab4 .= '                              <a href="#" onclick="addNewPiForm();return false;"><span class="ui-icon ui-icon-plus" style="float: left;"></span>' . TEXT_PRODUCTS_ADD_LARGE_IMAGE . '</a>' . PHP_EOL ;

      $contents_edit_prod_tab4 .= '                              <div id="piDelConfirm" title="' . TEXT_PRODUCTS_LARGE_IMAGE_DELETE_TITLE. '">' . PHP_EOL ;
      $contents_edit_prod_tab4 .= '                                   <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'. TEXT_PRODUCTS_LARGE_IMAGE_CONFIRM_DELETE . '</p>' . PHP_EOL ;
      $contents_edit_prod_tab4 .= '                             </div>' . PHP_EOL ;
	  
?>	  

<style type="text/css">
#piList { list-style-type: none; margin: 0; padding: 0; }
#piList li { margin: 5px 0; padding: 2px; }
</style>

<script type="text/javascript">
$('#piList').sortable({
  containment: 'parent'
});

var piSize = <?php echo $pi_counter; ?>;

function addNewPiForm() {
  piSize++;

  $('#piList').append('<li id="piId' + piSize + '" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s" style="float: right;"></span><a href="#" onclick="showPiDelConfirm(' + piSize + ');return false;" class="ui-icon ui-icon-trash" style="float: right;"></a><strong><?php echo TEXT_PRODUCTS_LARGE_IMAGE; ?></strong><br /><input type="file" name="products_image_large_new_' + piSize + '" /><br /><br /><?php echo TEXT_PRODUCTS_LARGE_IMAGE_HTML_CONTENT; ?><br /><textarea name="products_image_htmlcontent_new_' + piSize + '" wrap="soft" cols="70" rows="3"></textarea></li>');
}

var piDelConfirmId = 0;

$('#piDelConfirm').dialog({
  autoOpen: false,
  resizable: false,
  draggable: false,
  modal: true,
  buttons: {
    'Delete': function() {
      $('#piId' + piDelConfirmId).effect('blind').remove();
      $(this).dialog('close');
    },
    Cancel: function() {
      $(this).dialog('close');
    }
  }
});

function showPiDelConfirm(piId) {
  piDelConfirmId = piId;

  $('#piDelConfirm').dialog('open');
}
</script>
<?php       
      $contents_edit_prod_tab4 .= '                    </div>' . PHP_EOL ;
 	  
	  
	  $contents_edit_prod_tab4 .= '                </div>' . PHP_EOL;  // end div images
	  

?>