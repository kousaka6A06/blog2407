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

<a href="index.php">トップに戻る</a>