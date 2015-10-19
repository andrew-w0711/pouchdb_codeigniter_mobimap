<!DOCTYPE html>
<html>
<head>
  <title>MobiMap</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/font-awesome.min.css">
<link rel="stylesheet" href="/assets/css/leaflet.css" />
<link rel="stylesheet" href="/assets/css/ionicons.min.css" />
<link rel="stylesheet" href="/assets/jqwidgets/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="/assets/jqwidgets/styles/jqx.bootstrap.css" type="text/css" />
<link rel="stylesheet" href="/assets/css/select2.min.css" />
<link rel="stylesheet" href="/assets/css/select2-bootstrap.min.css" />
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
      <div id="divAddUser" class="panel panel-default collapse">
  <div class="panel-heading">
    <h3 class="panel-title">Add User  <i id="divAddUserClose" class="fa fa-times-circle-o"></i></h3>
  </div>
  <div class="panel-body">
    <div class="row">
    </div>
    <div class="row">
	    <div class="col-md-1">
      <input type="text" class="form-control" id="inputUser_Name_First" placeholder="First Name">
    </div>
	    <div class="col-md-1">
      <input type="text" class="form-control" id="inputUser_Name_Last" placeholder="Last Name">
    </div>
	    <div class="col-md-1">
      <input type="text" class="form-control" id="inputUser_Phone" placeholder="Telephone">
    </div>
    <div class="col-md-1">
      <input type="text" class="form-control" id="inputUser_Name" placeholder="Username">
    </div>
    <div class="col-md-2">
      <input type="email" class="form-control" id="inputUser_Email" placeholder="Email">
    </div>
    <div class="col-md-1">
      	<select class="form-control" name="inputLevel" id="inputUser_Level">
	  		<option value="" default selected>Access Level</option>
        	<option value="1" >User</option>
        	<option value="9" >Administrator</option>
    	</select>
    </div>
    <div class="col-md-2">
      <select class="form-control" id="selectClient">
        	<option value="0" default selected>Select Client</option>
      </select>
    </div>
    <div class="col-md-2">
    	<select class="form-control" id="selectProject">
        	<option value="0" default selected>All Projects</option>
    	</select>
    </div>
    <div class="col-md-1">
      <input id="buttonAdd" class="btn btn-primary" type="button" value="Add">
    </div>
    </div>
  </div>
</div>
</div>
</div>

  <div class="row">
    <div class="col-md-12">
      <div id="jqxGridEditUser"></div>
    </div>
  </div>



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
  $(document).ready(function ()
    {
      $.jqx.theme = "bootstrap";

	$("#inputUser_Level").select2({
		theme: "bootstrap"
	});
	$("#selectProject").select2({
		theme: "bootstrap"
	});

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
            // subscribe to the select event.
            $("#selectClient").on('select2:select', function (e)
              {
                console.log(e.params.data.id);
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
                text: "Select the Project"
              }
            });

          }
        });
                //if (event.args) {
                  //var item = event.args.item;
                //  if (e.params.data.id) {
                    //console.log("Working on project: " + e.params.data.text);
                //    populateEditGrid(e.params.data.id);
                //  }
                //}*/
              }
            );

          }
        });

      /*var generaterow = function (id) {
                      var row = {};
                      row["user_id"] = id;
                      row["user_name"] = '';
                      row["user_email"] = '';
                      row["user_level"] = '1';
                      row["user_banned"] = '0';
                      return row;
                  };*/

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
            {name: 'notes', type: 'string'},
			{name: 'description', type: 'string'},
			{name: 'description_sortable', type: 'string'}
          ],
        id: 'id',
        url: "/manage/index.php/api/project/get",
        root: 'data'
      };

      var projectsDataAdapter = new $.jqx.dataAdapter(projectsSource);

    var source =
    {
    datatype: "json",
    datafields:[
        {name: 'user_id', type: 'int'},
        {name: 'user_name', type: 'string'},
		{name: 'user_name_first', type: 'string'},
		{name: 'user_name_last', type: 'string'},
		{name: 'user_phone', type: 'string'},
        {name: 'user_email', type: 'string'},
        {name: 'user_level', type: 'int'},
        {name: 'user_banned', type: 'int'},
        {name: 'user_project', type: 'int'},
        {name: 'user_project_name', type: 'string'},
		{name: 'user_project_description_sortable', type: 'string'},
        {name: 'user_client', type: 'int'},
        {name: 'user_client_name', type: 'string'},
        {name: 'user_last_login', type: 'date'},
      ],
    id: 'user_id',
    url: "/manage/index.php/api/user/get/",
    root: 'data'
    };



    var dataAdapter = new $.jqx.dataAdapter(source);
    // initialize jqxGrid
    $("#jqxGridEditUser").jqxGrid(
      {
      width: '100%',
      source: dataAdapter,
      pageable: true,
      autoheight: true,
      //autoloadstate: true,
      //autosavestate: true,
      sortable: true,
      altrows: true,
      enabletooltips: true,
      editable: true,
      editmode: "dblclick",
      columnsresize: true,
      //columnsreorder: true,
      pagesizeoptions:['10', '20', '50', '100'],
      columns:[
	  	{text: 'ID', datafield: 'user_id', width: '5%', editable: false},
	  	{text: 'First Name', datafield: 'user_name_first', width: '10%'},
	  	{text: 'Last Name', datafield: 'user_name_last', width: '10%'},
		{text: 'Phone Number', datafield: 'user_phone', width: '10%'},
        {text: 'Username', datafield: 'user_name', width: '10%'},
        {text: 'Email', datafield: 'user_email', width: '10%'},
        {text: 'Level', datafield: 'user_level', width: '5%'},
        {text: 'Banned', datafield: 'user_banned', width: '5%'},
        {text: 'ClientID', datafield: 'user_client', width: '10%', displayfield: 'user_client_name', columntype: 'dropdownlist',
          createeditor: function (row, value, editor) {
            editor.jqxDropDownList({source: clientsDataAdapter, displayMember: 'name', valueMember: 'id'});
          }
        },
        {text: 'Project', datafield: 'user_project', width: '10%', displayfield: 'user_project_description_sortable', columntype: 'dropdownlist',
          createeditor: function (row, value, editor) {
            editor.jqxDropDownList({source: projectsDataAdapter, displayMember: 'description_sortable', valueMember: 'id'});
          }
        },
        {text: 'Last Login', datafield: 'user_last_login', width: '10%'},
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
                      $("#divAddUser").collapse('show');
                    });
                    // delete selected row.
                    deleteButton.click(function (event) {
                        var selectedrowindex = $("#jqxGridEditUser").jqxGrid('getselectedrowindex');
                        var rowscount = $("#jqxGridEditUser").jqxGrid('getdatainformation').rowscount;
                        var id = $("#jqxGridEditUser").jqxGrid('getrowid', selectedrowindex);
                        console.log(id);
                        $("#jqxGridEditUser").jqxGrid('deleterow', id);
                        $.ajax(
                          {
                          type: "POST",
                          url: "/manage/index.php/api/user/delete/" + id,
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
                        $("#jqxGridEditUser").jqxGrid({ source: dataAdapter });
                    });
                    //$('#selectClientBox').jqxDropDownList({source: clientsDataAdapter, displayMember: 'name', valueMember: 'id'});
                    //$('#selectProjectBox').jqxDropDownList({source: projectsDataAdapter, displayMember: 'name', valueMember: 'id'});
                },
      }
    );

    $("#jqxGridEditUser").on('cellendedit', function (event)
      {
        var args = event.args;
        var data = {};
        if ( $.type( args.value ) === "string" ){
          data[args.datafield] = args.value;
        } else {
          data[args.datafield] = args.value.value;
        }
        //if ( args.oldvalue.toString() != args.value.toString()) {
        console.log("Saving: id: " + args.row.user_id + " Edited: " + args.datafield + " New Value: " + args.value);
        $.ajax(
          {
          type: "POST",
          url: "/manage/index.php/api/user/update/" + args.row.user_id,
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

    $("#divAddUserClose").click(function (event) {
      $("#divAddUser").collapse('hide');
    });

    $("#buttonAdd").click(function (event) {
      console.log("Adding new User");
      var data = {};
      data['user_client'] = $('#selectClient').val();
      data['user_project'] = $('#selectProject').val();
      data['user_name'] = $("#inputUser_Name").val();
	    data['user_name_first'] = $("#inputUser_Name_First").val();
	    data['user_name_last'] = $("#inputUser_Name_Last").val();
	    data['user_phone'] = $("#inputUser_Phone").val();
      data['user_level'] = $("#inputUser_Level").val();
      data['user_banned'] = 0;
      data['user_email'] = $("#inputUser_Email").val();
      $.ajax(
        {
        type: "POST",
        url: "/manage/index.php/api/user/create/",
        data: data,
        success: function() {
          bootbox.alert("User Added!");
          $("#jqxGridEditUser").jqxGrid({ source: dataAdapter });
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
