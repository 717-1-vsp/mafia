<?php
	function generateSalt()
	{
		$salt = '';
		$saltLength = 8; //длина соли
		for($i=0; $i<$saltLength; $i++) {
			$salt .= chr(mt_rand(65,126)); //символ из ASCII-table
		}
		return $salt;
	}
?>