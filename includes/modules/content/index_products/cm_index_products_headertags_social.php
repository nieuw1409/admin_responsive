<?php
/*  $Id$
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2014 osCommerce
  Released under the GNU General Public License
*/

  class cm_index_products_headertags_social {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function cm_index_products_headertags_social() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_TITLE;
      $this->description = MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_DESCRIPTION;

      if ( defined('MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_STATUS') ) {
        $this->sort_order = MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate, $customer_id, $category;
      
      $content_width = (int)MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_CONTENT_WIDTH;
      
		require_once('includes/functions/header_tags.php');

		$TITLE = '';
		$NAME = '';
		$URL = '';
		$IMG = '';

		// works with seo url 5
		$parts = explode('/', $_SERVER["SCRIPT_NAME"]);
		$file = $parts[count($parts) - 1];
			 
		switch (true) {
			case ($file === FILENAME_PRODUCT_INFO):
				$NAME = htmlspecialchars($product_info['products_name'], ENT_QUOTES);
				$TITLE = urlencode($product_info['products_name']);
				$URL = urlencode(StripSID(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id'], 'NONSSL', false )));
				$IMG = (tep_not_null($product_info['products_image']) ? "&amp;media=" . HTTP_SERVER . DIR_WS_HTTP_CATALOG. DIR_WS_IMAGES . $product_info['products_image'] : '');
			break;

			case (! tep_not_null($TITLE) && isset($_GET['cPath'])):
				$parts = explode("_", $_GET['cPath']);
				$category_id = $parts[count($parts) - 1];
				$category_query = tep_db_query("select cd.categories_name, c.categories_image from " . TABLE_CATEGORIES . " c left join " .  TABLE_CATEGORIES_DESCRIPTION . " cd on c.categories_id = cd.categories_id where c.categories_id = '" . (int)$category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
				$category = tep_db_fetch_array($category_query);
				$NAME = htmlspecialchars($category['categories_name'], ENT_QUOTES);
				$TITLE = urlencode($category['categories_name']);
				$URL = urlencode(StripSID(tep_href_link(FILENAME_DEFAULT, 'cPath=' . $category_id , 'NONSSL', false )));
				$IMG = (tep_not_null($category['categories_image']) ? "&amp;media=" . HTTP_SERVER . DIR_WS_HTTP_CATALOG. DIR_WS_IMAGES . $category['categories_image'] : '');
			break;

			case (defined('FILENAME_ARTICLE_INFO') && $file === FILENAME_ARTICLE_INFO):
				$NAME = htmlspecialchars($article_info['articles_name'], ENT_QUOTES);
				$TITLE = urlencode($article_info['articles_name']);
				$URL = urlencode(StripSID(tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $article_info['articles_id'], 'NONSSL', false )));
			break;  

			case (defined('FILENAME_INFORMATION') && $file === FILENAME_INFORMATION):
				$NAME = htmlspecialchars($title, ENT_QUOTES);
				$TITLE = urlencode($title);
				$URL = urlencode(StripSID(tep_href_link(FILENAME_INFORMATION, 'info_id=' . (int)$_GET['info_id'], 'NONSSL', false )));
			break;     

			case (defined('FILENAME_PAGES') && $file === FILENAME_PAGES):
				$NAME = htmlspecialchars($header_tags_array['title'], ENT_QUOTES);
				$TITLE = urlencode($header_tags_array['title']);
				$URL = urlencode(StripSID(tep_href_link(FILENAME_PAGES, 'pages=' . tep_db_prepare_input($_GET['page']), 'NONSSL', false )));
			break;       

			default: 
				$URL = urlencode(StripSID(tep_href_link($file)));
		}

		$db_query = tep_db_query("select groupname, url, data from " . TABLE_HEADERTAGS_SOCIAL . " where section='socialicons' order by groupname desc");

		$dataStr = '<div class="hts_bookmarks">';
		$dataStrBox = '';

		$ctr = 0;
		$width = '';
		$height = '';


		while($db = tep_db_fetch_array($db_query)) {
			if ($ctr == 0) {
				list ($width, $height) = explode('x',$db['data']);
			}

			$name = ucfirst($db['groupname']);
			$url = str_replace('&', '&amp', $db['url']);
			$url = str_replace('URL', $URL, $url);
			$url = str_replace('TITLE', $TITLE, $url);
			$url = '<a rel="nofollow" target="_blank" href="' . $url . '&title=' . $TITLE . '"><img style="vertical-align:middle;display:inline;border-style:none" title="' . ucfirst($db['groupname']) . '" alt="' . ucfirst($db['groupname']) . '" src="' . DIR_WS_IMAGES . 'socialbookmark/' . $db['groupname'] .'-'.$db['data'] . '.png' . '" width="'.$width.'" height="'.$height.'" /></a>';
			
			$dataStrBox .= '<span style="padding:1px;">' . $url .'</span>';

			$dataStr .= '<div style="float:right;">';
			$dataStr .= '<div style="padding-right:1px;">' .$url . '</div>';
			$dataStr .= '</div>';

			$ctr++;
		}

		$padding = $height / 2; //align boxes

		$dataStr .=  '</div>';
		
        $oscTemplate->addBlock('<script type="text/javascript" src="https://apis.google.com/js/plusone.js" asynch defer></script>', 'header_tags');	    
      
      ob_start();
        include(DIR_WS_MODULES . 'content/' . $this->group . '/templates/products_headertags_social.php');
      $template = ob_get_clean();

      $oscTemplate->addContent($template, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_STATUS');
    }

    function install() {
	  global $multi_stores_config;			
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Index Products Header Tags Social Bookmark Module', 'MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_STATUS', 'True', 'Do you want to enable the Index Products Header Tags Social Bookmark  content module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Width', 'MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_CONTENT_WIDTH', '6', 'What width container should the content be shown in? (12 = full width, 6 = half width).', '6', '1', 'tep_cfg_select_option(array(\'12\', \'11\', \'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\'), ', now())");
      tep_db_query("insert into " .  $multi_stores_config   . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
	  global $multi_stores_config;			
      tep_db_query("delete from " .  $multi_stores_config   . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_STATUS', 'MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_CONTENT_WIDTH', 'MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_SORT_ORDER');
    }
   function enable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'True' where configuration_key = 'MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_STATUS == 'True');
            

    }
    function disable() {
	  global $multi_stores_config;			
            tep_db_query("update " . $multi_stores_config  . " set configuration_value = 'False' where configuration_key = 'MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_STATUS'");
            $this->enabled = (MODULE_CONTENT_INDEX_PRODUCTS_HT_SOCIAL_STATUS == 'False');

    }			
  }
?>