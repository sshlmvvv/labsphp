<?php
$server_start = microtime(true);

include 'menu.php';

$x_text = "2 сторінка";
$y_text = "2 page";

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
  <title>Lab4_p2 – перегляд Collapse</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
  <header class="block1">
    <nav>
      <ul>
        <?php foreach($menu as $link=>$name): ?>
          <li><a href="<?= htmlspecialchars($link) ?>"><?= htmlspecialchars($name) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>
    <div class="blockX"><?= htmlspecialchars($x_text) ?></div>
  </header>

  <div class="block2">
    <?= $block2_text ?>
    <?= $block2_img ?>
  </div>

  <div class="block3"><?= $block3_text ?></div>

  <div class="block4">
    <div class="collapse-view-wrapper">

      <div id="collapses-container" class="collapse-set"></div>
      <div id="status" class="status-message status-info">Завантаження даних…</div>
    </div>
  </div>

  <div class="block5">
    <?= $block5_text ?>
    <?= $block5_img ?>
  </div>

  <div class="block6"><?= $block6_text ?></div>
  
  <footer class="block7">
    <?= $block7_text ?>
    <div class="blockY"><?= htmlspecialchars($y_text) ?></div>
  </footer>
</div>

<script>

const container = document.getElementById('collapses-container');
const statusEl  = document.getElementById('status');

let lastDataJson = null;

function renderCollapses(items) {
  container.innerHTML = '';

  if (!items || items.length === 0) {
    container.innerHTML = '<p>Набір порожній.</p>';
    return;
  }

  items.forEach(item => {
    const wrapper = document.createElement('div');
    wrapper.className = 'collapse-item';

    const header = document.createElement('div');
    header.className = 'collapse-header';

    const titleSpan = document.createElement('span');
    titleSpan.className = 'collapse-title';
    titleSpan.textContent = item.title || 'Без заголовку';

    const iconSpan = document.createElement('span');
    iconSpan.className = 'collapse-icon';
    iconSpan.textContent = '>';

    header.appendChild(titleSpan);
    header.appendChild(iconSpan);

    const contentDiv = document.createElement('div');
    contentDiv.className = 'collapse-content';
    contentDiv.textContent = item.content || '';

    header.addEventListener('click', () => {
      const isOpen = wrapper.classList.contains('open');
      if (isOpen) {
        wrapper.classList.remove('open');
      } else {
        wrapper.classList.add('open');
      }
    });

    wrapper.appendChild(header);
    wrapper.appendChild(contentDiv);
    container.appendChild(wrapper);
  });
}

function loadData(showStatus = true) {
  if (showStatus) {
    statusEl.textContent = 'Завантаження даних…';
    statusEl.className = 'status-message status-info';
  }

  fetch('lab4_load.php')
    .then(response => response.json())
    .then(data => {
      if (!data.success) {
        statusEl.textContent = 'Помилка завантаження: ' + (data.error || 'невідома помилка');
        statusEl.className = 'status-message status-error';
        return;
      }

      const jsonStr = JSON.stringify(data.items);

      if (jsonStr !== lastDataJson) {
        lastDataJson = jsonStr;
        renderCollapses(data.items);
      }

      statusEl.textContent = 'Останнє оновлення: ' + new Date().toLocaleTimeString();
      statusEl.className = 'status-message status-info';
    })
    .catch(err => {
      statusEl.textContent = 'Помилка запиту: ' + err;
      statusEl.className = 'status-message status-error';
    });
}

loadData(true);

setInterval(() => {
  loadData(false);
}, 5000);
</script>
</body>
</html>
