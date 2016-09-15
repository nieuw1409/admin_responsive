<?php
require(DIR_WS_LANGUAGES . $language . '/_banned.php');

$banned_ip = array();
 $banned_query = tep_db_query("select * from banned_ip where bannedip = '" . $_SERVER['REMOTE_ADDR'] . "'");
       while($banned_values = tep_db_fetch_array($banned_query)){
            $banned_ip[] = $banned_values['bannedip'];
      }

foreach($banned_ip as $banned) {
	$ip = $_SERVER['REMOTE_ADDR'];
	if($ip == $banned){
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo BANNED_YOUAREBANNED, ' @ '. TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php //require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">
            <center>
            <font color="#FF0000" size="10"><strong><?php echo BANNED_YOUAREBANNED; ?></strong></font>
            <br />
            <br />
            <?php echo tep_image(DIR_WS_IMAGES . '_banned/stopsign.gif', BANNED_YOUAREBANNED, BANNED_IMAGE_WIDTH, BANNED_IMAGE_HEIGHT); ?>
            <br />
            <br />
            <p><strong><?php echo BANNED_TEXT; ?></strong></p>
            </center>
            </td>
          </tr>
        </table></td>
      </tr>
        </table></td>
      </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
    </table></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php //require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php
require(DIR_WS_INCLUDES . 'application_bottom.php');
		exit();
	}
}
?>