<?php 
$server_start = microtime(true);

include 'menu.php'; 
$x_text = "5 сторінка";
$y_text = "5 page";

$block2_text = "<p>“We never go out of style.” — Taylor Swift</p>";
$block3_text = "<p>“Cause darling I'm a nightmare dressed like a daydream.” — Taylor Swift</p>";
$block4_text = "<p>“You belong with me.” — Taylor Swift</p>";
$block5_text = "<p>“I don't know about you, but I'm feeling 22.” — Taylor Swift</p>";
$block6_text = "<p>“We are never ever getting back together.” — Taylor Swift</p>";
$block7_text = "<p>“I knew you were trouble when you walked in.” — Taylor Swift</p>";
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
  
  <div class="block2"><?= $block2_text ?></div>
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
