<?php
/*
  $Id: bm_newsletters.php 25 May 2015

  Based on http://addons.oscommerce.com/info/8472

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

  class bm_newsletters {
    var $code = 'bm_newsletters';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
	var $pages;		

    function bm_newsletters() {
      $this->title = MODULE_BOXES_NEWSLETTER_TITLE;
      $this->description = MODULE_BOXES_NEWSLETTER_DESCRIPTION;

      if ( defined('MODULE_BOXES_NEWSLETTER_STATUS') ) {
        $this->sort_order = MODULE_BOXES_NEWSLETTER_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_NEWSLETTER_STATUS == 'True');

//        $this->group = ((MODULE_BOXES_NEWSLETTER_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
		$this->pages = MODULE_BOXES_NEWSLETTER_DISPLAY_PAGES;			

//        $this->group = ((MODULE_BOXES_WISHLIST_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      $placement = MODULE_BOXES_NEWSLETTER_CONTENT_PLACEMENT;
       switch($placement) {
			case "Left Column" :
				$this->group = 'boxes_column_left';
				break;
			case "Right Column" :
				$this->group = 'boxes_column_right';
				break;
			case "Bread Column" :
				$this->group = 'boxes_column_bread';
				break;
			case "Head Column" :
				$this->group = 'boxes_column_head';
				break;
			case "Foot Column" :
				$this->group = 'boxes_column_foot';
				break;	
            case 'Left Header' : 
			    $this->group = 'header_contents_left';
                break;
            case 'Center Header' : 
			    $this->group = 'header_contents_center';
                break;
            case 'Right Header' : 
			    $this->group = 'header_contents_right';
                break;
            case 'Header Line' :  
			    $this->group = 'header_line';
                break;
            case 'Left Footer' : 
			    $this->group = 'footer_contents_left';
                break;
            case 'Center Footer' : 
			    $this->group = 'footer_contents_center';
                break;
            case 'Right Footer' : 
			    $this->group = 'footer_contents_right';
                break;
            case 'Footer Line' : 
			    $this->group = 'footer_line';
                break;					
		}

      }
    }

    function execute() {
      global $oscTemplate;

$VerifierMailBoxe = '<script type="text/javascript">
   function VerifierMailBoxe(form) {
       if (form.emailsubscription.value == "" ) {
           alert("' . BOX_NEWSLETTER_ERROR_EMPTY_FIELD . '")
           form.emailsubscription.focus();
           return false;
          }

       else if (form.emailsubscription.value.indexOf(",") > 0) {
           alert("' . BOX_NEWSLETTER_ERROR_COMMA . '")
           form.emailsubscription.focus();
           return false;
          }

       else if (form.emailsubscription.value.indexOf(" ") > 0) {
           alert("' . BOX_NEWSLETTER_ERROR_SPACES . '")
            form.emailsubscription.focus();
           return false;
           }

       else if (form.emailsubscription.value.indexOf("@") < 0) {
           alert("' . BOX_NEWSLETTER_ERROR_SIGN . '")
           form.emailsubscription.focus();
           return false;
          }

       else if (form.emailsubscription.value.lastIndexOf(".") < 0) {
           alert("' . BOX_NEWSLETTER_ERROR . '")
           form.emailsubscription.focus();
           return false;
          }

       else if ((form.emailsubscription.value.length - 1) - form.emailsubscription.value.lastIndexOf(".") < 2) {
           alert("' . BOX_NEWSLETTER_ERROR . '")
           form.emailsubscription.focus();
           return false;
          }

       else {
          // form.submit()
           return true;
          }
   }
</script>';

      $data = $VerifierMailBoxe . '<div class="panel panel-primary">
	                                  <div class="panel-heading">' . MODULE_BOXES_NEWSLETTER_BOX_TITLE . '</div>
									  <div class="panel-body text-center">' . 
									         tep_draw_form('newslettersubscription', tep_href_link(FILENAME_NEWSLETTER_SUBSCRIPTION, '', 'NONSSL'), 'post', 'onSubmit="return VerifierMailBoxe(this);"') . 
//											     tep_draw_input_field('emailsubscription', '', 'placeholder="' . BOX_NEWSLETTER_TEXT_EMAIL .'" size="15" maxlength="50"') . 
                                                 tep_bootstrap_form_input_field('emailsubscription',  '', 'id_input_email_subscriber',  BOX_NEWSLETTER_TEXT_EMAIL ) .      
                                                 tep_bootstrap_form_input_field('namesubscription',  '', 'id_input_name_subscriber',  BOX_NEWSLETTER_TEXT_NAME ) . 												 
												 tep_draw_button(IMAGE_BUTTON_NEWSLETTER_SUBSCRIPTION, 'plus', null, 'primary') . 
											'</form>
									  </div>
								  </div>';
          $oscTemplate->addBlock($data, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_NEWSLETTER_STATUS');
    }

    function install() {
	  global $multi_stores_config;		
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Newsletters Module', 'MODULE_BOXES_NEWSLETTER_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_NEWSLETTER_CONTENT_PLACEMENT', 'Left Column', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\', \'Bread Column\', \'Head Column\', \'Foot Column\', \'Left Header\', \'Center Header\', \'Right Header\', \'Header Line\', \'Left Footer\', \'Center Footer\', \'Right Footer\', \'Footer Line\'),', now())");  
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_NEWSLETTER_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display in pages.', 'MODULE_BOXES_NEWSLETTER_DISPLAY_PAGES', 'all', 'select pages where this box should be displayed. ', '6', '0','tep_cfg_select_pages(' , now())");	   	  
	  
    }

    function remove() {
	  global $multi_stores_config;			
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_NEWSLETTER_STATUS', 'MODULE_BOXES_NEWSLETTER_CONTENT_PLACEMENT', 'MODULE_BOXES_NEWSLETTER_SORT_ORDER', 'MODULE_BOXES_NEWSLETTER_DISPLAY_PAGES' );
    }
  }
?>
