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


<style type='text/css'>
</style>
</head>
<body>
<?php echo $menu;?>
<div class="container-fluid">
		<div>
		Welcome <?php echo $auth_user_name;?>
    </div>

			<?php if (isset($upload_error)) { ?>
			<div class="alert alert-danger" role="alert">
				<p><?php echo $upload_error['error'];?></p>
			</div>
		<?php } ?>
			<?php if (isset($upload_success)) { ?>
				<div class="alert alert-success" role="alert">
					<p><?php echo var_export($upload_success,true);?></p>
				</div>
			<?php } ?>
				<div class="panel panel-default">
  				<div class="panel-heading">
    				<h3 class="panel-title">Import Instructions</h3>
  				</div>
  				<div class="panel-body">
Points of interest uploads can ONLY be used to create new items, not to update items.  The file must be in CSV format with the first line containing column names.  The spelling and punctuation of the column names is of utmost importance and must match exactly.<br>
"name","lat","lon","icon","number","address1","address2","city","state","zip","phone1","phone2","fax"<br>
Only name, lat, lon are mandatory but there must not be any extra fields not included in this list.<br>
  				</div>
				</div>
				<div>
					<?php echo form_open_multipart('map/import_poi');?>
					<div class="row">
						<div class="col-md-3">
              <select name="projectid" id="projectid">
                  <option value="" default selected>Select layer to import POI</option>
              </select>
						</div>
  					<div class="col-md-4">
							<input class="form-control" type="file" name="poifile" size="20" />
						</div>
						<div class="col-md-2">
							<input class="btn btn-primary" type="submit" value="Upload POI" />
						</div>
					</div>
					</form>
				</div>



</div>
	    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/assets/js/jquery-1.11.3.min.js"></script>
    <script src="/assets/js/bootstrap-3.3.5.min.js"></script>
    <script type="text/javascript" src="/assets/jqwidgets/jqx-all.js"></script>
    <script src="/assets/js/select2.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function ()
    	{
        $.ajax({
          type: "GET",
          url: "/manage/index.php/api/project/get/",
          success: function(data) {
            //console.log(data);
            var layerList = $.parseJSON(data);
            $.each(layerList, function(i,d) {
              //console.log(i);
              $('#projectid').append('<option value="' + d.id + '">' + d.country + ' ' + d.state + ' ' + d.name + '</option');
            //	console.log(d);
            });
            $("#projectid").select2({
              placeholder: {
                id: "-1",
                text: "Select the layer to add to"
              }
            });
          }
        });

    });
	    </script>
</body>
</html>
