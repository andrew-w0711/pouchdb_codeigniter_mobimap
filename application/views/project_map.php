<!DOCTYPE html>
<html>
<head>
	<title>MobiMap</title>
	<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<link rel="stylesheet" href="/assets/css/bootstrap.min.css" >
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

#poilist {
	position:absolute;
	left: 9px;
	top:55px;
	bottom:0;
	width:20%;
	overflow: scroll;
}
#map {
	position:absolute;
	top:55px;
	right: 0px;
	bottom:0px;
	width:80%;
}
</style>
</head>
<script src="/assets/js/jquery-1.11.3.min.js"></script>
<script src="/assets/js/bootstrap-3.3.5.min.js"></script>
<script src='/assets/js/mapbox.js'></script>
<script src='/assets/js/L.Control.Locate.min.js'></script>
<script src='/assets/js/leaflet.markercluster.js'></script>
<script src='/assets/js/bootstrap-switch.js'></script>
<script src="/assets/js/select2.min.js"></script>
<script src="/assets/js/bootbox.min.js"></script>
<script>

var markerLayers = [];
var map = '';
var activeLayerId = '';
var activeLayerName = '';
var layerCount = <?php echo count($layers); ?>;

function deletePOI(id,_leaflet_id) {
	$.ajax({
		type: "POST",
		url: "/manage/index.php/api/poi/delete/" + id,
		success: function() {
			map.eachLayer(function(layer) {
				//console.log(layer._leaflet_id);
				if (layer._leaflet_id == _leaflet_id) {
					map.removeLayer(layer);
					//alert('delete this one');
				}
			});
			bootbox.alert("Deleted.  May not disappear until reload.");
			//alert("Deleted. Will disappear on reload.");
		}
	});
}

function savePOI(id){
	//console.log(id);
	console.log($('#inputName' + id).val());
	$.ajax({
		type: "POST",
		url: "/manage/index.php/api/poi/update/" + id,
		data: {
			name: $('#inputName' + id).val()
		},
		success: function(data) {
			bootbox.alert("Saved");
		}
	});
};

$( document ).ready(function() {

	$.ajax({
		type: "GET",
		url: "/manage/index.php/api/project/get/",
		success: function(data) {
			//console.log(data);
			var layerList = $.parseJSON(data);
			$.each(layerList, function(i,d) {
				//console.log(i);
				$('#activeLayer').append('<option value="' + d.id + '">' + d.country + ' ' + d.state + ' ' + d.name + '</option');
			//	console.log(d);
			});
			$("#activeLayer").select2({
				placeholder: {
  				id: "-1",
  				text: "Select the layer to add to"
				}
			});

			<?php if ($layer) { ?>
				//console.log(layerList);
				$("#activeLayer").val("<?php echo $layer; ?>").trigger("change");
				$("#activeLayer").val("<?php echo $layer; ?>").trigger("select2:select");
				//console.log($("#activeLayer").select2("data")[0].text);
				activeLayerId = "<?php echo $layer; ?>";
				activeLayerName = $("#activeLayer").select2("data")[0].text;
				console.log("Working on project: <?php echo $layer; ?> " + $("#activeLayer").select2("data")[0].text);
				//console.log("Selecting: <?php echo $layer; ?>");
			<?php } ?>
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
	//map.locate();
	map.setView([40, -74.50], 4);

  	var layers = {
			Terrain: L.mapbox.tileLayer('dkwiebe.mj1hgfg0'),
			Satellite: L.mapbox.tileLayer('dkwiebe.n9e8c0ah'),
			Streets: L.mapbox.tileLayer('dkwiebe.n9e8mb4o')
  	};

  	layers.Streets.addTo(map);
  	L.control.layers(layers).addTo(map);
		<?php foreach ($layers as $key => $layer) { ?>
			prepLayer(<?php echo $key; ?>);
		<?php } ?>
		L.control.locate().addTo(map);

		// Script for adding marker on map click
		map.on('click', onMapClick);

function prepLayer(layerId)
{
	//console.log('Prepping' + layerId);
	$.ajax({
	    dataType: 'json',
	    url: '/manage/index.php/api/poi/project_geojson/' + layerId,
	    success: function(geojson) {
	    	$("#spanLabel" + layerId).text(geojson.name + ', ' + geojson.city + ', ' + geojson.state + ', ' + geojson.country);
				$("#badge" + layerId).text(geojson.features.length);
				if (geojson.features.length > 0) {
					//var markers = new L.MarkerClusterGroup();
					markerLayers[layerId] = L.mapbox.featureLayer(geojson).eachLayer(function(m) {
						//console.log(m);
						m.options.draggable =  true;
						m.bindPopup('<h2>' + geojson.name + ' ' + geojson.city + ' ' + geojson.state + ' ' +geojson.country + '</h2><h2>' + m.feature.properties.title + '<\/h2><p>' + m.feature.properties.description + '<\/p><button onclick="deletePOI('
							+ m.feature.properties.id +',' + m._leaflet_id + ')">Delete</button>')
					  	.on('dragend', function(m) {
								console.log(m);
								bootbox.confirm("Are you sure you want to move " + m.target.feature.properties.name
									+ " to new coordinates?", function(result) {
										console.log(result);
										if (result) {
											$.ajax({
													type: "POST",
													url: "/manage/index.php/api/poi/update/" + m.target.feature.properties.id,
													data: {
													lat: m.target._latlng.lat,
													lon: m.target._latlng.lng,
												}
											});
										} else {
											bootbox.alert("You need to refresh the display to get the marker to move back.");
											// Revert move
											//m.setLatLng(new L.LatLng(m.target.feature.geometry.coordinates[0], //m.target.feature.geometry.coordinates[1]),{draggable:'true'});
										}
  								//Example.show("Confirm result: "+result);
								});

					  });
					});
					markerLayers[layerId].addTo(map);
					if (layerCount == 1) {
						map.fitBounds(markerLayers[layerId].getBounds());
					}
					$("#switch" + layerId).on('switchChange.bootstrapSwitch', function(event, state) {
						  console.log(layerId);
							console.log(markerLayers[layerId]);
							if (state === false ) {
								map.removeLayer(markerLayers[layerId]);
							} else {
								markerLayers[layerId].addTo(map);
							}
					});
				}
				//var group = new L.featureGroup(markerLayers);
				//map.fitBounds(group.getBounds());
	    }
	  });
}

//function editPOILink(id) {
//	return '<a href="#" onClick="MyWindow=window.open(\'/index.php/map/poi_edit/edit/' + id + //'\',\'MyWindow\',\'width=500,height=800\'); return false;">Edit</a>';
//}

//function deletePOILink(id) {
//	return '<a href="#" onClick="MyWindow=window.open(\'/index.php/map/poi_edit/delete/' + id + //'\',\'MyWindow\',\'width=500,height=800\'); return false;">Delete</a>';
//}

function onMapClick(e) {
	if (activeLayerId.length > 0) {
	//console.log('Adding to layer: ' + $("#activeLayerId").text());
	$.ajax({
		type: "POST",
		url: "/manage/index.php/api/poi/create/" + activeLayerId,
		data: {
			lat: e.latlng.lat,
			lon: e.latlng.lng,
			name: "New POI"
		},
		success: function(data) {
      console.log(data);
      //console.log($.parseJSON(data));
			var geojsonFeature = {
					"type": "Feature",
					"properties": {
						"title": "",
						"description": "",
						"id": data.id
					},
					"geometry": {
									"type": "Point",
									"coordinates": [data.lat, data.lon]
					}
			}

			var marker;

			L.geoJson(geojsonFeature, {
					pointToLayer: function(feature, latlng){
							marker = L.marker(e.latlng, {
									title: "Resource Location",
									alt: "Resource Location",
									riseOnHover: true,
									draggable: true,
							});
							marker.bindPopup('<h2>New Marker<\/h2><input placeholder="Name" id="inputName' + data.id + '">'
								+ '<br><button onclick="savePOI(' + data.id + ')">Save</button>'
								+ '<br><button onclick="deletePOI('
								+ data.id +',' + marker._leaflet_id + ')">Delete</button><br>');
							marker.on('dragend', function(marker) {
								console.log(m);
								bootbox.confirm("Are you sure you want to move " + m.target.feature.properties.name
									+ " to new coordinates?", function(result) {
										console.log(result);
										if (result) {
											$.ajax({
													type: "POST",
													url: "/manage/index.php/api/poi/update/" + m.target.feature.properties.id,
													data: {
													lat: m.target._latlng.lat,
													lon: m.target._latlng.lng,
												}
											});
										} else {
											bootbox.alert("You need to refresh the display to get the marker to move back.");
											// Revert move
											//m.setLatLng(new L.LatLng(m.target.feature.geometry.coordinates[0], //m.target.feature.geometry.coordinates[1]),{draggable:'true'});
										}
								});

						  });
							marker.on("popupopen", onPopupOpen);
							return marker;
					}
			}).addTo(map);
			marker.openPopup();
		//Bind Save Edit
		$('#saveName' + data.id).on("click", function() {
			console.log(this);
		});

    }

	});
} else {
	bootbox.alert("You must select a project before adding items.", function() {  });
}
console.log(map);
}

function onPopupOpen()
{};



});

</script>

<body>
<?php echo $menu; ?>
<div id="poilist">
<div class="container-fluid" id="container">
	<div class="row">
  		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-danger">
						<p class="bg-danger">Please be aware some changes may require a screen refresh to appear.</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
  						<div class="panel-heading">
    						<h3 class="panel-title">Active Project</h3>
  						</div>
  						<div class="panel-body">
							<span id="activeLayerName"></span>
							<select class="form-control" id="activeLayer">
								<option value="" default selected>Select project to add items</option>
							</select>
  						</div>
					</div>
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
				<ul class="list-group" id="list<?php echo $key; ?>">
			<?php foreach ($layers as $key => $layer) { ?>
				<li class="list-group-item">
					<span id="spanLabel<?php echo $key; ?>"><?php echo $layer; ?></span><br> <span class="badge" id="badge<?php echo $key; ?>">0</span>
					<input data-size="mini" class="checkbox" type="checkbox" id="switch<?php echo $key;?>" name="my-checkbox" checked>
					<a class="btn btn-primary btn-sm" href="/manage/index.php/map/poi/<?php echo $key;?>" role="button">Spreadsheet View</a>
				</li>
			<?php } ?>
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
    $(".checkbox").bootstrapSwitch();
		$("#controlLayers").on('switchChange.bootstrapSwitch', function(event, state) {
			if (state) {
				$('input[name="my-checkbox"]').bootstrapSwitch('state',true,true);
				$.each(markerLayers, function(layer) {
					//console.log(layer);
					console.log(markerLayers[layer]);
					//TODO add support to add layers back to the map in bulk.
					//layer.addTo(map);
					//console.log(d);
					//markerLayers[layer].addTo(map);
				});

			} else {
				//console.log("hide layers");
				$('input[name="my-checkbox"]').bootstrapSwitch('state',false,true);
				map.eachLayer(function (layer) {
					map.removeLayer(layer);
				});
			}
});
$("#activeLayer").on('select2:select', function (e)
	{
		//console.log(e);
		//console.log($("#activeLayer").text());
		//console.log($("#activeLayer").val());
		//if (event.args) {
			//var item = event.args.item;

			//if (e.params.data.id) {
				activeLayerId = $("#activeLayer").select2("data")[0].id;
				activeLayerName = $("#activeLayer").select2("data")[0].text;
				console.log("Working on project: " + $("#activeLayer").select2("data")[0].id + "  " + $("#activeLayer").select2("data")[0].text);
				//populateEditGrid(e.params.data.id);
			//}
		//}*/
	}
);
</script>
</html>
