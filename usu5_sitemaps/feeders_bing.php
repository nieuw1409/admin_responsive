<?php
//  Title: Bing Data Feeder
//  Version: 3.3 by Jack York (aka Jack_mcs) - www.oscommerce-solution.com

chdir('../');
include_once 'includes/application_top.php';
$use_mysqli = false; 
if (function_exists('tep_get_version')) { 
   $ver = tep_get_version();
   if (isset($ver[4]) && $ver[4] > 1) { //only versions after 2.3.1 use mysqli
      $use_mysqli = true;
   }
} 
$myfetch_mysql = ($use_mysqli ? mysqli_fetch_object : mysql_fetch_object);
/*************** BEGIN MASTER SETTINGS ******************/

define('SEO_ENABLED','true');    //Change to 'false' to disable if Ultimate SEO URLs is not installed
define('FEEDNAME', 'your-outfile.txt');       //from your googlebase account
define('DOMAIN_NAME', 'www.yourwebsite.com'); //your correct domain name (don't include www unless it is used but do include the shops directory)
define('FTP_USERNAME', 'bing_username'); //from your bingads account
define('FTP_PASSWORD', 'bing_password'); //from your bingads account
define('CONVERT_CURRENCY', '0'); //set to 0 to disable - only needed if a feed in a difference currecny is required
define('CURRENCY_TYPE', 'USD');  //(eg. USD, EUR, GBP)
define('DEFAULT_LANGUAGE_ID', $languages_id);   //Change this to the id of your language if different than what is set as the default language in admin
define('QUOTES_CATEGORY_NAME',''); //if the Quotes contribution is installed, enter the name of the quotes category here
define('SKIP_CATEGORY_ID', ''); //don't list any categories (or their products) listed here - separate each id by a comma
define('SKIP_PRODUCT_ID', ''); //don't list any products listed here - separate each id by a comma

/*************** OPTIONS - IF ENABLED, ALSO SET THE SIMILAR OPTION FARTHER DOWN ******************/
define('OPTIONS_ENABLED', 1);
define('OPTIONS_ENABLED_AGE_RANGE', 0);
define('OPTIONS_ENABLED_ATTRIBUTES', 0);
define('OPTIONS_ENABLED_BRAND', 1);            //if set, see options for this setting below
define('OPTIONS_ENABLED_CONDITION', 1);
define('OPTIONS_ENABLED_CURRENCY', 0);
define('OPTIONS_ENABLED_EXPIRATION', 1);
define('OPTIONS_ENABLED_FEED_LANGUAGE', 0);
define('OPTIONS_ENABLED_GTIN', 0);              //if set, a database field named products_gtin must exist
define('OPTIONS_ENABLED_BING_PRODUCT_CATEGORY', 1); //http://www.bing.com/support/merchants/bin/answer.py?answer=160081
define('OPTIONS_ENABLED_BING_UTM', 0);
define('OPTIONS_ENABLED_IDENTIFIER_EXISTS', 1); //set to 0 if required - https://support.google.com/merchants/answer/188494?hl=en
define('OPTIONS_ENABLED_ISBN', 0);              //if set, a database field named products_isbn must exist
define('OPTIONS_ENABLED_MADE_IN', 0);
define('OPTIONS_ENABLED_MPN', 1);               //if set, see options for this setting below
define('OPTIONS_ENABLED_PRODUCT_MODEL', 0);     //displays the product model
define('OPTIONS_ENABLED_PRODUCT_TYPE', 1);
define('OPTIONS_ENABLED_SHIPPING', 0);
define('OPTIONS_ENABLED_INCLUDE_TAX', 0);       //0 = no tax, 1 = uses bing method, 2 = UK Vat
define('OPTIONS_ENABLED_UPC', 0);               //if set, a database field named products_upc must exist
define('OPTIONS_ENABLED_WEIGHT', 0);

//some of the following only work if the matching option is enabled above.
define('OPTIONS_AGE_RANGE', '20-90 years');

define('OPTIONS_AVAILABILITY', 'in stock');     //in stock - Include this value if you are certain that it will ship (or be in-transit to the customer) in 3 business days or less.
                                                //available for order - Include this value if it will take 4 or more business days to ship it to the customer.
                                                //out of stock - You’re currently not accepting orders for this product.
                                                //preorder - You are taking orders for this product, but it’s not yet been released.
                                                //if empty (no entry), the data will be loaded from the database. A field in the products description table named products_availability is required
                                                //if "quantity," the field will be popuplated via the quantity: 0 or less = out of stock, greater than 0 = in stock
                                                //if "status," the field will be popuplated via the status field. in or out of stock

define('OPTIONS_BRAND', 'name');                //leave blank to load from the database field named products_brand, set to "name"  to substitute the products name, manu to substitute the manufactueres name or model to substitute the products model
define('OPTIONS_CONDITION', 'new');             //possible entries are New, Refurbished, Used or blank, which loads from the database field named products_condition
define('OPTIONS_CURRENCY', 'USD');
define('OPTIONS_CURRENCY_THOUSANDS_POINT', ','); //this is the thousands point as in $1,000.
define('OPTIONS_DATE_FORMAT', 'Y-m-d');         //change how the date is formatted
define('OPTIONS_FEED_LANGUAGE', 'en');
define('OPTIONS_BING_UTM', '?utm_source=bingads1&utm_medium=BaseFeed1&utm_campaign=products'); //see http://www.bing.com/support/binganalytics/bin/answer.py?hl=en&answer=55578
define('OPTIONS_BING_PRODUCT_CATEGORY', '');  //enter db to load from a database field named bing_product_category enter or enter a specific bing category - see taxomy - http://www.bing.com/support/merchants/bin/answer.py?answer=160081
define('OPTIONS_GTIN', '');
define('OPTIONS_ISBN', '');
define('OPTIONS_MADE_IN', 'US');
define('OPTIONS_MPN', 'name');                       //leave blank to load from the database field named products_mpn, set to "name"  to substitute the products name, manu to substitute the manufactueres name or model to substitute the products model
define('OPTIONS_PRODUCT_TYPE', ''); //full means the full category path (i.e., hardware,printers), anything else, or blank, means just the products category (i.e., printers)

//the following is for the shipping override option - enter multiple values separated by a comma
//Format entries follow. A colon must be present for each field, whether it is entered or not.
// COUNTRY - OPTIONAL - If country isn't included, we'll assume the shipping price applies to the target country of the item. If region isn't included, the shipping price will apply across the entire country.
// REGION  - OPTIONAL - blank for entire country, otherwise, us two-letter State (CA), full zip code (90210) or wildcard zip code (902*)
// SERVICE - OPTIONAL - The service class or delivery speed, i.e. ground
// PRICE   - REQUIRED - Fixed shipping price (assumes the same currency as the price attribute)
define('OPTIONS_SHIPPING_STRING', 'US:FL:Ground:7.00'); //says charge shipping to US for residents of Florida at 5% and don't apply tax to shipping

define('OPTIONS_TAX_RATE' , '20.0'); //default = 0 (e.g. for 20.0% tax use "$taxRate = 20.0;")  //only used in the next line
define('OPTIONS_TAX_CALC', (OPTIONS_ENABLED_INCLUDE_TAX == 2 ? (OPTIONS_TAX_RATE/100) + 1 : '1')); //UK. US tax rate - US is ignorded since it is 1
//the following is for the tax override option - enter multiple values separated by a comma
//Format entries follow. A colon must be present for each field, whether it is entered or not.
// COUNTRY  - OPTIONAL - country the tax applies to - only US for now
// REGION   - OPTIONAL - blank for entire country, otherwise, us two-letter State (CA), full zip code (90210) or wildcard zip code (902*)
// TAX      - REQUIRED - default = 0 (e.g. for 5.76% tax use 5.76)
// SHIPPING - OPTIONAL - do you charge tax on shipping - choices are y or n
define('OPTIONS_TAX_STRING', 'US:FL:5.00:n'); //says charge tax to US for residents of Florida at 5% and don't apply tax to shipping

define('OPTIONS_UPC', '');
define('OPTIONS_WEIGHT_ACCEPTED_METHODS', 'lb'); //Valid units include lb, pound, oz, ounce, g, gram, kg, kilogram.

//the following allow skipping certain items
define('OPTIONS_IGNORE_PRODUCT_PRICE', 0);  //0 = include products with price of 0 in output, 1 = ignore products with price of 0
define('OPTIONS_IGNORE_PRODUCT_ZERO', 0);  //0 = include products with qty of 0 in output, 1 = ignore products with qty of 0

/*************** END MASTER SETTINGS ******************/


/*************** NO EDITS NEEDED BELOW THIS LINE *****************/

//********************
//  Start TIMER
//  -----------
$stimer = explode( ' ', microtime() );
$stimer = $stimer[1] + $stimer[0];
//  -----------

define('FTP_ENABLED', (isset($_GET['noftp']) ? '0' : '1'));   //DO NOT CHANGE THIS LINE
$OutFile = "feeds/" . FEEDNAME;
$destination_file = FEEDNAME;
$source_file = $OutFile;
$imageURL = 'http://' . DOMAIN_NAME . '/images/';
if(SEO_ENABLED=='true'){
   $productURL = 'product_info.php'; // ***** Revised for SEO
   $productParam = "products_id=";   // ***** Added for SEO
}else{
   $productURL = 'http://' . DOMAIN_NAME . '/product_info.php?products_id=';
}

$already_sent = array();

if(CONVERT_CURRENCY)
{
   if(SEO_ENABLED=='true'){
       $productParam="currency=" . CURRENCY_TYPE . "&products_id=";
   }else{
       $productURL = "http://" . DOMAIN_NAME . "/product_info.php?currency=" . CURRENCY_TYPE . "&products_id=";  //where CURRENCY_TYPE is your currency type (eg. USD, EUR, GBP)
   }
}

$feed_exp_date = @date(OPTIONS_DATE_FORMAT, time() + 2419200 );

 

$quotes = '';
if (QUOTES_CATEGORY_NAME !== '') {
   $quotes = " and products.customers_email_address = '' and products.quotes_email_address = ''";
}

$identfierCtr = 0; //check if the identifier exists field applies
$extraFields = '';
if (OPTIONS_AVAILABILITY == '') {
   $extraFields .= ' products_description.products_availability as availability, ';
}
if (OPTIONS_ENABLED_BRAND == 1) {
   $identfierCtr++;
   if (strlen(OPTIONS_BRAND) == 0) {
       $extraFields .= ' products.products_brand as brand, ';
   } else {
       switch (OPTIONS_BRAND) {
           case 'name':   $extraFields .= ' products_description.products_name as brand, '; break;
           case 'manu':   $extraFields .= ' manufacturers.manufacturers_name as brand, ';   break;
           case 'model':  $extraFields .= ' products.products_model as brand, ';            break;
           default:       $extraFields .= ' products_description.products_name as brand, ';
       }
   }
}
if (OPTIONS_ENABLED_CONDITION == 1 && strlen(OPTIONS_CONDITION) == 0) {
   $extraFields .= ' products.products_condition as pcondition, ';
}
if (OPTIONS_ENABLED_GTIN == 1  && strlen(OPTIONS_GTIN) == 0) {
   $identfierCtr++;
   $extraFields .= ' products.products_gtin as gtin, ';
}
if (OPTIONS_ENABLED_ISBN == 1  && strlen(OPTIONS_ISBN) == 0) {
   $extraFields .= ' products.products_isbn as isbn, ';
}
if (OPTIONS_ENABLED_MPN == 1) {
   $identfierCtr++;
   if (strlen(OPTIONS_MPN) == 0) {
       $extraFields .= ' products.products_mpn as mpn, ';
   } else {
       switch (OPTIONS_MPN) {
           case 'name':   $extraFields .= ' products_description.products_name as mpn, '; break;
           case 'manu':   $extraFields .= ' manufacturers.manufacturers_name as mpn, ';   break;
           case 'model':  $extraFields .= ' products.products_model as mpn, ';            break;
           default:       $extraFields .= ' products_description.products_name as mpn, ';
       }
   }
}
if (OPTIONS_ENABLED_UPC == 1  && strlen(OPTIONS_UPC) == 0) {
   $extraFields .= ' products.products_upc as upc, ';
}

if (OPTIONS_ENABLED_BING_PRODUCT_CATEGORY == 1 && OPTIONS_BING_PRODUCT_CATEGORY == 'db') {
   $extraFields .= ' products_description.bing_product_category as bing_category, ';
if (tep_not_null(SKIP_CATEGORY_ID)) {
   $ids = explode(',', SKIP_CATEGORY_ID);
   for ($i = 0; $i < count($ids); ++$i) {
       $cStr .=  $ids[$i] . ",";       
   }
   $cStr = substr($cStr, 0, -1);
   $skipCatIDS = " and categories.parent_id NOT IN (" . $cStr . ") ";
}

if (tep_not_null(SKIP_PRODUCT_ID)) {
   $ids = explode(',', SKIP_PRODUCT_ID);
   $skipIDS = ' and ( ';
   for ($i = 0; $i < count($ids); ++$i) {
       $skipIDS .= ' products.products_id != ' . $ids[$i] . ' and ';
   }
   $skipIDS = substr($skipIDS, 0, -4) . ' ) ';
}

$sql = "
SELECT concat( '" . $productURL . "' ,products.products_id) AS product_url,
products_model AS prodModel,
manufacturers.manufacturers_id,
products.products_id AS id,
products_description.products_name AS name,
products_description.products_description AS description,
products.products_quantity AS quantity,
products.products_status AS prodStatus,
products.products_weight AS prodWeight, " . $extraFields . "
FORMAT( IFNULL(specials.specials_new_products_price, products.products_price) * " . OPTIONS_TAX_CALC . ",2) AS price,
CONCAT( '" . $imageURL . "' ,products.products_image) AS image_url,
products_to_categories.categories_id AS prodCatID,
categories.parent_id AS catParentID,
categories_description.categories_name AS catName
FROM (categories,
categories_description,
products,
products_description,
products_to_categories)

left join manufacturers on ( manufacturers.manufacturers_id = products.manufacturers_id )
left join specials on ( specials.products_id = products.products_id AND ( ( (specials.expires_date > CURRENT_DATE) OR (specials.expires_date is NULL) OR (specials.expires_date = 0) ) AND ( specials.status = 1 ) ) )

WHERE products.products_id=products_description.products_id
AND products.products_id=products_to_categories.products_id
AND products_to_categories.categories_id=categories.categories_id
AND categories.categories_id=categories_description.categories_id " . $quotes . $skipCatIDS . $skipIDS . "
AND categories_description.language_id = " . DEFAULT_LANGUAGE_ID . "
AND products_description.language_id = " . DEFAULT_LANGUAGE_ID . "
ORDER BY
products.products_id ASC
";

$quotes = '';
if (QUOTES_CATEGORY_NAME !== '') {
    $quotes = " and categories_description.categories_name NOT LIKE '" . QUOTES_CATEGORY_NAME . "' ";
}

$catInfo = "
SELECT
categories.categories_id AS curCatID,
categories.parent_id AS parentCatID,
categories_description.categories_name AS catName
FROM
categories,
categories_description
WHERE categories.categories_id = categories_description.categories_id " . $quotes . "
AND categories_description.language_id = " . DEFAULT_LANGUAGE_ID . "";

function findCat($curID, $catTempPar, $catTempDes, $catIndex) {
   if( (isset($catTempPar[$curID])) && ($catTempPar[$curID] != 0) ) {
       if(isset($catIndex[$catTempPar[$curID]])) {
           $temp=$catIndex[$catTempPar[$curID]];
       } else {
           $catIndex = findCat($catTempPar[$curID], $catTempPar, $catTempDes, $catIndex);
           $temp = $catIndex[$catTempPar[$curID]];
       }
   }
   if( (isset($catTempPar[$curID])) && (isset($catTempDes[$curID])) && ($catTempPar[$curID] == 0) ) {
       $catIndex[$curID] = $catTempDes[$curID];
   } else {
       $catIndex[$curID] = $temp . ", " . $catTempDes[$curID];
   }
   return $catIndex;
}

$catIndex = array();
$catTempDes = array();
$catTempPar = array();
$processCat = tep_db_query( $catInfo )or die( $FunctionName . ": SQL error " . tep_db_error() . "| catInfo = " . htmlentities($catInfo) );

while ( $catRow = $myfetch_mysql( $processCat ) ) {
   $catKey = $catRow->curCatID;
   $catName = $catRow->catName;
   $catParID = $catRow->parentCatID;
   if($catName != "") {
      $catTempDes[$catKey]=$catName;
      $catTempPar[$catKey]=$catParID;
   }
}

foreach($catTempDes as $curID=>$des)  { //don't need the $des
	  $catIndex = findCat($curID, $catTempPar, $catTempDes, $catIndex);
}

$_strip_search = array(
"![\t ]+$|^[\t ]+!m", // remove leading/trailing space chars
'%[\r\n]+%m'); // remove CRs and newlines
$_strip_replace = array(
'',
' ');
$_cleaner_array = array(">" => "> ", "&reg;" => "", "&nbsp;" => " ", "®" => "", "&trade;" => "", "™" => "", "\t" => "", '"' => '', "&quot;" => "\"");


if ( file_exists( $OutFile ) ) {
   unlink( $OutFile );
}

$output = "link\ttitle\tdescription\tprice\timage_link\tid\tavailability";
$attributesColumns = array();

//create optional section
if(OPTIONS_ENABLED == 1) {
   if(OPTIONS_ENABLED_AGE_RANGE == 1) 		$output .= "\tage_range";
   if(OPTIONS_ENABLED_BRAND == 1)            	$output .= "\tbrand";
   if(OPTIONS_ENABLED_CONDITION == 1)       	$output .= "\tcondition";
   if(OPTIONS_ENABLED_CURRENCY == 1)        	$output .= "\tcurrency";
   if(OPTIONS_ENABLED_EXPIRATION == 1)      	$output .= "\texpiration_date";
   if(OPTIONS_ENABLED_FEED_LANGUAGE == 1)   	$output .= "\tlanguage";
   if(OPTIONS_ENABLED_BING_PRODUCT_CATEGORY == 1) $output .= "\tbing product_category";
   if(OPTIONS_ENABLED_GTIN == 1)            	$output .= "\tgtin";
   if(OPTIONS_ENABLED_IDENTIFIER_EXISTS == 1 && $identfierCtr > 1)  $output .= "\tidentifier exists";
   if(OPTIONS_ENABLED_ISBN == 1)            	$output .= "\tisbn";
   if(OPTIONS_ENABLED_MADE_IN == 1)         	$output .= "\tmade_in";
   if(OPTIONS_ENABLED_MPN == 1)                 $output .= "\tmpn";
   if(OPTIONS_ENABLED_PRODUCT_MODEL == 1)   	$output .= "\tmodel";
   if(OPTIONS_ENABLED_PRODUCT_TYPE == 1)    	$output .= "\tproduct_type";
   if(OPTIONS_ENABLED_SHIPPING == 1)         	$output .= "\tshipping";
   if(OPTIONS_ENABLED_INCLUDE_TAX == 1)         $output .= "\ttax";
   if(OPTIONS_ENABLED_UPC == 1)             	$output .= "\tupc";
   if(OPTIONS_ENABLED_WEIGHT == 1)          	$output .= "\tshipping_weight";

   if (OPTIONS_ENABLED_ATTRIBUTES == 1)  {
       $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from products_options popt, products_attributes patrib where popt.language_id = '" . (int)1 . "' order by popt.products_options_name") or die(tep_db_error());
       while ($products_options_name = $myfetch_mysql($products_options_name_query)) {
           $attributesColumns[] = $products_options_name->products_options_name;
           $name = strtolower($products_options_name->products_options_name);
           $name = str_replace(" ","_", $name);
           $output .= "\tc:" . $name;
       }

       /*
       //If you want to only show particular attributes, comment out the above and uncomment this section.
       //Then enter two lines for each one you want to show. For example, if the atttributes you want to
       //show are named Color and Fabric, the entries would appear as follows:

       $attributesColumns[] = "Color";
       $attributesColumns[] = "Fabric";

       $output .= "\tc:" . strtolower("Color");
       $output .= "\tc:" . strtolower("Fabric");
       */
   }
}
$output .= "\n";


$result=tep_db_query( $sql )or die( $FunctionName . ": SQL error " . tep_db_error() . "| sql = " . htmlentities($sql) );

//Currency Information
if(CONVERT_CURRENCY) {
   $sql3 = "
   SELECT
   currencies.value AS curUSD
   FROM
   currencies
   WHERE currencies.code = '" . CURRENCY_TYPE . "'";

   $result3=tep_db_query( $sql3 )or die( $FunctionName . ": SQL error " . tep_db_error() . "| sql3 = " . htmlentities($sql3) );
   $row3 = $myfetch_mysql( $result3 );
}

$loop_counter = 0;
$statsArray = array();      //record messages
$statsArrayPrice = array(); //record prices of 0
$statsArrayQty = array();   //record quantities of 0
$showPriceZero = false;
$showQtyZero = false;


while( $row = $myfetch_mysql( $result ) ) {
   if (isset($already_sent[$row->id])) continue; // if we've sent this one, skip the rest of the while loop
   if (OPTIONS_IGNORE_PRODUCT_PRICE > 0 && $row->price <= 0) continue; //skip products with 0 price
   if (OPTIONS_IGNORE_PRODUCT_ZERO > 0 && $row->quantity < 1) continue; //skip products with 0 qty
   if (OPTIONS_IGNORE_PRODUCT_PRICE < 1 && $row->price <= 0 && $row->prodStatus == 1) {$statsArray['price']++; $showPriceZero = true; }//record for warning
   if (OPTIONS_IGNORE_PRODUCT_ZERO < 1 && $row->quantity < 1 && $row->prodStatus == 1) {$statsArray['qty']++; $showQtyZero = true; } //record for warning

   $statsArray['total']++;

   if ( $row->prodStatus == 1 ) {
      if (CONVERT_CURRENCY) {
          $row->price = preg_replace("/[^.0-9]/", "", $row->price);
          $row->price = $row->price *  $row3->curUSD;
          $row->price = number_format($row->price, 2, '.', OPTIONS_CURRENCY_THOUSANDS_POINT);
      }

      $availability = '';
      switch (OPTIONS_AVAILABILITY) {
         case 'quantity': $availability = ($row->quantity > 0 ? 'in stock' : 'out of stock'); break;
         case 'status':   $availability = ($row->prodStatus == 1 ? 'in stock' : 'out of stock'); break;
         case '':         $availability = $row->availability; break;
         default:         $availability = OPTIONS_AVAILABILITY;
      }

      $bing_utm = (OPTIONS_ENABLED_BING_UTM ? OPTIONS_BING_UTM : '');
      $pURL = $row->product_url;

      if(SEO_ENABLED=='true'){
          $output .= tep_href_link($productURL,$productParam . $row->id, 'NONSSL', false) . $bing_utm . "\t";
          $pURL = tep_href_link($productURL,$productParam . $row->id, 'NONSSL', false);
      } else {
          $output .= $row->product_url . $bing_utm . "\t";
      }

      if ($showPriceZero) {
          $showPriceZero = false;
          $statsArrayPrice[] = $pURL;
      }
      if ($showQtyZero) {
          $showQtyZero = false;
          $statsArrayQty[] = $pURL;
      }

      
      $output .=
      preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->name, $_cleaner_array) ) ) . "\t" .
      preg_replace($_strip_search, $_strip_replace, strip_tags( strtr($row->description, $_cleaner_array) ) ) . "\t" .
      $row->price . "\t" .
      $row->image_url . "\t" .
    //  $catIndex[$row->prodCatID] . "\t" .
      $row->id . "\t" .
      $availability;

      //optional values section
      if(OPTIONS_ENABLED == 1) {
         if(OPTIONS_ENABLED_AGE_RANGE == 1)
            $output .= "\t" . OPTIONS_AGE_RANGE;
         if(OPTIONS_ENABLED_BRAND == 1)
            $output .= "\t" . (isset($row->brand) ? $row->brand : (strlen(OPTIONS_BRAND) ? $row->name : "Not Supported"));
         if(OPTIONS_ENABLED_CONDITION == 1)
            $output .= "\t" . (isset($row->pcondition) ? $row->pcondition : OPTIONS_CONDITION);
         if(OPTIONS_ENABLED_CURRENCY == 1)
            $output .= "\t" . OPTIONS_CURRENCY;
         if(OPTIONS_ENABLED_EXPIRATION == 1)
            $output .= "\t" . $feed_exp_date;
         if(OPTIONS_ENABLED_FEED_LANGUAGE == 1)
            $output .= "\t" . OPTIONS_FEED_LANGUAGE;
         if(OPTIONS_ENABLED_BING_PRODUCT_CATEGORY == 1)
            $output .= "\t" . (OPTIONS_BING_PRODUCT_CATEGORY == 'db' ? $row->bing_category : OPTIONS_BING_PRODUCT_CATEGORY);
         if(OPTIONS_ENABLED_GTIN == 1)
            $output .= "\t" . (isset($row->gtin) ? $row->gtin : (strlen(OPTIONS_GTIN) ? OPTIONS_GTIN : "Not Supported"));
         if (OPTIONS_ENABLED_IDENTIFIER_EXISTS == 1 && $identfierCtr > 1) {
            $icnt = 0;
            if (OPTIONS_ENABLED_BRAND && empty($row->brand)) $icnt++;
            if (OPTIONS_ENABLED_GTIN && empty($row->gtin)) $icnt++;   
            if (OPTIONS_ENABLED_MPN && empty($row->mpn)) $icnt++;  
            if ($icnt > 1) { //at least two required fields are empty
              $output .= "\tFALSE";
            } else {
              $output .= "\tTRUE";
            }
         }   
         if(OPTIONS_ENABLED_ISBN == 1)
            $output .= "\t" . (isset($row->isbn) ? $row->isbn : (strlen(OPTIONS_ISBN) ? OPTIONS_ISBN : "Not Supported"));
         if(OPTIONS_ENABLED_MADE_IN == 1)
            $output .= "\t" . OPTIONS_MADE_IN;
         if(OPTIONS_ENABLED_MPN == 1)
            $output .= "\t" . (isset($row->mpn) ? $row->mpn : (strlen(OPTIONS_MPN) ? OPTIONS_MPN : "Not Supported"));
         if(OPTIONS_ENABLED_PRODUCT_MODEL == 1)
            $output .= "\t" . (! empty($row->prodModel) ? $row->prodModel : $row->catName);
         if(OPTIONS_ENABLED_PRODUCT_TYPE == 1)
            $output .= "\t" . ((OPTIONS_PRODUCT_TYPE == strtolower('full')) ? $catIndex[$row->prodCatID] : $row->catName);
         if(OPTIONS_ENABLED_SHIPPING == 1)
            $output .= "\t" . OPTIONS_SHIPPING_STRING;
         if(OPTIONS_ENABLED_INCLUDE_TAX == 1)
            $output .= "\t" . OPTIONS_TAX_STRING;
         if(OPTIONS_ENABLED_UPC == 1)
            $output .= "\t" . (isset($row->upc) ? $row->upc : (strlen(OPTIONS_UPC) ? OPTIONS_UPC : "Not Supported"));
         if(OPTIONS_ENABLED_WEIGHT == 1)
            $output .= "\t" . $row->prodWeight . ' ' .OPTIONS_WEIGHT_ACCEPTED_METHODS;

         /******************* BEGIN HANDLING THE ATTRIBUTES ********************/
         if (OPTIONS_ENABLED_ATTRIBUTES == 1)
         {
            $products_attributes_query = tep_db_query("select count(*) as total from products_options popt, products_attributes patrib where patrib.products_id='" . $row->id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)1 . "'");
            $products_attributes = $myfetch_mysql($products_attributes_query);
            if ($products_attributes->total > 0) {
              $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from products_options popt, products_attributes patrib where patrib.products_id='" . (int)$row->id . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)1 . "' order by popt.products_options_name") or die(tep_db_error());

              $trackTabs = '';

              while ($products_options_name = $myfetch_mysql($products_options_name_query)) {
                $products_options_array = array();
                $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from products_attributes pa, products_options_values pov where pa.products_id = '" . (int)$row->id . "' and pa.options_id = '" . $products_options_name->products_options_id . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)1 . "'");
                while ($products_options = $myfetch_mysql($products_options_query)) {
                  $products_options_array[] = array('id' => $products_options->products_options_values_id, 'text' => $products_options->products_options_values_name);
                }

                for ($a = 0; $a < count($attributesColumns); ++$a)
                {
                   if ($products_options_name->products_options_name == $attributesColumns[$a])
                   {
                     if ($a == 0)
                       $trackTabs = "\t";
                     else
                     {
                       if (empty($trackTabs))
                         $trackTabs = str_repeat("\t", $a);
                       $trackTabs .= "\t";
                     }

                     $output .= $trackTabs;
                     foreach ($products_options_array as $arr)
                       $output .=  $arr['text'] . ',';
                     $output = substr($output, 0, -1);
                   }
                }
              }
            }
         }
         /******************* END HANDLING THE ATTRIBUTES ********************/
      }
      $output .= " \n";
   }

   $already_sent[$row->id] = 1;
   $loop_counter++;

   if ($loop_counter>750) {
      $fp = fopen( $OutFile , "a" );
      $fout = fwrite( $fp , $output );
      fclose( $fp );
      $loop_counter = 0;
      $output = "";
   }
}

$fp = fopen( $OutFile , "a" );
$fout = fwrite( $fp , $output );
fclose( $fp );

echo '<p style="margin:auto; text-align:left">';
printf( "Feed contains %d products.", $statsArray['total'] );
echo '</p>';

$warning = false;
if (count($statsArray['price']) > 0) {
  $warning = true;
  echo '<p style="margin:auto; text-align:left;  padding:10px; 0px">';
  printf( "***Warning:*** There are %d products with a price of $0.<br>", $statsArray['price'] );

  for ($i = 0; $i < count($statsArrayPrice); ++$i) {
      echo  '&nbsp;&nbsp;&nbsp;' . $i . ' - ' . $statsArrayPrice[$i] .'<br>';
  }

  echo '</p>';
}

if (count($statsArray['qty']) > 0) {
  $warning = true;
  echo '<p style="margin:auto; text-align:left; padding-bottom:10px; ">';
  printf( "***Warning:*** There are %d products with a quantity of 0.<br>", $statsArray['qty'] );

  for ($i = 0; $i < count($statsArrayQty); ++$i) {
      echo  '&nbsp;&nbsp;&nbsp;' . $i . ' - ' . $statsArrayQty[$i] .'<br>';
  }

  echo '</p>';
}

if (tep_not_null(SKIP_PRODUCT_ID)) {
  echo '<p style="margin:auto; text-align:left; padding-bottom:10px; ">';
  printf( "The following product ID's were skipped: %s.", SKIP_PRODUCT_ID );
  echo '</p>';
}

$completed = 'File Completed' . ($warning ? ' (with warnings): ' : ':' );

echo '<p style="margin:auto; text-align:left">';
echo "$completed <a href=\"../" . $OutFile . "\" target=\"_blank\">" . $destination_file . "</a><br>\n\n";
echo '</p>';


$csvFileDest = str_replace('.txt', '.csv', $destination_file);
$csvFileLocn = str_replace('.txt', '.csv', $OutFile);
$csvStr = str_replace("\t", '", "', '"' . $output);
$csvStr = str_replace("\n", "\"\n\"", $csvStr);
$csvStr = substr($csvStr,0,-1);
$csvStr = str_replace("\t", '", "', '"' . $output . '"');

$fp = fopen( $csvFileLocn , "a" );
$fout = fwrite( $fp , $csvStr );
fclose( $fp );

echo '<p style="margin:auto; text-align:left; padding-top:10px;">';
echo 'Use the following for easier viewing from this page. It is still in development and not meant for anything other than viewing.' . "<br>\n\n";
echo "$completed <a href=\"../" . $csvFileLocn . "\" target=\"_blank\">" . $csvFileDest . "</a><br>\n";
echo '</p>';


chmod($OutFile, 0777);


//Start FTP

function ftp_file( $ftpservername, $ftpusername, $ftppassword, $ftpsourcefile, $ftpdirectory, $ftpdestinationfile ) {
   // set up basic connection
   $conn_id = ftp_connect($ftpservername);
   if ( $conn_id == false ) {
      echo "FTP open connection failed to $ftpservername <BR>\n" ;
      return false;
   }

   // login with username and password
   $login_result = ftp_login($conn_id, $ftpusername, $ftppassword);

   // check connection
   if ((!$conn_id) || (!$login_result)) {
      echo "FTP connection has failed!<BR>\n";
      echo "Attempted to connect to " . $ftpservername . " for user " . $ftpusername . "<BR>\n";
      return false;
   } else {
      echo "Connected to " . $ftpservername . ", for user " . $ftpusername . "<BR>\n";
   }

   if ( strlen( $ftpdirectory ) > 0 ) {
      if (ftp_chdir($conn_id, $ftpdirectory )) {
         echo "Current directory is now: " . ftp_pwd($conn_id) . "<BR>\n";
      } else {
         echo "Couldn't change directory on $ftpservername<BR>\n";
         return false;
      }
   }

   ftp_pasv ( $conn_id, true ) ;
   // upload the file
   $upload = ftp_put( $conn_id, $ftpdestinationfile, $ftpsourcefile, FTP_ASCII );

   // check upload status
   if (!$upload) {
      echo "$ftpservername: FTP upload has failed!<BR>\n";
      return false;
   } else {
      echo "Uploaded " . $ftpsourcefile . " to " . $ftpservername . " as " . $ftpdestinationfile . "<BR>\n";
   }

   // close the FTP stream
   ftp_close($conn_id);

   return true;
}

if (FTP_ENABLED)
 ftp_file( "feeds.adcenter.microsoft.com", FTP_USERNAME, FTP_PASSWORD, $source_file, "", $destination_file);

//End FTP


//  End TIMER
//  ---------
$etimer = explode( ' ', microtime() );
$etimer = $etimer[1] + $etimer[0];

echo '<p style="margin:auto; text-align:center">';
printf( "Script timer: <b>%f</b> seconds.", ($etimer-$stimer) );
echo '</p>';




//  ---------

?>