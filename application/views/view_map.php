<!DOCTYPE html>
<html>
<head>
	<title>MobiMap Viewer</title>
<meta charset="utf-8" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="MobiMap">
<meta name="mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no minimal-ui" />

<link rel="stylesheet" href="/assets/css/bootstrap.min.css" >
<link rel="stylesheet" href="/assets/css/bootstrap-treenav.min.css" >
<link rel="stylesheet" href="/assets/css/font-awesome.min.css">
<link rel="stylesheet" href='/assets/css/mapbox.css' rel='stylesheet' />
<link rel="stylesheet" href='/assets/css/L.Control.Locate.mapbox.css' rel='stylesheet' />
<!--[if lt IE 9]>
<link href='/assets/css/L.Control.Locate.ie.css' rel='stylesheet' />
<![endif]-->
<link rel='stylesheet' href='/assets/css/font-awesome.min.css'  />

<link rel='stylesheet' href='/assets/css/MarkerCluster.css' />
<link rel='stylesheet' href='/assets/css/MarkerCluster.Default.css' />
<link rel='stylesheet' href='/assets/css/bootstrap-switch.css'  />
<link rel="stylesheet" href="/assets/css/select2.min.css" />

<style type='text/css'>
.scrollable-menu {
    height: auto;
    max-height: 95vh;
    overflow-x: hidden;
}

.scrollable-menu::-webkit-scrollbar {
    -webkit-appearance: none;
    width: 4px;
}
.scrollable-menu::-webkit-scrollbar-thumb {
    border-radius: 3px;
    background-color: lightgray;
    -webkit-box-shadow: 0 0 1px rgba(255,255,255,.75);
}

.dropdown-menu {
    width: 300px;
}

.list-group-item-narrow {
	padding-top: 3px;
	padding-right: 0px;
	padding-bottom: 3px;
}

/*#poilist {
	position:absolute;
	left: 0px;
	top:0px;
	bottom:0;
	width:20%;
	overflow: scroll;
}*/
#map {
	position:absolute;
	top:50px;
	right: 0px;
	bottom:0px;
	left:0px;
}
</style>
</head>
<script src="/assets/js/jquery-1.11.3.min.js"></script>
<script src="/assets/js/bootstrap-3.3.5.min.js"></script>
<script src="/assets/js/bootstrap-treenav.min.js"></script>
<script src='/assets/js/mapbox.js'></script>
<script src='/assets/js/L.Control.Locate.min.js'></script>
<script src='/assets/js/leaflet.markercluster.js'></script>
<script src='/assets/js/bootstrap-switch.js'></script>
<script src="/assets/js/select2.min.js"></script>
<script src="/assets/js/bootbox.min.js"></script>
<script src="/assets/js/js.cookie.js"></script>
<script src="/assets/bower_components/webcomponentsjs/webcomponents.min.js"></script>
<link rel="import" href="/assets/bower_components/flag-icon/flag-icon.html">
<script>

var markerLayers = [];
var map = '';
var activeLayerId = '';
var activeLayerName = '';

$( document ).ready(function() {
	//console.log(Cookies.get());
//Cookies.set('name', 'value'
	$.ajax({
		dataType: "json",
		url: "/manage/index.php/data/subscription/<?php echo $subscriber; ?>",
		success: function(data) {
			//console.log(data);
			//var layerList = $.parseJSON(data);
			$.each(data.layers, function(i,d) {
				//console.log(i);
				//console.log(d);
				checked = '';
				console.log(Cookies.get('layer' + d.layer_id));
				//console.log('layer' + d.layer_id);
				if (Cookies.get('layer' + d.layer_id) == 'on') {
					checked  = 'checked';
				}
				console.log($('#layerList1' + d.project_country) );
				if ($('#layerList1' + d.project_country).length == 0) {
						string = '<li class="dropdown">' +
						'<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" ' +
						' aria-expanded="false"><flag-icon key="'+ d.project_country +'" width="25"></flag-icon>  ' + d.project_country + '<span class="caret"></span></a>' +
						'<ul class="dropdown-menu scrollable-menu" id="layerList1' + d.project_country + '"></ul></li>';
					$('#layerList1').append(string);
				}
				if ($('#layerList1' + d.project_country + d.project_state).length == 0 ) {
					string = '<li><ul class="list-group list-group-collapse" id="layerList1' + d.project_country + d.project_state + '"><li class="list-group-item list-group-item-narrow active">' +
					'<flag-icon ';
					if ( d.project_country == 'CA') {
						string = string + ' ca=true ';
					} else if ( d.project_country == 'US') {
						string = string + ' us=true ';
					}
					string = string + 'key="' + d.project_state +'" width="25"></flag-icon>  ' + d.project_state + '</li></ul></li>';
					$('#layerList1' + d.project_country ).append(string);
				}
				string = '<li class="list-group-item list-group-item-narrow"><label class="checkbox-inline">' +
					'<input data-size="mini" class="checkbox" type="checkbox" id="switch' + d.layer_id + '" name="my-checkbox" ' + checked + '>' + d.project_name + '        <span class="badge text-right" id="badge' + d.layer_id + '">1</span></label>' +
				'</li>';
				$('#layerList1' + d.project_country + d.project_state).append(string);

				prepLayer(d.subscriber_id , d.layer_id);
			});
			//$(".checkbox").bootstrapSwitch();
/*			$("#controlLayers").on('switchChange.bootstrapSwitch', function(event, state) {
				if (state) {
					Cookies.set('layer' + layer, 'on', { expires: 365 });
					Cookies.set('name1', 'val1ue', { expires: 365 });
					console.log('Set: ' + 'layer' + layer);
					console.log(Cookies.get('layer' + layer));
					$('input[name="my-checkbox"]').bootstrapSwitch('state',true,true);
					$.each(markerLayers, function(layer) {
						//console.log(layer);
						//console.log(markerLayers[layer]);
						//TODO add support to add layers back to the map in bulk.
						//layer.addTo(map);
						//console.log(d);
						//markerLayers[layer].addTo(map);
					});

				} else {
					//console.log("hide layers");
					Cookies.set('layer' + layer, 'off', { expires: 365 });
					console.log(Cookies.get('layer' + layer));
					$('input[name="my-checkbox"]').bootstrapSwitch('state',false,true);
					map.eachLayer(function (layer) {
						map.removeLayer(layer);
					});
				}
	});*/
		}
	});


     function showCoordinates (e) {
	      alert(e.latlng);
      }

      function centerMap (e) {
	      map.panTo(e.latlng);
      }

      function zoomIn (e) {
	      map.zoomIn();
      }

      function zoomOut (e) {
	      map.zoomOut();
      }

	L.mapbox.accessToken = 'pk.eyJ1IjoiZGt3aWViZSIsImEiOiI2NWY3YzYyMzY3OWZiNzUwYjhiYTg4MjgzMzZjYzg1OCJ9.NX7gAZKWDncSA3g05bWPTg';
	map = L.mapbox.map('map', null);
	map.setView([40, -74.50], 3);

  	var layers = {
      		Terrain: L.mapbox.tileLayer('dkwiebe.mj1hgfg0'),
      		Satellite: L.mapbox.tileLayer('dkwiebe.n9e8c0ah'),
					Streets: L.mapbox.tileLayer('dkwiebe.n9e8mb4o')
  	};

  	layers.Streets.addTo(map);
  	L.control.layers(layers).addTo(map);
		L.control.locate().addTo(map);

		//var controlSearch = new L.Control.Search({layer: markersLayer, initial: false, position:'topright'});
		//map.addControl( controlSearch );

		// Script for adding marker on map click
		//map.on('click', onMapClick);

function prepLayer(subscriberId, layerId)
{
	//console.log('Prepping' + layerId);
	$.ajax({
	    dataType: 'json',
	    url: '/manage/index.php/data/project/' + subscriberId + '/' + layerId,
	    success: function(geojson) {
	    	$("#spanLabel" + layerId).text(geojson.name + ', ' + geojson.city);
				//console.log(geojson.features);
				if (geojson.features === undefined) {
					//console.log ('UNDEFINED LAYER');
				} else {
					$("#badge" + layerId).text(geojson.features.length);
					if (geojson.features.length > 0) {
					//var markers = new L.MarkerClusterGroup();
						markerLayers[layerId] = L.mapbox.featureLayer(geojson).eachLayer(function(m) {
							m.bindPopup('<h2>' + geojson.country + ' ' +geojson.state + ' ' + geojson.name + '</h2><h2>' + 	m.feature.properties.title + '<\/h2><p>' + m.feature.properties.description + '<\/p>');
						});
						console.log(Cookies.get('layer' + layerId));
						if (Cookies.get('layer' + layerId) == 'on') {
							markerLayers[layerId].addTo(map);
						}
						//markerLayers.setFilter(showState);
						//$("#switch" + layerId).change(function() {
							//console.log(this);
        			//if(this.checked) {
						//$("#switch" + layerId).on('switchChange.bootstrapSwitch', function(event, state) {
						//$("#switch" + layerId).on('switchChange.bootstrapSwitch', function(event, state) {
							$('#switch' + layerId).change(function() {
						  console.log(layerId);
							//console.log(markerLayers[layerId]);
							if ($('#switch' + layerId).is(":checked")) {
								Cookies.set('layer' + layerId, 'on');
								markerLayers[layerId].addTo(map);
							} else {
								Cookies.set('layer' + layerId, 'off');
								map.removeLayer(markerLayers[layerId]);
							}
						});
					}
				}
	    }
	  });
}

});

</script>

<body>
	<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">
        <img alt="MobiMap" src="/manage/assets/img/mobimap_header.png">
      </a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li><a href="#about" id="about">About</a></li>
				<li><form class="navbar-form navbar-left" role="search">
	<div class="form-group">
		<input id="searchInput" type="text" class="form-control" placeholder="Search">
	</div>
	<button id="searchButton" type="button" class="btn btn-default">Search</button>
</form></li>
			</ul>
			<ul class="nav navbar-nav navbar-right" id="layerList1">
				<li><a href="#">Layers</a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>


<div id="poilist">
<div class="container-fluid" id="container">
	<div class="row">
  		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Layer Visibility</h3>
					</div>
						<div class="panel-body">
							<input data-off-text="Hidden" data-on-text="Visible" data-size="normal" class="checkbox" type="checkbox" id="controlLayers" name="controlLayers" checked>
							<br>
							<ul class="list-group list-group-collapse" id="layerList">
							</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
<div id="map"></div>

</body>

<script>
	$("#about").click( function() {
		bootbox.alert('<ul><li><a href="https://mobimap.io/">Home</a></li><li><a href="/portal/contact.php">Contact</a></li></ul>');
	});

	$('.dropdown-menu').click(function(e) {
    e.stopPropagation();
});

$("#searchButton").click( function() {
	bootbox.alert("Search not yet implemented.");
});
</script>
</html>
