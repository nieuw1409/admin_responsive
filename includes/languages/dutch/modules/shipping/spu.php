<?php
/*
  $Id: spu.php,v 1.3 2002/06/15 01:15:42 mbs Exp $
  CONTRIB is Store Pickup Shipping Module (http://www.oscommerce.com/community/contributions,164)
  Created by Michael Halvorsen
  http://www.arachnia-web.com

  Copyright (c) 2002 Arachnia-web Development.
  Released under the GNU General Public License.
  May be used and modified without permission.
*/

define('MODULE_SHIPPING_SPU_TEXT_TITLE', 'Afhalen Order in Winkel');
define('MODULE_SHIPPING_SPU_TEXT_DESCRIPTION', 'Afhalen in onze winkel. Tijdens KantoorUren ');
define('MODULE_SHIPPING_SPU_TEXT_WAY', 'U kunt uw order ophalen in Winkel Geen verzendkosten. Afhalen tijdens kantooruren' );

  define('TITLE_MODULE_SHIPPING_SPU_STATUS', _MODULES_ACTIVATE_STATUS . ' ' . MODULE_SHIPPING_SPU_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_SHIPPING_SPU_STATUS', _MODULES_ACTIVATE_STATUS_DESCRIPTION ) ;  
  
  define('TITLE_MODULE_SHIPPING_SPU_SORT_ORDER', _MODULES_SORT_ORDER . MODULE_SHIPPING_SPU_TEXT_TITLE ) ;
  define('DESCRIPTION_MODULE_SHIPPING_SPU_SORT_ORDER', _MODULES_SORT_ORDER_DESCRIPTION ) ;    
  
  define('TITLE_MODULE_SHIPPING_SPU_COST', 'Kosten Afhalen in Winkel' ) ; // Store Pickup Cost
  define('DESCRIPTION_MODULE_SHIPPING_SPU_COST', 'De Kosten voor het Afhalen in Winkel' ) ;    // What is the pickup cost? (The Handling fee will NOT be added.)
  
  define('TITLE_MODULE_SHIPPING_SPU_ZONE', 'Verzendigs Zone' ) ; // Shipping Zone
  define('DESCRIPTION_MODULE_SHIPPING_SPU_ZONE', 'Selecteer een zone, Verzenden is dan alleen geldig voor deze zone. <br />
                                                 Selecteerd u geen zone dan zal deze Verzend methode actief zijn in alle zones' ) ;    // If a zone is selected, only enable this payment method for that zone.

  define('TITLE_MODULE_SHIPPING_SPU_ZIP', 'Actief in Postcode' ) ; // Store Pick Up Zip Code Allowed
  define('DESCRIPTION_MODULE_SHIPPING_SPU_ZIP', 'De postcode waarin de module ' . MODULE_SHIPPING_SPU_TEXT_TITLE . ' actief is. <br />
                                                 Buiten deze postcode kan de klant de order niet afhalen in de winkel ') ;    // 	Enable zipcode selection for shop pickup

//      return array('MODULE_SHIPPING_SPU_STATUS', 'MODULE_SHIPPING_SPU_COST', 'MODULE_SHIPPING_SPU_SORT_ORDER', 'MODULE_SHIPPING_SPU_ZONE', 'MODULE_SHIPPING_SPU_ZIP');
	
?>
