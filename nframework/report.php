<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'include.php';
if($_POST['report']['description']!='' && $_POST['uid']==$_SESSION['last_uniqueid']){
	$report=[
		'env'=>$_ENV,
		'session'=>$_SESSION,
	    'description'=>$_POST['report']['descripcion'],
	    'timestamp'=>  date("Y-m-d H:i:s")
	];
    $m->nlared->reporttracker->save($report);
    echo "Reporte enviado";
}else{
	echo "Error al enviar reporte";
}?>
<a class="button" href="javascript:closeDialog('#dialogreport')">Cerrar</a>