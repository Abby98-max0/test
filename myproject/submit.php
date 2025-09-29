<?php
require 'config.php';

function clamp($v){ $i=(int)$v; if($i<1) $i=1; if($i>5) $i=5; return $i; }

$service_area = $_POST['service_area'] ?? 'Other';
$likelihood = clamp($_POST['likelihood'] ?? 1);
$impact = clamp($_POST['impact'] ?? 1);
$vuln_input = trim($_POST['vulnerabilities'] ?? '');
$controls_input = trim($_POST['controls'] ?? '');

// score & profile
$score = $likelihood * $impact; // basic
if ($score <= 6) $profile = 'LOW';
elseif ($score <= 12) $profile = 'MEDIUM';
elseif ($score <= 16) $profile = 'HIGH';
else $profile = 'CRITICAL';

// Map vulnerabilities to NIST controls using control_mappings
$found_controls = [];
if ($vuln_input !== '') {
    $keys = array_map('trim', explode(',', $vuln_input));
    $placeholders = rtrim(str_repeat('?,', count($keys)), ',');
    $stmt = $pdo->prepare("SELECT * FROM control_mappings WHERE factor_key IN ($placeholders)");
    $stmt->execute($keys);
    $rows = $stmt->fetchAll();
    foreach ($rows as $r) {
        $found_controls[] = [$r['factor_key'], $r['nist_family'], $r['nist_controls']];
    }
}

// Build mitigation plan (merge recommended controls + generic plan)
$plan = "Recommended NIST Controls:\n";
if (!empty($found_controls)) {
    foreach ($found_controls as $fc) {
        $plan .= sprintf("- %s → %s (%s)\n", $fc[0], $fc[1], $fc[2]);
    }
} else {
    $plan .= "- No mapped factors found; apply baseline NIST controls for DDoS (see SC, CP, IR, SI).\n";
}
$plan .= "\nSuggested Actions:\n- Deploy DDoS mitigation (CDN/WAF/Shield)\n- Implement redundancy/autoscaling\n- Schedule automated & tested backups\n- Configure monitoring & incident response\";

// insert assessment
$dsn  = "MySQL:host=localhost;dbname=db;charset=utf8mb4";
$user = "root";
$pass = "";

$pdo = new PDO($dsn, $user, $pass);

$pdo = new PDO($dsn, $user, $pass);
$stmt = $pdo->prepare("INSERT INTO assessments
(service_area,likelihood,impact,vulnerabilities,controls_selected,total_score,risk_profile,mitigation_plan)
VALUES (?,?,?,?,?,?,?,?)");
$stmt->execute([$service_area,$likelihood,$impact,$vuln_input,$controls_input,$score,$profile,$plan]);

header('Location: view.php?id=' . $pdo->lastInsertId());
exit;
?>
