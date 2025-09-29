<?php
require 'config.php';
$rows = $pdo->query('SELECT * FROM assessments ORDER BY created_at DESC')->fetchAll();
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="assessments_export_' . date('Ymd_His') . '.csv"');
$out = fopen('php://output', 'w');
if ($rows) {
    fputcsv($out, array_keys($rows[0]));
    foreach ($rows as $r) fputcsv($out, $r);
}
fclose($out);
exit;

