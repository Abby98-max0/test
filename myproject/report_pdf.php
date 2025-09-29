<?php
require 'config.php';
require 'vendor/autoload.php'; // dompdf
use Dompdf\Dompdf;

// If ?id= is provided, generate for single assessment, else for all
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM assessments WHERE id = ?');
    $stmt->execute([$id]);
    $rows = [$stmt->fetch()];
} else {
    $rows = $pdo->query('SELECT * FROM assessments ORDER BY created_at DESC LIMIT 100')->fetchAll();
}

// Build HTML content for PDF
$html = '<html><head><meta charset="utf-8"><style>body{font-family:Arial}h1{color:#2c3e50}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:8px;text-align:left}</style></head><body>';
$html .= '<h1>NGO - Risk Assessment Report</h1>';
$html .= '<p>Generated: ' . date('Y-m-d H:i:s') . '</p>';
foreach ($rows as $r) {
    $html .= '<h2>Assessment #' . $r['id'] . ' - ' . htmlspecialchars($r['service_area']) . '</h2>';
    $html .= '<p><strong>Score:</strong> ' . $r['total_score'] . ' &nbsp; <strong>Profile:</strong> ' . $r['risk_profile'] . ' &nbsp; <strong>Status:</strong> ' . $r['status'] . ' &nbsp; <strong>Created:</strong> ' . $r['created_at'] . '</p>';
    $html .= '<h3>Vulnerabilities</h3><pre>' . htmlspecialchars($r['vulnerabilities']) . '</pre>';
    $html .= '<h3>Controls (user)</h3><pre>' . htmlspecialchars($r['controls_selected']) . '</pre>';
    $html .= '<h3>Mitigation Plan (mapped to NIST SP 800-53)</h3><pre>' . htmlspecialchars($r['mitigation_plan']) . '</pre>';
    $html .= '<hr/>';
}
$html .= '</body></html>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$filename = 'risk_report_' . date('Ymd_His') . '.pdf';
// Stream to browser
$dompdf->stream($filename, ['Attachment' => 0]); // set Attachment=>1 to force download
exit;


