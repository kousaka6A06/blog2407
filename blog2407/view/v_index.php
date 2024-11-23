   <!DOCTYPE html>
   <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ブログサイト</title>
    <link rel="stylesheet" href="view/style.css">
   </head>
      <body>
        <div class="container">
           <h2>新規会員登録・ログイン</h2>
            <form action="registerMember.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="memberId" style="width: 50%;" placeholder="お名前"><br>
                <input type="text" name="handleName" style="width: 50%;" placeholder="ハンドルネーム"><br>
                <!--  アバターファイルのアップロード->type = "file" -->
                <input type="file" name="upfile">
                <br>
                <input type="text" name="password" style="width: 50%;" id="mpass1" placeholder="パスワード ※半角英数字6文字以上10文字以内"><br>
                <input type="text" name="passwordConf" style="width: 50%;" id="mpass2" placeholder="再パスワード"><br>
                <input type="submit" value="登録する" onClick="return isPasswordMatched('mpass1', 'mpass2');">
            </form>

            <hr>

            <h2>ブログ閲覧</h2>
            <form action="displayBlog.php" method="POST">
                <input type="submit" value="ブログを見る">
            </form>

            <hr>

            <h2>ブログ投稿</h2>
            <form action="postBlog.php" method="POST">
                <input type="text" name="memberId" style="width: 50%;" placeholder="お名前"><br>
                <input type="text" name="password" style="width: 50%;" placeholder="パスワード"><br>
                <textarea rows="5" id="sql" style="width: 50%;" name="content"></textarea><br>
                <input type="submit" value="投稿する">
            </form>

            <hr>

            <h2>パスワード変更</h2>
            <form action="changePassword.php" method="POST">
                <input type="text" name="memberId" style="width: 50%;" placeholder="お名前"><br>
                <input type="text" name="password" style="width: 50%;" placeholder="旧パスワード"><br>
                <input type="text" name="passwordNew" style="width: 50%;" id="cpass1" placeholder="新パスワード"><br>
                <input type="text" name="passwordConf" style="width: 50%;" id="cpass2" placeholder="新パスワード再入力"><br>
                <input type="submit" value="変更する" onClick="return isPasswordMatched('cpass1', 'cpass2');">
            </form>
        </div>
      </body>
        

<script>

function isPasswordMatched(id1, id2) {
var p1 = document.getElementById(id1).value;
var p2 = document.getElementById(id2).value;
if (p1 != p2) {
    alert("一致していません。");
    return false;
}
return true;
}

</script>



