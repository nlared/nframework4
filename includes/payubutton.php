<?
class PayUButton{
	
	public function __construct($item,$value,$Description)
	{
	$thi->info=array(
		'NombreItem'=>$Description,
		'TipoMoneda'=>1,
		'PrecioItem'=>$value,
		'E_Comercio'=>836814,
		'NroItem'=>$codigo,
		'image_url'=>'http://nlared.com/img/nlared_150_50.jpg',
		'DireccionExito'=>'',
		'DireccionFracaso'=>'',
		'DireccionEnvio'=>0,
		'Mensaje'=>0,
		'MediosPago'=>'13,14,2,2,7'
	);
	}	
	public function make()
	{
	if ($this->form==true){
		$result ='<form action="https://mexico.dineromail.com/Shop/Shop_Ingreso.asp" method="post">';
		foreach ($this->info as $key => $value) {
			$result.='<input name="'.$key.'" type="hidden" value="'.$value.'"/>';
		}
		$result.='<input type="image" src="https://mexico.dineromail.com/imagenes/botones/comprar-medios_c.gif?dmbypayu" border="0" name="submit" alt="Pagar con DineroMail"></form>';
	}else{
		$result="https://mexico.dineromail.com/Shop/Shop_Ingreso.asp?";
		foreach ($this->info as $key => $value) {
			$result.='&'.$key.'='.urlencode($value);
		}
		return $result;
	}
}
}