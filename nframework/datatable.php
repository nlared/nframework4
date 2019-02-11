<?php
require_once 'include.php';
$datainfo=$_SESSION['datatable'][$_GET['id']];

$searchs=array();
foreach ($datainfo['columns'] as $column){
    $showsss[$column]=true;
}
$psort=$_GET['order'];
foreach ($psort as $nsort){
    $sorts[$datainfo['columns'][$nsort['column']]]=($nsort['dir']=='asc'?1:-1);
}

foreach ($datainfo['columns'] as $cno=>$co){
    $buscarstr=trim($_GET['search']['value']). trim($_GET['columns'][$cno]['search']['value']);
    if ($buscarstr!=''){
	    $datainfo['busquedas'][]= $buscarstr;
	    $buscar[][$co]=  [
	        '$regex'=> str_replace('*','|',$buscarstr),
	        '$options'=>'i'
	    ];
    }
}

if (count($buscar)==1){
	$datainfo['query']=$buscar[0];	
}else if(count($buscar)==0){
	$datainfo['query']=[];
}else{
	$datainfo['query']=[
		'$or'=>$buscar,
	];
	$datainfo['query']=$buscar;
}
$ops=[];
if($_GET['start']>0){
    $ops['skip']=intval($_GET['start']);
}
if($_GET['length']>0){
    $ops['limit']=intval($_GET['length']);
}
        
$ops['sort']=$sorts;
if(!is_array($datainfo['query'])){
	$datainfo['query']=[];
}

$total=$m->{$datainfo['db']}->{$datainfo['collection']}->count();
$cursor=$m->{$datainfo['db']}->{$datainfo['collection']}->find($datainfo['query']);


$responce['draw']=(int)$_GET['draw'];
$responce["recordsTotal"]=$total;


foreach ($cursor as $doc){
    $toad=[];
    $doc['_id']=(string)$doc['_id'];
    foreach ($datainfo['columns'] as $column){
        $toad[$column]= $doc[$column];   
    }            
    $responce['data'][]=array_values($toad);
    $filtrados++;
}
$responce["recordsFiltered"]= $filtrados;
$responce['datainfo']=$datainfo;
echo json_encode($responce);
