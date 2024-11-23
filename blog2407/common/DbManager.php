<?php
function getDb() : PDO {
    try {
        $dbname = 'blog2407';  // データベース名
        $usr = 'root';         // ユーザー名
        $passwd = 'root';      // パスワード
        $dsn = "mysql:dbname={$dbname};host=127.0.0.1;charset=utf8";  // DSN (データソース名)

        // PDOでデータベース接続を作成
        $db = new PDO($dsn, $usr, $passwd);

        // エラーモードを例外に設定して、エラー発生時に例外をスローさせる
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // ここまで来れば、接続成功
        return $db;
    } catch (PDOException $e) {
        // 接続エラーが発生した場合、エラーメッセージを表示
        die('データベース接続エラー: ' . $e->getMessage());
    }
}

