<?php
// ============================================================
//  COSUP V2 — user/referral-form.php
//  Self-Referral Form
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

$page_title = 'Self-Referral';
require_once __DIR__ . '/../includes/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$success = '';
$error   = '';
$userId  = $_SESSION['cosup_user_id'] ?? null;

$conn = getDBConnection();

// Prefill from session if logged in
$prefillName  = '';
$prefillPhone = '';
$prefillEmail = '';

if ($userId) {
    $u = $conn->query(
        "SELECT * FROM users WHERE user_id = $userId LIMIT 1"
    )->fetch_assoc();
    $prefillName  = ($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? '');
    $prefillPhone = $u['phone']  ?? '';
    $prefillEmail = $u['email']  ?? '';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName     = $conn->real_escape_string(trim($_POST['full_name']     ?? ''));
    $contactNum   = $conn->real_escape_string(trim($_POST['contact_number'] ?? ''));
    $email        = $conn->real_escape_string(trim($_POST['email']          ?? ''));
    $area         = $conn->real_escape_string(trim($_POST['preferred_area'] ?? ''));
    $serviceNeed  = $conn->real_escape_string(trim($_POST['service_needed'] ?? ''));
    $message      = $conn->real_escape_string(trim($_POST['message']        ?? ''));
    $consent      = isset($_POST['consent']) ? 1 : 0;
    $userIdVal    = $userId ? intval($userId) : 'NULL';

    if (empty($fullName)) {
        $error = 'Please enter your full name.';
    } elseif (!$consent) {
        $error = 'Please accept the POPIA consent to continue.';
    } else {
        $conn->query(
            "INSERT INTO referrals
                (user_id, full_name, contact_number, email,
                 preferred_area, service_needed, message,
                 consent_given, status, source)
             VALUES
                ($userIdVal, '$fullName', '$contactNum', '$email',
                 '$area', '$serviceNeed', '$message',
                 $consent, 'pending', 'website')"
        );
        $success = 'Your referral has been submitted successfully! ' .
                   'A COSUP team member will contact you within 24 hours.';
    }
}
$conn->close();
?>

<section class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-text" data-aos="fade-up">
      <span class="section-tag">Get Help</span>
      <h1 class="page-hero-title">Self-Referral Form</h1>
      <p class="page-hero-desc">
        Submit your details and a COSUP caseworker will contact
        you within 24 hours. All information is confidential.
      </p>
    </div>
  </div>
</section>

<section class="section" style="background:var(--bg);padding-top:60px;">
  <div class="container">
    <div style="max-width:640px;margin:0 auto;">

      <?php if ($success): ?>
      <div class="auth-message success"
           style="margin-bottom:24px;padding:20px 24px;font-size:15px;">
        <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?>
      </div>
      <?php endif; ?>

      <?php if ($error): ?>
      <div class="auth-message error" style="margin-bottom:24px;">
        <i class="fa-solid fa-circle-xmark"></i> <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <?php if (!$success): ?>
      <div class="dashboard-card">
        <h3 class="dashboard-card-title">Your Details</h3>
        <p style="font-size:13px;color:var(--muted);margin-bottom:24px;">
          Fields marked * are required.
          All data is protected under POPIA (Act 4 of 2013).
        </p>

        <form method="POST" action="">
          <div class="form-group">
            <label>Full Name *</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="fa-regular fa-user"></i></span>
              <input type="text"
                     name="full_name"
                     value="<?= htmlspecialchars($prefillName) ?>"
                     placeholder="Your full name"
                     required/>
            </div>
          </div>

          <div class="form-row" style="margin-bottom:18px;">
            <div class="form-group" style="margin:0;">
              <label>Contact Number</label>
              <div class="input-wrap">
                <span class="input-icon"><i class="fa-solid fa-mobile-screen"></i></span>
                <input type="tel"
                       name="contact_number"
                       value="<?= htmlspecialchars($prefillPhone) ?>"
                       placeholder="+27 XX XXX XXXX"/>
              </div>
            </div>
            <div class="form-group" style="margin:0;">
              <label>Email Address</label>
              <div class="input-wrap">
                <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                <input type="email"
                       name="email"
                       value="<?= htmlspecialchars($prefillEmail) ?>"
                       placeholder="your@email.com"/>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Preferred Area *</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="fa-solid fa-location-dot"></i></span>
              <select name="preferred_area" required>
                <option value="">Select your area...</option>
                <?php
                $areas = [
                    'Pretoria CBD','Attridgeville','Bronkhorstspruit',
                    'Daspoort','Eersterust','Garankuwa','Hammanskraal',
                    'Laudium','Mamelodi','Olievenhoutbosch',
                    'Soshanguve','Winterveldt','Other'
                ];
                foreach ($areas as $area):
                ?>
                <option value="<?= $area ?>">
                  <?= $area ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label>Service Needed *</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="fa-solid fa-stethoscope"></i></span>
              <select name="service_needed" required>
                <option value="">Select a service...</option>
                <option>Opioid Agonist Therapy</option>
                <option>Needle &amp; Syringe Programme</option>
                <option>Psychosocial Support</option>
                <option>Skills Development &amp; Vocational Training</option>
                <option>HIV &amp; HCV Prevention</option>
                <option>Community Integration</option>
                <option>Not sure — need guidance</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label>Message (Optional)</label>
            <textarea name="message"
                      rows="4"
                      placeholder="Tell us more about your situation..."
                      style="width:100%;padding:12px 16px;
                             border:1px solid var(--border);
                             border-radius:10px;
                             background:var(--bg);
                             color:var(--text);
                             font-family:inherit;
                             font-size:14px;
                             outline:none;
                             resize:vertical;
                             transition:all 0.2s;"
                      onfocus="this.style.borderColor='var(--green-light)'"
                      onblur="this.style.borderColor='var(--border)'">
            </textarea>
          </div>

          <div class="form-check">
            <input type="checkbox" name="consent" id="refConsent" required/>
            <label for="refConsent">
              I consent to COSUP contacting me using the information
              provided above. I understand my data is protected under
              POPIA (Act 4 of 2013) and will only be used to facilitate
              access to COSUP services.
            </label>
          </div>

          <button type="submit" class="btn-form-submit">
            Submit Referral
          </button>
        </form>
      </div>
      <?php else: ?>
      <div style="text-align:center;padding:40px 0;">
        <a href="<?= BASE_URL ?>/user/dashboard.php"
           class="btn-primary">
          Back to Dashboard
        </a>
      </div>
      <?php endif; ?>

    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>