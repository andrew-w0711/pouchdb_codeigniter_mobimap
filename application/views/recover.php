 <!DOCTYPE html>
<html lang="en" >
<head>
  <title>MobiMap</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="/assets/css/font-awesome.min.css" />
<link rel="stylesheet" href="/assets/css/leaflet.css" />
<link rel="stylesheet" href="/assets/css/ionicons.min.css" />
<link rel="stylesheet" href="/assets/css/select2.min.css" />

<script src="/assets/js/jquery-1.11.3.min.js"></script>
<script src="/assets/js/bootstrap-3.3.5.min.js"></script>
<script src="/assets/js/select2.min.js"></script>
	<title>MobiMap</title>
	<style>
		/*body{background:#fee;}
		#menu{float:left;width:100%;background:pink;}
		@media only screen and ( min-width:801px ){
			#menu{float:right;width:25%;}
		}*/
	</style>
</head>
<body>
<div class="container-fluid">
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">
					<img alt="MobiMap" src="/assets/img/mobimap_header.png">
				</a>
				</div>
			</div>
		</nav>
	<div class="row">
  		<div class="col-md-4">
			<h3>Account Recovery</h3>
		</div>
	</div>
<?php
if( isset( $disabled ) )
{ ?>
		<div class="alert alert-danger" role="alert">
			<p>
				Account Recovery is Disabled.
			</p>
			<p>
				If you have exceeded the maximum login attempts, or exceeded
				the allowed number of password recovery attempts, account recovery
				will be disabled for a short period of time.
				Please wait ' . ( (int) config_item('seconds_on_hold') / 60 ) . '
				minutes, or contact us if you require assistance gaining access to your account.
			</p>
		</div>
<?php }
else if( isset( $user_banned ) )
{ ?>
		<div class="alert alert-danger" role="alert">
			<p>
				Account Locked.
			</p>
			<p>
				You have attempted to use the password recovery system using
				an email address that belongs to an account that has been
				purposely denied access to the authenticated areas of this website.
				If you feel this is an error, you may contact us
				to make an inquiry regarding the status of the account.
			</p>
		</div>
<?php }
else if( isset( $confirmation ) )
{  ?>
		<div class="alert alert-success" role="alert">
			<p>
				We have sent you an email with instructions on how
				to recover your account.
			</p>
		</div>
<?php }
else if( isset( $no_match ) )
{ ?>
		<div class="alert alert-danger" role="alert">
			<p class="feedback_header">
				Supplied email did not match any record.
			</p>
		</div>
<?php
	$show_form = 1;
} else { ?>
<div class="row">
	<div class="col-md-4">
		<p>
			If you've forgotten your password and/or username,
			enter the email address used for your account,
			and we will send you an e-mail
			with instructions on how to access your account.
		</p>
	</div>
</div>
<?php
	$show_form = 1;
}
if( isset( $show_form ) )
{ ?>

		 <?php echo form_open( '' ); ?>
			<div class="row">
				<div class="col-md-4">
					<legend>Enter your account's email address:</legend>
				</div>
			</div>
			<div class="row">
                <div class="col-md-2">
					<input placeholder="Email Address" type="text" name="user_email" value="" id="user_email" class="form-control" maxlength="255"  />
				</div>
  				<div class="col-md-2">
					<input type="submit" name="submit" value="Send Email" id="submit_button" class="btn btn-primary"  />
				</div>
			</div>
		</form>

	<?php } ?>

		</div>
	</div
</div>
</body>
</html>
