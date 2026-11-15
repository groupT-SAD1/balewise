<?php
require __DIR__ . '/db.php';
require __DIR__ . '/layout.php';

$bales = db()->query('SELECT b.id, b.weight_kg, b.asking_price, b.est_low, b.est_high, b.actual_value, b.created_at, s.name AS supplier
                      FROM bales b JOIN suppliers s ON s.id = b.supplier_id ORDER BY b.id DESC')->fetchAll();

$sup = db()->query('SELECT s.name, COUNT(b.id) AS n, SUM(b.actual_value IS NOT NULL) AS opened,
                           AVG(b.actual_value / NULLIF(b.base_value, 0)) AS factor
                    FROM suppliers s LEFT JOIN bales b ON b.supplier_id = s.id
                    GROUP BY s.id, s.name ORDER BY n DESC')->fetchAll();

page_header('History');
?>
<h2>Supplier reliability</h2>
<?php if (!$sup): ?><p class="hint">No suppliers yet.</p><?php else: ?>
<div class="card">
  <table>
    <tr><th>Supplier</th><th>Bales</th><th>Opened</th><th>Factor</th></tr>
    <?php foreach ($sup as $s): ?>
      <tr>
        <td><?= h($s['name']) ?></td>
        <td><?= (int)$s['n'] ?></td>
        <td><?= (int)$s['opened'] ?></td>
        <td><?= $s['factor'] !== null ? number_format($s['factor'], 2) : '–' ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <p class="hint">Factor below 1.00 means that supplier's bales usually turn out worth less than their samples suggest.</p>
</div>
<?php endif; ?>

<h2>All bales</h2>
<?php if (!$bales): ?><p class="hint">No bales saved yet. Start on the Value tab.</p><?php endif; ?>
<?php foreach ($bales as $b): ?>
  <a class="item" href="bale.php?id=<?= (int)$b['id'] ?>">
    <div><strong><?= h($b['supplier']) ?></strong> · <?= h($b['weight_kg']) ?> kg
      <small><?= h(date('d M Y', strtotime($b['created_at']))) ?></small></div>
    <div>Asking <?= money($b['asking_price']) ?> · Est. <?= money($b['est_low']) ?>–<?= money($b['est_high']) ?></div>
    <div class="<?= $b['actual_value'] === null ? 'pending' : 'done' ?>">
      <?= $b['actual_value'] === null ? 'Not opened yet' : 'Actual ' . money($b['actual_value']) ?>
    </div>
  </a>
<?php endforeach; ?>
<?php page_footer('history');