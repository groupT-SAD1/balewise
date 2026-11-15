<?php
require __DIR__ . '/db.php';
require __DIR__ . '/layout.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $ok = true;
    foreach (['A', 'B', 'C', 'R'] as $g) {
        $x = $_POST[$g] ?? '';
        if (!is_numeric($x) || $x < 0) $ok = false;
    }
    if ($ok) {
        $st = db()->prepare('REPLACE INTO grade_prices(grade, price_per_kg) VALUES(?, ?)');
        foreach (['A', 'B', 'C', 'R'] as $g) $st->execute([$g, $_POST[$g]]);
        $msg = 'Prices saved.';
    } else {
        $msg = 'Every price must be a number.';
    }
}
$p = grade_prices();

page_header('Grade prices');
?>
<h2>Grade prices (per kg)</h2>
<p class="hint">What you expect to resell each grade for. Change these to match your market.</p>
<?php if ($msg): ?><p class="alert"><?= h($msg) ?></p><?php endif; ?>
<form method="post" class="card">
  <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">
  <label>Grade A<input name="A" type="number" step="1" min="0" inputmode="numeric" value="<?= h((int)$p['A']) ?>"></label>
  <label>Grade B<input name="B" type="number" step="1" min="0" inputmode="numeric" value="<?= h((int)$p['B']) ?>"></label>
  <label>Grade C<input name="C" type="number" step="1" min="0" inputmode="numeric" value="<?= h((int)$p['C']) ?>"></label>
  <label>Reject<input name="R" type="number" step="1" min="0" inputmode="numeric" value="<?= h((int)$p['R']) ?>"></label>
  <button class="primary">Save prices</button>
</form>
<?php page_footer('prices');