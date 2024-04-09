<?php

ini_set('max_execution_time', 300); //300 seconds

$product = "Estimate";

//check required php version
$php_version_required = "8.0";
$current_php_version = PHP_VERSION;
if (!(version_compare($current_php_version, $php_version_required) >= 0)) {
    echo json_encode(array("success" => false, 'message' => app_lang("please_upgrade_your_php_version") . " " . app_lang("current_version") . ": <b>" . $current_php_version . "</b> " . app_lang("required_version") . ": <b>" . $php_version_required . "/+</b> "));
    exit();
}

// get config database from app/Config/Database.php
$db = db_connect('default');

//all input seems to be ok. check required files
if (!is_file(PLUGINPATH . "$product/install/database.sql")) {
    echo json_encode(array("success" => false, "message" => "The database.sql file could not found in install folder!"));
    exit();
}

//start installation
$sql = file_get_contents(PLUGINPATH . "$product/install/database.sql");

$dbprefix = get_db_prefix();

//set database prefix
$sql = str_replace('CREATE TABLE IF NOT EXISTS `', 'CREATE TABLE IF NOT EXISTS `' . $dbprefix, $sql);
$sql = str_replace('INSERT INTO `', 'INSERT INTO `' . $dbprefix, $sql);

$sql_explode = explode('#', $sql);
foreach ($sql_explode as $sql_query) {
    $sql_query = trim($sql_query);
    if ($sql_query) {
        echo 'Execute SQL: ' . $sql_query . '<br>';
        $db->query($sql_query);
    }
}

echo "install successfully database!";
