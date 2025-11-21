<?php 
$server_start = microtime(true);

include 'menu.php';
$x_text = "1 сторінка";
$y_text = "1 page";

$block2_text = "<p>“I feel it coming, I feel it coming, babe…” — The Weeknd</p>";
$block3_text = "<p>“Save your tears for another day…” — The Weeknd</p>";
$block4_text = "<p>“In your eyes, I see there's something burning inside you…” — The Weeknd</p>";
$block5_text = "<p>“And I said, ooh, I'm blinded by the lights…” — The Weeknd</p>";
$block6_text = "<p>“Call out my name, and I'll be on my way…” — The Weeknd</p>";
$block7_text = "<p>“I can't feel my face when I'm with you, but I love it…” — The Weeknd</p>";

$block2_img = "<img src='images/weeknd_concert1.jpg' alt='The Weeknd Concert 1' style='width:100%;border-radius:8px;'>";
$block5_img = "<img src='images/weeknd_concert2.png' alt='The Weeknd Concert 2' style='width:100%;border-radius:8px;'>";
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
    <?= $block2_img ?>
  </div>

  <div class="block3"><?= $block3_text ?></div>

  <div class="block4"><?= $block4_text ?></div>

  <div class="block5">
    <?= $block5_text ?>
    <?= $block5_img ?>
  </div>

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
