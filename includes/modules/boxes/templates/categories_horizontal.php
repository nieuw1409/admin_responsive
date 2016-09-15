<nav class="navbar <?php echo $inverse ; ?> navbar-no-corners navbar-no-margin" role="navigation">	  

  <div class="navbar-header"> 
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-navbar-cate_horizontal"> 
      <span class="sr-only"><?php echo HEADER_TOGGLE_NAV ; ?></span>
      <span class="icon-bar"></span> 
      <span class="icon-bar"></span> 
      <span class="icon-bar"></span> 
    </button> 
  </div> 
  <div class="collapse navbar-collapse" id="bs-navbar-cate_horizontal">
        <div class="container-fluid">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="<?php echo glyphicon_icon_to_fontawesome( "th-list" ) ; ?>"></i><span class="hidden-sm"> <?php echo TEXT_PRODUCTS_CATHOR ; ?></span><span class="caret"></span></a>
                 <?php echo build_hoz('dropdown-menu') ; ?>
            </li>
          </ul>
            <?php echo build_hoz() ; ?>
        </div>
   </div>	  
</nav>
<br />
<div class="clearfix"></div>