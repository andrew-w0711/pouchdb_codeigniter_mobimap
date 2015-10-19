<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Community Auth</title>
	<style>
		body{background:#fee;}
		#menu{float:left;width:100%;background:pink;}
		@media only screen and ( min-width:801px ){
			#menu{float:right;width:25%;}
		}
	</style>
</head>
<body>
<div id="menu">
	<ul>
		<li><?php
			if( isset( $auth_user_id ) ){
				echo secure_anchor('map/logout','Logout');
			}else{
				echo secure_anchor( 'LOGIN_PAGE' . '?redirect=map','Login');
			}
		?></li>
		<li>
			<?php echo secure_anchor('map/optional_login_test','Optional Login'); ?>
		</li>
		<li>
			<?php echo secure_anchor('map/simple_verification','Simple Verification'); ?>
		</li>
	</ul>
</div>

<?php

/* End of file page_header.php */
/* Location: /views/examples/page_header.php */
