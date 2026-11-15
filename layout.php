<?php
function page_header(string $title, string $active = ''): void { ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#0f766e">
<link rel="manifest" href="manifest.json">
<link rel="icon" href="assets/icon.svg" type="image/svg+xml">
<link rel="stylesheet" href="assets/style.css">
<title><?= h($title) ?> - BaleWise</title>
</head>
<body>
<header class="top"><h1>BaleWise</h1><span>Know the bale before you buy</span></header>
<main>
<?php }

function page_footer(string $active = ''): void { ?>
</main>
<nav class="tabs">
  <a href="index.php" class="<?= $active === 'value' ? 'on' : '' ?>">Value</a>
  <a href="history.php" class="<?= $active === 'history' ? 'on' : '' ?>">History</a>
  <a href="prices.php" class="<?= $active === 'prices' ? 'on' : '' ?>">Prices</a>
</nav>
</body>
</html>
<?php }