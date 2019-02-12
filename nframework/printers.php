<?

require_once 'GoogleCloudPrint.php';
$gcp = new GoogleCloudPrint();

// Login to Googel, email address and password is required
if($gcp->loginToGoogle("quique@nlared.com", "\$jkkefr8041")) {
	
	// Login is successfull so now fetch printers
	$printers = $gcp->getPrinters();
	echo "<pre>";
	print_r($printers);
	echo "</pre>";

}

?>