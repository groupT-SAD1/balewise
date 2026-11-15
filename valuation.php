<?php
function valuate(float $kg, int $a, int $b, int $c, int $r, array $p, array $ratios): array {
    $n = $a + $b + $c + $r;
    $perKg = $n === 0 ? 0 : ($a * $p['A'] + $b * $p['B'] + $c * $p['C'] + $r * $p['R']) / $n;
    $base = $kg * $perKg;

    $count = count($ratios);
    $factor = 1.0;
    $spread = 0.15;
    if ($count > 0) {
        $factor = array_sum($ratios) / $count;
        if ($count > 1) {
            $var = 0;
            foreach ($ratios as $x) $var += ($x - $factor) ** 2;
            $sd = sqrt($var / ($count - 1));
        } else {
            $sd = 0.10;
        }
        $spread = max(0.08, min(0.30, $sd));
    }
    return [
        'base' => $base,
        'factor' => $factor,
        'history' => $count,
        'low' => $base * $factor * (1 - $spread),
        'high' => $base * $factor * (1 + $spread),
    ];
}

function verdict(float $asking, float $base, float $low, float $high): array {
    if ($base <= 0) return ['warn', 'Not enough sample data for a verdict.'];
    if ($asking <= $low)  return ['good', 'GOOD BUY: asking price is below the estimated range.'];
    if ($asking <= $high) return ['fair', 'FAIR: inside the estimated range. Negotiate toward the low end.'];
    return ['bad', 'RISKY: asking price is above the estimated range.'];
}