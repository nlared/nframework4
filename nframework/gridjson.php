<?
set_time_limit(0);
$result = '';
$id = isset($_GET['id']) ? $_GET['id'] : "";
$offset = isset($_GET['offset']) ? $_GET['offset'] : '0';
$size = isset($_GET['page_size']) ? $_GET['page_size'] : '';
$get_total = isset($_GET['get_total']) ? strtolower($_GET['get_total']) : false;
include 'include.php';
header('Content-Type: application/json');

function ErrorResponse($msg) {
//  echo "\n<rows update_ui='false' /><error>" . $msg . "</error>";
}

function filtrar($renglon) {
    global $where;
    $valor = false;
    $conteo=count($where);
    foreach ($where as $key => $value) {
        if (preg_match($value, $renglon[$key])){
            //$valor = true;
            $conteo--;
        }
    }
    //return $valor;
    return $conteo==0;
}

if (isset($_GET['distinct'])) {
    $distinct = $_GET['distinct'];
    $size = 2999;
}
if (empty($id)) {
    ErrorResponse('No ID provided!');
} elseif (!is_numeric($offset)) {
    ErrorResponse('Invalid offset!');
} elseif (!is_numeric($size)) {
    ErrorResponse('Invalid size!');
} elseif (!isset($_SESSION['grid'][$id])) {
    ErrorResponse('Your connection with the server was idle for too long and timed out. Please refresh this page and try again.');
} else {
    
}
$gridData = $_SESSION['gridjson'][$_GET['id']];
$columns = $gridData['columns'];
$orderby = array();

function jsonquery() {
    global $id, $_GET, $columns, $orderby, $where, $where2, $colnames;
    $filters = array();
    $values = array();
    foreach ($_GET as $qs => $value) {
        $prefix = substr($qs, 0, 1);
        switch ($prefix) {
            // user-invoked condition
            case 'w':
            case 'h':
                $i = substr($qs, 1);
                if (!is_numeric($i))
                    break;
                $i = intval($i);
                //	echo "\n<debug>Query2xml: condition $prefix $i </debug>";
		  if ($i<0 || $i>=count($filters)) break;
                    if (strpos($newfilter,"?") !== false) $this->PushParam($value);
                break;
            // sort
            case 's':
                $i = substr($qs, 1);
                if (!is_numeric($i))
                    break;
                $i = intval($i);
                $value = strtoupper(substr($value, 0, 4));
                if ($value == 'ASC')
                    $orderby[$colnames[$i]] = SORT_ASC;
                if ($value == 'DESC')
                    $orderby[$colnames[$i]] = SORT_DESC;
                //echo "\n<debug> ORDER BY $columns[$i] $value</debug> " ;
                break;
            case 'f':
                foreach ($value as $i => $filter) {
                    //echo "\n<debug>Query2xml: user-supplier filter $columns[$i] $filter[0] $filter[len] $filter[op]</debug>";
                    switch ($filter['op']) {
                        case 'EQ':
                            $where[$columns[$i]] = $filter[0];
                            break;
                        case 'LE':
                            $newfilter.="<=?";
                            $this->PushParam($filter[0]);
                            break;
                        case 'GE':
                            $newfilter.=">=?";
                            $this->PushParam($filter[0]);
                            break;
                        case 'NULL': $newfilter.=" is null";
                            break;
                        case 'NOTNULL': $newfilter.=" is not null";
                            break;
                        case 'LIKE':
                            if (strpos($filter[0], '*') == 0) {
                                $value = substr($filter[0], 1, -1);
                            } else {
                                $value = $filter[0];
                            }
                            $value = str_replace('*', '.*', substr($filter[0], 1, -1));
                            $where2.="$value/";
                            $where[$colnames[$i]] = "/$value/i";
                            //echo "\n<debug>$columns[$i]='$value'</debug>";
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
}
eval($gridData['code']);
$nocols = count($columns);
if (count($data) > 0) {
    $colnames = array_keys($columns);
    jsonquery();
    foreach ($data as $row) {
        $mrow = array_fill_keys($colnames, '');
        foreach ($colnames as $column) {
            $mrow[$column] = $row[$column];
        }
        $data2[] = $mrow;
    }
    $data = $data2;
    $debug = array('sorts' => $orderby, 'filters' => $where);
    if (count($where) > 0)
        $data = array_filter($data, "filtrar");
    if (count($orderby) > 0) {
        foreach ($orderby as $key => $value) {
            $arg[] = array_column($data, $key);
            $arg[] = $value;
        }
        array_multisort($arg[0], $arg[1], $data);
    }

    $rowCount = count($data);
    $data = array_slice($data, $offset, $size);
}
if (isset($distinct)) {
    $data = array_unique(array_column($data, $columns[$distinct]));
    $rowCount = count($data);
    $result = array('update_ui' => true, 'offset' => $offset, 'rowCount' => $rowCount, 'debug' => $debug, 'rows' => $data);
    echo json_encode($result);
} else {
    $result = ['update_ui' => true, 
        'offset' => $offset, 
        'debug' => $debug,
        'rows' => $data,
        'rowsc'=>count($data)];
       
     if($get_total)$result['rowCount'] = $rowCount;
       
    echo json_encode($result);
}
