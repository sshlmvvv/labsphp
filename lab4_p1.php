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
  <title>Lab4_p1</title>
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
    <div class="collapse-form-wrapper">

      <div id="collapse-items-container"></div>

      <button class="btn btn-add" id="add-item-btn">+ Додати елемент</button><br>
      <button class="btn btn-save" id="save-items-btn">Зберегти в БД</button>

      <div id="status" class="status-message"></div>
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

const container = document.getElementById('collapse-items-container');
const addBtn    = document.getElementById('add-item-btn');
const saveBtn   = document.getElementById('save-items-btn');
const statusEl  = document.getElementById('status');

let itemCounter = 0;

function createFormItem(title = '', content = '') {
  itemCounter++;
  const wrapper = document.createElement('div');
  wrapper.className = 'collapse-form-item';
  wrapper.dataset.index = itemCounter;

  wrapper.innerHTML = `
    <div class="collapse-form-item-header">
      <input type="text" class="collapse-title-input" placeholder="заголовок" value="${title}">
      <button type="button" class="btn btn-small btn-remove">Видалити</button>
    </div>
    <textarea class="collapse-content-input" placeholder="вміст">${content}</textarea>
  `;

  const removeBtn = wrapper.querySelector('.btn-remove');
  removeBtn.addEventListener('click', () => {
    container.removeChild(wrapper);
  });

  return wrapper;
}

addBtn.addEventListener('click', () => {
  container.appendChild(createFormItem());
});

container.appendChild(
  createFormItem('', '')
);

saveBtn.addEventListener('click', () => {
  const items = [];
  const rows = container.querySelectorAll('.collapse-form-item');

  rows.forEach((row, index) => {
    const titleInput   = row.querySelector('.collapse-title-input');
    const contentInput = row.querySelector('.collapse-content-input');

    const title   = titleInput.value.trim();
    const content = contentInput.value.trim();

    if (title !== '' || content !== '') {
      items.push({
        title: title,
        content: content,
        position: index + 1
      });
    }
  });

  if (items.length === 0) {
    statusEl.textContent = 'Немає елементів для збереження.';
    statusEl.className = 'status-message status-error';
    return;
  }

  fetch('lab4_save.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ items })
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        statusEl.textContent = 'Дані успішно збережено на сервері.';
        statusEl.className = 'status-message status-success';
      } else {
        statusEl.textContent = 'Помилка збереження: ' + (data.error || 'невідома помилка');
        statusEl.className = 'status-message status-error';
      }
    })
    .catch(err => {
      statusEl.textContent = 'Помилка запиту: ' + err;
      statusEl.className = 'status-message status-error';
    });
});
</script>
</body>
</html>
