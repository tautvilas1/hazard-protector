<?php
// Report all errors
error_reporting(E_ALL);

require_once("classQuery.php");
require_once("config.php");

$table = "article";

$columns = array("title","link","description","thumbnail","publishDate","credit","tags","dateAdded");
$values = array($_POST['title'],$_POST['link'],$_POST['description'],$_POST['thumbnail'],$_POST['publishDate'],$_POST['credit'],$_POST['tags'],$_POST['dateAdded']);

query::insert($conn,$table,$columns,$values);

echo 'Query completed';


?>