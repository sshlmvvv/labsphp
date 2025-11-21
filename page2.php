<?php 
$server_start = microtime(true);

include 'menu.php';

$x_text = "2 сторінка";
$y_text = "2 page";

$block2_text = "<p>“Will you still love me when I'm no longer young and beautiful?” — Lana Del Rey</p>";
$block3_text = "<p>“I've got that summertime sadness…” — Lana Del Rey</p>";
$block4_text = "<p>“Kiss me hard before you go…” — Lana Del Rey</p>";
$block5_text = "<p>“Money is the anthem of success…” — Lana Del Rey</p>";
$block6_text = "<p>“Heaven is a place on earth with you…” — Lana Del Rey</p>";
$block7_text = "<p>“It's you, it's you, it's all for you…” — Lana Del Rey</p>";

$block2_list = ["Cherry cola dreams under neon skies", "Wild hearts dancing on motel floors"];
$block4_list = ["Driving fast down endless highways at dusk", "Lipstick-stained coffee cups at diners"];
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
    <ul>
      <?php foreach($block2_list as $item): ?>
        <li><?= $item ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  
  <div class="block3"><?= $block3_text ?></div>
  
  <div class="block4">
    <?= $block4_text ?>
    <ol>
      <?php foreach($block4_list as $item): ?>
        <li><?= $item ?></li>
      <?php endforeach; ?>
    </ol>
  </div>
  
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
