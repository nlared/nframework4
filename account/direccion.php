<?
$lat=($dataset->lat==''?'25.43228030':$dataset->lat);
$lng=($dataset->lng==''?'-101.00447970':$dataset->lng);

$_SESSION['ajax']['asentamientos']='
$result=[];
$ola=new MongoRegex("/".$data["query"]."/i");

if($data["estado"]==""|| $data["municipio"]==""){
	$result[]=[
		\'label\'=>"Seleccione estado y municipio",
		\'value\'=>"",
		\'nombre\'=>$dat["nombre"],
		\'tipo\'=>$dat["sett_type"]
	];
}else{
	foreach($m->geo->mapa->find([
		"st_name"=>$data["estado"],
		"mun_name" => $data["municipio"],
	 	"geografico" => "Colonia",
	 	"nombre"=>[
	 		\'$regex\'=>$ola
	 	]
		])->limit(10) as $dat){
		
		$result[]=[ 
			\'label\'=>"($dat[sett_type]) $dat[nombre]",
			\'value\'=>$dat["nombre"],
			\'nombre\'=>$dat["nombre"],
			\'tipo\'=>$dat["sett_type"]
		];
	}
}
';





?>
<div class="grid">
	<div class="row ">
        <div class="cell-md-6">
             <div class="row">
                <div class="cell"><?=$cp?></div>
                <div class="cell"><?=$estado?></div>                   
            </div>
            <div class="row">
                <div class="cell"><?=$municipio?></div>
                <div class="cell"><?=$asentamiento?></div>
            </div>
            <div class="row">
                <div class="cell cell2"><?=$vialidad1?></div>
                <div class="cell"><?=$noext?></div>
                <div class="cell "><?=$noint?></div>
            </div>
            <div class="row">
                <div class="cell"><?=$latitud?></div>
                <div class="cell"><?=$longitud?></div>                   
            </div>
        </div>
        <div class="cell-md-6 p-5">
        	<div id="map_canvas" class="full-size" style="height:250px">map div</div>
    	</div>
	</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script>
var inputestado;
var inputestado;
var inputmunicipio;
var inputasentamiento;
var inputtvialidad;
var inputcp;
var new_estado;
var new_municipio;
var new_asentamiento;
var new_vialidad;

function CoordMapType(tileSize) {
	this.tileSize = tileSize;
}
function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(setCurrentPosition);
  } else {
    alert("Geolocation is not supported by this browser.");
  }
}
function setCurrentPosition(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    var LatLng=new google.maps.LatLng(lat, lng);
    map.setCenter(LatLng);
    marker.setPosition(LatLng);
    updateMarkerPosition(LatLng);
}
   
var geocoder=new google.maps.Geocoder();
var map;
var marker;
var myLatlng = new  google.maps.LatLng(<?=$lat?>,<?=$lng?>);
var poligon //asentamiento o municipio


google.maps.Polygon.prototype.getBounds = function() {
    var bounds = new google.maps.LatLngBounds();
    var paths = this.getPaths();
    var path;        
    for (var i = 0; i < paths.getLength(); i++) {
        path = paths.getAt(i);
        for (var ii = 0; ii < path.getLength(); ii++) {
            bounds.extend(path.getAt(ii));
        }
    }
    return bounds;
}

function initialize() {
    var mapOptions = {
    zoom: 16,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.TERRAIN
    };    
    map = new google.maps.Map(document.getElementById('map_canvas'),mapOptions);
    var arrCoords = [];
    polygon = new google.maps.Polygon({
        paths: arrCoords,
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        map: map
    });
    polygon.setMap(map);
    marker= new google.maps.Marker({
            map:map,
            draggable:true,
            position:myLatlng
    });	
   
    google.maps.event.addListener(marker, 'dragend', function() {
        updateMarkerPosition(marker.getPosition());
    });
	inputpais=$("#pais");
    inputestado=$('#estado');
    inputmunicipio=$('#municipio');
    inputasentamiento=$('#asentamiento');
    inputvialidad=$('#vialidad');
    inputcp=$('#cp'); 
        //$('body').addClass('metro');	
}
$('#map_canvas').position({at:"center center"});

function updateMarkerPosition(latLng) {
    $("#data_lat").val(latLng.lat());
    $("#data_lng").val(latLng.lng());
    $.ajax({        
        url: "//www.nlared.com/services/geolocation.php",
        dataType:'json',
        data:{data_lat: latLng.lat(),data_lng:latLng.lng()}   
    }).done(function( data ) {
        new_estado= data.estado.nombre;
        new_municipio= data.municipio.nombre;
        new_asentamiento=data.asentamiento.nombre;
        new_vialidad=data.vialidad.nombre;
        
        
        inputcp.val(data.asentamiento.cp);
        var refreshasentamiento=false;
        if(inputestado.val()!==new_estado){
            inputestado.val(new_estado);
            $.ajax({
                url: "//www.nlared.com/services/municipios.php",
                data:{estado:new_estado},
                dataType:'json'
             }).done(function(datamunicipios) {
                inputmunicipio.val(new_municipio);                
            });
            inputasentamiento.val(new_asentamiento);
            refreshasentamiento=true;
        }else if (inputmunicipio.val()!==new_municipio){
            inputmunicipio.val(new_municipio);
            inputasentamiento.val(new_asentamiento);
            refreshasentamiento=true;           
        }else if(inputasentamiento.val()!==new_asentamiento){
            inputasentamiento.val(new_asentamiento);    
            refreshasentamiento=true;           
        }
        if(inputvialidad.val()!==new_vialidad){
            inputvialidad.val(new_vialidad);
        }        
        if(refreshasentamiento){
            $.ajax({
                url: "//www.nlared.com/services/coords.php",
                data:{
                	estado: inputestado.val().toUpperCase(),
                	municipio: data.municipio.nombre.toUpperCase(),
                	asentamiento:data.asentamiento.nombre
                },
                dataType:'json'
             }).done(function(data){
                var arrCoords = [];
                $.each(data.geometry.coordinates[0], function(key,value){ 
                    arrCoords.push({lat:parseFloat(value[1]),lng:parseFloat(value[0])});            
                });
               
                polygon.setPath(arrCoords);           
            });
        }
        
    });
}    


/*

$('#asentamiento').autocompleter({ 
 	source: '//nlared.com/services/asentamientos.php',
 	minLength:3,
 	data:{
 		Id:'asentamientos'
 	},
 	combine: function () {
        return {
        	estado:selectestado.find('option:selected').text().toUpperCase(),
            municipio: selectmunicipio.find('option:selected').text().toUpperCase()
        };
    },
 	callback: function (value, id,index) {
        console.log('Value ' + value + ' are selected (with index ' + index + ').');
	    $.ajax({
	        url: "//www.nlared.com/services/coords.php",
	        data:{
	        	estado: selectestado.find('option:selected').text().toUpperCase(),
	        	municipio:selectmunicipio.find('option:selected').text().toUpperCase(),
	        	asentamiento:index.nombre,
        		tipo:index.tipo
	        },
	        dataType:'json'
	     }).done(function(data){
	        var arrCoords = [];
	        $.each(data.geometry.coordinates[0], function(key,value){ 
	            arrCoords.push({lat:parseFloat(value[1]),lng:parseFloat(value[0])});            
	        });
	        polygon.setPath(arrCoords);
	        map.fitBounds(polygon.getBounds());
	        marker.setPosition(polygon.getBounds().getCenter());
	        latLng=polygon.getBounds().getCenter();
	        $("#data_lat").val(latLng.lat());
    		$("#data_lng").val(latLng.lng());
	        inputcp.val(data.postalcode);
	    });
    }
 });



$('#vialidad').autocompleter({ 
 	source: '//nlared.com/services/vialidades.php',
 	minLength:3,
 	data:{
 		Id:'vialidades'
 	},
 	combine: function () {
        return {
        	estado:selectestado.find('option:selected').text().toUpperCase(),
            municipio: selectmunicipio.find('option:selected').text().toUpperCase(),
            asentamiento:selectasentamiento.val()
        };
    }
});
*/



$('#cp').change(function(){
    $.ajax({
        url: "//www.nlared.com/services/cp.php",
        data:{cp:this.value},
        dataType:'json'
     }).done(function(data) {
        $('#estado').val(data.cve_ent);        
        var $select = $('#municipio');                        
        $select.find('option').remove();
        $('<option>').val('').text('Seleciona...').appendTo($select);
        $.each(data.municipios, function(key, value){              
            $('<option>').val(key).text(value).appendTo($select);     
        });        
        $select.val(data.cve_mun);        
        /*var $select2 = $('#asentamiento');                        
        $select2.find('option').remove();                
        $('<option>').val('').text('Seleciona...').appendTo($select2);        
        $.each(data.asentamientos, function(key, value) {              
            $('<option>').val(key).text(value).appendTo($select2);     
        });
        */
        if(Object.keys(data.asentamientos).length===1){
            $asentamientoselect.val(data.asentamientos[0][1]);
        }
         $.ajax({
            url: "//www.nlared.com/services/coords.php",
            data:{asentamiento:data.asentamiento},
            dataType:'json'
         }).done(function(datacoords){
            var arrCoords = [];
            $.each(datacoords.geometry.coordinates[0], function(key,value){ 
                arrCoords.push({lat:parseFloat(value[1]),lng:parseFloat(value[0])});            
            });
            polygon.setPath(arrCoords);
            map.fitBounds(polygon.getBounds());
            marker.setPosition(polygon.getBounds().getCenter());
        });
        
        
        var $select3 = $('#vialidad');                        
        $select3.find('option').remove();                
        $('<option>').val('').text('Seleciona...').appendTo($select3);        
        $.each(data.vialidades, function(key, value) {              
            $('<option>').val(key).text(value).appendTo($select3);     
        });
    });
});
initialize();
/*
$('#estado').autocompleter({ 
 	source: '//nlared.com/services/estados.php',
 	minLength:3,
 	data:{
 		Id:'asentamientos'
 	},
 	combine: function () {
        return {
        	pais:inputpais.val(),
        };
    }
 });
 
 
 $('#municipio').autocompleter({ 
 	source: '//nlared.com/services/municipios.php',
 	minLength:3,
 	data:{
 		Id:'asentamientos'
 	},
 	combine: function () {
        return {
        	pais:inputpais.val(),
        	estado:inputestado.val()
        };
    }
 });
*/
</script>
<style>
	.autocompleter-list {
	background-color:#ffffff; 
	z-index: 9999;
    position: absolute;
    list-style: none;
    margin: 0;
    padding: 0;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
</style>