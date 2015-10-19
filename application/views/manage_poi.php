  <!DOCTYPE html>
<html>
<head>
  <title>MobiMap</title>
	<meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="/manage/assets/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="/manage/assets/css/font-awesome.min.css" type="text/css" />
<link rel="stylesheet" href="/manage/assets/css/leaflet.css" type="text/css" />
<link rel="stylesheet" href="/manage/assets/css/ionicons.min.css" type="text/css" />
<link rel="stylesheet" href="/manage/assets/jqwidgets/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="/manage/assets/jqwidgets/styles/jqx.bootstrap.css" type="text/css" />
<link rel="stylesheet" href="/manage/assets/css/select2.min.css" type="text/css" />
<link rel="stylesheet" href="/manage/assets/css/mobimap_ui.css" type="text/css" />

<style type='text/css'>
</style>
</head>
<body>
<?php echo $menu;?>
<div class="container-fluid">
		<div>
		Welcome <?php echo $auth_user_name;?>
    </div>
<div class="row">
    <div class="col-md-3">Select the project you wish to work with</div>
    <div class="col-md-3">
      <select id="selectProject">
        	<option value="" default selected>Select project to manage markers</option>
      </select>
    </div>
    <div class="col-md-3">
      <a class="btn btn-primary" id="buttonViewMap" href="#" role="button">View Map</a>
    </div>
</div>
<div class="row">
	<div class="col-md-1">
		<input type="button" value="Export to Excel" id='excelExport' />
	</div>
    <div class="col-md-1">
		<input type="button" value="Export to XML" id='xmlExport' />
	</div>
    <div class="col-md-1">
		<input type="button" value="Export to CSV" id='csvExport' />
    </div>
	<div class="col-md-1">
		<input type="button" value="Export to TSV" id='tsvExport' />
	</div>
    <div class="col-md-1">
		<input type="button" value="Export to HTML" id='htmlExport' />
	</div>
	<div class="col-md-1">
    	<input type="button" value="Export to JSON" id='jsonExport' />
    </div>
    <div class="col-md-1">
		<input type="button" value="Export to PDF" id='pdfExport' />
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div id="jqxGridEditPOI"></div>
	</div>
</div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/assets/js/jquery-1.11.3.min.js"></script>
    <script src="/assets/js/bootstrap-3.3.5.min.js"></script>
    <script type="text/javascript" src="/assets/jqwidgets/jqx-all.js"></script>
    <script src="/assets/js/select2.min.js"></script>
    <script src="/assets/js/bootbox.min.js"></script>
    <script type="text/javascript">

    var iconsSourceData = {
      datatype: "json",
      datafields:[
            {name: 'id', type: 'int'},
            {name: 'name', type: 'string'},
            {name: 'description', type: 'string'}
          ],
        id: 'id',
        url: "/manage/index.php/api/icon/get",
        root: 'data'
        };

    var iconsDataAdapter = new $.jqx.dataAdapter(iconsSourceData, { autoBind: true, async: true });
      //  iconsDataAdapter.dataBind();

    $(document).ready(function ()
    	{
        $.jqx.theme = "bootstrap";

        $.ajax({
          type: "GET",
          url: "/manage/index.php/api/project/get/",
          success: function(data) {
            //console.log(data);
            var layerList = $.parseJSON(data);
            $.each(layerList, function(i,d) {
              //console.log(i);
              $('#selectProject').append('<option value="' + d.id + '">' + d.country + ' ' + d.state + ' ' + d.name + '</option');
            //	console.log(d);
            });
            $("#selectProject").select2({
              placeholder: {
                id: "-1",
                text: "Select the layer to add to"
              }
            });
            // subscribe to the select event.
            $("#selectProject").on('select2:select', function (e)
              {
                //console.log();
                //console.log(e.params.data.id);
                //if (event.args) {
                  //var item = event.args.item;
                  //if (e.params.data.id) {
                    //console.log("Working on project: " + e.params.data.text);
                    //$("#buttonViewMap").prop('disabled', false);
                    $("#buttonViewMap").attr("href", "/manage/index.php/map/view_project/" + $("#selectProject").val());
                    populateEditGrid($("#selectProject").val());
                  //}
                //}*/
              }
            );
            <?php if ($layer) { ?>
              $("#selectProject").val("<?php echo $layer; ?>").trigger("change");
              $("#selectProject").val("<?php echo $layer; ?>").trigger("select2:select");

            <?php } ?>

          }
        });



    	}
    );

    function populateEditGrid(id) {
      /*var iconsData = [
               { id: 1, name: "building", description: "" },
               { id: 2, name: "circle", description: ""},
               { id: 5, name: "test", description: ""}
             ];
             */



    	// prepare the data
    	var source =
    	{
    	datatype: "json",
    	datafields:[
    			{name: 'id', type: 'int'},
    			{name: 'project', type: 'int'},
    			{name: 'name', type: 'string'},
    			{name: 'lat', type: 'string'},
    			{name: 'lon', type: 'string'},
				  {name: 'iconView', value: 'icon', values: { source: iconsDataAdapter.records, value: 'id', name: 'name' } },
    			{name: 'icon', type: 'int'},
    			{name: 'number', type: 'int'},
    			{name: 'address1', type: 'string'},
    			{name: 'address2', type: 'string'},
    			{name: 'city', type: 'string'},
    			{name: 'state', type: 'string'},
    			{name: 'zip', type: 'string'},
    			{name: 'phone1', type: 'string'},
    			{name: 'phone2', type: 'string'},
    			{name: 'fax', type: 'string'},
    			{name: 'uuid', type: 'string'},
          {name: 'visible', type: 'int'}
    		],
    	id: 'id',
    	url: "/manage/index.php/api/poi/project/" + id,
    	root: 'data'
    	};

    	var dataAdapter = new $.jqx.dataAdapter(source);
    	// initialize jqxGrid
    	$("#jqxGridEditPOI").jqxGrid(
    		{
    		width: '100%',
    		source: dataAdapter,
    		pageable: true,
    		autoheight: true,
    		autoloadstate: true,
    		autosavestate: true,
    		sortable: true,
    		altrows: true,
    		enabletooltips: true,
    		editable: true,
    		editmode: "dblclick",
    		columnsresize: true,
        columnsreorder: true,
    		pagesizeoptions:['10', '20', '50', '100'],
    		columns:[
    			{text: 'Name', datafield: 'name', width: 250, pinned: true},
				  {text: 'ID', datafield: 'id', hidden: true},
    			{text: 'Project', datafield: 'project', hidden: true},
    			{text: 'Latitude', datafield: 'lat' },
    			{text: 'Longitude', datafield: 'lon' },
    			{text: 'Icon', datafield: 'icon', cellsalign: 'right', displayfield: 'iconView', columntype: 'dropdownlist',
				      createeditor: function (row, value, editor) {
                editor.jqxDropDownList({ source: iconsDataAdapter, displayMember: 'name', valueMember: 'id' });
              }
				  },
    			//{text: 'Sort Order', datafield: 'number', hidden: true},
          //{text: 'iconView', datafield:'iconView'},
    			{text: 'Address 1', datafield: 'address1'},
    			{text: 'Address 2', datafield: 'address2'},
    			{text: 'City', datafield: 'city'},
    			{text: 'State', datafield: 'state'},
    			{text: 'Zip', datafield: 'zip'},
    			{text: 'Phone 1', datafield: 'phone1'},
    			{text: 'Phone 2', datafield: 'phone2'},
    			{text: 'Fax', datafield: 'fax'},
          {text: 'Visible', datafield: 'visible',columntype: 'checkbox' },
    		],showstatusbar: true,
        renderstatusbar: function (statusbar) {
                      // appends buttons to the status bar.
                      var container = $("<div style='overflow: hidden; position: relative; margin: 5px;'></div>");
                      //var addButton = $("<div style='float: left; margin-left: 5px;'><i class='fa fa-plus'></i><span> Add</span></div>");
                      var deleteButton = $("<div style='float: left; margin-left: 5px;'><i class='fa fa-trash'></i><span> Delete</span></div>");
                      var reloadButton = $("<div style='float: left; margin-left: 5px;'><i class='fa fa-refresh'></i><span> Reload</span></div>");
                      //container.append(addButton);
                      container.append(deleteButton);
                      container.append(reloadButton);
                      statusbar.append(container);
                      //addButton.jqxButton({  width: 60, height: 20 });
                      deleteButton.jqxButton({  width: 65, height: 20 });
                      reloadButton.jqxButton({  width: 65, height: 20 });
                      /*addButton.click(function (event) {
                        $("#divAddPackage").collapse('show');
                      });*/

                      // delete selected row.
                      deleteButton.click(function (event) {
                          var selectedrowindex = $("#jqxGridEditPOI").jqxGrid('getselectedrowindex');
                          var rowscount = $("#jqxGridEditPOI").jqxGrid('getdatainformation').rowscount;
                          var id = $("#jqxGridEditPOI").jqxGrid('getrowid', selectedrowindex);
                          console.log(id);
                          $("#jqxGridEditPOI").jqxGrid('deleterow', id);
                          $.ajax(
                            {
                            type: "POST",
                            url: "/manage/index.php/api/poi/delete/" + id,
                            //data: data,
                            success: function() {
                                //alert( "success" );
                              },
                            error: function() {
                                //alert( "Failed to Save!");
                              }//,
                              //dataType: dataType
                            }
                          );
                      });
                      // reload grid data.
                      reloadButton.click(function (event) {
                          $("#jqxGridEditPOI").jqxGrid({ source: dataAdapter });
                      });


                      },
    		}
    	);
      $("#jqxGridEditPOI").bind('bindingcomplete', function()
      {
        $("#jqxGridEditPOI").jqxGrid('sortby', 'name', 'asc');
      });
    	$("#excelExport").jqxButton();
    	$("#xmlExport").jqxButton();
    	$("#csvExport").jqxButton();
    	$("#tsvExport").jqxButton();
    	$("#htmlExport").jqxButton();
    	$("#jsonExport").jqxButton();
    	$("#pdfExport").jqxButton();
    	$("#excelExport").click(function ()
    		{
    			$("#jqxGridEditPOI").jqxGrid('exportdata', 'xls', 'jqxGrid');
    		}
    	);
    	$("#xmlExport").click(function ()
    		{
    			$("#jqxGridEditPOI").jqxGrid('exportdata', 'xml', 'jqxGrid');
    		}
    	);
    	$("#csvExport").click(function ()
    		{
    			$("#jqxGridEditPOI").jqxGrid('exportdata', 'csv', 'jqxGrid');
    		}
    	);
    	$("#tsvExport").click(function ()
    		{
    			$("#jqxGridEditPOI").jqxGrid('exportdata', 'tsv', 'jqxGrid');
    		}
    	);
    	$("#htmlExport").click(function ()
    		{
    			$("#jqxGridEditPOI").jqxGrid('exportdata', 'html', 'jqxGrid');
    		}
    	);
    	$("#jsonExport").click(function ()
    		{
    			$("#jqxGridEditPOI").jqxGrid('exportdata', 'json', 'jqxGrid');
    		}
    	);
    	$("#pdfExport").click(function ()
    		{
    			$("#jqxGridEditPOI").jqxGrid('exportdata', 'pdf', 'jqxGrid');
    		}
    	);

    	$("#jqxGridEditPOI").on('cellendedit', function (event)
    		{

    			var args = event.args;
            console.log($.type( args.value ));
    			var data = {};
          if ( $.type( args.value ) === "string" ){
            data[args.datafield] = args.value;
          } else if ( $.type( args.value ) === "boolean" ){
            data[args.datafield] = args.value;
          } else {
            data[args.datafield] = args.value.value;
          }
          //data[args.datafield] = args.value;
    			//if ( args.oldvalue.toString() != args.value.toString()) {
    			console.log("Saving: id: " + args.row.id + " Edited: " + args.datafield + " New Value: " + args.value);
    			$.ajax(
    				{
    				type: "POST",
    				url: "/manage/index.php/api/poi/update/" + args.row.id,
    				data: data,
    				success: function() {
    						//alert( "success" );
    					},
    				error: function() {
    						//alert( "Failed to Save!");
    					}//,
    					//dataType: dataType
    				}
    			);
    			console.log(args);
    		}
    	);
    }
    </script>
</body>
</html>
