<?
class nFramework{
	
	function __construct(){
		$this->varlocales=explode(',',explode(';',$_SERVER['HTTP_ACCEPT_LANGUAGE'])[0])[0];
		$this->varisAjax=(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
		&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}
	
	function locales(){
		return $this->locales;
	}
	
	function isAjax() {
		return $this->varisAjax;
	}

}