<?php
  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);
  
// get the configuration settings for the store this order was created with 
// first get the configuration name for this store
$stores_number = $order->billing[ 'stores_id' ] ;
$stores_query = tep_db_query("select stores_id, stores_config_table from " . TABLE_STORES . " where stores_id = '" . $stores_number . "'"); // MULTI STORES
$stores_configuration = tep_db_fetch_array($stores_query) ;
$stores_config_table =  $stores_configuration ['stores_config_table'] ;
$stores_id =  $stores_configuration ['stores_id'] ;
// define all variables for this store
$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . $stores_config_table . " where configuration_key != ''"); // MULTI STORES
while ($configuration = tep_db_fetch_array($configuration_query)) {
    define( 'STORE_' . $configuration['cfgKey'], $configuration['cfgValue']);
}		 
  
  
function tep_dr($address_format_id, $address, $html, $boln, $eoln) {
    $address_format_query = tep_db_query("select address_format as format from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . (int)$address_format_id . "'");
    $address_format = tep_db_fetch_array($address_format_query);
    $company = tep_output_string_protected($address['company']);
    if (isset($address['firstname']) && tep_not_null($address['firstname'])) {
      $firstname = tep_output_string_protected($address['firstname']);
      $lastname = tep_output_string_protected($address['lastname']);
    } elseif (isset($address['name']) && tep_not_null($address['name'])) {
      $firstname = tep_output_string_protected($address['name']);
      $lastname = '';
    } else {
      $firstname = '';
      $lastname = '';
    }
    $street = tep_output_string_protected($address['street_address']);
    $suburb = tep_output_string_protected($address['suburb']);
    $city = tep_output_string_protected($address['city']);
    $state = tep_output_string_protected($address['state']);
    if (isset($address['country_id']) && tep_not_null($address['country_id'])) {
      $country = tep_get_country_name($address['country_id']);
      if (isset($address['zone_id']) && tep_not_null($address['zone_id'])) {
        $state = tep_get_zone_code($address['country_id'], $address['zone_id'], $state);
      }
    } elseif (isset($address['country']) && tep_not_null($address['country'])) {
      $country = tep_output_string_protected($address['country']);
    } else {
      $country = '';
    }
    $postcode = tep_output_string_protected($address['postcode']);
    $zip = $postcode; 
    $line1= "$firstname $lastname";
    $line2= "$company";
    $line3="$street";
    $line4= "$zip". ', ' . "$city";
    $line5="$country";

require('../ext/tcpdf/config/lang/eng.php'); 
require('../ext/tcpdf/tcpdf.php');

$pdf=new TCPDF( STORE_SHIPLABEL_PORTRAIT_LANDSCAPE,'mm',array(STORE_SHIPLABEL_WIDTH, STORE_SHIPLABEL_HEIGHT));
$pdf->AddPage();
$pdf->SetXY('20', '20');
$pdf->SetMargins('0','0','0');

$bg_color=explode(",",STORE_SHIPLABEL_BG_COLOR );
$pdf->SetFillColor($bg_color[0], $bg_color[1], $bg_color[2]);
//$pdf->Rect($pdf->lMargin,0,$pdf->w-$pdf->rMargin,$pdf->h,'F');

if ( STORE_SHIPLABEL_SHOW_LOGO == 'true' ) {
  $pdf->image( STORE_DIR_FS_CATALOG_IMAGES . STORE_SHIPLABEL_STORE_LOGO, 15, 01, 00, 15 ) ;
}

$text_color=explode(",",STORE_SHIPLABEL_BODY_COLOR_TEXT );
$pdf->SetTextColor($text_color[0], $text_color[1], $text_color[2]);

$pdf->SetX(15);
$pdf->SetFont('helvetica','B',STORE_SHIPLABEL_TEXT_HEIGHT);
$pdf->Cell(0,12,"$line1",T,2);
if($line2 != ''){
 $pdf->Cell(40,12,"$line2",0,2);
}
$pdf->Cell(40,12,"$line3",0,2);
$pdf->Cell(40,12,"$line4",0,2);
if($line5 != STORE_STORE_COUNTRY ){
  $pdf->Cell(0,12,"$line5",B,2);
}
$pdf->SetX('15');
//$pdf->Cell(100,4,'',T,1);

// set and draw background color
$text_color=explode(",",STORE_SHIPLABEL_FOOTER_COLOR_TEXT );
$pdf->SetTextColor($text_color[0], $text_color[1], $text_color[2]);

$pdf->SetFont('helvetica','B',STORE_SHIPLABEL_FOOTER_TEXT_HEIGHT );
$pdf->SetX('15');
$pdf->Cell(80,4,STORE_SHIPLABEL_FOOTER_TEXT,0,1);

$pdf->Output();

    if ($html) {
// HTML Mode
      $HR = '<hr>';
      $hr = '<hr>';
      if ( ($boln == '') && ($eoln == "\n") ) { // Values not specified, use rational defaults
        $CR = '<br>';
        $cr = '<br>';
        $eoln = $cr;
      } else { // Use values supplied
        $CR = $eoln . $boln;
        $cr = $CR;
      }
    } else {
// Text Mode
      $CR = $eoln;
      $cr = $CR;
      $HR = '----------------------------------------';
      $hr = '----------------------------------------';
    }
    $statecomma = '';
    $streets = $street;
    if ($suburb != '') $streets = $street . $cr . $suburb;
    if ($country == '') $country = tep_output_string_protected($address['country']);
    if ($state != '') $statecomma = $state . ', ';

    $fmt = $address_format['format'];
    eval("\$address = \"$fmt\";");

    if ( (ACCOUNT_COMPANY == 'true') && (tep_not_null($company)) ) {
      $address = $company . $cr . $address;
    }
  }
tep_dr($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); 
?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>