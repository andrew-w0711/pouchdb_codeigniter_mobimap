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
      <div id="divAddPackage" class="panel panel-default collapse">
  <div class="panel-heading">
    <h3 class="panel-title">Package  <i id="divAddPackageClose" class="fa fa-times-circle-o"></i></h3>
  </div>
  <div class="panel-body">
    <div class="row">
    </div>
    <div class="row">
      <div class="col-md-3">
      <input type="text" class="form-control" id="inputDescription" placeholder="Description">
    </div>
    <div class="col-md-3">
      <select class="form-control" id="selectClient">
          <option value="0" default selected>Select Client</option>
      </select>
    </div>
    <div class="col-md-2">
        <select class="form-control" name="inputStatus" id="selectStatus">
        <option value="" default selected>Status</option>
          <option value="1" >Active</option>
          <option value="0" >Inactive</option>
      </select>
    </div>
    <div class="col-md-2">
        <select class="form-control" name="inputLayers" id="selectLayers">
        <option value="" default selected>Layers Included</option>
          <option value="0" >All</option>
          <option value="1" >As Selected</option>
      </select>
    </div>
    <div class="col-md-2">
      <input id="buttonAdd" class="btn btn-primary" type="button" value="Add">
    </div>
    </div>
  </div>
</div>
</div>
</div>


<div class="row">
<div class="col-md-12">
  <div id="jqxGridEditPackage"></div>
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

      $.jqx.theme = "bootstrap";

  /*    $("#selectClient").select2({
    		theme: "bootstrap"
    	});*/
    	$("#selectLevel").select2({
    		theme: "bootstrap"
    	});
      $("#selectLayers").select2({
    		theme: "bootstrap"
    	});


    var source =
    {
    datatype: "json",
    datafields:[
        {name: 'id', type: 'int'},
        {name: 'clientid', type: 'int'},
        {name: 'clientname', type: 'string'},
        {name: 'status', type: 'int'},
        {name: 'description', type: 'string'},
        {name: 'layers', type: 'int'}
      ],
    id: 'id',
    url: "/manage/index.php/api/package/get/",
    root: 'data'
    };

    var dataAdapter = new $.jqx.dataAdapter(source);
    // initialize jqxGrid

    $("#jqxGridEditPackage").jqxGrid(
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
        {text: 'ClientID', datafield: 'clientid', width: '10%', displayfield: 'clientname', columntype: 'dropdownlist',
          createeditor: function (row, value, editor) {
            editor.jqxDropDownList({source: clientsDataAdapter, displayMember: 'name', valueMember: 'id'});
          }
        },
        {text: 'Description', datafield: 'description'},
        {text: 'Status', datafield: 'status'},
        {text: 'Layers', datafield: 'layers'},
        {text: 'ID', datafield: 'id', editable: false},
      ],showstatusbar: true,
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
                      $("#divAddPackage").collapse('show');
                    });
                    // delete selected row.
                    deleteButton.click(function (event) {
                        var selectedrowindex = $("#jqxGridEditPackage").jqxGrid('getselectedrowindex');
                        var rowscount = $("#jqxGridEditPackage").jqxGrid('getdatainformation').rowscount;
                        var id = $("#jqxGridEditPackage").jqxGrid('getrowid', selectedrowindex);
                        console.log(id);
                        $("#jqxGridEditPackage").jqxGrid('deleterow', id);
                        $.ajax(
                          {
                          type: "POST",
                          url: "/manage/index.php/api/package/delete/" + id,
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
                        $("#jqxGridEditPackage").jqxGrid({ source: dataAdapter });
                    });
                    //$('#selectClientBox').jqxDropDownList({source: clientsDataAdapter, displayMember: 'name', valueMember: 'id'});
                    //$('#selectProjectBox').jqxDropDownList({source: projectsDataAdapter, displayMember: 'name', valueMember: 'id'});
                },
      }
    );

    $("#jqxGridEditPackage").on('cellendedit', function (event)
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
          url: "/manage/index.php/api/package/update/" + args.row.id,
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


    $("#divAddPackageClose").click(function (event) {
      $("#divAddPackage").collapse('hide');
    });

    $("#buttonAdd").click(function (event) {
      console.log("Adding new Package");
      var data = {};

            /*var item = $("#selectClientBox").jqxDropDownList('getSelectedItem');
            //console.log("Item: " + item);
            if (item == null) {
              data['user_client'] = 0;
            } else {
              data['user_client'] = item.value;
            }

            var item = $("#selectProjectBox").jqxDropDownList('getSelectedItem');
            console.log("Item: " + item);
            if (item == null) {
              data['user_project'] = 0;
            } else {
              data['user_project'] = item.value;
            }     */
            //console.log($('#selectClient'));
      	  //console.log($('#selectProject'));
            data['description'] = $("#inputDescription").val();
      	  data['clientid'] = $("#selectClient").val();
      	  data['status'] = $("#selectStatus").val();
      	  data['layers'] = $("#selectLayers").val();
            $.ajax(
              {
              type: "POST",
              url: "/manage/index.php/api/package/create/",
              data: data,
              success: function() {
                bootbox.alert("Package Added!");
                $("#jqxGridEditPackage").jqxGrid({ source: dataAdapter });
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
