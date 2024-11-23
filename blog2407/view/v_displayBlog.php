<!DOCTYPE html>
   <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ブログサイト</title>
    <link rel="stylesheet" href="view/style.css">
   </head>

    <h2>
      <main>
      <table border="1">
      <?php
      foreach ($arr as $a) {
      ?>
          <tr>
            <td> <img src="images/<?=$a['image']?>" alt="" width="100px"></td>
            <td><?=$a['handleName']?></td>
            <td><?=$a['dtime']?></td>
            <td><?=$a['postContent']?></td>
          </tr>
      <?php
      }
      ?>
      </table>
      </main>
    </h2>

    <h3>
     <a href="index.php">トップに戻る</a>
    </h3>
