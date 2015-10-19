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
    <div class="col-md-4">
      <div id="divAddClient" class="panel panel-primary collapse">
  <div class="panel-heading">
    <h3 class="panel-title">Add Client  <i id="divAddClientClose" class="fa fa-times-circle-o"></i></h3>
  </div>
  <div class="panel-body">
    <div class="row">
    <div class="col-md-8">
      <input type="text" class="form-control" id="inputName" placeholder="Name">
    </div>
    <div class="col-md-4">
      <input id="buttonAdd" class="btn btn-primary" type="button" value="Add">
    </div>
    </div>
  </div>
</div>
</div>
</div>



<div class="row">
<div class="col-md-12">
  <div id="jqxGridEditClient"></div>
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


    var source =
    {
    datatype: "json",
    datafields:[
        {name: 'id', type: 'int'},
        {name: 'name', type: 'string'},
        {name: 'uuid', type: 'string'}
      ],
    id: 'id',
    url: "/manage/index.php/api/client/get/",
    root: 'data'
    };

    var dataAdapter = new $.jqx.dataAdapter(source);
    // initialize jqxGrid
    $("#jqxGridEditClient").jqxGrid(
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
        {text: 'Name', datafield: 'name'},
        {text: 'ID', datafield: 'id', hidden: true},
        {text: 'UUID', datafield: 'uuid', editable: false}
      ],
      showstatusbar: true,
      renderstatusbar: function (statusbar) {
                    // appends buttons to the status bar.
                    var container = $("<div style='overflow: hidden; position: relative; margin: 5px;'></div>");
                    var addButton = $("<div style='float: left; margin-left: 5px;'><i class='fa fa-plus'></i><span> Add</span></div>");
                    var deleteButton = $("<div style='float: left; margin-left: 5px;'><i class='fa fa-trash'></i><span> Delete</span></div>");
                    var reloadButton = $("<div style='float: left; margin-left: 5px;'><i class='fa fa-refresh'></i><span> Reload</span></div>");
                    //var searchButton = $("<div style='float: left; margin-left: 5px;'><i class='fa fa-search'></i><span> Find</span></div>");
                    container.append(addButton);
                    container.append(deleteButton);
                    container.append(reloadButton);
                    //container.append(searchButton);
                    statusbar.append(container);
                    addButton.jqxButton({  width: 60, height: 20 });
                    deleteButton.jqxButton({  width: 65, height: 20 });
                    reloadButton.jqxButton({  width: 65, height: 20 });
                    //searchButton.jqxButton({  width: 50, height: 20 });
                    // add new row.
                    addButton.click(function (event) {
                        $("#divAddClient").collapse('show');
                    });
                    // delete selected row.
                    deleteButton.click(function (event) {
                        var selectedrowindex = $("#jqxGridEditClient").jqxGrid('getselectedrowindex');
                        var rowscount = $("#jqxGridEditClient").jqxGrid('getdatainformation').rowscount;
                        var id = $("#jqxGridEditClient").jqxGrid('getrowid', selectedrowindex);
                        $("#jqxGridEditClient").jqxGrid('deleterow', id);
                    });
                    // reload grid data.
                    reloadButton.click(function (event) {
                        $("#jqxGridEditClient").jqxGrid({ source: dataAdapter });
                    });
                    // search for a record.
                    /*searchButton.click(function (event) {
                        var offset = $("#jqxGridEditClient").offset();
                        $("#jqxGridEditClient").jqxWindow('open');
                        $("#jqxGridEditClient").jqxWindow('move', offset.left + 30, offset.top + 30);
                    });*/
                },
      }
    );


  //  $("#findButton").jqxButton({ width: 70});
  //  $("#clearButton").jqxButton({ width: 70});
    $("#jqxGridEditClient").on('cellendedit', function (event)
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
          url: "/manage/index.php/api/client/update/" + args.row.id,
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

    $("#divAddClientClose").click(function (event) {
      $("#divAddClient").collapse('hide');
    });

    $("#buttonAdd").click(function (event) {
      console.log("Adding New Client");
      var data = {};
      data['name'] = $("#inputName").val();

      $.ajax(
        {
        type: "POST",
        url: "/manage/index.php/api/client/create/",
        data: data,
        success: function() {
          bootbox.alert("Client Added!");
          $("#jqxGridEditClient").jqxGrid({ source: dataAdapter });
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
