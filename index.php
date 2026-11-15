<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/db.php';
require __DIR__ . '/valuation.php';
require __DIR__ . '/layout.php';

$errors = [];
$v = ['supplier' => '', 'weight' => '', 'asking' => '', 'a' => 0, 'b' => 0, 'c' => 0, 'r' => 0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $v['supplier'] = trim($_POST['supplier'] ?? '');
    $v['weight'] = $_POST['weight'] ?? '';
    $v['asking'] = $_POST['asking'] ?? '';
    foreach (['a', 'b', 'c', 'r'] as $k) $v[$k] = max(0, (int)($_POST[$k] ?? 0));

    if ($v['supplier'] === '' || strlen($v['supplier']) > 100) $errors[] = 'Enter the supplier name (max 100 characters).';
    if (!is_numeric($v['weight']) || $v['weight'] <= 0) $errors[] = 'Bale weight must be a number above 0.';
    if (!is_numeric($v['asking']) || $v['asking'] < 0) $errors[] = 'Asking price must be a number.';
    if ($v['a'] + $v['b'] + $v['c'] + $v['r'] === 0) $errors[] = 'Count at least one sample item.';

    $photo = null;
    if (!$errors && isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $f = $_FILES['photo'];
        $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if ($f['error'] !== UPLOAD_ERR_OK || $f['size'] > 5 * 1024 * 1024) {
            $errors[] = 'Photo upload failed or is bigger than 5 MB.';
        } else {
            $info = @getimagesize($f['tmp_name']);
            $mime = $info['mime'] ?? '';
            if (!isset($types[$mime])) {
                $errors[] = 'Photo must be JPG, PNG or WebP.';
            } else {
                $dir = __DIR__ . '/uploads';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $photo = bin2hex(random_bytes(8)) . '.' . $types[$mime];
                if (!move_uploaded_file($f['tmp_name'], "$dir/$photo")) {
                    $errors[] = 'Could not save the photo.';
                    $photo = null;
                }
            }
        }
    }

    if (!$errors) {
        try {
            $sid = supplier_id($v['supplier']);
            $val = valuate((float)$v['weight'], $v['a'], $v['b'], $v['c'], $v['r'], grade_prices(), supplier_ratios($sid));
            db()->prepare('INSERT INTO bales(supplier_id, weight_kg, asking_price, sample_a, sample_b, sample_c, sample_r,
                            base_value, est_low, est_high, photo_path) VALUES(?,?,?,?,?,?,?,?,?,?,?)')
                ->execute([$sid, $v['weight'], $v['asking'], $v['a'], $v['b'], $v['c'], $v['r'],
                           $val['base'], $val['low'], $val['high'], $photo]);
            $newId = db()->lastInsertId();
            header('Location: bale.php?id=' . $newId);
            exit;
        } catch (Throwable $e) {
            $errors[] = 'Save failed: ' . $e->getMessage();
        }
    }
}

$suppliers = db()->query('SELECT name FROM suppliers ORDER BY name')->fetchAll();

page_header('Value a bale');
?>
<h2>Value a bale</h2>
<?php foreach ($errors as $e): ?><p class="alert"><?= h($e) ?></p><?php endforeach; ?>

<form method="post" enctype="multipart/form-data" class="card">
  <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">

  <label>Supplier
    <input name="supplier" list="sup" value="<?= h($v['supplier']) ?>" autocomplete="off" required>
  </label>
  <datalist id="sup"><?php foreach ($suppliers as $s): ?><option value="<?= h($s['name']) ?>"><?php endforeach; ?></datalist>

  <div class="row">
    <label>Bale weight (kg)
      <input name="weight" type="number" step="0.1" min="0" inputmode="decimal" value="<?= h($v['weight']) ?>" required>
    </label>
    <label>Asking price
      <input name="asking" type="number" step="1" min="0" inputmode="numeric" value="<?= h($v['asking']) ?>" required>
    </label>
  </div>

  <p class="hint">Open a few sample items and count them by grade:</p>
  <div class="grid4">
    <label>Grade A<input name="a" type="number" min="0" inputmode="numeric" value="<?= h($v['a']) ?>"></label>
    <label>Grade B<input name="b" type="number" min="0" inputmode="numeric" value="<?= h($v['b']) ?>"></label>
    <label>Grade C<input name="c" type="number" min="0" inputmode="numeric" value="<?= h($v['c']) ?>"></label>
    <label>Reject<input name="r" type="number" min="0" inputmode="numeric" value="<?= h($v['r']) ?>"></label>
  </div>

  <label>Sample photo (opens the camera on a phone)
    <input name="photo" type="file" accept="image/*" capture="environment">
  </label>

  <button type="submit" class="primary">Estimate value</button>
</form>
<?php page_footer('value');