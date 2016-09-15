<nav class="navbar <?php echo $inverse ; ?> navbar-no-corners navbar-no-margin" role="navigation">
	  
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-navbar-collapse-1">
      <span class="sr-only"><?php echo HEADER_TOGGLE_NAV ; ?></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
  </div>	
  <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <?php 
          foreach ( $navigation_left as $left_nav_item ) {
            echo $left_nav_item;
          }
          ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <?php
          foreach ( $navigation_right as $right_nav_item ) {
            echo $right_nav_item;
          }
          ?>
       </ul>
	  
    </div> <!--// end container fluid -->
  </div>	<!--// end collapse navbar-collappse --> 
 
</nav>  <!--//end navbar header-->

<div class="clearfix"></div><?php echo PHP_EOL ;
  