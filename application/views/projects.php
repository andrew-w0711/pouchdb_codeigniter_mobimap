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

    <div class="row">
      <div class="col-md-12">
        <div id="divAddProject" class="panel panel-primary collapse">
          <div class="panel-heading">
            <h3 class="panel-title">Add Project  <i id="divAddProjectClose" class="fa fa-times-circle-o"></i></h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-2">
                <input type="text" class="form-control" id="inputName" placeholder="Name">
              </div>
              <div class="col-md-2">
                <select class="form-control" id="selectClient">
                  <option value="0" default selected>Select Client</option>
                </select>
              </div>
      <div class="col-md-2">
        <input type="text" class="form-control" id="inputCity" placeholder="City">
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" id="inputState" placeholder="State">
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" id="inputCountry" placeholder="Country">
      </div>

      <div class="col-md-2">
        <input id="buttonAdd" class="btn btn-primary" type="button" value="Add">
      </div>
      <div class="col-md-8">
        <input type="textarea" class="form-control" id="inputNotes" placeholder="Notes">
      </div>
      </div>
    </div>
  </div>
  </div>
  </div>

    <div class="row">
    <div class="col-md-12">
    <div id="jqxGridEditProject"></div>
	</div>
	</div>



	</div>
	    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="/assets/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap-3.3.5.min.js"></script>
    <script type="text/javascript" src="/assets/jqwidgets/jqx-all.js"></script>
    <script src="/assets/js/select2.min.js"></script>
    <script src="/assets/js/bootbox.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function ()
		{

      $.ajax({
              type: "GET",
              url: "/manage/index.php/api/client/get/",
              success: function(data) {
                //console.log(data);
                var layerList = $.parseJSON(data);
                $.each(layerList, function(i,d) {
                  //console.log(i);
                  $('#selectClient').append('<option value="' + d.id + '">' + d.name + '</option');
                //	console.log(d);
                });
                $("#selectClient").select2({
                  theme: "bootstrap",
                  placeholder: {
                    id: "-1",
                    text: "Select Client"
                  }
                });
              }
            });

      $.jqx.theme = "bootstrap";


      var clientsSource = {
        datatype: "json",
        datafields: [
          {name: 'id', type: 'int'},
          {name: 'name', type: 'string'}
        ],
      id: 'id',
      url: "/manage/index.php/api/client/get",
      root: 'data'
      };
      var clientsDataAdapter = new $.jqx.dataAdapter(clientsSource);

		var projectsUrl = "/manage/index.php/api/project/get";
    		var projectsSource = {
    		datatype: "json",
    		datafields:[
    				{name: 'id', type: 'int'},
    				{name: 'name', type: 'string'},
    				{name: 'clientid', type: 'int'},
            {name: 'clientname', type: 'string'},
    				{name: 'uuid', type: 'string'},
    				{name: 'city', type: 'string'},
    				{name: 'state', type: 'string'},
    				{name: 'country', type: 'string'},
    				{name: 'notes', type: 'string'}
    			],
    		id: 'id',
    		url: projectsUrl,
    		root: 'data'
    		};
    		var projectsDataAdapter = new $.jqx.dataAdapter(projectsSource);


    	// initialize jqxGrid
    	$("#jqxGridEditProject").jqxGrid(
    		{
    		width: '100%',
    		source: projectsDataAdapter,
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
    			{text: 'ClientID', datafield: 'clientid', displayfield: 'clientname', columntype: 'dropdownlist',
            createeditor: function (row, value, editor) {
              editor.jqxDropDownList({source: clientsDataAdapter, displayMember: 'name', valueMember: 'id'});
            }
          },
    			{text: 'UUID', datafield: 'uuid' },
    			{text: 'City', datafield: 'city' },
    			{text: 'State', datafield: 'state'},
    			{text: 'Country', datafield: 'country'},
    			{text: 'Notes', datafield: 'notes'}
    		],
        showstatusbar: true,
        renderstatusbar: function (statusbar) {
                      // appends buttons to the status bar.
                      var container = $("<div style='overflow: hidden; position: relative; margin: 5px;'></div>");
                      var addButton = $("<div style='float: left; margin-left: 5px;'><i class='fa fa-plus'></i><span> Add</span></div>");
                      var deleteButton = $("<div style='float: left; margin-left: 5px;'><i class='fa fa-trash'></i><span> Delete</span></div>");
                      var reloadButton = $("<div style='float: left; margin-left: 5px;'><i class='fa fa-refresh'></i><span> Reload</span></div>");
                      container.append(addButton);
                      container.append(deleteButton);
                      container.append(reloadButton);
                      statusbar.append(container);
                      addButton.jqxButton({  width: 60, height: 20 });
                      deleteButton.jqxButton({  width: 65, height: 20 });
                      reloadButton.jqxButton({  width: 65, height: 20 });
                      //searchButton.jqxButton({  width: 50, height: 20 });
                      // add new row.
                      addButton.click(function (event) {
                        $("#divAddProject").collapse('show');
                      });
                      // delete selected row.
                      deleteButton.click(function (event) {
                          var selectedrowindex = $("#jqxGridEditProject").jqxGrid('getselectedrowindex');
                          var rowscount = $("#jqxGridEditProject").jqxGrid('getdatainformation').rowscount;
                          var id = $("#jqxGridEditProject").jqxGrid('getrowid', selectedrowindex);
                          console.log(id);
                          $("#jqxGridEditProject").jqxGrid('deleterow', id);
                          $.ajax(
                            {
                            type: "POST",
                            url: "/manage/index.php/api/project/delete/" + id,
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
                          $("#jqxGridEditProject").jqxGrid({ source: dataAdapter });
                      });
                      //$('#selectClientBox').jqxDropDownList({source: clientsDataAdapter, displayMember: 'name', valueMember: 'id'});
                      //$('#selectProjectBox').jqxDropDownList({source: projectsDataAdapter, displayMember: 'name', valueMember: 'id'});
                  },
    		}
    	);

    	$("#jqxGridEditProject").on('cellendedit', function (event)
    		{
    			var args = event.args;
    			var data = {};
          if ( $.type( args.value ) === "string" ){
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
    				url: "/manage/index.php/api/project/update/" + args.row.id,
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
    			//}
    			//console.log("Event Type: cellendedit, Column: " + args.datafield + ", Row: " + (1 + args.rowindex) + ", Value: " + args.value);
    			console.log(args);
    		}
    	);

      $("#divAddProjectClose").click(function (event) {
        $("#divAddProject").collapse('hide');
      });

      $("#buttonAdd").click(function (event) {
        console.log("Adding new Project");
        var data = {};
        data['clientid'] = $('#selectClient').val();
        data['name'] = $("#inputName").val();
  	    data['city'] = $("#inputCity").val();
  	    data['state'] = $("#inputState").val();
  	    data['country'] = $("#inputCountry").val();
        data['notes'] = $("#inputNotes").val();
        $.ajax(
          {
          type: "POST",
          url: "/manage/index.php/api/project/create/",
          data: data,
          success: function() {
            bootbox.alert("Project Added!");
            $("#jqxGridEditProject").jqxGrid({ source: dataAdapter });
              //alert( "success" );
            },
          error: function() {
            bootbox.alert("Failed to Save!");
              //alert( "Failed to Save!");
            }//,
            //dataType: dataType
          }
        );
      });

    });
	    </script>
</body>
</html>
