  <div class="modal fade" id="popupModal<?php echo $popup_id ; ?>">  
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center"><?php echo $popup_title ; ?></h4>
        </div>
        <div class="modal-body">
          <center><h5><?php echo $popup_string; ?></h5></center>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary pull-right" data-dismiss="modal"> <?php echo IMAGE_BUTTON_CLOSE;?> <span class="' . <?php echo glyphicon_icon_to_fontawesome( "close" );?> . '"></span></button>
        </div>
      </div>
    </div>
  </div>
  
  <script type="text/javascript">
     $(window).load(function(){$('#popupModal<?php echo $popup_id ; ?>').modal('show');});
  </script>
