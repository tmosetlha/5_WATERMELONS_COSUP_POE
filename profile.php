<?php
// ============================================================
//  COSUP V2 — user/profile.php
//  User Profile Edit Page
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

$page_title = 'My Profile';
require_once __DIR__ . '/../includes/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['cosup_user_id'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$userId  = $_SESSION['cosup_user_id'];
$success = '';
$error   = '';

$conn    = getDBConnection();
$userRow = $conn->query(
    "SELECT * FROM users WHERE user_id = $userId LIMIT 1"
)->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $conn->real_escape_string(trim($_POST['first_name'] ?? ''));
    $lastName  = $conn->real_escape_string(trim($_POST['last_name']  ?? ''));
    $phone     = $conn->real_escape_string(trim($_POST['phone']      ?? ''));
    $langPref  = $conn->real_escape_string(trim($_POST['lang_pref']  ?? 'en'));

    if (empty($firstName) || empty($lastName)) {
        $error = 'First and last name are required.';
    } else {
        $conn->query(
            "UPDATE users SET
                first_name = '$firstName',
                last_name  = '$lastName',
                phone      = '$phone',
                lang_pref  = '$langPref',
                updated_at = NOW()
             WHERE user_id = $userId"
        );
        $_SESSION['cosup_first_name'] = $firstName;
        $success = 'Profile updated successfully!';
        $userRow = $conn->query(
            "SELECT * FROM users WHERE user_id = $userId LIMIT 1"
        )->fetch_assoc();
    }
}
$conn->close();
?>

<section class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-text" data-aos="fade-up">
      <span class="section-tag">Account</span>
      <h1 class="page-hero-title">My Profile</h1>
      <p class="page-hero-desc">
        Update your personal details and preferences.
      </p>
    </div>
  </div>
</section>

<section class="section" style="background:var(--bg);padding-top:60px;">
  <div class="container">
    <div style="max-width:600px;margin:0 auto;">

      <?php if ($success): ?>
      <div class="auth-message success" style="margin-bottom:24px;">
        <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?>
      </div>
      <?php endif; ?>

      <?php if ($error): ?>
      <div class="auth-message error" style="margin-bottom:24px;">
        <i class="fa-solid fa-circle-xmark"></i> <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <div class="dashboard-card">
        <h3 class="dashboard-card-title">Personal Information</h3>
        <form method="POST" action="">
          <div class="form-row" style="margin-bottom:18px;">
            <div class="form-group" style="margin:0;">
              <label>First Name</label>
              <div class="input-wrap">
                <span class="input-icon"><i class="fa-regular fa-user"></i></span>
                <input type="text" name="first_name"
                       value="<?= htmlspecialchars($userRow['first_name'] ?? '') ?>"
                       required/>
              </div>
            </div>
            <div class="form-group" style="margin:0;">
              <label>Last Name</label>
              <div class="input-wrap">
                <span class="input-icon"><i class="fa-regular fa-user"></i></span>
                <input type="text" name="last_name"
                       value="<?= htmlspecialchars($userRow['last_name'] ?? '') ?>"
                       required/>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Phone Number</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="fa-solid fa-mobile-screen"></i></span>
              <input type="tel" name="phone"
                     value="<?= htmlspecialchars($userRow['phone'] ?? '') ?>"
                     placeholder="+27 XX XXX XXXX"/>
            </div>
          </div>
          <div class="form-group">
            <label>Preferred Language</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="fa-solid fa-globe"></i></span>
              <select name="lang_pref">
                <?php
                $langs = [
                    'en'=>'English','zu'=>'IsiZulu',
                    'ts'=>'Xitsonga','ve'=>'TshiVenda',
                    'tn'=>'SeTswana / Sepedi',
                    'af'=>'Afrikaans','xh'=>'Xhosa'
                ];
                foreach ($langs as $code => $label):
                    $sel = ($userRow['lang_pref'] ?? 'en') === $code
                           ? 'selected' : '';
                ?>
                <option value="<?= $code ?>" <?= $sel ?>>
                  <?= $label ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Email Address</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
              <input type="email"
                     value="<?= htmlspecialchars($userRow['email'] ?? '') ?>"
                     disabled
                     style="opacity:0.5;cursor:not-allowed;"/>
            </div>
            <small style="color:var(--muted);font-size:11px;
                          margin-top:4px;display:block;">
              Email cannot be changed here. Managed via Firebase.
            </small>
          </div>
          <button type="submit" class="btn-form-submit">
            Save Changes
          </button>
        </form>
      </div>

      <div style="text-align:center;margin-top:20px;">
        <a href="<?= BASE_URL ?>/user/dashboard.php"
           class="contact-link">
          ← Back to Dashboard
        </a>
      </div>

    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>