<?php
require_once 'include.php';
header('Content-Type: application/json');

eval($_SESSION['ajax'][$_POST['Id']]);
//echo json_encode($result);
