<?php

ini_set('display_errors', 'On');

$notificacion = $_REQUEST['Notificacion'];
/*
Ejemplo de notificacion
$str_RequestNotif = "<notificacion><tiponotificacion>12</tiponotificacion><operaciones>";
$str_RequestNotif = $str_RequestNotif . "<operacion><tipo>1</tipo><id>2320</id></operacion>";
$str_RequestNotif = $str_RequestNotif . "<operacion><tipo>1</tipo><id>434</id></operacion>";
$str_RequestNotif = $str_RequestNotif . "</operaciones></notificacion>";


$doc = new SimpleXMLElement($notificacion);
$tipo_notificacion = $doc -> tiponotificacion;
echo 'Tipo notificacion :'. $tipo_notificacion, '<br />';
foreach ($doc -> operaciones -> operacion as $operacion) 
{
   $tipo_operacion= $operacion -> tipo;
   $id_operacion= $operacion -> id;   
   echo 'tipo operacion :'. $tipo_operacion, '<br />';
   echo 'ID operacion :'. $id_operacion, '<br />';
}

*/
//*************************************************


$url = 'https://mexico.dineromail.com/Vender/Consulta_IPN.asp';
$data = 'DATA=<REPORTE><NROCTA>0836814</NROCTA><DETALLE><CONSULTA><CLAVE>multimed</CLAVE><TIPO>1</TIPO><OPERACIONES>';
$idop='530801e851ddb3719436';
$data.="<ID>$idop</ID>";
$data.='</OPERACIONES></CONSULTA></DETALLE></REPORTE>';
// parsea URL
$url = parse_url($url);
// obtiene host y path
$host = $url['host'];
$path = $url['path'];
// abre conexion en puerto 80
$fp = fsockopen('ssl://'.$host, 443, $errno, $errstr, 30);
// request

fputs($fp, "POST $path HTTP/1.1\r\n");
fputs($fp, "Host: $host\r\n");
fputs($fp, "Referer: $referer\r\n");
fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
fputs($fp, "Content-length: ". strlen($data) ."\r\n");
fputs($fp, "Connection: close\r\n\r\n");
fputs($fp, $data);

$result = ''; 
while(!feof($fp)) {
   // resultado del request
   $result .= fgets($fp, 128);
}
// cierra conexion
fclose($fp);
// separa el header del content
$result = explode("\r\n\r\n", $result, 2);
$header = isset($result[0]) ? $result[0] : '';
$content = isset($result[1]) ? $result[1] : '';
// imprime el content del resultado del request
print $content;

?>