<?
class Webcam{
	function __construct($file){
		$_SESSION['nframework']['webcamfile']=$file;
	}
	function __toString(){
		return '<script type="text/javascript" src="//nlared.com/js/webcam/webcam.min.js"></script>
<script language="JavaScript">
function take_snapshot() {
    Webcam.snap(function(data_uri) {
    document.getElementById(\'results\').innerHTML = \'<img id="base64image" src="\'+data_uri+\'"/><button onclick="SaveSnap();">Save Snap</button>\';
});
}
function ShowCam(){
Webcam.set({
width: 320,
height: 240,
image_format: \'jpeg\',
jpeg_quality: 90
});
Webcam.attach(\'#my_camera\');
}
function SaveSnap(){
    document.getElementById("loading").innerHTML="Saving, please wait...";
    var file =  document.getElementById("base64image").src;
    var formdata = new FormData();
    formdata.append("base64image", file);
    var ajax = new XMLHttpRequest();
    ajax.addEventListener("load", function(event) { uploadcomplete(event);}, false);
    ajax.open("POST", "/nframework/uploadcam.php");
    ajax.send(formdata);
}
function uploadcomplete(event){
    document.getElementById("loading").innerHTML="";
    var image_return=event.target.responseText;
    var showup=document.getElementById("uploaded").src=image_return;
}
window.onload= ShowCam;
</script>
		<style type="text/css">
		.containerr{display:inline-block;width:320px;}
		#Cam{background:rgb(255,255,215);}#Prev{background:rgb(255,255,155);}#Saved{background:rgb(255,255,55);}
		</style>
		</head>
		<body>
		<div class="containerr" id="Cam"><b>Vista Previa...</b>
		    <div id="my_camera"></div><form><input type="button" value="Snap It" onClick="take_snapshot()"></form>
		</div>
		<div class="containerr" id="Prev">
		    <b>Snap Preview...</b><div id="results"></div>
		</div>
		<div class="containerr" id="Saved">
		    <b>Saved</b><span id="loading"></span><img id="uploaded" src=""/>
		</div>';
	}
}