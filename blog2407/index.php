<?php
require_once 'common/DbManager.php';
require_once 'common/Encode.php';

session_start();

// デバッグ用
// print_r($_SESSION);
// print_r($_POST);


if (array_key_exists('memberId', $_SESSION)) {
    $memberId = $_SESSION['memberId'];
    $handleName = $_SESSION['handleName'];
    $password = $_SESSION['password'];
}
else {
    $memberId = '';
    $handleName = '';
    $password = '';
}

if (array_key_exists('view', $_SESSION)) {
    $view = $_SESSION['view'];
}
else {
    $view = 1;
}

$_SESSION['memberId'] = $memberId;
$_SESSION['handleName'] = $handleName;
$_SESSION['password'] = $password;
$_SESSION['view'] = $view;



// テーブルがあれば何もしない、なければ作成する
// contentテーブル・memberテーブル
// SQL: CREATE TABLE IF NOT EXISTS content; ～ member;
// content テーブルは、ID（INTEGER）、memberId（投稿者：文字列）、dtime（投稿日時：文字列）、postContent（投稿内容：テキスト）
// member テーブルは、memberId（文字列）、handleName（文字列）、password（文字列）

$dbhdl = getDb();
try {
    $sql = "CREATE TABLE IF NOT EXISTS content (ID INTEGER PRIMARY KEY AUTO_INCREMENT, memberId VARCHAR(32), dtime VARCHAR(32), postContent TEXT)";
    $dbhdl->exec($sql);
    $sql = "CREATE TABLE IF NOT EXISTS member (memberId VARCHAR(32) PRIMARY KEY, handleName VARCHAR(32), image VARCHAR(64), password  VARCHAR(256))";
    $dbhdl->exec($sql);
}
catch (PDOException $e) {
    printf("テーブル作成に失敗しました：%s<br>", $e);
    // printf("テーブル作成に失敗しました：%s<br>", $e->getMessage());
    exit;
}

if ($view == 1) {
    $v_title = 'ブログサイト構築';
    $v_includeFile = 'view/v_index.php';
}
else {
    $v_title = 'ブログサイト構築';
    $v_includeFile = 'view/v_index2.php';
}

require_once 'view/template.php';