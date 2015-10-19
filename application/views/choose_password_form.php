<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="utf-8">
	<title>MobiMap</title>
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
<h3>Account Recovery - Stage 2</h3>

<?php

$showform = 1;

if( isset( $validation_errors ) )
{
	echo '
		<div class="alert alert-danger" role="alert">
			<p>
				The following error occurred while changing your password:
			</p>
			<ul>
				' . $validation_errors . '
			</ul>
			<p>
				PASSWORD NOT UPDATED
			</p>
		</div>
	';
}
else
{
	$display_instructions = 1;
}

if( isset( $validation_passed ) )
{
	echo '
		<div style="border:1px solid green;">
			<p>
				You have successfully changed your password.
			</p>
			<p>
				You can now ' . secure_anchor(LOGIN_PAGE, 'login') . '.
			</p>
		</div>
	';

	$showform = 0;
}
if( isset( $recovery_error ) )
{
	echo '
		<div class="alert alert-danger" role="alert">
			<p>
				No usable data for account recovery.
			</p>
			<p>
				Account recovery links expire after
				' . ( (int) config_item('recovery_code_expiration') / ( 60 * 60 ) ) . '
				hours.<br />You will need to use the
				' . secure_anchor('map/recover','Account Recovery') . ' form
				to send yourself a new link.
			</p>
		</div>
	';

	$showform = 0;
}
if( isset( $disabled ) )
{
	echo '
		<div class="alert alert-danger" role="alert">
			<p>
				Account recovery is disabled.
			</p>
			<p>
				You have exceeded the maximum login attempts or exceeded the
				allowed number of password recovery attempts.
				Please wait ' . ( (int) config_item('seconds_on_hold') / 60 ) . '
				minutes, or contact us if you require assistance gaining access to your account.
			</p>
		</div>
	';

	$showform = 0;
}
if( $showform == 1 )
{
	if( isset( $recovery_code, $user_id ) )
	{
		if( isset( $display_instructions ) )
		{
			if( isset( $user_name ) )
			{
				echo '<p>
					Your login user name is <i>' . $user_name . '</i><br />
					Please write this down, and change your password now:
				</p>';
			}
			else
			{
				echo '<p>Please change your password now:</p>';
			}
		}

		?>
			<div id="form">
				<?php echo form_open( '' ); ?>
					<fieldset>
						<legend>Step 2 - Choose your new password</legend>
						<div>

							<?php
								// PASSWORD LABEL AND INPUT ********************************
								echo form_label('Password','user_pass',array('class'=>'form_label'));

								$input_data = array(
									'name'       => 'user_pass',
									'id'         => 'user_pass',
									'class'      => 'form_input password',
									'max_length' => config_item('max_chars_for_password')
								);
								echo form_password($input_data);
							?>

						</div>
						<div>

							<?php
								// CONFIRM PASSWORD LABEL AND INPUT ******************************
								echo form_label('Confirm Password','user_pass_confirm',array('class'=>'form_label'));

								$input_data = array(
									'name'       => 'user_pass_confirm',
									'id'         => 'user_pass_confirm',
									'class'      => 'form_input password',
									'max_length' => config_item('max_chars_for_password')
								);
								echo form_password($input_data);
							?>

						</div>
					</fieldset>
					<div>
						<div>

							<?php
								// RECOVERY CODE *****************************************************************
								echo form_hidden('recovery_code',$recovery_code);

								// USER ID *****************************************************************
								echo form_hidden('user_identification',$user_id);

								// SUBMIT BUTTON **************************************************************
								$input_data = array(
									'name'  => 'form_submit',
									'id'    => 'submit_button',
									'value' => 'Change Password'
								);
								echo form_submit($input_data);
							?>

						</div>
					</div>
				</form>
			</div>
		<?php
	}
}
/* End of file choose_password_form.php */
/* Location: /views/examples/choose_password_form.php */  ?>

		</div>
	</div
</div>
</body>
</html>
