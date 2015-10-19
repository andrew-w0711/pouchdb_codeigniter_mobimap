<!DOCTYPE html>
<html lang="en" >
<head>
	<title>MobiMap</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/font-awesome.min.css">
<link rel="stylesheet" href="/assets/css/leaflet.css" />
<link rel="stylesheet" href="/assets/css/ionicons.min.css" />

<script src="/assets/js/jquery-1.11.3.min.js"></script>
<script src="/assets/js/bootstrap-3.3.5.min.js"></script>
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

<?php
if( ! isset( $optional_login ) )
{
	echo '<h3>Login</h3>';
}

if( ! isset( $on_hold_message ) )
{
	if( isset( $login_error_mesg ) )
	{
		echo '
			<div class="alert alert-danger" role="alert">
				<p>
					Login Error: Invalid Username, Email Address, or Password.
				</p>
				<p>
					Username, email address and password are all case sensitive.
				</p>
			</div>
		';
	}

	if( $this->input->get('logout') )
	{
		echo '
			<div class="alert alert-success" role="alert">
				<p>
					You have successfully logged out.
				</p>
			</div>
		';
	}

	echo form_open( $login_url, array( 'class' => 'std-form' ) );
?>

	<div>
		<input placeholder="Username or Email" type="text" name="login_string" id="login_string" class="form-control" autocomplete="off" maxlength="50" />
		<br />
		<input placeholder="Password" type="password" name="login_pass" id="login_pass" class="form-control" autocomplete="off" maxlength="<?php echo config_item('max_chars_for_password'); ?>" />


		<?php
			if( config_item('allow_remember_me') )
			{
		?>

			<br />

			<label for="remember_me" class="form_label">Remember Me</label>
			<input class="form-control" type="checkbox" id="remember_me" name="remember_me" value="yes" />

		<?php
			}
		?>

		<p>
			<a href="<?php echo secure_site_url('map/recover'); ?>">
				Can't access your account?
			</a>
		</p>


		<input class="btn btn-primary" type="submit" name="submit" value="Login" id="submit_button"  />

	</div>
</form>

<?php

	}
	else
	{
		// EXCESSIVE LOGIN ATTEMPTS ERROR MESSAGE
		echo '
			<div class="alert alert-danger" role="alert">
				<p>
					Excessive Login Attempts
				</p>
				<p>
					You have exceeded the maximum number of failed login<br />
					attempts that this website will allow.
				<p>
				<p>
					Your access to login and account recovery has been blocked for ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes.
				</p>
				<p>
					Please use the ' . secure_anchor('map/recover','Account Recovery') . ' after ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes has passed,<br />
					or contact us if you require assistance gaining access to your account.
				</p>
			</div>
		';
	}

?>
		</div>
	</div
</div>
</body>
</html>
