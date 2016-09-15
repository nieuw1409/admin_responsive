<?php
/*
 * @copyright Copyright 2008 - http://www.e-imaginis.com
 * @copyright Portions Copyright 2003 osCommerce
 * @license GNU Public License V2.0
 * @version $Id:
*/

  class ht_cookie_law {
    var $code = 'ht_cookie_law';
    var $group = 'header_tags';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var	$pages = SYS_DISPLAY_ALL_PAGES ;

    function ht_cookie_law() {
      $this->title = MODULE_HEADER_TAGS_COOKIE_LAW_TITLE;
      $this->description = MODULE_HEADER_TAGS_COOKIE_LAW_DESCRIPTION;

      if ( defined('MODULE_HEADER_TAGS_COOKIE_LAW_STATUS') ) {
        $this->sort_order = MODULE_HEADER_TAGS_COOKIE_LAW_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_COOKIE_LAW_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate;


      if (MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_STATUS == 'True'  ) { //|| MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_UNIVERSAL_STATUS == 'True')  {
        if ( MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_ID != '' ) { // || MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_UNIVERSAL_STATUS !='' ) {


        $cookie_law = '
<script>
 gaProperty = \''.MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_ID.'\'

var disableStr = \'ga-disable-\' + gaProperty;

if (document.cookie.indexOf(\'hasConsent=false\') > -1) {
window[disableStr] = true;
}

function getCookieExpireDate() {
 var cookieTimeout = 34214400000;
 var date = new Date();
 date.setTime(date.getTime()+cookieTimeout);
 var expires = "; expires="+date.toGMTString();
 return expires;
}


function askConsent(){
    var bodytag = document.getElementsByTagName(\'body\')[0];
    var div = document.createElement(\'div\');
    div.setAttribute(\'id\',\'cookie-banner\');
    div.setAttribute(\'width\',\'70%\');
    // Le code HTML de la demande de consentement
    // Vous pouvez modifier le contenu ainsi que le style
    div.innerHTML =  \'<div class="cookie_law_eu">'.MODULE_HEADER_TAGS_COOKIE_LAW_TEXT.'.\
    <a href="javascript:gaOptout()">'.MODULE_HEADER_TAGS_COOKIE_LAW_TEXT_1.'</a>.</div>\';
    bodytag.insertBefore(div,bodytag.firstChild); // Ajoute la bannière juste au début de la page
    document.getElementsByTagName(\'body\')[0].className+=\' cookiebanner\';
}

// Retourne la chaine de caractère correspondant à nom=valeur
function getCookie(NomDuCookie)  {
    if (document.cookie.length > 0) {
        begin = document.cookie.indexOf(NomDuCookie+"=");
        if (begin != -1)  {
            begin += NomDuCookie.length+1;
            end = document.cookie.indexOf(";", begin);
            if (end == -1) end = document.cookie.length;
            return unescape(document.cookie.substring(begin, end));
        }
     }
    return null;
}


// Fonction d\'effacement des cookies
function delCookie(name )   {
  path = ";path=" + "/";
  domain = ";domain=" + "."+document.location.hostname;
  var expiration = "Thu, 01-Jan-1970 00:00:01 GMT";
  document.cookie = name + "=" + path + domain + ";expires=" + expiration;
}

// Efface tous les types de cookies utilisés par Google Analytics
function deleteAnalyticsCookies() {
    var cookieNames = ["__utma","__utmb","__utmc","__utmz","_ga"]
    for (var i=0; i<cookieNames.length; i++)
        delCookie(cookieNames[i])
}



// La fonction d\'opt-out
function gaOptout() {
  document.cookie = disableStr + \'=true;\'+ getCookieExpireDate() +\' ; path=/\';
  document.cookie = \'hasConsent=false;\'+ getCookieExpireDate() +\' ; path=/\';
  var div = document.getElementById(\'cookie-banner\');
// Ci dessous le code de la bannière affichée une fois que l utilisateur s est opposé au dépôt
// Vous pouvez modifier le contenu et le style
  if ( div!= null ) div.innerHTML = \'<div class="cookie_law_eu"> '.MODULE_HEADER_TAGS_COOKIE_LAW_OPPOSED.' </div>\'
    window[disableStr] = true;
    deleteAnalyticsCookies();
}



//Ce bout de code vérifie que le consentement n a pas déjà été obtenu avant d afficher
// la banniére
var consentCookie =  getCookie(\'hasConsent\');
if (!consentCookie) { // L utilisateur n a pas encore de cookie de consentement
 var referrer_host = document.referrer.split(\'/\')[2];
   if ( referrer_host != document.location.hostname ) { //si il vient d un autre site
   //on désactive le tracking et on affiche la demande de consentement
     window[disableStr] = true;
     window[disableStr] = true;
     window.onload = askConsent;
   } else { //sinon on lui dépose un cookie
          document.cookie = \'hasConsent=true; \'+ getCookieExpireDate() +\' ; path=/\';
        }
      }
</script>
';

//          $cookie_law .= '<div style="background-color:#ffffff; text-align:center; font-size:xx-small;"><a href="javascript:gaOptout()">'.MODULE_HEADER_TAGS_COOKIE_LAW_OPPOSED_1.'</a></div>';


      $oscTemplate->addBlock($cookie_law, $this->group);
/*
          $this->group = 'footer_scripts';
          $OSCOM_Template->addBlock($cookie_law, $this->group);
*/


        } // end MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_ID
      }// end MODULE_HEADER_TAGS_GOOGLE_ANALYTICS_STATUS
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_HEADER_TAGS_COOKIE_LAW_STATUS');
    }

    function install() {
	  GLOBAL $multi_stores_config ;		
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display a banner information about the EU law concerning  the cookie  ?', 'MODULE_HEADER_TAGS_COOKIE_LAW_STATUS', 'True', 'Display a banner at the top of the site to the customer that the site uses Google Analytics cookies.<br /><br /><strong>Note :</strong><br/>- Google analytics module must be installed<br />- Module according to Directive 2009/136/EC EU', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_COOKIE_LAW_SORT_ORDER', '35', 'Sort order of display. Lowest is displayed first.', '6', '2', now())");
      tep_db_query("update " . $multi_stores_config . " set configuration_value = '1' where configuration_key = 'WEBSITE_MODULE_INSTALLED'");
    }

    function remove() {
	  GLOBAL $multi_stores_config ;		
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_HEADER_TAGS_COOKIE_LAW_STATUS',
                   'MODULE_HEADER_TAGS_COOKIE_LAW_SORT_ORDER'
                  );
    }
  }
?>