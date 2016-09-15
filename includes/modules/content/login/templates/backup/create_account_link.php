<div class="contentContainer <?php echo (MODULE_CONTENT_CREATE_ACCOUNT_LINK_CONTENT_WIDTH == 'Half') ? 'col-sm-6' : 'col-sm-12'; ?>">
  <h2><?php echo MODULE_CONTENT_LOGIN_HEADING_NEW_CUSTOMER; ?></h2>

  <div class="contentText">
    <p><?php echo MODULE_CONTENT_LOGIN_TEXT_NEW_CUSTOMER; ?></p>
    <p><?php echo MODULE_CONTENT_LOGIN_TEXT_NEW_CUSTOMER_INTRODUCTION; ?></p>

    <p align="right">
      <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'glyphicon-chevron-right', tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL')); ?></p>
  </div>
</div>
