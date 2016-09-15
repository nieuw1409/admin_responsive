<?php 
 /**
 *
 * ULTIMATE Seo Urls 5
 *
 * 
 * @package Ultimate Seo Urls 5
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU Public License
 * @link http://www.fwrmedia.co.uk
 * @copyright Copyright 2008-2009 FWR Media
 * @author Robert Fisher, FWR Media, http://www.fwrmedia.co.uk 
 * @lastdev $Author:: Rob                                              $:  Author of last commit
 * @lastmod $Date:: 2011-03-17 09:11:09 +0000 (Thu, 17 Mar 2011)       $:  Date of last commit
 * @version $Rev:: 203                                                 $:  Revision of last commit
 * @Id $Id:: index.php 203 2011-03-17 09:11:09Z Rob                     $:  Full Details   
 */
  chdir( '../' );
  include_once 'includes/application_top.php';

  $oscTemplate->buildBlocks();

 echo '<!-- BOF: Javascript and CSS files -->' . PHP_EOL ; 
 echo $oscTemplate->getBlocks('javascript_css_top') . PHP_EOL; 
 echo '<!-- EOF: Javascript and CSS files  -->' . PHP_EOL;   
  
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_GOOGLE_XMLMAPS);    
  
  $stores_query = tep_db_query("select stores_std_cust_group from " . TABLE_STORES . " where stores_id='" . SYS_STORES_ID . "'");
  $stores = tep_db_fetch_array($stores_query) ;
  $sys_std_group_id = $stores[ 'stores_std_cust_group' ] ;  

  
  function usu5_xml_exists( $doc, $file ) {
    $filepath = realpath( dirname( __FILE__ ) . '/../' ) . '/' . $file;
    if ( !is_readable( $filepath ) ) {
      if ( $fp = fopen( $filepath, 'w+' ) ) {
        fclose( $fp );
      } else {
        return trigger_error( __FUNCTION__ . ' could not open ' . $file, E_USER_WARNING );
      }
    }
    $doc->save( $filepath );
  }
  
  function usu5_xml_init( &$doc, &$root, $index = false ) {
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->formatOutput = true;
    if ( false === $index ) {
      $root = $doc->createElement( "urlset" );
    } else {
      $root = $doc->createElement( "sitemapindex" );
    } 
    $root->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
    $doc->appendChild( $root );
  }
  
  function usu5_node_create( &$doc, &$root, $detail, $index = false ) {
    if ( false === $index ) {
      $parent = $doc->createElement( "url" );
    } else {
      $parent = $doc->createElement( "sitemap" );
    }
    $current = $doc->createElement( "loc" );
    $current->appendChild(
    $doc->createTextNode( $detail['url'] ) );
    $mod = $doc->createElement( "lastmod" );
    $mod->appendChild(
    $doc->createTextNode( $detail['lastmod'] ) );
    $freq = $doc->createElement( "changefreq" );
    $freq->appendChild(
    $doc->createTextNode( $detail['freq'] ));
    $priority = $doc->createElement( "priority" );
    $priority->appendChild(
    $doc->createTextNode( $detail['priority'] ));
    $parent->appendChild( $current );
    $parent->appendChild( $mod );
    if ( false === $index ) $parent->appendChild( $freq );
    if ( false === $index ) $parent->appendChild( $priority );
    $root->appendChild( $parent );
  }

  function setCpath( $categories, $id ) {
    static $entry_id, $cpatharray;
    if(!isset($entry_id) || $entry_id == NULL) $entry_id = $id;
    ( empty( $cpatharray ) ? $cpatharray = array( $id ) : NULL );
    array_push( $cpatharray, $categories[$id]['parent'] );
    if( ( isset( $categories[$categories[$id]['parent']]['parent'] ) && $categories[$categories[$id]['parent']]['parent'] != '0' ) ) {
      setCpath( $categories, $categories[$id]['parent'] );
    }
    $fwrcpath = implode( '_', array_reverse( $cpatharray ) );
    if( $id == $entry_id ) {
      $entry_id = NULL;
      $cpatharray = array();
      return $fwrcpath;
    }
  }

  function categoriesFullScan(){
    $sql = "SELECT categories_id, parent_id, date_added, last_modified, categories_hide_from_groups, categories_to_stores FROM " . TABLE_CATEGORIES . " 
	where find_in_set('" . _SYS_STORE_CUSTOMER_GROUP . "', categories_hide_from_groups) = 0 
	  and find_in_set('" . SYS_STORES_ID . "', categories_to_stores) != 0  
	  and categories_status = '1' 
	      GROUP BY categories_id ORDER BY date_added ASC, last_modified ASC"; // multi stores
    return tep_db_query($sql);
  }

  function buildCategoriesCache() {
    $result = categoriesFullScan();

    while ( $row = tep_db_fetch_array( $result ) ) {
      $categories[$row['categories_id']] = array( 'id'       => $row['categories_id'],
                                                  'parent'   => $row['parent_id'],
                                                  'path'     => '', 
                                                  'last_mod' => ( strtotime( $row['last_modified'] ) > strtotime( $row['date_added'] ) ) ? $row['last_modified'] : $row['date_added'] );
//  echo 		'id ' . $row['categories_id'] . 'parentid ' . $row['parent_id'] ;
    }
    tep_db_free_result($result); // Housekeeping

    foreach ( $categories as $cat_id => $key ) {
      if ( $key['parent'] != '0' ) {
        ( isset( $categories[$key['parent']]['children'] ) && ( $categories[$key['parent']]['children'] !== null ) ) ? null : $categories[$key['parent']]['children'] = '';
        $categories[$key['parent']]['children'] .= $key['id'] . ',';
      } else {
        $categories[$key['id']]['path'] .= $key['id'];
      }
    }

    foreach ( $categories as $cat_id => $key ) {
      $fullcatpath = '';
      if( $key['parent'] != '0' ) {
        $fullcatpath = setCpath( $categories, $key['id'] );
        $categories[$key['id']]['path'] = $fullcatpath;
      }
    }
    return $categories;
  }
  
  function create_single_sitemap_index( &$doc, &$root, $filename_suffix = '.xml' ) {
    $detail = array( 'url' => tep_href_link( 'sitemapcategories' . $filename_suffix ),
                     'lastmod' => date( "Y-m-d" ),
                     'freq' => 'weekly',
                     'priority' => '0.5' );
    usu5_node_create( $doc, $root, $detail, true );
    $detail = array( 'url' => tep_href_link( 'sitemapproducts' . $filename_suffix ),
                     'lastmod' => date( "Y-m-d" ),
                     'freq' => 'weekly',
                     'priority' => '0.5' );
    usu5_node_create( $doc, $root, $detail, true );
    $detail = array( 'url' => tep_href_link( 'sitemapmanufacturers' . $filename_suffix ),
                     'lastmod' => date( "Y-m-d" ),
                     'freq' => 'weekly',
                     'priority' => '0.5' );
    usu5_node_create( $doc, $root, $detail, true );

  }

  function usu5_create_sitemap_set( $language_directory, $code ) {

    $filename_suffix = ( $code == DEFAULT_LANGUAGE ) ? '.xml' : '_' . $language_directory . '.xml';
    /**
    * Now for the categories
    */
    $detail = array();
    usu5_xml_init( $doc, $root);
    
    $categories = buildCategoriesCache();
    foreach ( $categories as $cid => $detail ) {
      if( preg_match( '@[0-9_]@', $detail['path'] ) ) {
        $detail = array( 'url' => tep_href_link( FILENAME_DEFAULT, 'cPath=' . $detail['path'], 'NONSSL', false ),
                         'lastmod' => date( "Y-m-d", strtotime($detail['last_mod'] ) ),
                         'freq' => 'weekly',
                         'priority' => '0.5' );
        usu5_node_create( $doc, $root, $detail );
      }
    }
    usu5_xml_exists( $doc, 'sitemapcategories' . $filename_suffix );
    // End categories xml
    
    /**
    * Now the products
    */
    $detail = array();
    usu5_xml_init( $doc, $root);
    $query = "SELECT p.products_id, p.products_date_added, p.products_last_modified FROM " . TABLE_PRODUCTS_DESCRIPTION . " pd INNER JOIN " . TABLE_PRODUCTS . " p ON p.products_id = pd.products_id 
	                   WHERE p.products_status = '1' 
					    and find_in_set('" . SYS_STORES_ID . "', products_to_stores) != 0  
						and find_in_set('" . SYS_STORES_ID . "', products_hide_from_groups) = 0  
						    ORDER BY p.products_last_modified DESC, p.products_date_added DESC"; // multi stores
    $result = tep_db_query( $query );
    $count = 1;
    while ( $row = tep_db_fetch_array( $result ) ) {
      $detail = array( 'url' => tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . (int)$row['products_id'], 'NONSSL', false ),
                       'lastmod' => ( strtotime( $row['products_last_modified'] ) > strtotime( $row['products_date_added'] ) ) ?  date( "Y-m-d", strtotime( $row['products_last_modified'] ) ) : date( "Y-m-d", strtotime( $row['products_date_added'] ) ),
                       'freq' => 'weekly',
                       'priority' => '0.5' );
      usu5_node_create( $doc, $root, $detail );
    }
    tep_db_free_result( $result );
    usu5_xml_exists( $doc, 'sitemapproducts' . $filename_suffix );
    
    /**
    * Manufacturers_sitemap
    */
    $detail = array();
    usu5_xml_init( $doc, $root);
    $query = "SELECT m.manufacturers_id, m.date_added, m.last_modified FROM " . TABLE_MANUFACTURERS . " m 
						    where find_in_set('" . SYS_STORES_ID . "', manufacturers_to_stores) != 0  
							ORDER BY m.last_modified DESC, m.date_added DESC"; // multi stores
    $result = tep_db_query( $query );
    $count = 1;
    while ( $row = tep_db_fetch_array( $result ) ) {
      $detail = array( 'url' => tep_href_link( FILENAME_DEFAULT, 'manufacturers_id=' . (int)$row['manufacturers_id'], 'NONSSL', false ),
                       'lastmod' => ( strtotime( $row['last_modified'] ) > strtotime( $row['date_added'] ) ) ?  date( "Y-m-d", strtotime( $row['last_modified'] ) ) : date( "Y-m-d", strtotime( $row['date_added'] ) ),
                       'freq' => 'weekly',
                       'priority' => '0.5' );
      usu5_node_create( $doc, $root, $detail );
    }
    tep_db_free_result( $result );
    usu5_xml_exists( $doc, 'sitemapmanufacturers' . $filename_suffix );
  
  } // end function
  
  if ( defined( 'USU5_MULTI_LANGUAGE_SEO_SUPPORT' ) && USU5_MULTI_LANGUAGE_SEO_SUPPORT == 'true' ) {
    $languages_query = tep_db_query( "select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " 
   	          where find_in_set('" . SYS_STORES_ID . "', languages_to_stores) != 0  order by sort_order" ); // multi stores
    $current_language = '';
    $languages_array = array();
    while ( $languages = tep_db_fetch_array( $languages_query ) ) {
      $languages_id = $languages['languages_id'];
      $language = $languages['directory'];
      Usu_Main::i()->initiate( array(), $languages_id, $language, true );
      usu5_create_sitemap_set( $languages['directory'], $languages['code'] );
      $languages_array[] = $languages;
    }
    usu5_xml_init( $doc, $root, true );
    foreach ( $languages_array as $index => $language_data ) {
      $filename_suffix = ( $language_data['code'] == DEFAULT_LANGUAGE ) ? '.xml' : '_' . $language_data['directory'] . '.xml';
      create_single_sitemap_index( $doc, $root, $filename_suffix );
    }
     usu5_xml_exists( $doc, 'sitemapindex.xml' );
  } else {
    usu5_xml_init( $doc, $root, true );
    create_single_sitemap_index( $doc, $root );
    usu5_xml_exists( $doc, 'sitemapindex.xml' );
    usu5_create_sitemap_set( $language, DEFAULT_LANGUAGE );
  }
  
  echo '<div class="panel panel-primary">'. PHP_EOL ;
  echo '  <div class="panel-heading"> </div>'. PHP_EOL ;  
  echo '  <div class="panel-body">'. PHP_EOL ;   

  echo '			     <ul class="list-group">'. PHP_EOL ;
  echo '			       <li class="list-group-item">'. PHP_EOL ;                         					   
  echo '					   <p>' . GOOGLE_SITEMAPS_HERE_INDEX . $sys_std_group_id . '</p>'. PHP_EOL ;
  echo '                   </li>'. PHP_EOL ;	   
  echo '			       <li class="list-group-item">'. PHP_EOL ;
  echo '					  <p>' . GOOGLE_SITEMAPS_HERE_INDEX . SYS_STORES_ID . '</p>'. PHP_EOL ; 
  echo '                   </li>'. PHP_EOL ;
  echo '			       <li class="list-group-item">'. PHP_EOL ;
  echo '					  <p>' . GOOGLE_SITEMAPS_HERE_INDEX . $google->base_url . 'sitemapindex.xml' . '</p>'. PHP_EOL ;
  echo '                   </li>'. PHP_EOL ;
  echo '			       <li class="list-group-item">'. PHP_EOL ;
  echo '					  <p>' . GOOGLE_SITEMAPS_HERE_PRODUCT . $google->base_url . 'sitemapproducts.xml' . '</p>'. PHP_EOL ;
  echo '                   </li>'. PHP_EOL ;
    echo '			       <li class="list-group-item">'. PHP_EOL ;
  echo '					  <p>' . GOOGLE_SITEMAPS_HERE_CATEGORY . $google->base_url . 'sitemapcategories.xml' . '</p>'. PHP_EOL ;
  echo '                   </li>'. PHP_EOL ;
  echo '                 </ul>'. PHP_EOL ;
/*  
  echo '    <p>' . GOOGLE_SITEMAPS_HERE_INDEX . $sys_std_group_id . '<br />';
  echo             GOOGLE_SITEMAPS_HERE_INDEX . SYS_STORES_ID . '<br />';  
  
  echo             GOOGLE_SITEMAPS_HERE_INDEX . $google->base_url . 'sitemapindex.xml' . '<br />';  

  echo             GOOGLE_SITEMAPS_HERE_PRODUCT . $google->base_url . 'sitemapproducts.xml' . '<br />';

  echo             GOOGLE_SITEMAPS_HERE_CATEGORY . $google->base_url . 'sitemapcategories.xml' . '<br /><br />';  
*/
  echo             tep_draw_button( IMAGE_BUTTON_BACK, 'chevron-left',  DIR_WS_ADMIN . $HTTP_GET_VARS['return'] . '?osCAdminID=' . $HTTP_GET_VARS['admin_id'] , null, null, 'btn-primary ')  ;

  echo '  </div>'. PHP_EOL ;
  echo '</div>'. PHP_EOL ;    
  
   echo '<!-- BOF: Javascript and CSS files -->' . PHP_EOL ; 
 echo $oscTemplate->getBlocks('javascript_css_bottom') . PHP_EOL; 
 echo '<!-- EOF: Javascript and CSS files  -->' . PHP_EOL;  
  
  include_once DIR_WS_INCLUDES . 'application_bottom.php';
?>