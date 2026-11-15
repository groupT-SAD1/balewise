<?php
require __DIR__ . '/config.php';
session_start();

function db(): PDO {
    static $pdo = null;
    if (!$pdo) {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }
    return $pdo;
}

function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money($n): string { return number_format((float)$n, 0); }

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function csrf_check(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('Invalid request. Go back and try again.');
    }
}

function supplier_id(string $name): int {
    $st = db()->prepare('SELECT id FROM suppliers WHERE name = ?');
    $st->execute([$name]);
    if ($row = $st->fetch()) return (int)$row['id'];
    db()->prepare('INSERT INTO suppliers(name) VALUES(?)')->execute([$name]);
    return (int)db()->lastInsertId();
}

function grade_prices(): array {
    $out = ['A' => 0, 'B' => 0, 'C' => 0, 'R' => 0];
    foreach (db()->query('SELECT grade, price_per_kg FROM grade_prices') as $r) {
        $out[$r['grade']] = (float)$r['price_per_kg'];
    }
    return $out;
}

function supplier_ratios(int $sid): array {
    $st = db()->prepare('SELECT actual_value / base_value AS r FROM bales
                         WHERE supplier_id = ? AND actual_value IS NOT NULL AND base_value > 0');
    $st->execute([$sid]);
    return array_map('floatval', array_column($st->fetchAll(), 'r'));
}