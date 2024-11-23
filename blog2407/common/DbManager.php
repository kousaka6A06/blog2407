<?php
function getDb() : PDO {
    $dbname = 'blog2407';
    $usr = 'root';
    $passwd = 'root';
    $dsn = "mysql:dbname={$dbname}; host=127.0.0.1; charset=utf8";
    $db = new PDO($dsn, $usr, $passwd);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}
