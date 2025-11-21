<?php
$server_start = microtime(true);

include 'menu.php';
$x_text = "3 сторінка";
$y_text = "3 page";

$block2_text = "<p>“I'm the bad guy, duh.” — Billie Eilish</p>";
$block3_text = "<p>“You should see me in a crown.” — Billie Eilish</p>";
$block4_text = "<p>“I had a dream I got everything I wanted.” — Billie Eilish</p>";
$block5_text = "<p>“Quiet when I'm coming home…” — Billie Eilish</p>";
$block6_text = "<p>“All the good girls go to hell.” — Billie Eilish</p>";
$block7_text = "<p>“I've been watching you for some time…” — Billie Eilish</p>";

$link_block2 = "<a href='https://open.spotify.com/artist/6qqNVTkY8uBg9cP3Jd7DAH' target='_blank'>Слухати Billie Eilish</a>";

$image_block = "<img src='images/inst_youtube.jpg' alt='Billie Eilish' style='width:100%;border-radius:8px; margin-top: 10px' usemap='#billie-map'>";

$image_map = "
<map name='billie-map'>
  <area shape='rect' coords='0,0,70,70' href='https://www.youtube.com/user/BillieEilish' alt='Instagram Billie'>
  <area shape='rect' coords='0,0,140,140' href='https://www.instagram.com/billieeilish/' alt='YouTube Billie'>
</map>";
?>

<!DOCTYPE html>
<html lang="uk">
<head>
<meta charset="UTF-8">
<title>IS32_LemaevaSasha</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
  <header class="block1">
    <nav>
      <ul>
      <?php foreach($menu as $link=>$name): ?>
        <li><a href="<?= $link ?>"><?= $name ?></a></li>
      <?php endforeach; ?>
      </ul>
    </nav>
    <div class="blockX"><?= $x_text ?></div>
  </header>
  
  <div class="block2">
    <?= $block2_text ?>
    <?= $link_block2 ?>
    <?= $image_block ?>
    <?= $image_map ?>
  </div>

  <div class="block3"><?= $block3_text ?></div>
  <div class="block4"><?= $block4_text ?></div>
  <div class="block5"><?= $block5_text ?></div>
  <div class="block6"><?= $block6_text ?></div>
  
  <footer class="block7">
    <?= $block7_text ?>
    <div class="blockY"><?= $y_text ?></div>
  </footer>
</div>

  <script src="script.js"></script>

  <script>
    window.__serverGenMs = <?php echo round((microtime(true) - $server_start) * 1000, 1); ?>;
  </script>
</body>
</html>
