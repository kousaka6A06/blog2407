<?php

$errorCode = 0;
require_once('common/DbManager.php');
require_once('common/Encode.php');

session_start();

// デバッグ用
// print_r($_SESSION);
// print_r($_POST);

// if (array_key_exists('memberId', $_SESSION)) {
//     $memberId = $_SESSION['memberId'];
//     $handleName = $_SESSION['handleName'];
//     $password = $_SESSION['password'];
// }
// else {
//     $memberId = '';
//     $handleName = '';
//     $password = '';
// }

// $_SESSION['memberId'] = $memberId;
// $_SESSION['handleName'] = $handleName;
// $_SESSION['password'] = $password;

try {
    $db = getDb();
    $stt = $db->prepare("SELECT * FROM content JOIN member ON content.memberId = member.memberId");
    
    $stt->execute();
    $arr = [];
    while ($row = $stt->fetch(PDO::FETCH_ASSOC)) {
      $arr[] = $row;
    //   print_r($row);
    //   print '<br>';
    }
    $errorCode = 0;
    // print_r($arr);
    // print '<br>';

}
catch (PDOException $e) {
     $errorCode = 1;
}

switch ($errorCode) {
    case 0 : 
        $v_title = '表示完了';
        $v_includeFile = 'view/v_displayBlog.php';
        break;
    case 1 :
        $v_message = 'データベースエラーです。';
        $v_includeFile = 'view/v_displayBlogError.php';
        $v_title = 'エラー1';
        break;
    }


require_once 'view/template.php';