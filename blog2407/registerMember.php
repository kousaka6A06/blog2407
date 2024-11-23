<?php

require_once('common/DbManager.php');
require_once('common/Encode.php');


session_start();

$memberId = e($_POST['memberId']);
$handleName = e($_POST['handleName']);
$password = e($_POST['password']);

// DBに登録する
// (1) memberId、handleName、password について、あらかじめ決めておいた形式に合致するかチェックを行う。	
// 	・memberId … 文字列長、文字種、等
// 	・handleName … 文字列長、等
// 	・password … 文字列長、文字種、等
//   ---> JavaScriptで実行して、ここではやらない（エラーになったものは来ない）

// (2) memberId については、既存会員の memberId と衝突していないかを確認する。
//　・入力された memberIdで SELECTする。	
//　・memberId、handleName、password を INSERTする。

$errorCode = 0;
$now = new DateTime();
try {

    $db = getDb();
    $stt = $db->prepare("SELECT * FROM member WHERE memberId = :memberId");
    $stt->bindValue(':memberId', $memberId);
    $stt->execute();
    if ($row = $stt->fetch(PDO::FETCH_ASSOC)){
        //この場合はmemberIdが重複している
        $errorCode = 2;

    }
    else {
        if ($_FILES['upfile']['error'] !== UPLOAD_ERR_OK) {
            $msg = [UPLOAD_ERR_INI_SIZE => 'php.iniのupload_max_filesize制限を超えています。',
            UPLOAD_ERR_FORM_SIZE => 'HTMLのMAX_FILE_SIZE 制限を超えています。',
            UPLOAD_ERR_PARTIAL => 'ファイルが一部しかアップロードされていません。',
            UPLOAD_ERR_NO_FILE => 'ファイルはアップロードされませんでした。',
            UPLOAD_ERR_NO_TMP_DIR => '一時保存フォルダーが存在しません。',
            UPLOAD_ERR_CANT_WRITE => 'ディスクへの書き込みに失敗しました。',
            UPLOAD_ERR_EXTENSION => '拡張モジュールによってアップロードが中断されました。'
                    ]; 
            $err_msg = $msg[$_FILES['upfile']['error']];
        } elseif (!in_array(
            strtolower(pathinfo($_FILES['upfile']['name'])['extension']),
            ['gif', 'jpg', 'jpeg', 'png'])) {
            $err_msg = '画像以外のファイルはアップロードできません。';
        } elseif (!in_array(
            finfo_file(
            finfo_open(FILEINFO_MIME_TYPE), $_FILES['upfile']['tmp_name']),
            ['image/gif', 'image/jpg', 'image/jpeg', 'image/png'])) {
            $err_msg = 'ファイルの内容が画像ではありません。';
        } else {
            $src = $_FILES['upfile']['tmp_name'];
            $dest = $_FILES['upfile']['name'];
            $dest1 = $now->format('U').$_FILES['upfile']['name'];
            if (!move_uploaded_file($src, 'images/'.$dest1)) {
                $err_msg = 'アップロード処理に失敗しました。';
            }
        }
        if (isset($err_msg)) {
            die('<div style="color:Red;">'.$err_msg.'<?div>');
        }

        // $hash = sha1();

        $hashedPassword = sha1($password);

        $stt = $db->prepare("INSERT INTO member (image, memberId, handleName, password) VALUES (:image, :memberId, :handleName, :password)");     

        $stt->bindValue(':image', $dest1);
        $stt->bindValue(':memberId', $memberId);
        $stt->bindValue(':handleName', $handleName);
        $stt->bindValue(':password', $hashedPassword);
        $stt->execute();
    }
    //memberId, handeleName, passwordをテーブルに INSERT する
}
catch (PDOException $e) {
      $errorCode = 1;
      // データ重複ではないそれ以外のエラー
}

// (3) password については、そのまま DB に保存せず、ハッシュ値を求めそれを保存するようにする。	

switch ($errorCode) {
    case 0 : 
     // アップロードされたファイルの処理を行う（リスト8.27 p.397）
        $view = 1;
        $v_message = '正しく登録しました。';
        $v_title = '会員登録';
        $v_includeFile = 'view/v_registerMember.php';
        break;
    case 1 :
        $view = 1;
        $v_message = 'データベースエラーです。';
        $v_title = '会員登録';
        $v_includeFile = 'view/v_registerMember.php';
        break;
    case 2 :
        $view = 2;
        $v_message = 'ログインしました。';
        $v_title = '会員登録';
        $v_includeFile = 'view/v_registerMember.php';
        break;
}

$_SESSION['memberId'] = $memberId;
$_SESSION['handleName'] = $handleName;
$_SESSION['password'] = $password;
$_SESSION['view'] = $view;

require_once 'view/template.php';

// try {
//     $db = getDb();
//     $stt = $db->prepare("SELECT * FROM member WHERE memberId = :memberId");
//     $stt->bindValue(':memberId', $memberId);
//     $stt->execute();
    
//     if ($row = $stt->fetch(PDO::FETCH_ASSOC)) {
//         // この場合はmemberIdが重複している
//         $errorCode = 2; // 会員IDが重複している
//     } else {
//         if ($_FILES['upfile']['error'] !== UPLOAD_ERR_OK) {
//             $msg = [
//                 UPLOAD_ERR_INI_SIZE => 'php.iniのupload_max_filesize制限を超えています。',
//                 UPLOAD_ERR_FORM_SIZE => 'HTMLのMAX_FILE_SIZE 制限を超えています。',
//                 UPLOAD_ERR_PARTIAL => 'ファイルが一部しかアップロードされていません。',
//                 UPLOAD_ERR_NO_FILE => 'ファイルはアップロードされませんでした。',
//                 UPLOAD_ERR_NO_TMP_DIR => '一時保存フォルダーが存在しません。',
//                 UPLOAD_ERR_CANT_WRITE => 'ディスクへの書き込みに失敗しました。',
//                 UPLOAD_ERR_EXTENSION => '拡張モジュールによってアップロードが中断されました。'
//             ]; 
//             $err_msg = $msg[$_FILES['upfile']['error']];
//         } elseif (!in_array(strtolower(pathinfo($_FILES['upfile']['name'])['extension']), ['gif', 'jpg', 'jpeg', 'png'])) {
//             $err_msg = '画像以外のファイルはアップロードできません。';
//         } elseif (!in_array(finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['upfile']['tmp_name']), ['image/gif', 'image/jpg', 'image/jpeg', 'image/png'])) {
//             $err_msg = 'ファイルの内容が画像ではありません。';
//         } else {
//             $src = $_FILES['upfile']['tmp_name'];
//             $dest1 = $now->format('U') . $_FILES['upfile']['name'];
//             if (!move_uploaded_file($src, 'images/' . $dest1)) {
//                 $err_msg = 'アップロード処理に失敗しました。';
//             }
//         }

//         if (isset($err_msg)) {
//             // エラーメッセージを表示して処理を終了
//             die('<div style="color:Red;">' . $err_msg . '</div>');
//         }

//         // // パスワードをハッシュ化
//         // $hashedPassword = sha1($password, PASSWORD_DEFAULT);

//         // 会員情報をデータベースに登録
//         $stt = $db->prepare("INSERT INTO member (image, memberId, handleName, password) VALUES (:image, :memberId, :handleName, :password)");     
//         $stt->bindValue(':image', $dest1);
//         $stt->bindValue(':memberId', $memberId);
//         $stt->bindValue(':handleName', $handleName);
//         $stt->bindValue(':password', $password);
//         $stt->execute();
//     }
// }
// catch (PDOException $e) {
//     $errorCode = 1; // データベースエラー
// }

// // エラーコードに基づいてメッセージを設定
// switch ($errorCode) {
//     case 0 : 
//         // 正常登録
//         $view = 1;
//         $v_message = '正しく登録しました。';
//         $v_title = '会員登録';
//         $v_includeFile = 'view/v_registerMember.php';
//         break;
//     case 1 :
//         // データベースエラー
//         $view = 1;
//         $v_message = 'データベースエラーです。';
//         $v_title = '会員登録';
//         $v_includeFile = 'view/v_registerMember.php';
//         break;
//     case 2 :
//         // 会員ID重複
//         $view = 2;
//         $v_message = 'その会員IDは既に使用されています。';
//         $v_title = '会員登録';
//         $v_includeFile = 'view/v_registerMember.php';
//         break;
// }

// $_SESSION['memberId'] = $memberId;
// $_SESSION['handleName'] = $handleName;
// $_SESSION['password'] = $password;
// $_SESSION['view'] = $view;

// require_once 'view/template.php';
