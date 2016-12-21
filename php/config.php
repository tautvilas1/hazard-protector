<?php

	// $dbhost='localhost';
	// $dbuser='hazardp';
	// $dbpass='Ro3XvsLDg';
	// $dbname='hazard_protector';

	// $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);


    try{
   		 $conn = new PDO('mysql:host=localhost;port=3306;dbname=hazard_protector;charset=utf8','hazardp','Ro3XvsLDg',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(PDOException $pe) {
        echo $pe->getMessage();
    }    

?>