<?php
require 'config.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) die('Missing id');
$stmt = $pdo->prepare('SELECT * FROM assessments WHERE id = ?');
$stmt->execute([$id]);
$r = $stmt->fetch();
if (!$r) die('Not found');
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Assessment #<?php echo $r['id']; ?></title>
<style>body{font-family:Arial;padding:18px;background:#f4f6f8}.box{max-width:900px;margin:auto;background:#fff;padding:18px;border-radius:8px;box-shadow:0 0 8px #ccc}</style>
</head>
<body>
<div class="box">
  <h2>Assessment #<?php echo $r['id']; ?></h2>
  <p><strong>Service:</strong> <?php echo htmlspecialchars($r['service_area']); ?></p>
  <p><strong>Likelihood:</strong> <?php echo $r['likelihood']; ?> &nbsp; <strong>Impact:</strong> <?php echo $r['impact']; ?></p>
  <p><strong>Score:</strong> <?php echo $r['total_score']; ?> &nbsp; <strong>Profile:</strong> <?php echo $r['risk_profile']; ?></p>
  <h3>Vulnerabilities</h3>
  <pre><?php echo htmlspecialchars($r['vulnerabilities']); ?></pre>
  <h3>Controls (user)</h3>
  <pre><?php echo htmlspecialchars($r['controls_selected']); ?></pre>
  <h3>Mitigation Plan</h3>
  <pre><?php echo htmlspecialchars($r['mitigation_plan']); ?></pre>
  <p><a href="monitor.php">Back to dashboard</a> | <a href="report_pdf.php?id=<?php echo $r['id']; ?>">Generate PDF for this assessment</a></p>
</div>
</body>
</html>
