<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ChartjsDataset{
    public $label;
    public $fillColor;
    public $strokeColor;
    public $highlightFill;
    public $highlightStroke;
    public $data;
    function __construct() {        
        $this->fillColor='rgba(220,220,220,0.5)';
        $this->strokeColor='rgba(220,220,220,0.8)';
        $this->highlightFill='rgba(220,220,220,0.75)';
        $this->highlightStroke='rgba(220,220,220,1)';
        $this->data=[];
    }
    function __toString() {
        '{label: "'.$this->label.'",
            fillColor: "'.$this->fillColor.'",
            strokeColor: "'.$this->strokeColor.'",
            highlightFill: "'.$this->highlightFill.'",
            highlightStroke: "'.$this->highlightStroke.'",
            data: ['.$this->data.']
        }';
    }
}

class Chartjs{
    public $datasets;
    function __construct($id,$labels) {
        $this->id=$id;
        $this->labels=$labels;
    }
    function __toString() {        
        foreach ($this->datasets as $dataset=>$data){
            $datasets[]='{label: "'.$dataset.'",
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "rgba(220,220,220,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data: ['.$data.']
        }';            
        }
        return 'var ctx = document.getElementById("'.$this->id.'").getContext("2d");
    var data = {
    labels: ['.$this->labels.'],
    datasets: ['.implode(',', $datasets).']
};
var myBarChart = new Chart(ctx).Bar(data, {
    barShowStroke: false
});';
    }
}