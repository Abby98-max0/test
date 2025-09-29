<?php
require 'config.php';
// load control mappings to show suggested controls dynamically
$mapStmt = $pdo->query('SELECT * FROM control_mappings');
mappings = $mapStmt->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>NGO NIST RMF - Enhanced Assessment</title>
  <style>
    body{font-family:Arial;background:#f4f6f8;padding:20px}
    .container{max-width:900px;margin:auto;background:#fff;padding:20px;border-radius:8px;box-shadow:0 0 8px #ccc}
    label{display:block;margin-top:12px;font-weight:600}
    textarea,input,select{width:100%;padding:8px;margin-top:6px}
    button{margin-top:12px;padding:10px 14px;background:#2980b9;color:#fff;border:none;border-radius:6px}
    .hint{font-size:0.9em;color:#555}
    .mappings{background:#f9f9f9;padding:10px;border-radius:6px;margin-top:12px}
  </style>
</head>
<body>
<div class="container">
  <h1>NIST RMF DDoS Risk Assessment (Enhanced)</h1>
  <p class="hint">This form will map inputs to NIST SP 800-53 families and store the assessment.</p>
  <form method="post" action="submit.php">
    <label>Service Area</label>
    <select name="service_area" required>
      <option value="Website">Website / Donor Portal</option>
      <option value="Hosting">Hosting Infrastructure</option>
      <option value="Email">Email / Communication</option>
      <option value="Other">Other</option>
    </select>

    <label>Likelihood (1=Low, 5=High)</label>
    <input type="number" name="likelihood" min="1" max="5" value="3" required>

    <label>Impact (1=Low, 5=High)</label>
    <input type="number" name="impact" min="1" max="5" value="4" required>

    <label>Known Vulnerabilities (comma-separated keys or free text). Example keys: poor_backups, single_server, no_waf, no_monitoring</label>
    <input type="text" name="vulnerabilities" placeholder="poor_backups, single_server">

    <label>Controls already implemented (free text)</label>
    <textarea name="controls" rows="3"></textarea>

    <button type="submit">Assess & Save</button>
  </form>

  <div class="mappings">
    <h3>Known factor → NIST control mappings (editable in control_mappings table)</h3>
    <ul>
<?php foreach($mappings as $m): ?>
      <li><strong><?php echo htmlspecialchars($m['factor_key']); ?></strong>: <?php echo htmlspecialchars($m['factor_description']); ?> → <em><?php echo htmlspecialchars($m['nist_family']); ?></em> (<?php echo htmlspecialchars($m['nist_controls']); ?>)</li>
<?php endforeach; ?>
    </ul>
  </div>

  <p style="margin-top:10px"><a href="monitor.php">Open Monitoring Dashboard</a> | <a href="report_pdf.php">Generate Latest PDF Report</a></p>
</div>
</body>
</html>
