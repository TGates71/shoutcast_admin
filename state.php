<?php
	function findRadio($port) {
		$fp = @fsockopen ("127.0.0.1", $port, $errno, $errstr, 30);
		if(!$fp){ return "offline"; } 
		else {
			$sc_state="online";
		}	
	 	fclose($fp);		
		return $sc_state;		
	}
	echo 'document.write("'.findRadio($_GET['port']).'");';
?>