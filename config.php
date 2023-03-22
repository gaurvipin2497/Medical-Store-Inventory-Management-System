<?php
	$dbhost = 'bloodbank.cq5ahkkmqrau.ap-south-1.rds.amazonaws.com';
	$dbname = 'db';
	$dbtable = 'users';
	$dbuser = 'admin';
	$dbpass = 'admin1234';

	try {
		$pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		echo "Error connecting to database: " . $e->getMessage();
	}
?>
