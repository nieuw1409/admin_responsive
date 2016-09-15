<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/
  class alertBlock {    
	// class constructor
    function alertBlock($contents, $alert_output = false) {
	  $alertBox_string = '';
		  
      if ( sizeof($contents) > 2 ) {  
        $alertBox_string .= '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">' . PHP_EOL;
        $alertBox_string .= '  <div class="panel panel-warning">' . PHP_EOL;
        $alertBox_string .= '    <div class="panel-heading" role="tab" id="headingOne">' . PHP_EOL;
        $alertBox_string .= '      <h4 class="panel-warning">' . PHP_EOL;
        $alertBox_string .= '        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-controls="collapseOne">		' . PHP_EOL;
        $alertBox_string .=              ICON_ERROR . PHP_EOL;
        $alertBox_string .= '        </a>' . PHP_EOL;
        $alertBox_string .= '      </h4>' . PHP_EOL;
        $alertBox_string .= '    </div>	  ' . PHP_EOL;		
        $alertBox_string .= '    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">' . PHP_EOL;	
        $alertBox_string .= '      <div class="panel-body">		' . PHP_EOL;	
	  }
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $alertBox_string .= '          <div';
		  
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params']))
		  $alertBox_string .= ' ' . $contents[$i]['params'];
        
		  $alertBox_string .= '>' . PHP_EOL;
          $alertBox_string .= '            <button type="button" class="close" data-dismiss="alert">&times;</button>' . "\n";
          $alertBox_string .= '            ' . $contents[$i]['text'] . "\n";
    
          $alertBox_string .= '          </div>' . "\n";
      }
      if ( sizeof($contents) > 2 ) {  
        $alertBox_string .= '       </div>' . PHP_EOL;
        $alertBox_string .= '    </div>' . PHP_EOL;
        $alertBox_string .= ' </div>' . PHP_EOL;
        $alertBox_string .= '</div>		' . PHP_EOL;
	  }

      if ($alert_output == true) echo $alertBox_string;
        return $alertBox_string;
     }
  }
?>
