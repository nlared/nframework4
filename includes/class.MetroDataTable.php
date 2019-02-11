<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



class MetroDataTable{
    public $id;
    public $addclass;
    public $columns;
    public $data;
    private $ajax;
    public $header;
    public $foot;
    public $columnDefs;
    public $tableborder;
    public $query=[];
    public $projection=[];
    public function __construct(){
    	$this->data=[];
    	$this->striped=true;
    	$this->tableborder=true;
    	$this->rowhover=true;
    }
    public function Ajax($options=[]){
    	foreach ($options as $option => $value) {
    		$this->{$option}=$value;	        
        }
    	$_SESSION['datatable'][$this->id]=[
            'db'=>$this->db,
            'collection'=>$this->collection,
            'query'=>$this->query,
            'projection'=>$this->projection,
            'columns'=>$this->columns,
        ];
        $this->ajax=true;
    }

    public function __toString(){
        global $javas,$javasonce;
        
        if($this->tableborder){
        	$class[]='table-border';
        }
        if($this->rowborder){
        	$class[]='row-border';
        }
        if($this->cellborder){
        	$class[]='cell-border';
        }
        
        if($this->compact){
        	$class[]='compact';
        }
        if($this->rowhover){
        	$class[]='rowhover';
        }
        if($this->cellhover){
        	$class[]='cell-hover';
        }
        if($this->striped){
        	$class[]='striped';
        }
       
        $class[]=$this->addclass;
        
        $result='<table id="'.$this->id.'" class="table '.implode(' ',$class).'" data-role="datastable" width="100%" data-searching="true">'.
                ($this->header!=''?'<thead>'.str_replace('td>', 'th>', $this->header) .'</thead>':'').''.
                ($this->foot!=''? '<tfoot>'.$this->foot   .'</tfoot>':'');
        if ($this->columnDefs!=''){
        	foreach($this->columnDefs as $targets=>$render){
        		
        		$columnDefss[]='{"targets": '.$targets.',"render":function(data,type,row,meta){return '.$render. '(data,type,row,meta);}}';
        		
        		//$columnDefss[]='{"targets": '.$targets.',"render":function (data,type,row) {return \''. str_replace(["\r\n","\n"],['',''],$render) .	"';}}";
        			
        	}
        	//$columnDefs=',"columnDefs":'.str_replace("\n",'',$this->columnDefs);
        	$columnDefs=',"columnDefs":['.implode(',',$columnDefss).']';
        }
        
        
          if($this->ajax){
             $ajax='"processing": true,
        	"serverSide": true,
         	"ajax": $.fn.dataTable.pipeline( {
            url: \'/nframework/datatable.php?id='.$this->id.'\',
            pages: 5 // number of pages to cache
        } )';
          	
          }else{
              $ajax='';
                $result.='<tbody>';
                foreach ($this->data as $row){
                    $result.='<tr><td>'.implode('</td><td>', $row).'</td></tr>';
                }
                $result.='</tbody>';
          } // TODO: Crear object by names
         if($javasonce['datatable']==false){
         	$javasonce['datatable']=true;
         
         $javas->addjs('
    table=$("table[data-role=\'datastable\']").DataTable( {
    "language": {
        "sProcessing":     "Procesando...",
		"sLengthMenu":     "Mostrar _MENU_ registros",
		"sZeroRecords":    "No se encontraron resultados",
		"sEmptyTable":     "Ningún registro disponible",
		"sInfo":           "Del _START_ al _END_ de _TOTAL_",
		"sInfoEmpty":      "Del 0 al 0 de 0 ",
		"sInfoFiltered":   "(de un total de _MAX_ )",
		"sInfoPostFix":    "",
		"sSearch":         "Buscar:",
		"sUrl":            "",
		"sInfoThousands":  ",",
		"sLoadingRecords": "Cargando...",
		"oPaginate": {
			"sFirst":    "1",
			"sLast":     "_MAX_",
			"sNext":     ">",
			"sPrevious": "<"
		},
		"oAria": {
			"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
			"sSortDescending": ": Activar para ordenar la columna de manera descendente"
		}
        },
   "responsive":true,
    "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "Todos"]],
    "stateSave": true,
     '.$ajax.$columnDefs.'
});','ready');
		}
          return  $result.'</table>';
      
    }
    
}