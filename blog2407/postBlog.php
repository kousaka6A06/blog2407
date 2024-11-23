<?php

require_once('common/DbManager.php');
require_once('common/Encode.php');

session_start();

if (array_key_exists('memberId', $_SESSION)) {
    $memberId = $_SESSION['memberId'];
    $password = $_SESSION['password'];
}
else {
    $memberId = e($_POST['memberId']);
    $password = e($_POST['password']);
}

$content = e($_POST['content']);

$now = new DateTime();
$time = $now->format('Y年m月d日 H:i:s');

// (1) memerId に対して正しい passwordであるかを確認する。
// ※ただし、passwprd はハッシュ値を求めたうえで　DB の値と比較する。
$errorCode = 0;

try {
    $db = getDb();
    $stt = $db->prepare("SELECT * FROM member WHERE memberId = :memberId");
    $stt->bindValue(':memberId', $memberId);
    $stt->execute();
    if ($row = $stt->fetch(mode: PDO::FETCH_ASSOC)) {
        echo "1".$row['password']."<br>";
        echo "2".sha1($password);
        if ($row['password'] == sha1($password) && $row['memberId'] == $memberId) {
            // passwordがOK
            // 投稿内容をinsertする (contentテーブルに)
            $stt = $db->prepare("INSERT INTO content(memberId, dtime, postContent) VALUES(:memberId, :time, :content)");
            $stt->bindValue(':memberId', $memberId);
            $stt->bindValue(':time', $time);
            $stt->bindValue(':content', $content);
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

switch ($errorCode) {
    case 0 : 
        $v_message ='投稿しました。';
        $v_title = '投稿完了';
        break;
    case 1 :
        $v_message ='データベースエラーです。';
        $v_title = 'エラー1';
        break;
    case 2 :
        $v_message ='お名前かパスワードが間違っています。';
        $v_title = 'エラー2';
        break;
    case 3 :
        $v_message ='登録されていないユーザーです。';
        $v_title = 'エラー3';
        break;
}

$_SESSION['memberId'] = $memberId;
$_SESSION['password'] = $password;

$v_includeFile = 'view/v_postBlog.php';
require_once 'view/template.php';

