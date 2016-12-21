<!DOCTYPE html>
<html>
<head>
<title>Get Articles</title>
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

$columns = array();
$values = array();
$query = query::select($conn,$table,$columns,$values);

$result = $query->fetchAll();

?>
<div id="container">


<?php 
$data = array("data"	=>		$result);
echo json_encode($data); ?>

</div>
<?php
// $date = date('Y-m-d H:i:s');

// $columns = array("title","link","description","thumbnail","publishDate","credit","tags","dateAdded");

// $values = array($_POST['title'],$_POST['link'],$_POST['description'],$_POST['thumbnail'],$_POST['publishDate'],$_POST['credit'],$_POST['tags'],$date);

// if(query::insert($conn,$table,$columns,$values)) {
// 	echo 'Query completed';
// }

// else {
// 	echo 'Query failed';
// }


 // $myfile = file_put_contents('log.txt', $_POST['description'].PHP_EOL , FILE_APPEND | LOCK_EX);


?>

</body>
</html>