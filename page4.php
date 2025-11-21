<?php
$server_start = microtime(true);

include 'menu.php';
$x_text = "4 сторінка";
$y_text = "4 page";

$block2_text = "<p>“Hello from the other side.” — Adele</p>";
$block3_text = "<p>“Never mind, I'll find someone like you.” — Adele</p>";
$block4_text = "<p>We could have had it all, rolling in the deep.” — Adele</p>";
$block5_text = "<p>“Sometimes it lasts in love, but sometimes it hurts instead.” — Adele</p>";
$block6_text = "<p>“Should I give up or should I just keep chasing pavements?” — Adele</p>";
$block7_text = "<p>“Set fire to the rain.” — Adele</p>";
?>
<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <title>IS32_LemaevaSasha</title>
  <link rel="stylesheet" href="style.css">
</head>
<body data-server-gen="<?= number_format(microtime(true) - $server_start, 6, '.', '') ?>">
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
