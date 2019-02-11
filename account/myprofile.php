<?
require 'include.php';
$data=$_POST['data'];
$data['_id']=$user->_id;
$backlink='/';
require '../account/profile.php';
?>