<?php
if (!tep_session_is_registered('customer_id')) {
   if ((basename($PHP_SELF) != FILENAME_LOGIN) && (basename($PHP_SELF) != FILENAME_CREATE_ACCOUNT)) {
?>	   
	 <div class="panel panel-default panel-primary ">
<?php 
     if ( $title == True ) {
?>		 
	   <div class="panel-heading text-center"><?php echo MODULE_BOXES_LOGIN_BOX_TITLE ; ?></div> 
<?php
     }
?>	 
	 <div class="panel-body text-left">
		<?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL'), 'post', 'class="form-vertical" ', true) ;?>
        <div class="input-group">
		    <div class="col-xs-12">
<?php
                  echo tep_draw_input_field('email_address', '', 'required placeholder="' . MODULE_BOXES_LOGIN_BOX_EMAIL_ADDRESS . '"')  ;
                  echo tep_draw_input_field('password', '', 'required placeholder="' . MODULE_BOXES_LOGIN_BOX_PASSWORD . '"') ;
                  echo tep_draw_button(MODULE_BOXES_LOGIN_BOX_ACCOUNT_LOGIN, glyphicon_icon_to_fontawesome( "log-in" ), null, 'primary', '', '') ;
?>
                  <br /><br />
		          <a class="label label-info"    href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '"><?php echo MODULE_BOXES_LOGIN_BOX_PASSWORD_FORGOTTEN  ; ?></a><br /><br />
		          <a class="label label-success" href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '"><?php echo MODULE_BOXES_LOGIN_BOX_NEW_ACCOUNT             ; ?></a>
		     </div>
        </div>
        </form>
     </div>
	</div>
<?php	 
   }
} elseif (tep_session_is_registered('customer_id')) {
	if (MODULE_BOXES_LOGIN_ACCOUNT_LINKS == 'True') {
?>					
	    <div class="panel panel-default panel-primary">
<?php 
        if ( $title == True ) {
?>		
		    <div class="panel-heading"><?php echo MODULE_BOXES_LOGIN_BOX_ACCOUNT_TITLE ; ?></div>
<?php
        }
?>				
		<div class="panel-body text-left">
		  <ul class="list-unstyled">
			<?php echo ((MODULE_BOXES_LOGIN_GREETING == 'True') ? '  <strong>' . MODULE_BOXES_LOGIN_BOX_ACCOUNT_TEXT . $customer_first_name . '</strong><br /><hr>' : '') ; ?>
			
			<li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_EDIT,          '', 'SSL') ; ?>"><?php echo MODULE_BOXES_LOGIN_BOX_ACCOUNT_INFORMATION ; ?></a></li><br />
			<li><a href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK,          '', 'SSL') ; ?>"><?php echo MODULE_BOXES_LOGIN_BOX_ACCOUNT_ADDRESS_BOOK ; ?></a></li><br />
			<li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_PASSWORD,      '', 'SSL') ; ?>"><?php echo MODULE_BOXES_LOGIN_BOX_ACCOUNT_PASSWORD ; ?></a></li><br />
			<li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY,       '', 'SSL') ; ?>"><?php echo MODULE_BOXES_LOGIN_BOX_ACCOUNT_ORDERS_VIEW ; ?></a></li><br />
			<li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS,   '', 'SSL') ; ?>"><?php echo MODULE_BOXES_LOGIN_BOX_ACCOUNT_EMAIL_NOTIFICATIONS_NEWSLETTERS ; ?></a></li><br />
			<li><a href="<?php echo tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL') ; ?>"><?php echo MODULE_BOXES_LOGIN_BOX_ACCOUNT_EMAIL_NOTIFICATIONS_PRODUCTS ; ?></a></li><hr>
			<li><a href="<?php echo tep_href_link(FILENAME_LOGOFF,                '', 'SSL') ; ?>"><span class="' . glyphicon_icon_to_fontawesome( 'log-off' ) . '"></span><?php echo MODULE_BOXES_LOGIN_BOX_ACCOUNT_LOGOFF ; ?></a></li>
           </ul>			
        </div>
        </div>
<?php		
	}
}
?>