<?php
/*
  $Id: cm_sc_discount_code.php
  $Loc: catalog/includes/modules/content/shopping_cart/

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/


  class cm_sc_discount_code {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_sc_discount_code() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_SC_DISCOUNT_CODE_TITLE;
      $this->description = MODULE_CONTENT_SC_DISCOUNT_CODE_DESCRIPTION;
      $this->description .= '<div class="secWarning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';

      if ( defined('MODULE_CONTENT_SC_DISCOUNT_CODE_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_SC_DISCOUNT_CODE_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_SC_DISCOUNT_CODE_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $cart, $sess_discount_code;

      $content_width = (int)MODULE_CONTENT_SC_DISCOUNT_CODE_CONTENT_WIDTH;

      ?>

      <script>
      	function discount_submit(sid){
      		if(sid){
      			document.discount.sid.value=sid;
      		}
      		document.discount.submit();
      		return false;
      	}
			  
      	$(document).ready(function() {
      			var a = 0;
      			discount_code_process();
      			$('#discount_code').blur(function() { if (a == 0) discount_code_process(); a = 0 });
      			$("#discount_code").keypress(function(event) { if (event.which == 13) { event.preventDefault(); a = 1; discount_code_process() } });
		        function discount_code_process() { if ($("#discount_code").val() != "") { $("#discount_code").attr("readonly", "readonly"); $("#discount_code_status").empty().append('<i class="fa fa-cog fa-spin fa-lg">&nbsp;</i>'); $.post("discount_code.php", { discount_code: $("#discount_code").val() }, function(data) { data == 1 ? $("#discount_code_status").empty().append('<i class="fa fa-check fa-lg" style="color:#00b100;"></i>') : $("#discount_code_status").empty().append('<i class="fa fa-ban fa-lg" style="color:#ff2800;"></i>'); $("#discount_code").removeAttr("readonly") }); } }      	});
      </script>
      
      <?php
      if (($cart->count_contents() > 0)) {

        $discount_code = NULL;
        $discount_code .= '<div class="panel panel-default">';
        $discount_code .= '	<div class="panel-heading">';
        $discount_code .= '		<h3 class="panel-title">' . MODULE_CONTENT_SC_DISCOUNT_HEADER_TITLE . '</h3>';
        $discount_code .= '	</div>';
        $discount_code .= '	<div class="panel-body">';
		
 		$discount_code .= tep_draw_form('discount', tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'), 'post');
		   
		    	if ($sess_discount_code == 'undefined') $sess_discount_code = '';
		    	$discounttxt = '<div class="row">';
          	if (tep_session_is_registered('customer_id')) {
          		$discounttxt .= '<div class="col-xs-8">
									<div class="form-group has-feedback">' . tep_draw_input_field('discount_code', $sess_discount_code, 'id="discount_code"') . '<span class="form-control-feedback" id="discount_code_status"></span></div>
								</div>	
              	      		     <div class="col-xs-4">
									<a class="btn btn-default" role="button" href="_" onclick="return discount_submit(\'\');">'. IMAGE_BUTTON_APPLY . '</a>
								 </div>';
            } else {		       
            	$discounttxt .= '<div class="col-sm-12"><p>' . MODULE_CONTENT_SC_DISCOUNT_TEXT_LOG_IN . '</p></div>
              	      			 <div class="col-sm-12">' . tep_draw_button(IMAGE_BUTTON_LOG_IN, 'log-in', tep_href_link('login.php'), 'btn-success btn-sm') . '</div>';
            }
            $discounttxt .= '</div>'; // eof row
			
            $discount_code .= $discounttxt;
            
			$discount_code .= '</form>';
			
        $discount_code .= ' </div>'; // eof panel-body
        $discount_code .= '</div>'; // eof panel panel-default
       
      ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/discount_code.php');
        $template = ob_get_clean();

        $oscTemplate->addContent($template, $this->group);
     }
    
	} // eof execute

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_SC_DISCOUNT_CODE_STATUS');
    }

    function install() {
	  global $multi_stores_config;			
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Shopping Cart Discount Code Module', 'MODULE_CONTENT_SC_DISCOUNT_CODE_STATUS', 'True', 'Do you want to add the module to your shopping cart?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_SC_DISCOUNT_CODE_CONTENT_WIDTH', '4', 'What width container should the content be shown in?', '6', '2', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config  . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_DISCOUNT_CODE_SORT_ORDER', '600', 'Sort order of display. Lowest is displayed first.', '6', '3', now())");
   }

    function remove() {
	  global $multi_stores_config;			
      tep_db_query("delete from " . $multi_stores_config  . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_SC_DISCOUNT_CODE_STATUS', 'MODULE_CONTENT_SC_DISCOUNT_CODE_CONTENT_WIDTH', 'MODULE_CONTENT_SC_DISCOUNT_CODE_SORT_ORDER');
    }
  }
?>