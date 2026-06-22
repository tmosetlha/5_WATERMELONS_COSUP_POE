<?php
// ============================================================
//  COSUP V2 — user/my-referrals.php
//  My Referrals — View + Edit
//  Community Oriented Substance Use Programme
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

$page_title = 'My Referrals';
require_once __DIR__ . '/../includes/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['cosup_user_id'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$userId  = $_SESSION['cosup_user_id'];
$success = '';
$error   = '';

$conn = getDBConnection();

// ---- HANDLE EDIT SUBMISSION ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['referral_id'])) {
    $refId         = intval($_POST['referral_id']);
    $contactNumber = $conn->real_escape_string(trim($_POST['contact_number'] ?? ''));
    $area          = $conn->real_escape_string(trim($_POST['preferred_area']  ?? ''));
    $serviceNeeded = $conn->real_escape_string(trim($_POST['service_needed']  ?? ''));
    $message       = $conn->real_escape_string(trim($_POST['message']         ?? ''));

    $check = $conn->query(
        "SELECT referral_id, status FROM referrals
         WHERE referral_id = $refId AND user_id = $userId LIMIT 1"
    );

    if ($check && $check->num_rows > 0) {
        $existing = $check->fetch_assoc();
        if ($existing['status'] === 'pending') {
            $conn->query(
                "UPDATE referrals SET
                    contact_number = '$contactNumber',
                    preferred_area = '$area',
                    service_needed = '$serviceNeeded',
                    message        = '$message',
                    updated_at     = NOW()
                 WHERE referral_id = $refId AND user_id = $userId"
            );
            $success = 'Referral #' . $refId . ' updated successfully!';
        } else {
            $error = 'You can only edit referrals that are still pending.';
        }
    } else {
        $error = 'Referral not found or access denied.';
    }
}

// ---- FETCH ALL REFERRALS ----
$myReferrals = $conn->query(
    "SELECT * FROM referrals
     WHERE user_id = $userId
     ORDER BY submitted_at DESC"
)->fetch_all(MYSQLI_ASSOC);

$conn->close();

$areas = [
    'Pretoria CBD','Attridgeville','Bronkhorstspruit',
    'Daspoort','Eersterust','Garankuwa','Hammanskraal',
    'Laudium','Mamelodi','Olievenhoutbosch',
    'Soshanguve','Winterveldt','Other'
];

$services = [
    'Opioid Agonist Therapy',
    'Needle & Syringe Programme',
    'Psychosocial Support',
    'Skills Development & Vocational Training',
    'HIV & HCV Prevention',
    'Community Integration',
    'Not sure — need guidance'
];
?>

<section class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-text" data-aos="fade-up">
      <span class="section-tag">My Account</span>
      <h1 class="page-hero-title">My Referrals</h1>
      <p class="page-hero-desc">
        View, track and edit your COSUP referral submissions.
      </p>
    </div>
  </div>
</section>

<section class="section" style="background:var(--bg);padding-top:48px;">
  <div class="container">

    <?php if ($success): ?>
    <div class="auth-message success" style="margin-bottom:24px;">
      <i class="fa-solid fa-circle-check"></i>
      <?= htmlspecialchars($success) ?>
    </div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="auth-message error" style="margin-bottom:24px;">
      <i class="fa-solid fa-circle-xmark"></i>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <div style="display:flex;justify-content:space-between;
                align-items:center;margin-bottom:28px;
                flex-wrap:wrap;gap:16px;">
      <h2 style="font-family:'Playfair Display',serif;
                 font-size:24px;color:var(--text);">
        All Referrals
        <span style="font-size:16px;color:var(--muted);
                     font-family:'DM Sans',sans-serif;">
          (<?= count($myReferrals) ?>)
        </span>
      </h2>
      <a href="<?= BASE_URL ?>/user/referral-form.php"
         class="btn-primary"
         style="font-size:13px;padding:12px 24px;">
        <i class="fa-solid fa-plus"></i> New Referral
      </a>
    </div>

    <?php if (empty($myReferrals)): ?>
    <div class="dashboard-card">
      <div class="empty-state">
        <i class="fa-regular fa-clipboard"
           style="font-size:48px;color:var(--muted);
                  margin-bottom:16px;display:block;"></i>
        <p>You have not submitted any referrals yet.</p>
        <a href="<?= BASE_URL ?>/user/referral-form.php"
           class="contact-link"
           style="margin-top:8px;display:inline-block;">
          Submit your first referral &rarr;
        </a>
      </div>
    </div>

    <?php else: ?>
    <div style="display:flex;flex-direction:column;gap:16px;">
      <?php foreach ($myReferrals as $ref): ?>
      <div class="dashboard-card"
           id="ref-card-<?= $ref['referral_id'] ?>">

        <!-- Referral Summary Row — clickable -->
        <div onclick="toggleReferral(<?= $ref['referral_id'] ?>)"
             style="display:flex;align-items:center;gap:16px;
                    cursor:pointer;flex-wrap:wrap;">
          <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
            <span style="font-family:'Playfair Display',serif;
                          font-size:22px;font-weight:700;
                          color:var(--green);opacity:0.4;flex-shrink:0;">
              #<?= $ref['referral_id'] ?>
            </span>
            <div style="min-width:0;">
              <div style="font-size:14px;font-weight:700;
                           color:var(--text);margin-bottom:2px;">
                <?= htmlspecialchars($ref['service_needed'] ?? 'Service not specified') ?>
              </div>
              <div style="font-size:12px;color:var(--muted);">
                <i class="fa-solid fa-location-dot"></i>
                <?= htmlspecialchars($ref['preferred_area'] ?? '&mdash;') ?>
                &nbsp;&middot;&nbsp;
                <i class="fa-regular fa-clock"></i>
                <?= date('d M Y', strtotime($ref['submitted_at'])) ?>
              </div>
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:12px;flex-shrink:0;">
            <span class="status-badge status-<?= $ref['status'] ?>">
              <?= ucfirst($ref['status']) ?>
            </span>
            <?php if ($ref['status'] === 'pending'): ?>
            <span style="font-size:11px;color:var(--green);font-weight:600;">
              <i class="fa-solid fa-pen"></i> Editable
            </span>
            <?php endif; ?>
            <i class="fa-solid fa-chevron-down"
               id="chevron-<?= $ref['referral_id'] ?>"
               style="color:var(--muted);font-size:12px;
                      transition:transform 0.3s;"></i>
          </div>
        </div>

        <!-- Expandable Details / Edit Form -->
        <div id="details-<?= $ref['referral_id'] ?>"
             style="display:none;margin-top:20px;padding-top:20px;
                    border-top:1px solid var(--border2);">

          <?php if ($ref['status'] === 'pending'): ?>
          <!-- EDIT FORM -->
          <form method="POST" action="">
            <input type="hidden" name="referral_id"
                   value="<?= $ref['referral_id'] ?>"/>
            <div style="display:grid;grid-template-columns:1fr 1fr;
                        gap:16px;margin-bottom:16px;">

              <div class="form-group" style="margin:0;">
                <label>Contact Number</label>
                <div class="input-wrap">
                  <span class="input-icon">
                    <i class="fa-solid fa-mobile-screen"></i>
                  </span>
                  <input type="tel" name="contact_number"
                         value="<?= htmlspecialchars($ref['contact_number'] ?? '') ?>"
                         placeholder="+27 XX XXX XXXX"/>
                </div>
              </div>

              <div class="form-group" style="margin:0;">
                <label>Preferred Area</label>
                <div class="input-wrap">
                  <span class="input-icon">
                    <i class="fa-solid fa-location-dot"></i>
                  </span>
                  <select name="preferred_area">
                    <?php foreach ($areas as $a): ?>
                    <option value="<?= $a ?>"
                      <?= ($ref['preferred_area'] === $a) ? 'selected' : '' ?>>
                      <?= $a ?>
                    </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group" style="margin:0;grid-column:1/-1;">
                <label>Service Needed</label>
                <div class="input-wrap">
                  <span class="input-icon">
                    <i class="fa-solid fa-stethoscope"></i>
                  </span>
                  <select name="service_needed">
                    <?php foreach ($services as $svc): ?>
                    <option value="<?= $svc ?>"
                      <?= ($ref['service_needed'] === $svc) ? 'selected' : '' ?>>
                      <?= $svc ?>
                    </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group" style="margin:0;grid-column:1/-1;">
                <label>Message</label>
                <textarea name="message" rows="3"
                          style="width:100%;padding:12px 16px;
                                 border:1px solid var(--border);
                                 border-radius:10px;background:var(--bg);
                                 color:var(--text);font-family:inherit;
                                 font-size:14px;outline:none;resize:vertical;"
                          onfocus="this.style.borderColor='var(--green-light)'"
                          onblur="this.style.borderColor='var(--border)'"
                ><?= htmlspecialchars($ref['message'] ?? '') ?></textarea>
              </div>
            </div>

            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
              <button type="submit" class="btn-form-submit"
                      style="width:auto;padding:12px 28px;">
                <i class="fa-solid fa-floppy-disk"></i> Save Changes
              </button>
              <span style="font-size:12px;color:var(--muted);">
                <i class="fa-solid fa-circle-info"></i>
                Only pending referrals can be edited
              </span>
            </div>
          </form>

          <?php else: ?>
          <!-- READ ONLY -->
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

            <div>
              <div class="profile-label" style="margin-bottom:4px;">Contact Number</div>
              <div style="font-size:14px;color:var(--text);">
                <?= htmlspecialchars($ref['contact_number'] ?? '&mdash;') ?>
              </div>
            </div>

            <div>
              <div class="profile-label" style="margin-bottom:4px;">Preferred Area</div>
              <div style="font-size:14px;color:var(--text);">
                <?= htmlspecialchars($ref['preferred_area'] ?? '&mdash;') ?>
              </div>
            </div>

            <div style="grid-column:1/-1;">
              <div class="profile-label" style="margin-bottom:4px;">Service Needed</div>
              <div style="font-size:14px;color:var(--text);">
                <?= htmlspecialchars($ref['service_needed'] ?? '&mdash;') ?>
              </div>
            </div>

            <?php if (!empty($ref['message'])): ?>
            <div style="grid-column:1/-1;">
              <div class="profile-label" style="margin-bottom:4px;">Message</div>
              <div style="font-size:14px;color:var(--text);line-height:1.6;">
                <?= htmlspecialchars($ref['message']) ?>
              </div>
            </div>
            <?php endif; ?>

            <div>
              <div class="profile-label" style="margin-bottom:4px;">Source</div>
              <div style="font-size:14px;color:var(--text);">
                <?= ucfirst($ref['source']) ?>
              </div>
            </div>

            <div>
              <div class="profile-label" style="margin-bottom:4px;">Last Updated</div>
              <div style="font-size:14px;color:var(--text);">
                <?= date('d M Y H:i', strtotime($ref['updated_at'])) ?>
              </div>
            </div>
          </div>

          <div style="margin-top:16px;padding:12px 16px;
                      background:var(--bg2);border-radius:8px;
                      font-size:13px;color:var(--muted);">
            <i class="fa-solid fa-circle-info"></i>
            This referral is <strong><?= $ref['status'] ?></strong>
            and can no longer be edited.
            Contact COSUP if changes are needed.
          </div>
          <?php endif; ?>

        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div style="margin-top:28px;">
      <a href="<?= BASE_URL ?>/user/dashboard.php" class="contact-link">
        <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
      </a>
    </div>

  </div>
</section>

<script>
function toggleReferral(id) {
    var details = document.getElementById('details-' + id);
    var chevron = document.getElementById('chevron-' + id);
    if (!details) return;
    if (details.style.display === 'none' || details.style.display === '') {
        details.style.display = 'block';
        if (chevron) chevron.style.transform = 'rotate(180deg)';
    } else {
        details.style.display = 'none';
        if (chevron) chevron.style.transform = 'rotate(0deg)';
    }
}
<?php if ($success && preg_match('/#(\d+)/', $success, $m)): ?>
document.addEventListener('DOMContentLoaded', function() {
    toggleReferral(<?= intval($m[1]) ?>);
});
<?php endif; ?>
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>