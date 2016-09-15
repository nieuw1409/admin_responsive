<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/
?>
          <br />   
    </section>

  </div>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>


<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/bootstrap/js/bootstrap.min.js', '', 'SSL'); ?>"></script>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/bootstrap-typeahead/bootstrap3-typeahead.min.js', '', 'SSL'); ?>"></script>

<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/bootstrap/js/bootstrap-datepicker.min.js', '', 'SSL'); ?>"></script> 
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/bootstrap-select/js/bootstrap-select-admin.min.js', '', 'SSL'); ?>"></script>

<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/flot/jquery.flot.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo tep_catalog_href_link('ext/flot/jquery.flot.time.min.js'); ?>"></script>
</div> <!-- container-fluid -->

<?php
  if (tep_session_is_registered('admin')) {
?>      
<script>
$(document).ready(function() {
	
    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {

        localStorage.setItem('activeTab', $(e.target).attr('href'));

    });

    var activeTab = localStorage.getItem('activeTab');

    if(activeTab){

        $('#tab_content_category a[href="' + activeTab + '"]').tab('show');

    }	


  $('.selectpicker').selectpicker({
    style: 'btn-default'
  });

  $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('selected');
  });

  $('.menu-open').parent('ul').addClass('in');

  $('[data-toggle=collapse]').click(function() {
    $(this).find('.click').toggleClass('clickopen clickclose');
  });
  
  $('.file-wrapper input[type=file]').bind('change focus click', fileinputbtn.fileInputs);
  

var fileinputbtn = fileinputbtn || {};
fileinputbtn.fileInputs = function() {
//SITE.fileInputs = function() {
var $this = $(this),
$val = $this.val(),
valArray = $val.split('\\'),
newVal = valArray[valArray.length-1],
$button = $this.siblings('.btn'),
$fakeFile = $this.siblings('.file-holder');
if(newVal !== '') { 
$button.text('<?php echo TEXT_BROWSE_DONE; ?>');
if($fakeFile.length === 0) {
$button.after('<br><span class="label label-info file-holder">' + newVal + '</span>');
} else {
$fakeFile.text(newVal);
}
}
};


  equalheight = function(container) {

    var currentTallest = 590,
      currentRowStart = 0,
      rowDivs = new Array(),
      $el,
      topPosition = 0;
    $(container).each(function() {

      $el = $(this);
      $($el).height('auto')
      topPostion = $el.position().top;

      if (currentRowStart != topPostion) {
        for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
          rowDivs[currentDiv].height(currentTallest);
        }
        rowDivs.length = 0; // empty the array
        currentRowStart = topPostion;
        currentTallest = $el.height();
        rowDivs.push($el);
      } else {
        rowDivs.push($el);
        currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
      }
      for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
        rowDivs[currentDiv].height(currentTallest);
      }
    });
  }

  $(window).load(function() {
    equalheight('.row-offcanvas .equal');
  });

  $(window).resize(function() {
    equalheight('.row-offcanvas .equal');
  });

  $(window).scroll(function() {
    equalheight('.row-offcanvas .equal');		
  });
  
$(function(){$("#quick_search").typeahead({items:15,source:function(e,r){$.ajax({url:"ext/modules/content/header/store_search/content_searches.php",type:"POST",data:"query="+e,dataType:"JSON",async:!0,success:function(e){var n=e.map(function(e){var r={icon:e.icon,href:e.href,name:e.title,price:e.price};return JSON.stringify(r)});return r(n)}})},matcher:function(){return!0},sorter:function(e){for(var r,n=[],t=[],i=[];link=e.shift();){var r=JSON.parse(link);r.name.toLowerCase().indexOf(this.query.toLowerCase())?~r.name.indexOf(this.query)?t.push(JSON.stringify(r)):i.push(JSON.stringify(r)):n.push(JSON.stringify(r))}return n.concat(t,i)},highlighter:function(e){var r=JSON.parse(e);return'<i class="fa fa-'+r.icon+'"></i> '+r.name+(r.price?" ("+r.price+")":"")},updater:function(e){var r=JSON.parse(e);window.location.href=r.href}})});  
    
});  
</script> 
<?php 
  } 
?>
</body>
</html>