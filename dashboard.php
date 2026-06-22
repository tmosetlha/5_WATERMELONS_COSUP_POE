<?php
// ============================================================
//  COSUP V2 — user/dashboard.php
//  User Dashboard
//  Community Oriented Substance Use Programme
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

$page_title = 'My Dashboard';
require_once __DIR__ . '/../includes/header.php';

// Session check — redirect if not logged in
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['cosup_user_id'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$userId    = $_SESSION['cosup_user_id'];
$firstName = $_SESSION['cosup_first_name'] ?? 'User';
$role      = $_SESSION['cosup_role']       ?? 'user';

// Fetch user data + referrals from DB
$conn = getDBConnection();

$userRow = $conn->query(
    "SELECT * FROM users WHERE user_id = $userId LIMIT 1"
)->fetch_assoc();

$myReferrals = $conn->query(
    "SELECT * FROM referrals
     WHERE user_id = $userId
     ORDER BY submitted_at DESC
     LIMIT 5"
)->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<section class="dashboard-hero">
  <div class="container">
    <div class="dashboard-welcome" data-aos="fade-up">
      <div class="dashboard-avatar">
        <?= strtoupper(substr($firstName, 0, 1)) ?>
      </div>
      <div class="dashboard-welcome-text">
        <h1>Welcome back, <?= htmlspecialchars($firstName) ?>!</h1>
        <p>
          <?= htmlspecialchars($userRow['email'] ?? '') ?> ·
          <span class="role-badge role-<?= $role ?>">
            <?= ucfirst($role) ?>
          </span>
        </p>
      </div>
      <?php if (in_array($role, ['admin','superadmin'])): ?>
      <a href="<?= BASE_URL ?>/admin/dashboard.php"
         class="btn-primary">
        Admin Dashboard
      </a>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ===== DASHBOARD GRID ===== -->
<section class="section" style="background:var(--bg);padding-top:48px;">
  <div class="container">
    <div class="dashboard-grid">

      <!-- Quick Actions -->
      <div class="dashboard-card" data-aos="fade-up">
        <h3 class="dashboard-card-title">Quick Actions</h3>
        <div class="quick-actions">
          <a href="<?= BASE_URL ?>/user/referral-form.php"
             class="quick-action-btn">
            <span><i class="fa-regular fa-clipboard"></i></span>
            <div>
              <strong>Submit Referral</strong>
              <small>Request COSUP support</small>
            </div>
          </a>
          <a href="<?= BASE_URL ?>/pages/find-a-site.php"
             class="quick-action-btn">
            <span><i class="fa-solid fa-location-dot"></i></span>
            <div>
              <strong>Find a Site</strong>
              <small>16 sites across Tshwane</small>
            </div>
          </a>
          <a href="<?= BASE_URL ?>/pages/services.php"
             class="quick-action-btn">
            <span><i class="fa-solid fa-stethoscope"></i></span>
            <div>
              <strong>Our Services</strong>
              <small>Free harm reduction services</small>
            </div>
          </a>
          <a href="tel:0800611197"
             class="quick-action-btn hopeline-action"
             onclick="logHopelineClick('call')">
            <span><i class="fa-solid fa-circle-exclamation"></i></span>
            <div>
              <strong>Hopeline</strong>
              <small>0800 611 197 — Always free</small>
            </div>
          </a>
        </div>
      </div>

      <!-- My Profile -->
      <div class="dashboard-card" data-aos="fade-up" data-delay="100">
        <h3 class="dashboard-card-title">My Profile</h3>
        <div class="profile-info">
          <div class="profile-row">
            <span class="profile-label">Name</span>
            <span class="profile-value">
              <?= htmlspecialchars(
                  ($userRow['first_name'] ?? '') . ' ' .
                  ($userRow['last_name']  ?? '')
              ) ?>
            </span>
          </div>
          <div class="profile-row">
            <span class="profile-label">Email</span>
            <span class="profile-value">
              <?= htmlspecialchars($userRow['email'] ?? '') ?>
            </span>
          </div>
          <div class="profile-row">
            <span class="profile-label">Phone</span>
            <span class="profile-value">
              <?= htmlspecialchars($userRow['phone'] ?? 'Not provided') ?>
            </span>
          </div>
          <div class="profile-row">
            <span class="profile-label">Language</span>
            <span class="profile-value">
              <?php
              $langs = [
                  'en'=>'English','zu'=>'IsiZulu','ts'=>'Xitsonga',
                  've'=>'TshiVenda','tn'=>'SeTswana/Sepedi',
                  'af'=>'Afrikaans','xh'=>'Xhosa'
              ];
              echo $langs[$userRow['lang_pref'] ?? 'en'] ?? 'English';
              ?>
            </span>
          </div>
          <div class="profile-row">
            <span class="profile-label">Email Verified</span>
            <span class="profile-value">
              <?= $userRow['email_verified']
                  ? '<span style="color:var(--green);font-weight:700;"><i class="fa-solid fa-circle-check"></i> Verified</span>'
                  : '<span style="color:var(--red);"><i class="fa-solid fa-circle-xmark"></i> Not verified</span>' ?>
            </span>
          </div>
          <div class="profile-row">
            <span class="profile-label">Member Since</span>
            <span class="profile-value">
              <?= date('d F Y', strtotime($userRow['created_at'] ?? 'now')) ?>
            </span>
          </div>
        </div>
        <a href="<?= BASE_URL ?>/user/profile.php"
           class="btn-outline"
           style="margin-top:20px;display:inline-flex;
                  font-size:13px;padding:10px 24px;">
          Edit Profile
        </a>
      </div>

      <!-- My Referrals -->
      <div class="dashboard-card dashboard-card-wide"
           data-aos="fade-up" data-delay="200">
        <div style="display:flex;justify-content:space-between;
                    align-items:center;margin-bottom:20px;">
          <h3 class="dashboard-card-title" style="margin:0;">
            My Referrals
          </h3>
          <a href="<?= BASE_URL ?>/user/referral-form.php"
             class="btn-primary"
             style="font-size:13px;padding:10px 20px;">
            + New Referral
          </a>
        </div>

        <?php if (empty($myReferrals)): ?>
        <div class="empty-state">
          <div style="font-size:40px;margin-bottom:12px;"><i class="fa-regular fa-clipboard"></i></div>
          <p>No referrals submitted yet.</p>
          <a href="<?= BASE_URL ?>/user/referral-form.php"
             class="contact-link">
            Submit your first referral →
          </a>
        </div>
        <?php else: ?>
        <div class="referrals-table-wrap">
          <table class="referrals-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Service Needed</th>
                <th>Area</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($myReferrals as $ref): ?>
              <tr>
                <td>
                  <?= date('d M Y', strtotime($ref['submitted_at'])) ?>
                </td>
                <td>
                  <?= htmlspecialchars($ref['service_needed'] ?? '—') ?>
                </td>
                <td>
                  <?= htmlspecialchars($ref['preferred_area'] ?? '—') ?>
                </td>
                <td>
                  <span class="status-badge status-<?= $ref['status'] ?>">
                    <?= ucfirst($ref['status']) ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>

    </div><!-- end dashboard-grid -->
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>