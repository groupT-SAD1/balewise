<?php
require __DIR__ . '/db.php';
require __DIR__ . '/valuation.php';
require __DIR__ . '/layout.php';

$id = (int)($_GET['id'] ?? 0);
$st = db()->prepare('SELECT b.*, s.name AS supplier FROM bales b JOIN suppliers s ON s.id = b.supplier_id WHERE b.id = ?');
$st->execute([$id]);
$b = $st->fetch();
if (!$b) { http_response_code(404); exit('Bale not found. <a href="history.php">Back</a>'); }

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (($_POST['action'] ?? '') === 'actual') {
        $x = $_POST['actual'] ?? '';
        if (is_numeric($x) && $x >= 0) {
            db()->prepare('UPDATE bales SET actual_value = ?, opened_at = NOW() WHERE id = ?')->execute([$x, $id]);
            header('Location: bale.php?id=' . $id);
            exit;
        }
        $msg = 'Enter the actual value as a number.';
    } elseif (($_POST['action'] ?? '') === 'delete') {
        if ($b['photo_path']) @unlink(__DIR__ . '/uploads/' . basename($b['photo_path']));
        db()->prepare('DELETE FROM bales WHERE id = ?')->execute([$id]);
        header('Location: history.php');
        exit;
    }
}

[$cls, $text] = verdict((float)$b['asking_price'], (float)$b['base_value'], (float)$b['est_low'], (float)$b['est_high']);
$ratios = supplier_ratios((int)$b['supplier_id']);
$ratio = $b['actual_value'] !== null && $b['base_value'] > 0 ? $b['actual_value'] / $b['base_value'] : null;

page_header('Bale #' . $id);
?>
<h2>Bale #<?= $id ?> · <?= h($b['supplier']) ?></h2>
<?php if ($msg): ?><p class="alert"><?= h($msg) ?></p><?php endif; ?>

<div class="verdict <?= $cls ?>"><?= h($text) ?></div>

<div class="card">
  <div class="big">
    <small>Estimated value range</small>
    <strong><?= money($b['est_low']) ?> – <?= money($b['est_high']) ?></strong>
  </div>
  <dl>
    <dt>Asking price</dt><dd><?= money($b['asking_price']) ?></dd>
    <dt>Weight</dt><dd><?= h($b['weight_kg']) ?> kg</dd>
    <dt>Samples (A / B / C / Reject)</dt><dd><?= (int)$b['sample_a'] ?> / <?= (int)$b['sample_b'] ?> / <?= (int)$b['sample_c'] ?> / <?= (int)$b['sample_r'] ?></dd>
    <dt>Value from sample mix</dt><dd><?= money($b['base_value']) ?></dd>
    <dt>Supplier history</dt><dd><?= count($ratios) ?> opened bale<?= count($ratios) === 1 ? '' : 's' ?></dd>
  </dl>
  <?php if ($b['photo_path']): ?>
    <img class="photo" src="uploads/<?= h(basename($b['photo_path'])) ?>" alt="Sample photo">
  <?php endif; ?>
</div>

<div class="card">
  <h3>After you open the bale</h3>
  <?php if ($b['actual_value'] !== null): ?>
    <p>Actual value: <strong><?= money($b['actual_value']) ?></strong>
       (<?= number_format($ratio, 2) ?>× the sample-based value)</p>
  <?php else: ?>
    <p class="hint">Record what it was really worth. This teaches the app how reliable this supplier's samples are.</p>
  <?php endif; ?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="action" value="actual">
    <label>Actual value
      <input name="actual" type="number" step="1" min="0" inputmode="numeric" value="<?= h($b['actual_value'] ?? '') ?>" required>
    </label>
    <button class="primary">Save actual value</button>
  </form>
</div>

<form method="post" onsubmit="return confirm('Delete this bale?')">
  <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">
  <input type="hidden" name="action" value="delete">
  <button class="danger">Delete bale</button>
</form>
<?php page_footer('history');