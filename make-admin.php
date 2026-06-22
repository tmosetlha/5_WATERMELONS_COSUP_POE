<?php
// ============================================================
//  COSUP V2 — make-admin.php
//  Run this file ONCE then DELETE IT immediately!
//  Visit: http://localhost/COSUP_V2/make-admin.php
// ============================================================
require_once __DIR__ . '/config/DBConn.php';

$conn = getDBConnection();

// Get all users
$users = $conn->query("SELECT user_id, first_name, last_name, email, role FROM users")->fetch_all(MYSQLI_ASSOC);

$message = '';

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $uid = intval($_POST['user_id']);
    $conn->query("UPDATE users SET role = 'superadmin' WHERE user_id = $uid");
    $message = 'Done! User is now superadmin. DELETE this file now!';
    // Refresh users list
    $users = $conn->query("SELECT user_id, first_name, last_name, email, role FROM users")->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>COSUP — Make Admin</title>
  <style>
    body { font-family: DM Sans, sans-serif; background: #0a1410; color: #e4f0e8; padding: 40px; }
    h2 { color: #3BA53E; margin-bottom: 24px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    th { text-align: left; padding: 10px; background: #152b1e; color: #a0c8b0; font-size: 12px; text-transform: uppercase; }
    td { padding: 12px 10px; border-bottom: 1px solid #1a3424; font-size: 14px; }
    .btn { padding: 8px 20px; background: #3BA53E; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 700; }
    .role-superadmin { color: #f0c93a; font-weight: 700; }
    .role-user { color: #6a9878; }
    .msg { background: rgba(59,165,62,0.15); border: 1px solid rgba(59,165,62,0.3); padding: 16px 20px; border-radius: 8px; color: #3BA53E; font-weight: 700; margin-bottom: 24px; }
    .warning { background: rgba(224,90,58,0.15); border: 1px solid rgba(224,90,58,0.3); padding: 16px 20px; border-radius: 8px; color: #e05a3a; margin-top: 24px; }
  </style>
</head>
<body>
  <h2>COSUP — Set Superadmin</h2>

  <?php if ($message): ?>
  <div class="msg"><?= $message ?></div>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Current Role</th>
        <th>Make Superadmin</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
      <tr>
        <td><?= $u['user_id'] ?></td>
        <td><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td class="role-<?= $u['role'] ?>"><?= ucfirst($u['role']) ?></td>
        <td>
          <?php if ($u['role'] !== 'superadmin'): ?>
          <form method="POST">
            <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>"/>
            <button type="submit" class="btn">Make Superadmin</button>
          </form>
          <?php else: ?>
          <span class="role-superadmin">Already Superadmin ✓</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="warning">
    ⚠️ <strong>DELETE this file immediately after use!</strong><br/>
    Do not leave make-admin.php on your server.
  </div>

  <br/>
  <a href="<?= 'http://localhost/COSUP_V2/admin/dashboard.php' ?>"
     style="display:inline-block;margin-top:16px;padding:12px 28px;
            background:#3BA53E;color:white;border-radius:8px;
            font-weight:700;text-decoration:none;">
    Go to Admin Dashboard &rarr;
  </a>
</body>
</html>