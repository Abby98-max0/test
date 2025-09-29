<?php
require 'config.php';

// update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $stmt = $pdo->prepare('UPDATE assessments SET status=? WHERE id=?');
    $stmt->execute([$_POST['status'], (int)$_POST['update_id']]);
}

$rows = $pdo->query('SELECT * FROM assessments ORDER BY created_at DESC')->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Monitoring Dashboard</title>
<style>body{font-family:Arial;background:#f4f6f8;padding:20px}.container{max-width:1100px;margin:auto;background:#fff;padding:18px;border-radius:8px;box-shadow:0 0 8px #ccc}table{width:100%;border-collapse:collapse}th,td{padding:8px;border:1px solid #eee}</style>
</head>
<body>
<div class="container">
  <h1>Monitoring Dashboard</h1>
  <p><a href="index.php">New Assessment</a> | <a href="report_pdf.php">Generate PDF Report</a></p>
  <table>
    <thead><tr><th>ID</th><th>Service</th><th>Score</th><th>Profile</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
    <tbody>
<?php foreach($rows as $r): ?>
<tr>
  <td><?php echo $r['id']; ?></td>
  <td><?php echo htmlspecialchars($r['service_area']); ?></td>
  <td><?php echo $r['total_score']; ?></td>
  <td><?php echo $r['risk_profile']; ?></td>
  <td><?php echo $r['status']; ?></td>
  <td><?php echo $r['created_at']; ?></td>
  <td>
    <form method="post" style="display:inline">
      <input type="hidden" name="update_id" value="<?php echo $r['id']; ?>">
      <select name="status">
        <option <?php if($r['status']=='Pending') echo 'selected'; ?>>Pending</option>
        <option <?php if($r['status']=='Implemented') echo 'selected'; ?>>Implemented</option>
        <option <?php if($r['status']=='Verified') echo 'selected'; ?>>Verified</option>
      </select>
      <button type="submit">Update</button>
    </form>
    <a href="view.php?id=<?php echo $r['id']; ?>">View</a>
  </td>
</tr>
<?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>

