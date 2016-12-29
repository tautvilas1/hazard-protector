<DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<?php

// Report all errors

error_reporting(E_ALL);

require_once("classQuery.php");

require_once("config.php");

$table = "article";

/*
VALIDATE IF ARTICLE ALREADY EXISTS
*/


$date = date('Y-m-d H:i:s');

$columns = array("title","link","description","thumbnail","publishDate","credit","tags","dateAdded");

$values = array($_POST['title'],$_POST['link'],$_POST['description'],$_POST['thumbnail'],$_POST['publishDate'],$_POST['credit'],$_POST['tags'],$date);

if(query::insert($conn,$table,$columns,$values)) {
	echo '1';
}

else {
	echo '0';
}


 // $myfile = file_put_contents('log.txt', $_POST['description'].PHP_EOL , FILE_APPEND | LOCK_EX);


?>

</body>
</html>