<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$v_title?></title>
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="css/style1.css">
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/script1.js"></script>
</head>
<header>
    <div class="container">
        <h1 class="logo">ブログサイト</h1>
    </div>
</header>
<body>
    <main>
        <?php include $v_includeFile; ?>
    </main>
</body>
</html>
