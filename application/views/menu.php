<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">
        <img alt="MobiMap" src="/manage/assets/img/mobimap_header.png">
      </a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li><a href="/manage/index.php/map/index"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="/manage/index.php/map/view_project"><i class="fa fa-map"></i> Map</a></li>
        <li><a href="/manage/index.php/map/poi"><i class="fa fa-map-marker"></i> Marker</a></li>
        <li><a href="/manage/index.php/map/import_poi"><i class="fa fa-map-marker"></i> Import</a></li>
<?php if ($auth_level == 9) { ?>
        <li><a href="/manage/index.php/map/subscribers"><i class="fa fa-usd"></i> Subscribers</a></li>
        <li><a href="/manage/index.php/map/subscriptions"><i class="fa fa-usd"></i> Subscriptions</a></li>
        <li><a href="/manage/index.php/map/clients"><i class="fa fa-users"></i> Clients</a></li>
        <li><a href="/manage/index.php/map/users"><i class="fa fa-user"></i> Users</a></li>
<?php } ?>
		<li><a href="/manage/index.php/map/projects"><i class="fa fa-folder-open"></i> Projects</a></li>

		<?php if ($auth_level == 9) { ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-pencil"></i> Settings <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="/manage/index.php/map/icons"><i class="fa fa-camera-retro"></i> Icons</a></li>
	    <li><a href="/manage/index.php/map/packages"><i class="fa fa-gift"></i> Packages</a></li>
	    <li><a href="/manage/index.php/map/migrations"><i class="fa fa-wrench"></i> Upgrade Database Layout</a></li>
          </ul>
        </li>
		<?php } ?>
       <li><a href="/manage/index.php/map/logout"><i class="fa fa-sign-out"></i> Logout</a></li>
      </ul>
    </div><!--/.nav-collapse -->
  </div><!--/.container-fluid -->
</nav>
