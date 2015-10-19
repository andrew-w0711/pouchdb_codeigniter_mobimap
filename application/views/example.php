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
<link rel="stylesheet" href="/assets/css/select2.min.css" />
<?php
if (isset($css_files)) {
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach;
}
?>

<script src="/assets/js/jquery-1.11.3.min.js"></script>
<script src="/assets/js/bootstrap-3.3.5.min.js"></script>
<script src="/assets/js/select2.min.js"></script>
<?php
if (isset($js_files)) {
	foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach;
}
?>
<style type='text/css'>
body
{
	font-family: Arial;
	font-size: 14px;
  padding-top: 70px;
}

}
a {
    color: blue;
    text-decoration: none;
    font-size: 14px;
}
a:hover
{
	text-decoration: underline;
}
</style>
</head>
<body>
<?php echo $menu; ?>
<div class="container">
 <!-- /container -->

		<div>
		<?php echo $output; ?>
    </div>
		<div>
			Auth: <?php echo $auth_level; ?><br>
			Client: <?php echo $auth_client; ?><br>
			Project: <?php echo $auth_project; ?><br>
		</div>
</div>
</body>
</html>
