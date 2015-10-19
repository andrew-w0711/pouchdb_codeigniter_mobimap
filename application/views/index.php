 <!DOCTYPE html>
<html>
<head>
  <title>MobiMap</title>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="/assets/css/font-awesome.min.css" />
<link rel="stylesheet" href="/assets/css/leaflet.css" />
<link rel="stylesheet" href="/assets/css/ionicons.min.css" />
<link rel="stylesheet" href="/assets/jqwidgets/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="/assets/jqwidgets/styles/jqx.bootstrap.css" type="text/css" />
<link rel="stylesheet" href="/assets/css/select2.min.css" />
<link rel="stylesheet" href="/assets/css/mobimap_ui.css" />

<script src="/assets/js/jquery-1.11.3.min.js"></script>
<script src="/assets/js/bootstrap-3.3.5.min.js"></script>
<script type="text/javascript" src="/assets/jqwidgets/jqx-all.js"></script>
<script src="/assets/js/select2.min.js"></script>
<style type='text/css'>
</style>
</head>
<body>
<?php echo $menu; ?>
<div class="container-fluid">
 <!-- /container -->
	<div>
		Welcome <?php echo $auth_user_name; ?>
    </div>
	<div class="row">
    	<div class="col-md-2">
			<div class="panel panel-default">
  				<div class="panel-heading">Icon Availability</div>
  				<div class="panel-body">
    	   			We use the Mapbox Maki icons found <a href="https://www.mapbox.com/maki/">here</a>.<br>
					Please contact support if you wish to use icons located in that set which we do not list.<br>
  				</div>
  			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
  				<div class="panel-heading">Icon List</div>
  				<div class="panel-body">
    	   			<br>
  				</div>
  			</div>
		</div>
	</div>
    <div class="row">
		<div class="col-md-1">
        	Auth: <?php echo $auth_level; ?><br>
			Client: <?php echo $auth_client; ?><br>
			Project: <?php echo $auth_project; ?><br>
		</div>
	</div>
</div>
</body>
</html>
