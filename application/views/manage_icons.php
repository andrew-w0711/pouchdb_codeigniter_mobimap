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
  <div id="jqxGridEditIcon"></div>
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

      $.jqx.theme = "bootstrap";


    var source =
    {
    datatype: "json",
    datafields:[
        {name: 'id', type: 'int'},
        {name: 'name', type: 'string'},
        {name: 'description', type: 'string'}
      ],
    id: 'id',
    url: "/manage/index.php/api/icon/get/",
    root: 'data'
    };

    var dataAdapter = new $.jqx.dataAdapter(source);
    // initialize jqxGrid
    $("#jqxGridEditIcon").jqxGrid(
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
        {text: 'Description', datafield: 'description'},
        {text: 'ID', datafield: 'id', hidden: true},
      ]
      }
    );

    $("#jqxGridEditIcon").on('cellendedit', function (event)
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
          url: "/manage/index.php/api/icon/update/" + args.row.id,
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

  });
  </script>
</body>
</html>
