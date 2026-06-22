<?php
// ============================================================
//  COSUP — config/db_test.php
//  Database Connection Verifier
//  Project Folder: COSUP_V2
//  Visit: http://localhost/COSUP_V2/config/db_test.php
//  DELETE THIS FILE before going live on production!
// ============================================================

require_once 'DBConn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>COSUP V2 — DB Connection Test</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;600;700&display=swap');

    * { margin:0; padding:0; box-sizing:border-box; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: #0a0f0b;
      color: #e4f0e8;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    .test-card {
      background: linear-gradient(135deg, #0d1f15 0%, #152b1e 100%);
      border: 1px solid #1a3424;
      border-radius: 20px;
      padding: 48px;
      max-width: 640px;
      width: 100%;
      box-shadow: 0 24px 64px rgba(0,0,0,0.5);
    }

    .logo {
      font-size: 32px;
      font-weight: 900;
      color: #3BA53E;
      letter-spacing: -1px;
      margin-bottom: 4px;
    }

    .logo span { color: #f0c93a; }

    .version-tag {
      display: inline-block;
      background: rgba(59,165,62,0.15);
      border: 1px solid rgba(59,165,62,0.3);
      color: #3BA53E;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 2px;
      text-transform: uppercase;
      padding: 4px 12px;
      border-radius: 50px;
      margin-bottom: 8px;
    }

    .subtitle {
      font-size: 13px;
      color: #6a9878;
      letter-spacing: 2px;
      text-transform: uppercase;
      margin-bottom: 36px;
    }

    .test-title {
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 24px;
      color: #e4f0e8;
    }

    .result-row {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 14px 18px;
      border-radius: 10px;
      margin-bottom: 12px;
      font-size: 14px;
      font-weight: 500;
    }

    .result-row.success {
      background: rgba(59,165,62,0.12);
      border: 1px solid rgba(59,165,62,0.3);
      color: #5adb5e;
    }

    .result-row.error {
      background: rgba(224,90,58,0.12);
      border: 1px solid rgba(224,90,58,0.3);
      color: #ff7a5a;
    }

    .result-row.info {
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.08);
      color: #a0c8b0;
    }

    .badge { font-size: 18px; flex-shrink: 0; }

    .tables-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      margin-top: 20px;
    }

    .table-chip {
      background: rgba(59,165,62,0.1);
      border: 1px solid rgba(59,165,62,0.25);
      border-radius: 8px;
      padding: 8px 12px;
      font-size: 12px;
      font-weight: 600;
      color: #3BA53E;
      text-align: center;
    }

    .warning-box {
      margin-top: 28px;
      background: rgba(240,201,58,0.08);
      border: 1px solid rgba(240,201,58,0.25);
      border-radius: 10px;
      padding: 14px 18px;
      font-size: 12px;
      color: #f0c93a;
      line-height: 1.6;
    }

    .divider {
      height: 1px;
      background: rgba(255,255,255,0.06);
      margin: 24px 0;
    }

    code {
      background: rgba(255,255,255,0.08);
      padding: 2px 6px;
      border-radius: 4px;
      font-size: 11px;
    }
  </style>
</head>
<body>
<div class="test-card">

  <div class="logo">COSUP</div>
  <div class="version-tag">V2</div>
  <div class="subtitle">Database Connection Test</div>
  <div class="test-title">🔬 Running diagnostics for COSUP_V2...</div>

  <?php

  // ---- TEST 1: MySQLi Connection ----
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if ($conn->connect_error) {
      echo '<div class="result-row error">
              <span class="badge">❌</span>
              <span>MySQLi Connection FAILED: ' . htmlspecialchars($conn->connect_error) . '</span>
            </div>';
  } else {
      echo '<div class="result-row success">
              <span class="badge">✅</span>
              <span>MySQLi Connection — <strong>SUCCESS</strong></span>
            </div>';

      // ---- TEST 2: Database name ----
      echo '<div class="result-row info">
              <span class="badge">🗄️</span>
              <span>Database: <strong>' . DB_NAME . '</strong> on <strong>' . DB_HOST . '</strong></span>
            </div>';

      // ---- TEST 3: Project path ----
      echo '<div class="result-row info">
              <span class="badge">📁</span>
              <span>Project Base URL: <strong>' . BASE_URL . '</strong></span>
            </div>';

      // ---- TEST 4: Server info ----
      echo '<div class="result-row info">
              <span class="badge">⚙️</span>
              <span>MySQL Version: <strong>' . $conn->server_info . '</strong></span>
            </div>';

      // ---- TEST 5: Character set ----
      $conn->set_charset(DB_CHARSET);
      echo '<div class="result-row info">
              <span class="badge">🔤</span>
              <span>Character Set: <strong>' . DB_CHARSET . '</strong></span>
            </div>';

      // ---- TEST 6: Check all 9 tables ----
      $expected_tables = [
          'users','sessions','sites','services',
          'site_services','referrals','news_items',
          'hopeline_logs','audit_logs'
      ];

      $result     = $conn->query("SHOW TABLES");
      $existing   = [];
      while ($row = $result->fetch_array()) {
          $existing[] = $row[0];
      }

      $all_found = true;
      foreach ($expected_tables as $t) {
          if (!in_array($t, $existing)) { $all_found = false; }
      }

      if ($all_found) {
          echo '<div class="result-row success">
                  <span class="badge">✅</span>
                  <span>All <strong>9 tables</strong> verified in cosup_db</span>
                </div>';
      } else {
          echo '<div class="result-row error">
                  <span class="badge">❌</span>
                  <span>Some tables are missing — re-import cosup_db.sql</span>
                </div>';
      }

      // ---- TEST 7: Row counts ----
      echo '<div class="divider"></div>';
      echo '<div class="test-title">📊 Table Row Counts</div>';
      echo '<div class="tables-grid">';
      foreach ($expected_tables as $t) {
          $r   = $conn->query("SELECT COUNT(*) as cnt FROM `$t`");
          $cnt = $r ? $r->fetch_assoc()['cnt'] : '?';
          echo '<div class="table-chip">' . $t . '<br/><strong>' . $cnt . ' rows</strong></div>';
      }
      echo '</div>';

      // ---- TEST 8: PDO Connection ----
      echo '<div class="divider"></div>';
      try {
          $pdo = getPDOConnection();
          echo '<div class="result-row success">
                  <span class="badge">✅</span>
                  <span>PDO Connection (Android API bridge) — <strong>SUCCESS</strong></span>
                </div>';
      } catch (Exception $e) {
          echo '<div class="result-row error">
                  <span class="badge">❌</span>
                  <span>PDO FAILED: ' . htmlspecialchars($e->getMessage()) . '</span>
                </div>';
      }

      // ---- TEST 9: Views check ----
      $views          = ['vw_sites_with_services','vw_published_news','vw_pending_referrals'];
      $vresult        = $conn->query("SHOW FULL TABLES WHERE Table_type = 'VIEW'");
      $existing_views = [];
      while ($row = $vresult->fetch_array()) {
          $existing_views[] = $row[0];
      }
      $views_ok = count(array_intersect($views, $existing_views)) === count($views);

      if ($views_ok) {
          echo '<div class="result-row success">
                  <span class="badge">✅</span>
                  <span>All <strong>3 database views</strong> verified</span>
                </div>';
      } else {
          echo '<div class="result-row error">
                  <span class="badge">❌</span>
                  <span>Views missing — re-import cosup_db.sql</span>
                </div>';
      }

      $conn->close();
  }
  ?>

  <div class="warning-box">
    ⚠️ <strong>DELETE BEFORE GOING LIVE:</strong>
    Remove <code>COSUP_V2/config/db_test.php</code> before deploying
    to production. This file exposes your database structure.
  </div>

</div>
</body>
</html>