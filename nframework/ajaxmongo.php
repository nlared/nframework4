<?php
set_time_limit(0);
$result='';
$id=isset($_GET["id"]) ? $_GET["id"] : "";
$offset=isset($_GET["offset"]) ? $_GET["offset"] : "0";
$size=isset($_GET["page_size"]) ? $_GET["page_size"] : "";
$total=isset($_GET["get_total"]) ? strtolower($_GET["get_total"]) : "false";
include('include.php');
header("Content-Type: text/xml");
echo "<?xml version='1.0' encoding='UTF-8'?".">\n";
echo "<ajax-response><response type='object' id='".$id."_updater'>";

function ErrorResponse($msg) {
  echo "\n<rows update_ui='false' /><error>" . $msg . "</error>";
}

$mongodata=$_SESSION['grid'][$_GET['id']];
$columns=explode(',',$mongodata['columns']);

if (isset($_GET['distinct'])){
	$distinct=$_GET['distinct'];
	$size=2999;
}
if (empty($id)) {
  ErrorResponse("No ID provided!");
} elseif (!is_numeric($offset)) {
  ErrorResponse("Invalid offset!");
} elseif (!is_numeric($size)) {
  ErrorResponse("Invalid size!");
} elseif (!isset($_SESSION['grid'][$id])) {
  ErrorResponse("Your connection with the server was idle for too long and timed out. Please refresh this page and try again.");
} else{


$orderby=array();
$where=$mongodata['mongos'];
function jsonquery(){	
	global $id,$_GET,$columns,$orderby,$where,$where2;
	
	$filters=array();
	$values=array();
	foreach($_GET as $qs => $value) {
      $prefix=substr($qs,0,1);
      switch ($prefix) {
        // user-invoked condition
        case "w":
		case "h":
	        $i=substr($qs,1);
	        if (!is_numeric($i)) break;
	        $i=intval($i);
        //	echo "\n<debug>Query2xml: condition $prefix $i </debug>";
//		  if ($i<0 || $i>=count($filters)) break;
    //      if (strpos($newfilter,"?") !== false) $this->PushParam($value);
          break;
        // sort
        case "s":
          	$i=substr($qs,1);
          	if (!is_numeric($i)) break;
          	$i=intval($i);
          	$value=strtoupper(substr($value,0,4));
          	if ($value=='ASC')$orderby[$columns[$i]]= 1;
        	if ($value=='DESC')$orderby[$columns[$i]]=-1;        	
			echo "\n<debug> ORDER BY $columns[$i] $value</debug> " ;
		  break;
        case "f":
          foreach($value as $i => $filter) {
          	echo "\n<debug>Query2xml: user-supplier filter $columns[$i] $filter[0] $filter[len] $filter[op]</debug>";
			switch ($filter['op']) {
              case "EQ":
              	$where[$columns[$i]]=$filter[0];
			    break;
              
			  case "LE":
                $newfilter.="<=?";
                $this->PushParam($filter[0]);
                break;
              case "GE":
                $newfilter.=">=?";
                $this->PushParam($filter[0]);
                break;
              case "NULL": $newfilter.=" is null"; break;
              case "NOTNULL": $newfilter.=" is not null"; break;
              case "LIKE":
			  		if (strpos($filter[0],'*')==0){
						$value=substr($filter[0],1,-1);
					}else{
						$value=$filter[0];
					}
					$where2.="$value ";
      			$where[$columns[$i]] = new MongoRegex("/$value/i");
				//  array ('$regex'=> "$value",'$options'=>'i');				
				echo "\n<debug>$columns[$i]='$value'</debug>";
				break;
              /*
			  case "NE":
                $flen=$filter['len'];
                if (!is_numeric($flen)) break;
                $flen=intval($flen);
                $newfilter.=" NOT IN (";
                for ($j=0; $j<$flen; $j++) {
                  if ($j > 0) $newfilter.=",";
                  $newfilter.='?';
                  $this->PushParam($filter[$j]);
                }
                $newfilter.=")";
                break;
                */
            }
          }
          break;
      }
    }
}}
$m = new MongoClient();
$db=$m->selectDB($mongodata['db']);	
$colection = $db->selectCollection($mongodata['collection']);
foreach($columns as $addcol)$showcols[$addcol]=1;
jsonquery();

if(isset($distinct)){
	echo "\n<debug>distinct \"$columns[$distinct]\"</debug>";
	$cursor = $colection->distinct($columns[$distinct]);
	$conteo=count($cursor);
	sort($cursor);
	$result.="\n<rows update_ui='true' offset='$offset'>";  
	foreach ($cursor as $doc)$result.="\n<tr><td>$doc</td></tr>"; 
}else{
	
	/*
	$mongocommand=	array(
			'text' => $mongodata['collection'], //this is the name of the collection where we are searching
			'search' => $where2,//'hotel', //the string to search
			'limit' => $size+1,//5, //the number of results, by default is 1000
			'project' => $showcols, //Array('title' => 1) //the fields to retrieve from db	
			'language'=>'spanish'
		);
	$mongocommand['filter']='' ;
	$result2 = $db->command($mongocommand);
	print_r($result2);
	*/
	
	$cursor = $colection->find($where,$showcols)->timeout(0);
	$conteo=$cursor->count();
	$cursor=$cursor->limit($size+1)->skip($offset);
	if (count($orderby)>0)$cursor->sort($orderby);
	echo "\n<debug>db:$mongodata[db], colleccton: $mongodata[collection] </debug>";	
	//echo "\n<rowcount>$conteo</rowcount>";
	$result.="\n<rows update_ui='true' offset='$offset'>\n";  
	foreach ($cursor as $doc){ 
		
		$dockeys=array_keys($doc);
		foreach($dockeys as $r) {
			if(MongoDBRef::isRef($doc[$r])){
				$o=$m->canacintra->getDBRef($doc[$r]); 
				//unset($o['_id']);
				$doc[$r]=$o;				
			}
		}
		$result.='<tr>';		
		foreach($columns as $r) {
		
			if(is_array($doc[$r])){
				$result.='<td>'.$doc[$r].'</td>';	
			}else{
				$result.='<td>'.htmlspecialchars ($doc[$r],ENT_HTML5,'UTF-8').'</td>';
			}
		}
		$result.="</tr>\n";
	}
}
$result.= "</rows>";
echo $result;
echo "\n<rowcount>$conteo</rowcount>";
echo "\n</response></ajax-response>";
?>