<?php
/*
www.u2commerce.com
v1.7 Re-inserted English instructions, Redesigned, bugfixed, full css-implementation, W3C-validated and made multilingual

remove__unused_images.php v1.3 by pyramids 11-6-2008
AZER: (This is an option to set n the code below)
commented out gif and png reports since it give a lot of infos on image type few people use for products photos
*/
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_REMOVE_IMAGES);
// azer modifid to read from configure.php : $root_dir = 'v:/easyphp/www/ms2fr/shop';
$root_dir = DIR_FS_CATALOG ;// look in this root

// azer modifid, to read from configure.php :  $base_dir = 'http://localhost/ms2fr/shop';
$base_dir = HTTP_SERVER . DIR_WS_CATALOG ; // for links

///////////////////////////////////////////////////////////////////////////////////
//                           ADDITIONAL OPTIONS AND SETTINGS BELOW
// if script timesout you may elect to turn off the displays for the list of images
// in the DB and the server, this will free up server resources,
// the lists are for infomational purposes only anyway.
//
///////////////////////////////////////////////////////////////////////////////////

//TURN OFF DB DISPLAY
$turn_off_db_display = 0;  // 0 means it is on, 1 means it is off

//TURN OFF SERVER DISPLAY
$turn_off_server_display = 0;  // 0 means it is on, 1 means it is off

// look in this images folder - do not add more folders here - just the main one
$images_dir = DIR_WS_IMAGES; // set to catalog images folder ie 'images';

// Check product descriptions for images (set to false to disable)
$descip_check = true;

// Remove images matching pattern from the check, ie put 'thumb' to exclude all images with 'thumb' as part of the name (or directory).
$pattern = '';

// Add links to product images with missing files, only applies to product images (not description) (set to true to enable)
$product_links = true;

// check sub-folders within the 'images' directory (will only go 'one deep') (set to true to enable)
$check_folders = true;

// exclude the following folders from the check, format must be EX: $exclude_folders = array("banners","default","icons","mail","infobox","pr","links");
// Compare with (if you have it): Select Product Image Directory & Instant Update - Multilanguage V1.15
// catalog/images subfolders to exclude from adding new images
// $exclude_folders = array( "UHtmlEmails","banners","default","icons","mail","buttons","infobox","js");

$exclude_folders = array("UHtmlEmails","banners","default","mail","icons");

// to add more tables use do it like so: $table_array = array("products_image","products_image_med");
// see below for possible image names
$table_array = array("products_image");

/* Additional possible image names
,"products_image_med","products_image_lrg","products_image_sm_1","products_image_xl_1","products_image_sm_2","products_image_xl_2","products_image_sm_3","products_image_xl_3","products_image_sm_4","products_image_xl_4","products_image_sm_5", "products_image_xl_5","products_image_sm_6","products_image_xl_6"
*/

// name of this script
$script_name = "remove_unused_images.php";

/* ADVANCED OPTION for SQL query -
DEFAULT SETTING: get all products even if status is off and there is no quantity
$optional_sql = ""

get image info if the product status is on or if the product has a qty greater than 0 example query:
$optional_sql = " where p.products_status = '1' or p.products_quantity >= '0'";

get image info if the product status is on only example query:
$optional_sql = " where p.products_status = '1'";
*/
$optional_sql = "";

// Additional tables to be checked ie TABLE_LINKS
$dbase_tables = array(TABLE_PRODUCTS_IMAGES, TABLE_PRODUCTS, TABLE_CATEGORIES, TABLE_MANUFACTURERS);
// Image Field names within above tables (THESE MUST MATCH) ie  'links_image_url'
$image_array = array('image','products_image','categories_image', 'manufacturers_image');

/////////////////////////////////////////////////////
//                                                 //
//            Do not edit below                    //
//                                                 //
/////////////////////////////////////////////////////

// AZERISH *****
//original:  AZER  removed the extra slash : $root_images_dir = $root_dir . '/' . $images_dir;// look in this main images folder
   $sess_id = (tep_not_null(SID));
   if (substr($images_dir, -1) != '/') $images_dir .= '/'; //add trailing slash to images dir if none..
   $exclude_folders[] = "UNUSED";

   $root_images_dir = $root_dir .  $images_dir;// look in this main images folder

if (!file_exists($root_images_dir)) die('<center><br><br><b>'.TEXT_LINE_96_1.' ('.$root_images_dir.') '.TEXT_LINE_96_2.'</b></center><br><br>');

// Read the database, then put all existing db images into an array called $full_image_list[]
$sql = "select ";

  $numb_tables = count($table_array);
  for ($i = 0; $i < $numb_tables; ++$i)
  {
    $sql .= ' p.' . $table_array[$i] . ($i == ($numb_tables-1) ? ' ' : ', ');
  }
if ($product_links) { $sql .= ', p.products_id '; $id_array = array(); }
$sql .= "from " . TABLE_PRODUCTS . " p ";
$sql .= $optional_sql;

if (require ('includes/configure.php')){}else{echo '<center><br><br>'.TEXT_LINE_110.'</center><br><br>';break;}// login info

$conn = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die("<center><br><br>".TEXT_LINE_112."</center><br><br>" . mysql_error());// connect to db

$select_db = mysql_select_db(DB_DATABASE, $conn) or die("<center><br><br>".TEXT_LINE_114."</center><br><br>" . mysql_error()); //select the right db

$image_info_query = mysql_query($sql, $conn) or die("<center><br><br>".TEXT_LINE_116."</center><br><br>" . mysql_error());

$numb_tables = count($table_array);
// put the images in an array

while ($image_info = mysql_fetch_array($image_info_query))
{

    for ($i = 0; $i < $numb_tables; ++$i)
    {

// ***azerish: *If only jpg format is used*  if (strpos($image_info[$table_array[$i]], 'jpg'))
if( strpos($image_info[$table_array[$i]], 'jpg') || strpos($image_info[$table_array[$i]], 'gif') || strpos($image_info[$table_array[$i]], 'png'))
{ $full_image_list[] = strip_tags($image_info[$table_array[$i]]);  //put all db images into 1 array
 if ($product_links) $id_array[$image_info[$table_array[$i]]] = $image_info['products_id'];	} // store product id for image
    }
}

// Place images from additional tables into the same array
    if (count($dbase_tables) != count($image_array)) die("<center><br><br>".TEXT_LINE_135."</center><br><br>");
      $r=count($dbase_tables);
      for ($i = 0; $i < $r; ++$i)
      {

$image_query = tep_db_query("select " . $image_array[$i] . " as image from " . $dbase_tables[$i]);
while ($image = tep_db_fetch_array($image_query)){
if (strpos($image['image'], 'jpg') || strpos($image['image'], 'gif') || strpos($image['image'], 'png'))
$full_image_list[] = strip_tags(str_replace($images_dir, '', $image['image']));
 }
}
// end reading the database for installed images

// get the server images/
  $files = array();
  GetImageListFromServer($root_images_dir,$files);

// get sub-directories of images directory (non-recursive), exlude any in exclude list

if ($check_folders) {

$dir_array = array();
  if (is_dir($root_images_dir)) {
    foreach(glob($root_images_dir) as $file)  {
      if ($file != "." && $file != "..") {
        if (is_dir($root_images_dir.$file) && !in_array($file,$exclude_folders)) $dir_array[] = preg_replace("/\/\//si", "/", $file);
      }
    }
    
  } else { die("<center><br><br>".TEXT_LINE_163."</center><br><br>");}

// get images in sub-directories
    foreach ($dir_array as $key => $value)
       GetImageListFromServer($root_images_dir.'/'.$value,$files);
    }

if (tep_not_null($pattern)) {

  sort($files);// server file list
  $z=count($files);
  for ($i = 0; $i < $z; ++$i) // remove any pattern matched images from the server list
  {
    if (strpos(strtolower($files[$i]),strtolower($pattern))) { unset($files[$i]); }
  }
  }

  $files = array_unique($files);  //remove duplicates
  sort($files);// server file list
 $z=count($files);
  for ($i = 0; $i < $z; ++$i)
    {
     $files[$i] = str_replace($root_images_dir . '/', "", $files[$i]);// remove the root part of the image name
    }

 if ($descip_check)  {
// check if any server images are used within product description & add to db list if so.
  $image_desc_query = mysql_query('select p.products_description as products_description from ' . TABLE_PRODUCTS_DESCRIPTION . ' p ', $conn) or die(TEXT_LINE_188 . mysql_error());

  while ($image_desc = mysql_fetch_array($image_desc_query))
   {
       $z=count($files);
      for ($i = 0; $i < $z; ++$i)
            {
            if (strpos($image_desc['products_description'], $files[$i])) $full_image_list[] = $files[$i];
            }
  }
  }

// check for images in specific files, ie header.php, index.php etc add to db list if so.
  $check1 = file_get_contents (DIR_FS_CATALOG.DIR_WS_INCLUDES . 'header.php');
  $check2 = file_get_contents (DIR_FS_CATALOG.FILENAME_DEFAULT);
  $check3 = file_get_contents (DIR_FS_CATALOG.'stylesheet.css');
  $check4 = file_get_contents (DIR_FS_CATALOG.DIR_WS_FUNCTIONS . 'html_output.php');
  $z=count($files);
  for ($i = 0; $i < $z; ++$i)
            {
            if (strpos($check1, $files[$i])) { $full_image_list[] = $files[$i]; continue; }
            if (strpos($check2, $files[$i])) { $full_image_list[] = $files[$i]; continue; }
            if (strpos($check3, $files[$i])) { $full_image_list[] = $files[$i]; continue; }
            if (strpos($check4, $files[$i])) $full_image_list[] = $files[$i];
            }

  $full_image_list = array_unique($full_image_list); //remove duplicates
  sort($full_image_list);
  $count_db_list = count($full_image_list);//number of images installed in the database
  $count_server_list = count($files);//number of files on the server

// start the html listing page
?>


</head>
<body onLoad="SetFocus();">
<!-- header //-->
<? require(DIR_WS_INCLUDES . 'template_top.php'); ?>
<!-- header_eof //-->
<script type="text/javascript"><!--
var formblock;
var forminputs;
function prepare() {
formblock= document.getElementById("unused_images");
forminputs = formblock.getElementsByTagName("input");
}
function select_all(name, value) {
for (i = 0; i < forminputs.length; i++) {
// regex here to check name attribute
var regex = new RegExp(name, "i");
if (regex.test(forminputs[i].getAttribute("name"))) {
if (value == "1") {
forminputs[i].checked = true;
} else {
forminputs[i].checked = false;
}}}}
if (window.addEventListener) {
window.addEventListener("load", prepare, false);
} else if (window.attachEvent) {
window.attachEvent("onload", prepare)
} else if (document.getElementById) {
window.onload = prepare;
}
//--></script>
<style>
.rm_pageHeading {
 font-family: Verdana, Arial, sans-serif; 
 font-size: 16px; 
 color: #727272; 
 font-weight: normal; 
 background-color: #D3D3D3;
 }
.rm_recheck { 
background-color: #fff7d6;
 font-family: Verdana, Arial, sans-serif; 
 font-size: 16px; 
 color: #727272; 
 font-weight: normal; 
 background-color: #D3D3D3;
}
.rm_278 {
font-family: Verdana, Arial, sans-serif; 
font-size: 12px; 
color: #000000; /*black*/
font-weight: normal;
text-decoration:none;
background-color: #EAF5F5;
}
.rm_302 {
font-family: Verdana, Arial, sans-serif; 
font-size: 14px; 
color: #ff0000; /*red*/
font-weight: normal;
text-decoration:none;
background-color: transparent;
}
.rm_318 {
font-family: Verdana, Arial, sans-serif; 
font-size: 12px; 
color: #008000; /*green*/
font-weight: normal;
text-decoration:none;
background-color: #ccffff;
}
.rm_359 {
font-family: Verdana, Arial, sans-serif; 
font-size: 12px; 
color: #0000ff; /*blue*/
font-weight: normal;
text-decoration:none;
background-color: #B4F4F4;
}
.rm_364 {
font-family: Verdana, Arial, sans-serif; 
font-size: 12px; 
font-weight: normal;
text-decoration:none;
}
.rm_moved {
font-family: Verdana, Arial, sans-serif; 
font-size: 12px; 
color: #00ff00; /*lime*/
font-weight: normal;
text-decoration:none;
background-color: #B4F4F4;
}
.rm_380 {
font-family: Verdana, Arial, sans-serif; 
font-size: 14px; 
color: #0000ff; /*blue*/
font-weight: normal;
text-decoration:underline;
background-color: transparent;
}
.rm_387 {
font-family: Verdana, Arial, sans-serif; 
font-size: 14px; 
color: #0000ff; /*blue*/
font-weight: normal;
text-decoration:none;
background-color: #B4F4F4;
}
.rm_388 {
font-family: Verdana, Arial, sans-serif; 
font-size: 12px; 
color: #0000ff; /*blue*/
font-weight: bold;
text-decoration:none;
background-color: #B4F4F4;
}
.rm_411 {
font-family: Verdana, Arial, sans-serif; 
font-size: 12px; 
color: #0000ff; /*blue*/
font-weight: normal;
text-decoration:none;
background-color: #B4F4F4;
}
.rm_416 {
font-family: Verdana, Arial, sans-serif; 
font-size: 14px; 
color: #ff0000; /*red*/
font-weight: normal;
text-decoration:none;
background-color: #B4F4F4;
}
.rm_417 {
font-family: Verdana, Arial, sans-serif; 
font-size: 14px; 
color: #000000; /*black*/
font-weight: normal;
text-decoration:none;
background-color: #00ff00;
}
.rm_428_1 {
font-family: Verdana, Arial, sans-serif; 
font-size: 12px; 
color: #ff0000; /*red*/
font-weight: normal;
text-decoration:none;
background-color: #D3D3D3;
}
.rm_428_2 {
font-family: Verdana, Arial, sans-serif; 
font-size: 12px; 
color: #000000; /*black*/
font-weight: normal;
text-decoration:none;
background-color: #D3D3D3;
}
.rm_429_1 {
font-family: Verdana, Arial, sans-serif; 
font-size: 14px; 
color: #ff0000; /*red*/
font-weight: normal;
text-decoration:none;
background-color: #D3D3D3;
}
.rm_429_2 {
font-family: Verdana, Arial, sans-serif; 
font-size: 12px; 
color: #000000; /*black*/
font-weight: normal;
text-decoration:none;
background-color: transparent;
}
.rm_429_3 {
font-family: Verdana, Arial, sans-serif; 
font-size: 12px; 
color: #ff0000; /*red*/
font-weight: normal;
text-decoration:none;
background-color: #D3D3D3;
}
</style>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
<tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">

    </table></td>
<!-- body_text //-->
    <td valign="top" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
 <td class="rm_pageHeading" colspan="2" align="center"><?php echo TEXT_LINE_270; ?></td>
 <td class="rm_recheck" style="margin-top:5px;" align="center"><form action='remove_unused_images.php' method='post'>&nbsp;<input type='submit' value="Recheck"></form></td>
     </tr>

<tr>

<?php
//**** AZERISH ajout d'un background bgcolor="#F5F5F5"
$msg = '<td width="33%" valign="top" class="rm_278">';
// ****************** dBase List **********************
$msg .= TEXT_LINE_280_1.$count_db_list.'</b>'.TEXT_LINE_280_2.'<i>' . $sql . '</i><br>' . ($descip_check ? TEXT_LINE_280_3 : '');

 if (count($dbase_tables)) {
  $l = false; $msg .= '<b>';
   foreach ($dbase_tables as $key => $value) {
   $msg .= ($l ? ', ' : '') . $value; $l=true;
   }
  $msg .= '</b>'.TEXT_LINE_285;
  }

for ($i = 0; $i < $count_db_list; ++$i)
{
  if ($turn_off_db_display == 0)
  {
  if (file_exists($root_images_dir.$full_image_list[$i]))
   {

$msg .= '<a target="_blank" href="' . $base_dir . $images_dir . $full_image_list[$i] . '">' . $full_image_list[$i] . '</a>' . "<br>";// print server images

     }else{
   $hold_red = 1;
   if($product_links && tep_not_null($id_array[$full_image_list[$i]])) {
$msg .= '<a style="color:Red; font-size:12px" target="_blank" href="' . tep_href_link('../product_info.php','products_id=' . $id_array[$full_image_list[$i]]) . '">' . $full_image_list[$i] . '</a>' . '<br>'; // print server images that are not in the server with links
} else {
$msg .= '<p class="rm_302">' . $full_image_list[$i] . '</p><br>';// print server images that are not in the server
      }
      }
  }
}

if ($turn_off_db_display == 1){
$msg .= TEXT_LINE_310;
}

$msg .= '<br>';

//************** CONVERT NEW FILES TO NORMAL FILENAMES ****************/
//**** AZERISH ajout d'un background bgcolor="#F5F5F5"
// ****************** Server List **********************

$msg .= '</td><td class="rm_318" width="33%" valign="top">'.TEXT_LINE_318 . $count_server_list . "</b><br><br>";

$msg .= TEXT_LINE_320.'<b>' . $root_images_dir . '</b><br>';

if (count($dir_array))
{
$msg .= TEXT_LINE_324.'<b>';
$l=false;
foreach ($dir_array as $key => $value) {
  $msg .= ($l ? ', ' : '') . $value; $l=true;
}

  $msg .= '</b><br />';
 }

$msg .= (tep_not_null($pattern) ? TEXT_LINE_331_1 . $pattern . TEXT_LINE_331_2 : '');

$msg .= '<br>';
if ($turn_off_server_display == 0)
    {
        for ($i = 0; $i < $count_server_list; ++$i)
        {

$msg .= '<a target="_blank" href="' . $base_dir . $images_dir . $files[$i] . '">' . $files[$i] . '</a>' . "<br>";// echo server images in folders

        }
    }

if ($turn_off_server_display == 1){
$msg .= TEXT_LINE_345;
}
$msg .= '';

$image_diff = array_diff($files, $full_image_list);//return the images that are on the server and not in the db
$count_diff_list = count($image_diff);//the number of missing files
sort($image_diff);

//**** AZERISH ajout d'un background bgcolor="#F5F5F5"
$msg .= '</td><td width="33%" valign="top" class="rm_359">';
// ****************** Unused List **********************
if ($_POST['action'] == 'rename') {}else{
$msg .= '<form id="unused_images" name="unused_images" action="' . tep_href_link($script_name) . '" method="post">';}

$msg .=  TEXT_LINE_359 . $count_diff_list . '</b><br><br>';

$hold_exist = ($count_server_list-$count_db_list);

if ($hold_exist < $count_diff_list ){
$msg .= TEXT_LINE_364_1.'<p class="rm_364" style="color:red;">'. ($count_diff_list-$hold_exist) .' </p><p class="rm_364" style="color:blue;">'.TEXT_LINE_364_2.'</p><p class="rm_364" style="color:red;">'.TEXT_LINE_364_3.'</p></b><br><br><div align="center">' . ($sess_id ? '' : '<a href="#end"><u>'.TEXT_LINE_364_4.'</u></a>') . '</div><br>';
}
$msg .= '<br>';

  if ($count_diff_list > 0)
  {
for ($i = 0; $i < $count_diff_list; ++$i)
  {
if($image_diff[$i]){
$msg .= '<a target="_blank" href="' . $base_dir . $images_dir . $image_diff[$i].'">'.$image_diff[$i].'</a> <input type="checkbox" name="checked_unused_images[]" value="'.$image_diff[$i].'"><br>';//unused images to rename checked
  }
  }
  }

$msg .= '';

if ($count_diff_list > 0) {
$msg .= "<a name=button></a><a name=end></a><br><div align=center ><a href=\"#button\" onClick=\"select_all('checked_unused_images', '1');\"><u class=rm_380 >".TEXT_LINE_381_1."</u></a>&nbsp;|&nbsp;<a href=\"#button\" onClick=\"select_all('checked_unused_images', '0');\"><u class=rm_380 >".TEXT_LINE_381_2."</u></a></div><br>";
}

//start section where we process the images to rename
if ($_POST['action'] == 'rename') {
if ($hold_change >=0){ //Do not print if nothing was moved
$msg_s = '<td><table class="rm_moved" width="100%" align="center"><tr><td align="center"><p class="rm_387">'.TEXT_LINE_387.'</p></td></tr></table></td></tr>
<tr><td><table width="100%" align="center"><tr><td valign="top" align="center"><p class="rm_388">'.TEXT_LINE_388.'</p>
<div class="main">';
 } // end  if ($hold_change !=0){
$hold_change = 0;
if(!is_dir($root_images_dir . 'UNUSED')) mkdir($root_images_dir . 'UNUSED');
//missing code line 318?
$checked_unused_images = $_POST['checked_unused_images'];

if ($count_diff_list > 0) {
  for ($i = 0; $i < $count_diff_list; ++$i)
  {
    if ($checked_unused_images[$i])
  {
      $hold_change = 1;

//if image is in a folder create folder in UNUSED
      $pos = strpos($checked_unused_images[$i], '/');
      if ($pos){$dir = substr($checked_unused_images[$i],0,$pos);if(!is_dir($root_images_dir . 'UNUSED/' . $dir)) mkdir($root_images_dir . 'UNUSED/' .$dir);}

      $rename_image_diff = 'UNUSED/' . $checked_unused_images[$i];

      if (rename($root_images_dir . $checked_unused_images[$i], $root_images_dir . $rename_image_diff)){
      $msg_s .= '<p class="rm_411" style="color:blue;">'.TEXT_LINE_410_1.'</p>' . $root_images_dir . $checked_unused_images[$i] . '<br><p class="rm_411" style="color:red;">'.TEXT_LINE_410_2.'</p>' . $root_images_dir . $rename_image_diff . '<br><br>';
      }else{$msg_s .= '<p class="rm_411" style="color:red;">'.TEXT_LINE_411_1.'</p>&nbsp;&nbsp;&nbsp;' . $checked_unused_images[$i] .TEXT_LINE_411_2.'<br />';}
    }
  }
}

if ($hold_change ==0){$msg_s .= '<br><br><center><p class="rm_416">'.TEXT_LINE_416.'</p></center>';
}else{$msg_s .= '<p class="rm_417">'.TEXT_LINE_417.'</p>';
}

$msg_s .= '</div></td></tr><tr><td><div align="center"><a href="' . tep_href_link('remove_unused_images.php') . '"><br><br><u>'.TEXT_LINE_420.'</u><br><br></a></div></td></tr></table>';

echo $msg_s;

}else{

if ($count_diff_list > 0) {
$msg .=  '<br><table width="100%" cellpadding="0"><tr><td>
<div align="center"><input type="submit" name="submit" value=" '.TEXT_LINE_427.' "><br><p class="rm_428_1">'.TEXT_LINE_428_1.'</p><p class="rm_428_2">'.TEXT_LINE_428_2.'</p></div>
<br><hr><center><p class="rm_429_1">'.TEXT_LINE_429_1.'</p></center><hr><p class="rm_429_2">'.TEXT_LINE_429_2.'</p><hr><center><p class="rm_429_3">'.TEXT_LINE_429_3.'</p></center><hr>
</td></tr></table>
<input type="hidden" name="action" value="rename"></form>';

} else {$msg .=  '<div align="center" style="color:#009900">'.TEXT_LINE_440.'</div>';}
$msg .=  '';
echo $msg;

}

function GetImageListFromServer($dir,&$files)
{
// ******** ACIDVERTIGO if exclude gif's and png's change: {jpg} { //in

  foreach(glob($dir."*.{gif,jpg,png}", GLOB_BRACE) as $file) 
  {
        $files[] = basename ($file);
    
  }

}
?>

<!-- body_text_eof //-->
</td></tr>
</table>

<!-- body_eof //-->
</table>
  <!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'template_bottom.php'); ?>
<!-- footer_eof //-->
