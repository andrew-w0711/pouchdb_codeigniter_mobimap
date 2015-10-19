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
  <div id="jqxGridEditSubscription"></div>
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

    var subscribersSource =
    {
    datatype: "json",
    datafields:[
        {name: 'id', type: 'int'},
        {name: 'firstname', type: 'string'},
        {name: 'lastname', type: 'string'},
        {name: 'email', type: 'string'},
        {name: 'public_subscriber_id', type: 'int'},
        {name: 'whmcs_client_id', type: 'int'},
        {name: 'clientid', type: 'int'},
        {name: 'client_name', type: 'string'},
        {name: 'uuid', type: 'string'},
      ],
    id: 'id',
    url: "/manage/index.php/api/subscriber/get/",
    root: 'data'
    };
    var subscribersDataAdapter = new $.jqx.dataAdapter(subscribersSource);

    var source =
    {
    datatype: "json",
    datafields:[
        {name: 'id', type: 'int'},
        {name: 'subscriber_id', type: 'int'},
        {name: 'subscriber_name', type: 'string'},
        {name: 'package_description', type: 'string'},
        {name: 'status', type: 'int'},
        {name: 'description', type: 'string'},
        {name: 'reg_date', type: 'date'},
        {name: 'exp_date', type: 'date'},
        {name: 'whmcs_client_id', type: 'int'},
        {name: 'whmcs_service_id', type: 'int'},
        {name: 'whmcs_package_id', type: 'int'},
        {name: 'clientid', type: 'int'},
        {name: 'client_name', type: 'string'},
        {name: 'public_subscriber_id', type: 'int'},
        {name: 'package', type: 'int'},
        {name: 'layers', type: 'int'}
      ],
    id: 'id',
    url: "/manage/index.php/api/subscription/get/",
    root: 'data'
    };

    var renderExportLink = function (row, column, value) {
                var html = value + ' <a target="_blank" href="/manage/index.php/map/export_gpi/' + value + '">XML</a>';
                return html;
            }

    var dataAdapter = new $.jqx.dataAdapter(source);


    // initialize jqxGrid
    //$("#jqxGridEditSubscription").jqxGrid(
    $("#jqxGridEditSubscription").jqxDataTable(
      {
      width: '100%',
      source: dataAdapter,
      pageable: true,
    //  autoheight: true,
    //  autoloadstate: true,
    //  autosavestate: true,
      sortable: true,
      altRows: true,
      columnsResize: true,
    //  altrows: true,
    //  enabletooltips: true,
      editable: false,
      //editmode: "dblclick",
  //    columnsresize: true,
      //columnsreorder: true,
  //    pagesizeoptions:['10', '20', '50', '100'],
      columns:[
        {text: 'id', datafield: 'id'},
        {text: 'Subscriber', datafield: 'subscriber_name'},
        {text: 'Status', datafield: 'status'},
        {text: 'Client', datafield: 'client_name' },
        {text: 'Package', datafield: 'package_description'},
        {text: 'Description', datafield: 'description'},
        {text: 'Reg Date', datafield: 'reg_date', cellsFormat: 'yyyy-mm-dd'},
        {text: 'Exp Date', datafield: 'exp_date', cellsFormat: 'yyyy-mm-dd'},
        {text: 'Client', columngroup: 'WHMCS', datafield: 'whmcs_client_id'},
        {text: 'Service', columngroup: 'WHMCS', datafield: 'whmcs_service_id'},
        {text: 'Package', columngroup: 'WHMCS', datafield: 'whmcs_package_id'},
        {text: 'Subscriber', datafield: 'public_subscriber_id', cellsrenderer: renderExportLink },
        {text: 'Layers', datafield: 'layers'}
      ],
      columnGroups:
      [
        { text: 'WHMCS', align: 'center', name: 'WHMCS' },        ]
      }
    );
/*
    $("#jqxGridEditSubscription").on('cellendedit', function (event)
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
          url: "/manage/index.php/api/subscriber/update/" + args.row.id,
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
    );*/

  });
  </script>
</body>
</html>
