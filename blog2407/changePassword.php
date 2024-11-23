<?php

require_once('common/DbManager.php');
require_once('common/Encode.php');

session_start();

// デバッグ用
print_r($_SESSION);
print_r($_POST);


if (array_key_exists('memberId', $_SESSION)) {
    $memberId = $_SESSION['memberId'];
    $handleName = $_SESSION['handleName'];
    $password = $_SESSION['password'];
    $passwordOld = $_SESSION['password'];
    $isLoginPassed = true; // ログイン済み
}
else {
    $memberId = e($_POST['memberId']);
    $handleName = e($_POST['handleName']);
    $password = e($_POST['password']);
    // $passwordOld = e($_POST['passwordOld']);
    $isLoginPassed = false; // ログインしていない

}

$passwordNew = e($_POST['passwordNew']);
// $passwordConf = e($_POST['passwordConf']);

// デバッグ用
// print '<br>';
// print $memberId;
// print '<br>';

$errorCode = 0;

// // ログイン済みでない時
if ($isLoginPassed == false) {
    $db = getDb();
    $stt = $db->prepare("SELECT * FROM member WHERE memberId = :memberId");
    $stt->bindValue(':memberId', $memberId);
    $stt->execute();
    try {
        $db = getDb();
        $stt = $db->prepare("SELECT * FROM member WHERE memberId = :memberId");
        $stt->bindValue(':memberId', $memberId);
        $stt->execute();
        if ($row = $stt->fetch(mode: PDO::FETCH_ASSOC)) {
            if ($row['password'] == $passwordOld && $row['memberId'] == $memberId) {
                // memberId,passwordがOK
                // passwordをupdateする (memberテーブルに)
                $stt = $db->prepare("UPDATE member SET password = :passwordNew WHERE memberId = :memberId");
    
                $hashedPassword = sha1($passwordNew);
    
                $stt->bindValue(':passwordNew', $hashedPassword);
                $stt->bindValue(':memberId', $memberId);
                $stt->execute();
                $errorCode = 0;
            }
            else {
                $errorCode = 2;
            }
        }else {
            $errorCode = 3;
        }
    }
    catch (PDOException $e) {
        $errorCode = 1;
    }
}

// ログイン済みの時
else {         
    $db = getDb();
    $stt = $db->prepare("SELECT * FROM member WHERE memberId = :memberId");
    $stt->bindValue(':memberId', $memberId);
    $stt->execute();
    if ($row = $stt->fetch(mode: PDO::FETCH_ASSOC)) {
        if ($row['password'] == $passwordOld && $row['memberId'] == $memberId) {
            // memberId,passwordがOK
            // passwordをupdateする (memberテーブルに)
            $stt = $db->prepare("UPDATE member SET password = :passwordNew WHERE memberId = :memberId");

            $hashedPassword = sha1($passwordNew);

            $stt->bindValue(':passwordNew', $hashedPassword);
            $stt->bindValue(':memberId', $memberId);
            $stt->execute();
            $errorCode = 0;
        }
        else {
    }
}else {
}
}

// デバッグ用
// print $errorCode;

// $db = getDb();
// $stt = $db->prepare("SELECT * FROM member WHERE memberId = :memberId");
// $stt->bindValue(':memberId', $memberId);
// $stt->execute();
// try {
//     if ($row = $stt->fetch(mode: PDO::FETCH_ASSOC)) {
//         if ($row['password'] == $passwordOld && $row['memberId'] == $memberId) {
//             // memberId,passwordがOK
//             // passwordをupdateする (memberテーブルに)
//             $stt = $db->prepare("UPDATE member SET password = :passwordNew WHERE memberId = :memberId");

//             $hashedPassword = sha1($passwordNew);

//             $stt->bindValue(':passwordNew', $hashedPassword);
//             $stt->bindValue(':memberId', $memberId);
//             $stt->execute();
//             $errorCode = 0;
//         }
//         else {
//             $errorCode = 2;
//         }
//     }else {
//         $errorCode = 3;
//     }
// }
// catch (PDOException $e) {
//     $errorCode = 1;
// }

switch ($errorCode) {
    case 0 : 
        $v_message = 'パスワードを変更しました。';
        $v_title = '変更完了';
        break;
    case 1 :
        $v_message = 'データベースエラーです。';
        $v_title = 'エラー1';
        break;
    case 2 :
        $v_message = 'お名前かパスワードが間違っています。';
        $v_title = 'エラー2';
        break;
    case 3 :
        $v_message = '登録されていないユーザーです。';
        $v_title = 'エラー3';
        break;
}

$_SESSION['memberId'] = $memberId;
$_SESSION['handleName'] = $handleName;
$_SESSION['password'] = $passwordNew;

$v_includeFile = 'view/v_changePassword.php';
require_once 'view/template.php';