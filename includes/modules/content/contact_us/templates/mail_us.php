<div class="col-sm-<?php echo $content_width; ?>">
<?php echo tep_draw_form('contact_us', tep_href_link(FILENAME_CONTACT_US, 'action=send'), 'post', 'class="form-horizontal"', true); ?>

<div class="contentContainer">
  <div class="contentText">
  
    <p class="inputRequirement text-right"><?php echo FORM_REQUIRED_INFORMATION; ?></p>
    <div class="clearfix"></div>

    <div class="form-group has-feedback">
      <label for="inputFromName" class="control-label col-xs-3"><?php echo ENTRY_NAME; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_input_field('name', isset($_SESSION['customer_first_name'])?$_SESSION['customer_first_name']:NULL, 'required autofocus="autofocus" aria-required="true" id="inputFromName" placeholder="' . ENTRY_NAME . '"');
        echo FORM_REQUIRED_INPUT;
        ?>
      </div>
    </div>
    <div class="form-group has-feedback">
      <label for="inputFromEmail" class="control-label col-xs-3"><?php echo ENTRY_EMAIL; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_input_field('email', isset($_SESSION['customer_email_ref'])?$_SESSION['customer_email_ref']:NULL, 'required aria-required="true" id="inputFromEmail" placeholder="' . ENTRY_EMAIL . '"');
        echo FORM_REQUIRED_INPUT;
        ?>
      </div>
    </div>
    <div class="form-group has-feedback">
      <label for="inputEnquiry" class="control-label col-xs-3"><?php echo ENTRY_ENQUIRY; ?></label>
      <div class="col-xs-9">
        <?php
        echo tep_draw_textarea_field('enquiry', 'soft', 50, 15, NULL, 'required aria-required="true" id="inputEnquiry" placeholder="' . ENTRY_ENQUIRY . '"');
        echo FORM_REQUIRED_INPUT;
        ?>
      </div>
    </div>
  </div>

  <div class="buttonSet">
    <span class="buttonAction"><?php echo tep_draw_button(IMAGE_SEND_EMAIL, 'send', null, 'primary'); ?></span>
  </div>
</div>

</form>
</div>