<?php
/*  $Id: stats_keywords.php, Aaron Hiatt, 6-3-05  Scared Rabbit Productions  http://www.scaredrabbit.com  osCommerce, Open Source E-Commerce Solutions  http://www.oscommerce.com  Copyright (c) 2003 osCommerce  Released under the GNU General Public License*/
require('includes/application_top.php');

$page = $HTTP_GET_VARS['page'] ; 

// bof delete function
if (isset($HTTP_POST_VARS["update_keyword_list"])) {
    $update_keyword_list = $HTTP_POST_VARS["update_keyword_list"];
    for ($i=0; $i<count($update_keyword_list); $i++) {
tep_db_query("delete from " . TABLE_SEARCH_QUERIES . " where search_text = '" . $update_keyword_list[$i] . "' and stores_id = '" . $multi_stores_id . "'");
echo '<font size="2" color="#FF0000">DELETED: '.$update_keyword_list[$i].'&nbsp;&nbsp;</font>';
    }
}
// eof delete function


  if (isset($HTTP_GET_VARS['viewedSortOrder']))
  {
    if ( ! tep_session_is_registered('viewedSortOrder') ) tep_session_register('viewedSortOrder');
    $viewedSortOrder = $HTTP_GET_VARS['viewedSortOrder'];
  }

  switch ($viewedSortOrder)
 {
      case "name-asc":
          $sortOrderViewed = "search_text ASC, search_count DESC";
          break;
      case "name-desc":
          $sortOrderViewed = "search_text DESC, search_count DESC";
          break;
      case "viewed-asc":
          $sortOrderViewed = "search_count ASC, search_text  ASC";
          break;
      case "viewed-desc":
          $sortOrderViewed = "search_count DESC, search_text  ASC";
          break;
      default:
          $sortOrderViewed = "search_text ASC, search_count DESC";
  }
  require(DIR_WS_INCLUDES . 'template_top.php');
  
  echo tep_draw_bs_form('delete_search', FILENAME_STATS_PRODUCTS_KEYWORDS, 'page=' . $HTTP_GET_VARS['page'] , 'post', 'class="form-horizontal" role="form"', 'id_delete_search_terms') . PHP_EOL;  
?>

   <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <div class="page-header">
            <h1 class="col-xs-12 col-md-6"><?php echo HEADING_TITLE  ; ?></h1> 
            <div class="clearfix"></div>
          </div><!-- page-header-->
          <div class="table-responsive">
            <table class="table table-condensed table-striped">
              <thead>
                <tr class="heading-row">
                   <th class="text-center"> <?php echo TABLE_HEADING_DELETE; ?></th> 				
                   <th class="text-left"  > <?php echo '<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_KEYWORDS, 'viewedSortOrder=name-asc&page=' . $page, 'NONSSL') . '">' . tep_glyphicon('sort-by-alphabet' ) . '</a>'; ?>&nbsp;
				                            <?php echo TABLE_HEADING_KEYWORD; ?>&nbsp;
											<?php echo '<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_KEYWORDS, 'viewedSortOrder=name-desc&page=' . $page, 'NONSSL') . '">' . tep_glyphicon('sort-by-alphabet-alt' ) .  '</a>'; ?></td>
											 
                   <th class="text-center"> <?php echo '<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_KEYWORDS, 'viewedSortOrder=viewed-desc&page=' . $page, 'NONSSL') . '">' . tep_glyphicon('sort-numeric-desc' ) . '</a>'; ?>&nbsp;
				                            <?php echo TABLE_HEADING_SEARCH_TOTAL; ?>&nbsp;
											<?php echo '<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_KEYWORDS, 'viewedSortOrder=viewed-asc&page=' . $page, 'NONSSL') . '">' . tep_glyphicon('sort-numeric-asc ' ) . '</a>'; ?></td>
 				
				   
                   <th class="text-center"><?php echo TABLE_HEADING_SEARCH_DATE; ?></th> 	   
                </tr>
              </thead>
              <tbody>
<?php
                 $keywords_query_raw = "select * from " . TABLE_SEARCH_QUERIES . " where stores_id = '" . $multi_stores_id . "' order by " . $sortOrderViewed  ;  
				 $keywords_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $keywords_query_raw, $keywords_query_numrows);  
				 $keywords_query = tep_db_query($keywords_query_raw);  
				 while ($keywords = tep_db_fetch_array($keywords_query)) {    
?>				 
                    <tr>  
					   <td class="text-center">
 	                      <div class="form-group">
 	                          <div class="checkbox checkbox-success">
			                      <?php echo tep_bs_checkbox_field('update_keyword_list[]', $keywords['search_text'], null, 'id'.$keywords['search_text'], false, 'checkbox checkbox-success') ; ?>
                              </div>
                          </div>
					   </td>
                       <td class="text-left">    <?php echo $keywords['search_text']; ?></td>
                       <td class="text-center">  <?php echo $keywords['search_count']; ?></td>
                       <td class="text-center">  <?php echo $keywords['search_date']; ?></td>
                    </tr>				 
<?php				 
				 }
?>			  

			  </tbody>
			</table>
		  </div>
   </table>
   <?php echo tep_draw_bs_button(IMAGE_DELETE, 'remove', null) ; ?>
  </form>
   <table class="table" style="width: 100%"> <!-- osCommerce table foot -->
	     <hr>
         <div class="row">
               <div class="mark text-left  col-xs-12 col-md-6"><?php echo $keywords_split->display_count($keywords_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_KEYWORDS); ?></div>			   
               <div class="mark text-right col-xs-12 col-md-6"><?php echo $keywords_split->display_links($keywords_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></div>		   
	     </div> 
    </table>  
<!-- bof Keyword List -->
<?php
$keywords_list= '';
$keywords_list_query = tep_db_query("select search_text from " . TABLE_SEARCH_QUERIES . " where stores_id = '" .  $multi_stores_id . "' order by " . $sortOrderViewed  ) ;  
while ($keywords = tep_db_fetch_array($keywords_list_query)) {
$rows++;
$keywords_list .= $keywords['search_text']. PHP_EOL ;
}
?>
<div>
<br><?php echo HEADING_KEYWORDS_LIST; ?><br>
<textarea cols="35" rows="8" style="width: 95%; border: 1px solid #ccd"><?php echo $keywords_list; ?></textarea>
</div>
<!-- eof Keyword List -->

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>