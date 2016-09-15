<?php
/*
  $Id: FAQDesk 2.3

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FAQ);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FAQ));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<script language="JavaScript">
    function showItem(id){
         var item = document.getElementById(id);
         if (item){
               if (!item.style.display || item.style.display == '' )
                   item.style.display = 'none';
               else
                   item.style.display = '';
         }
   }
</script>

<div class="page-header">
  <h1>
     <?php echo HEADING_TITLE; ?>
  </h1>
</div>

<div class="page-header">
  <h4>
    <?php echo TEXT_CLICK_REVEAL; ?>
  </h4>
</div>  

<div class="contentContainer">
  <div class="contentText">
  
    <div class="panel-group" id="accordion">

<?php
      $faq_query = tep_db_query('select f.faq_id, fd.faq_question, fd.faq_answer, f.last_modified, f.sort_order, f.faq_status from '.TABLE_FAQ.' f,  '.TABLE_FAQ_DESCRIPTION.' fd where f.faq_id=fd.faq_id and f.faq_status and fd.language_id=' . (int)$languages_id . ' order by f.sort_order, fd.faq_question');
      $count = 1;
      while($faq = tep_db_fetch_array($faq_query)){
?>
         <div class="panel panel-default panel-info">
           <div class="panel-heading">
             <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#faq_answer_<?php echo $faq['faq_id'];?>">
                  <?php echo $faq['faq_question'];?>
                </a>
             </h4>
            </div>
            
			<div id="faq_answer_<?php echo $faq['faq_id'];?>" class="panel-collapse collapse">
              <div class="panel-body">
                 <?php echo $faq['faq_answer'];?>     
              </div>
            </div>
           </div>
<?php
        $count++;
      }
?>   

    </div> 

  </div>
  
  <div class="buttonSet">
       <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'chevron-right', tep_href_link(FILENAME_DEFAULT)); ?>
  </div>

</div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>