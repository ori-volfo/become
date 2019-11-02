<?php

require_once('db.php');
$db = new DB();

$get_param = $_GET['query'];
$database = $db::getDbCon();
$result = $database->{$get_param}();
print_r( json_encode($result) );