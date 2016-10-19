<?php
// Report all errors
error_reporting(E_ALL);

require_once("classQuery.php");
require_once("config.php");

$table = "article";

$columns = array("title","link","description","thumbnail","publish_date","credit");
// $values = array($_POST['title'],$_POST['link'],$_POST['description'],$_POST['thumbnail'],$_POST['publish_date'],$_POST['credit']);
$values = array("a","a","a","a","a","a");

$query = query::insert($conn,$table,$columns,$values);

?>