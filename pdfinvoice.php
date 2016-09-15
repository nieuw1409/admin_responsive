<?php
/* overtollig verwijderd
  $Id: create_customer_pdf,v 1.1 2007/07/25 clefty (osc forum id chris23)

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  Based on create_pdf , originally written by Neil Westlake (nwestlake@gmail.com
*/
 require('ext/tcpdf/tcpdf.php');
 require('ext/tcpdf/config/lang/eng.php');

 
 require_once('includes/application_top.php');
 
 // perform security check to prevent "get" tampering to view other customer's invoices

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
 
 if ( $stream ) {
    $order_id = $invoice_number ;
} else {
  $order_id = 	$HTTP_GET_VARS['order_id'] ;
}	

    if (!isset($order_id) || (isset($order_id) && !is_numeric($order_id))) {
      tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
    }
	
  
  
  $customer_info_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". (int)$order_id . "'");
  $customer_info = tep_db_fetch_array($customer_info_query);
  if ($customer_info['customers_id'] != $customer_id) {
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }
 
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CUSTOMER_PDF);
 
  require_once(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY_INFO);

  require_once(DIR_WS_CLASSES . 'order.php');  
 
//if ( $stream ) {
// $order_id = $invoice_number ;
   $order = new order($order_id);
// } else {
//  $order = new order($HTTP_GET_VARS['order_id']);
//  } 

  //$order = new order($HTTP_GET_VARS['order_id']);
   
    //contamos los items total de la factura - codigo facilitado por Eusebio
$items_total = 0;
$count_items_query = tep_db_query("select count(orders_products_id) as total from " .
TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
$count_items = tep_db_fetch_array($count_items_query);
$items_total += $count_items['total'];
  
  // set invoice date - today or day ordered. set in config
    $date = (PDF_INV_DATE_TODAY == 'today') ? strftime(DATE_FORMAT_LONG) : tep_date_long($order->info['date_purchased']);

class PDF extends TCPDF
{

//Page header
 function RoundedRect($x, $y, $w, $h,$r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' or $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2f %.2f m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l', $xc*$k,($hp-$y)*$k ));

        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
	
    function Header()
    {
	$date = strftime('%A, %d %B %Y');
	
    $bg_color=explode(",",PDF_INV_PAGE_FILL_COLOR );
    $this->SetFillColor($bg_color[0], $bg_color[1], $bg_color[2]);
    $this->Rect($this->lMargin,0,$this->w-$this->rMargin,$this->h,'F');
	 
	//Logo
    if ( PDF_INV_SHOW_LOGO == 'true' ) {
	    $this->Image( 'images/' . PDF_INV_STORE_LOGO ,5,10, 125, 35);
    }

	// Company Address
	$this->SetX(0);
	$this->SetY(10);
    $this->SetFont( PDF_INV_HEADER_TEXT_FONT,
                    PDF_INV_HEADER_TEXT_EFFECT,
                    PDF_INV_HEADER_TEXT_HEIGHT);  
                    
    $text_color=explode(",",PDF_INV_HEADER_TEXT_COLOR );
    $this->SetTextColor($text_color[0], $text_color[1], $text_color[2]);  
        
    $text_color=explode(",",PDF_INV_HEADER_FILL_COLOR );
    $this->SetFillColor( $text_color[0], $text_color[1], $text_color[2] );  
    
    $text_color=explode(",",PDF_INV_HEADER_LINE_COLOR );
    $this->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );       

    $this->Ln(0);
    $this->Cell(149);
    
    if ( PDF_INV_SHOW_ADRESSSHOP == 'true' ) {    
	   $this->MultiCell(50, 3.5, STORE_NAME_ADDRESS,0,'L', '1' ); 
    }   
	
	//email
	$this->SetX(0);
	$this->SetY(37);
//	$this->SetFont('Arial','B',10);
    $this->SetFont( PDF_INV_HEADER_TEXT_FONT,
                    PDF_INV_HEADER_TEXT_EFFECT,
                    PDF_INV_HEADER_TEXT_HEIGHT);  
                    
    $text_color=explode(",",PDF_INV_HEADER_TEXT_COLOR );
    $this->SetTextColor($text_color[0], $text_color[1], $text_color[2]);  
        
    $text_color=explode(",",PDF_INV_HEADER_FILL_COLOR );
    $this->SetFillColor( $text_color[0], $text_color[1], $text_color[2] );  
    
    $text_color=explode(",",PDF_INV_HEADER_LINE_COLOR );
    $this->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );     
    
	$this->Ln(0);
    $this->Cell(81);
    if ( PDF_INV_SHOW_MAILWEB == 'true' ) {    
	   $this->MultiCell(100, 6, "E-mail: " . STORE_OWNER_EMAIL_ADDRESS,0,'R', '1' );
    }   
	
	//website
	$this->SetX(0);
	$this->SetY(42);
    $this->SetFont( PDF_INV_HEADER_TEXT_FONT,
                    PDF_INV_HEADER_TEXT_EFFECT,
                    PDF_INV_HEADER_TEXT_HEIGHT);  
                    
    $text_color=explode(",",PDF_INV_HEADER_TEXT_COLOR );
    $this->SetTextColor($text_color[0], $text_color[1], $text_color[2]);  
        
    $text_color=explode(",",PDF_INV_HEADER_FILL_COLOR );
    $this->SetFillColor( $text_color[0], $text_color[1], $text_color[2] );  
    
    $text_color=explode(",",PDF_INV_HEADER_LINE_COLOR );
    $this->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );     
    
	$this->Ln(0);
    $this->Cell(88);
    if ( PDF_INV_SHOW_MAILWEB == 'true' ) {    
	   $this->MultiCell(100, 6, "Web  : " . HTTP_SERVER,0,'R', '1' );
    }  
    
    //Draw the top line with invoice text
    $this->Cell(50);
    $this->SetY(60);
    
    $text_color=explode(",",PDF_INV_HEADINVOICE_TEXT_COLOR );
    $this->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );

//    $this->Cell(15,.1,'',1,1,'L',1);
    $this->Cell( 15, 0, '', 'T', 0, 'C');

    $this->SetFont( PDF_INV_HEADINVOICE_TEXT_FONT,
                    PDF_INV_HEADINVOICE_TEXT_EFFECT,
                    PDF_INV_HEADINVOICE_TEXT_HEIGHT); 
               
    $text_color=explode(",",PDF_INV_HEADINVOICE_TEXT_COLOR );
    $this->SetTextColor( $text_color[0], $text_color[1], $text_color[2] );

    $this->Text(22,57,PRINT_INVOICE);
    $this->SetY(60);

    $text_color=explode(",",PDF_INV_HEADINVOICE_TEXT_COLOR );
    $this->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );

    $this->Cell(38);
    $this->Cell( 160, 0, '', 'T', 0, 'C');	
//    $this->Cell(160,.1,'',1,1,'L',1);  
   
}

function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-17);

    $this->SetFont( PDF_INV_FOOTER_TEXT_FONT,
                    PDF_INV_FOOTER_TEXT_EFFECT,
                    PDF_INV_FOOTER_TEXT_HEIGHT);  
                    
    $text_color=explode(",",PDF_INV_FOOTER_TEXT_COLOR );
    $this->SetTextColor($text_color[0], $text_color[1], $text_color[2]);
        
	$this->Cell(0,10, PDF_INV_FOOTER_TEXT, 0, 0,'C');
	
	$this->SetY(-14);
	//Page number
    $this->Cell(0,10,PRINT_INVOICE_PAGE_NUMBER.$this->PageNo(),0,0,'C' );
	}
}

# for backward compatibility
if (!function_exists('file_get_contents')){
	function file_get_contents($filename, $use_include_path = 0){
		$file = @fopen($filename, 'rb', $use_include_path);
		if ($file){
			if ($fsize = @filesize($filename))
				$data = fread($file, $fsize);
			else {
				$data = '';
				while (!feof($file)) $data .= fread($file, 1024);
			}
			fclose($file);
			return $data;
		}else
			return false;
	}
}

$pdf=new PDF( PDF_INV_PORTRAIT_LANDSCAPE,'mm',array(PDF_INV_PAPER_WIDTH, PDF_INV_PAPER_HEIGHT ) ) ;

// Set the Page Margins
$pdf->SetMargins(6,2,6);

// Add the first page
$pdf->AddPage();


//Draw Box for Invoice Address
if ( PDF_INV_SHOW_SOLDTO == 'true' ) {  
  $text_color=explode(",",PDF_INV_SOLDTO_LINE_COLOR );
  $pdf->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );
  $pdf->SetLineWidth(0.2);
  $text_color=explode(",",PDF_INV_SOLDTO_FILL_COLOR );
  $pdf->SetFillColor( $text_color[0], $text_color[1], $text_color[2] );
  $pdf->RoundedRect(6, 67, 90, 35, 2, 'DF');

  //Draw the invoice address text
  $pdf->SetFont( PDF_INV_SOLDTO_TEXT_FONT,
                 PDF_INV_SOLDTO_TEXT_EFFECT,
                 PDF_INV_SOLDTO_TEXT_HEIGHT );  
                    
  $text_color=explode(",",PDF_INV_SOLDTO_TEXT_COLOR );
  $pdf->SetTextColor($text_color[0], $text_color[1], $text_color[2]);  
    
  $pdf->Text(11,77, ENTRY_SOLD_TO);
  $pdf->SetX(0);
  $pdf->SetY(80);
  $pdf->Cell(9);
  $pdf->MultiCell(70, 3.3, tep_address_format(1, $order->customer, '', '', "\n"),0,'L', '1');
}  
	
	//Draw Box for Delivery Address
if ( PDF_INV_SHOW_SENDTO == 'true' ) { 	
    $text_color=explode(",",PDF_INV_SENDTO_LINE_COLOR );
    $pdf->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );
	$pdf->SetLineWidth(0.2);
    $text_color=explode(",",PDF_INV_SENDTO_FILL_COLOR );
    $pdf->SetFillColor( $text_color[0], $text_color[1], $text_color[2] );
	$pdf->RoundedRect(108, 67, 90, 35, 2, 'DF');
	
	//Draw the invoice delivery address text
    $pdf->SetFont( PDF_INV_SENDTO_TEXT_FONT,
                   PDF_INV_SENDTO_TEXT_EFFECT,
                   PDF_INV_SENDTO_TEXT_HEIGHT );
                    
    $text_color=explode(",",PDF_INV_SENDTO_TEXT_COLOR );
    $pdf->SetTextColor($text_color[0], $text_color[1], $text_color[2]);  
	$pdf->Text(113,77,ENTRY_SHIP_TO);
	$pdf->SetX(0);
	$pdf->SetY(80);
    $pdf->Cell(111);
	$pdf->MultiCell(70, 3.3, tep_address_format(1, $order->delivery, '', '', "\n"),0,'L', '1');
}	

	//Draw Box for Order Number, Date & Payment method
    $text_color=explode(",",PDF_INV_ORDERDETAILS_LINE_COLOR );
    $pdf->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );
	$pdf->SetLineWidth(0.2);
    $text_color=explode(",",PDF_INV_ORDERDETAILS_FILL_COLOR );
    $pdf->SetFillColor( $text_color[0], $text_color[1], $text_color[2] );
	$pdf->RoundedRect(6, 107, 192, 11, 2, 'DF');
	
	// print the order details
	$pdf->SetFont( PDF_INV_ORDERDETAILS_TEXT_FONT,
                   PDF_INV_ORDERDETAILS_TEXT_EFFECT,
                   PDF_INV_ORDERDETAILS_TEXT_HEIGHT);  
                    
    $text_color=explode(",",PDF_INV_ORDERDETAILS_TEXT_COLOR );
    $pdf->SetTextColor($text_color[0], $text_color[1], $text_color[2]);  

	//Draw Order Number Text
	$temp = str_replace('&nbsp;', ' ', PRINT_INVOICE_ORDER);
//	$pdf->Text(10,113, $temp . $HTTP_GET_VARS['order_id']);	
	$pdf->Text(10,113, $temp . $order_id);		
	//Draw Date of Order Text
	$temp = str_replace('&nbsp;', ' ', PRINT_INVOICE_DATE);
	$pdf->Text(50,113,$temp . tep_date_short($order->info['date_purchased']));	
	//Draw Payment Method Text
	$temp = substr ($order->info['payment_method'] , 0, 23);
	$pdf->Text(107,113,ENTRY_PAYMENT_METHOD . ' ' . $temp);	

//Fields Name position
$Y_Fields_Name_position = 125;
//Table position, under Fields Name
$Y_Table_Position = 131;


function output_table_heading($Y_Fields_Name_position){
    global  $pdf, $cell_color;
//First create each Field Name
// color filling each Field Name box

//Bold Font for Field Name
$pdf->SetFont( PDF_INV_TABLEHEADING_TEXT_FONT,
               PDF_INV_TABLEHEADING_TEXT_EFFECT,
               PDF_INV_TABLEHEADING_TEXT_HEIGHT);  

$text_color=explode(",",PDF_INV_TABLEHEADING_TEXT_COLOR );
$pdf->SetTextColor($text_color[0], $text_color[1], $text_color[2]); 

$text_color=explode(",",PDF_INV_TABLEHEADING_LINE_COLOR );
$pdf->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );
$text_color=explode(",",PDF_INV_TABLEHEADING_FILL_COLOR );
$pdf->SetFillColor( $text_color[0], $text_color[1], $text_color[2] );                

$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(6);
$pdf->Cell(9,6,TABLE_HEADING_QUANTITY,1,0,'C',1);
$pdf->SetX(15);
$pdf->Cell(27,6,TABLE_HEADING_PRODUCTS_MODEL,1,0,'C',1);
$pdf->SetX(40);
$pdf->Cell(78,6,TABLE_HEADING_PRODUCTS,1,0,'C',1);
//$pdf->SetX(105);
//$pdf->Cell(15,6,TABLE_HEADING_TAX,1,0,'C',1);
$pdf->SetX(118);
$pdf->Cell(20,6,TABLE_HEADING_PRICE_EXCLUDING_TAX,1,0,'C',1);
$pdf->SetX(138);
$pdf->Cell(20,6,TABLE_HEADING_PRICE_INCLUDING_TAX,1,0,'C',1);
$pdf->SetX(158);
$pdf->Cell(20,6,TABLE_HEADING_TOTAL_EXCLUDING_TAX,1,0,'C',1);
$pdf->SetX(178);
$pdf->Cell(20,6,TABLE_HEADING_TOTAL_INCLUDING_TAX,1,0,'C',1);
$pdf->Ln();
}

output_table_heading($Y_Fields_Name_position);
$item_count = 0 ;
for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
	
    $pdf->SetFont( PDF_INV_PRODUCTS_TEXT_FONT,
                   PDF_INV_PRODUCTS_TEXT_EFFECT,
                   PDF_INV_PRODUCTS_TEXT_HEIGHT);  

    $text_color=explode(",",PDF_INV_PRODUCTS_TEXT_COLOR );
    $pdf->SetTextColor($text_color[0], $text_color[1], $text_color[2]); 
    
     
    $text_color=explode(",",PDF_INV_PRODUCTS_FILL_COLOR );
    $pdf->SetFillColor( $text_color[0], $text_color[1], $text_color[2] );  
    
    $text_color=explode(",",PDF_INV_PRODUCTS_LINE_COLOR );
    $pdf->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );                 

	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(6);
	$pdf->MultiCell(9,6,$order->products[$i]['qty'],1,'C', '1');
	
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(15);
    $pdf->SetFont( PDF_INV_PRODUCTS_TEXT_FONT,
                   PDF_INV_PRODUCTS_TEXT_EFFECT,
                   PDF_INV_PRODUCTS_TEXT_HEIGHT);  
    
	$pdf->MultiCell(25,6,$order->products[$i]['model'],1,'L', '1');
		
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(40);
	if (strlen($order->products[$i]['name']) > 40 && strlen($order->products[$i]['name']) < 50){
		$pdf->SetFont( PDF_INV_PRODUCTS_TEXT_FONT,
                       PDF_INV_PRODUCTS_TEXT_EFFECT,
                       PDF_INV_PRODUCTS_TEXT_HEIGHT);  
		$pdf->MultiCell(78,6,$order->products[$i]['name'],1,'L', '1');
		}
	else if (strlen($order->products[$i]['name']) > 50){
		$pdf->SetFont( PDF_INV_PRODUCTS_TEXT_FONT,
                       PDF_INV_PRODUCTS_TEXT_EFFECT,
                       PDF_INV_PRODUCTS_TEXT_HEIGHT);  

		$pdf->MultiCell(78,6,substr($order->products[$i]['name'],0,50),1,'L', '1');
	} else{
        $pdf->SetFont( PDF_INV_PRODUCTS_TEXT_FONT,
                       PDF_INV_PRODUCTS_TEXT_EFFECT,
                       PDF_INV_PRODUCTS_TEXT_HEIGHT);  
		
		$pdf->MultiCell(78,6,$order->products[$i]['name'],1,'L', '1');
    }
    
       
	//$pdf->SetFont('Arial','',10);
	//$pdf->SetY($Y_Table_Position);
	//$pdf->SetX(95);
	//$pdf->MultiCell(15,6,tep_display_tax_value($order->products[$i]['tax']) . '%',1,'C');

	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(118);
    //$pdf->SetFont('Arial','',10);
	$pdf->SetFont( PDF_INV_PRODUCTS_TEXT_FONT,
                   PDF_INV_PRODUCTS_TEXT_EFFECT,
                   PDF_INV_PRODUCTS_TEXT_HEIGHT);  

	$pdf->MultiCell(20,6,$currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']),1,'R', '1');
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(138);
	$pdf->MultiCell(20,6,$currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']),1,'R', '1');
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(158);
	$pdf->MultiCell(20,6,$currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']),1,'R', '1');
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(178);
	$pdf->MultiCell(20,6,$currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']),1,'R', '1');
	$Y_Table_Position += 6;	
	
	//get attribs 
    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
        for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
	      $pdf->SetY($Y_Table_Position);
	      $pdf->SetX(40);
	      
    	  if (strlen(" - " .$order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] )> 40 && strlen(" - " .$order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value']) < 50){
		
		    $pdf->SetFont( PDF_INV_PRODUCTS_TEXT_FONT,
                           PDF_INV_PRODUCTS_TEXT_EFFECT,
                           PDF_INV_PRODUCTS_TEXT_HEIGHT);  
		    $pdf->MultiCell(78,6," - " .$order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'],1,'L', '1');
		  }
	      else if (strlen(" - " .$order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value']) > 50){
            $pdf->SetFont( PDF_INV_PRODUCTS_TEXT_FONT,
                           PDF_INV_PRODUCTS_TEXT_EFFECT,
                           PDF_INV_PRODUCTS_TEXT_HEIGHT);  

		    $pdf->MultiCell(78,6,substr(" - " .$order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'],0,50),1,'L', '1');
	      } else {
            $pdf->SetFont( PDF_INV_PRODUCTS_TEXT_FONT,
                           PDF_INV_PRODUCTS_TEXT_EFFECT,
                           PDF_INV_PRODUCTS_TEXT_HEIGHT);  
		      
		    $pdf->MultiCell(78,6," - " .$order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'],1,'L', '1');
          }	      

          //$pdf->MultiCell(78,6," - " .$order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'],1,'L', '1');
          $Y_Table_Position += 6;

          //Check for product line overflow
          $item_count++;
	      if ( $item_count > 20 ) {
             $pdf->AddPage();
             //Fields Name position
             $Y_Fields_Name_position = 125;
             //Table position, under Fields Name
             $Y_Table_Position = 70;
             output_table_heading($Y_Table_Position-6);
             $item_count = 1;
             $text_color=explode(",",PDF_INV_PRODUCTS_TEXT_COLOR );
             $pdf->SetTextColor($text_color[0], $text_color[1], $text_color[2]); 
    
     
             $text_color=explode(",",PDF_INV_PRODUCTS_FILL_COLOR );
             $pdf->SetFillColor( $text_color[0], $text_color[1], $text_color[2] );  
    
             $text_color=explode(",",PDF_INV_PRODUCTS_LINE_COLOR );
             $pdf->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );    
          }         
        }
    }   

    //Check for product line overflow
    $item_count++;
//    if ((is_long($item_count / 32) && $i >= 20) || ($i == 20)){
	if ( $item_count > 20 ) {	
        $pdf->AddPage();
        //Fields Name position
        $Y_Fields_Name_position = 125;
        //Table position, under Fields Name
        $Y_Table_Position = 70;
        output_table_heading($Y_Table_Position-6);
        //if ($i == 20) 
        $item_count = 1;
        $text_color=explode(",",PDF_INV_PRODUCTS_TEXT_COLOR );
        $pdf->SetTextColor($text_color[0], $text_color[1], $text_color[2]); 
    
     
        $text_color=explode(",",PDF_INV_PRODUCTS_FILL_COLOR );
        $pdf->SetFillColor( $text_color[0], $text_color[1], $text_color[2] );  
    
        $text_color=explode(",",PDF_INV_PRODUCTS_LINE_COLOR );
        $pdf->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );    
    }
}

$pdf->SetFont( PDF_INV_ORDERTOTAL1_TEXT_FONT,
               PDF_INV_ORDERTOTAL1_TEXT_EFFECT,
               PDF_INV_ORDERTOTAL1_TEXT_HEIGHT);  

$text_color=explode(",",PDF_INV_ORDERTOTAL1_TEXT_COLOR );
$pdf->SetTextColor($text_color[0], $text_color[1], $text_color[2]);

$text_color=explode(",",PDF_INV_ORDERTOTAL1_FILL_COLOR );
$pdf->SetFillColor( $text_color[0], $text_color[1], $text_color[2] );  
    
$text_color=explode(",",PDF_INV_ORDERTOTAL1_LINE_COLOR );
$pdf->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );       
 
for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
	$pdf->SetY($Y_Table_Position + 5);
	$pdf->SetX(103);
	$temp = substr ($order->totals[$i]['text'],0 ,8);
	if ($temp == '<strong>') {
        $pdf->SetFont( PDF_INV_ORDERTOTAL2_TEXT_FONT,
                       PDF_INV_ORDERTOTAL2_TEXT_EFFECT,
                       PDF_INV_ORDERTOTAL2_TEXT_HEIGHT);  

        $text_color=explode(",",PDF_INV_ORDERTOTAL2_TEXT_COLOR );
        $pdf->SetTextColor($text_color[0], $text_color[1], $text_color[2]);

        $text_color=explode(",",PDF_INV_ORDERTOTAL2_FILL_COLOR );
        $pdf->SetFillColor( $text_color[0], $text_color[1], $text_color[2] );  
    
        $text_color=explode(",",PDF_INV_ORDERTOTAL2_LINE_COLOR );
        $pdf->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );   
		$temp2 = substr($order->totals[$i]['text'], 8);
		$order->totals[$i]['text'] = substr($temp2, 0, strlen($temp2)-9);
	}
	$pdf->MultiCell(94,6,$order->totals[$i]['title'] . ' ' . $order->totals[$i]['text'],0,'R', '1' );
	$Y_Table_Position += 5;

	//Check for product line overflow
    $item_count++;
	if ( $item_count > 20 ) {	
        $pdf->AddPage();
        //Fields Name position
        $Y_Fields_Name_position = 125;
        //Table position, under Fields Name
        $Y_Table_Position = 70;
        output_table_heading($Y_Table_Position-6);

        $item_count = 1;
        $text_color=explode(",",PDF_INV_ORDERTOTAL1_TEXT_COLOR );
        $pdf->SetTextColor($text_color[0], $text_color[1], $text_color[2]); 
    
     
        $text_color=explode(",",PDF_INV_ORDERTOTAL1_FILL_COLOR );
        $pdf->SetFillColor( $text_color[0], $text_color[1], $text_color[2] );  
    
        $text_color=explode(",",PDF_INV_ORDERTOTAL1_LINE_COLOR );
        $pdf->SetDrawColor( $text_color[0], $text_color[1], $text_color[2] );    
    }
}


     // set PDF metadata
     $pdf->SetTitle(PDF_META_TITLE);
//     $pdf->SetSubject(PDF_META_SUBJECT . $HTTP_GET_VARS['order_id']);
     $pdf->SetSubject(PDF_META_SUBJECT . $order_id);	 
     $pdf->SetAuthor(STORE_OWNER);
     
  	// PDF created

    function safe_filename ($filename) {
    $search = array(
    '/ß/',
    '/ä/','/Ä/',
    '/ö/','/Ö/',
    '/ü/','/Ü/',
    '([^[:alnum:]._])' 
    );
    $replace = array(
    'ss',
    'ae','Ae',
    'oe','Oe',
    'ue','Ue',
    '_'
    );
    
    // return a safe filename, lowercased and suffixed with invoice number.
    
    return strtolower(preg_replace($search,$replace,$filename));
}
    $file_name = safe_filename(STORE_NAME) ;
    $file_name .= "_invoice_" . $order_id . ".pdf";
    $mode = (FORCE_PDF_INVOICE_DOWNLOAD == 'true') ? 'D' : 'I';
    // what do we do? display inline or force download 
	    if($stream){
		  ob_end_clean(); 
	       return $pdf->Output( "", "S" );
    } else {
	  ob_end_clean();
	  $pdf->Output();
	}  
	
?>