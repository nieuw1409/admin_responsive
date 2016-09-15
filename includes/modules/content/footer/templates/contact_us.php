<div class="col-sm-<?php echo $content_width; ?>">
  <div class="footerbox contact">
    <h2><?php echo MODULE_CONTENT_FOOTER_CONTACT_US_HEADING_TITLE; ?></h2>
    <address>
      <strong><?php echo STORE_NAME; ?></strong><br>
      <?php echo nl2br(STORE_ADDRESS); ?><br>
      <abbr title="Phone"><?php echo TEXT_FOOTER_PHONE ; ?></abbr> <?php echo STORE_PHONE; ?><br>
      <abbr title="Email"><?php echo TEXT_FOOTER_EMAIL ; ?></abbr> <?php echo STORE_OWNER_EMAIL_ADDRESS; ?>
    </address>
    <ul class="list-unstyled">
      <li><a class="btn btn-success btn-sm btn-block" role="button" href="<?php echo tep_href_link(FILENAME_CONTACT_US); ?>"><i class="<?php echo glyphicon_icon_to_fontawesome( "home" ) ; ?>"></i> <?php echo MODULE_CONTENT_FOOTER_CONTACT_US_EMAIL_LINK; ?></a></li>
    </ul>
  </div>
</div>
