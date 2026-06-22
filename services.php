<?php
// ============================================================
//  COSUP V2 — pages/services.php
//  Services Page
//  Community Oriented Substance Use Programme
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

$page_title = 'Our Services';
require_once __DIR__ . '/../includes/header.php';

// Fetch all services from DB
$conn        = getDBConnection();
$allServices = $conn->query(
    "SELECT * FROM services WHERE is_active = 1 ORDER BY service_id ASC"
)->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!-- ===== PAGE HERO ===== -->
<section class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-text" data-aos="fade-up">
      <span class="section-tag">What We Offer</span>
      <h1 class="page-hero-title">
        Comprehensive care for<br/>every step of your journey
      </h1>
      <p class="page-hero-desc">
        All COSUP services are free of charge. No referral needed.
        Walk in or contact us directly at any of our 16 sites.
      </p>
    </div>
  </div>
</section>

<!-- ===== SERVICES GRID ===== -->
<section class="section services-section">
  <div class="container">
    <div class="services-grid">
      <?php foreach ($allServices as $i => $service): ?>
      <div class="service-card"
           data-aos="fade-up"
           data-delay="<?= $i * 100 ?>">
        <div class="service-icon-space">
          <img src="<?= BASE_URL ?>/<?= htmlspecialchars($service['icon_path']) ?>"
               alt="<?= htmlspecialchars($service['service_name']) ?>"
               class="service-icon-img"
               onerror="this.style.display='none'"/>
        </div>
        <h3><?= htmlspecialchars($service['service_name']) ?></h3>
        <p><?= htmlspecialchars($service['service_description']) ?></p>
        <div class="service-category-tag">
          <?= htmlspecialchars($service['category']) ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== HOW TO ACCESS ===== -->
<section class="section" style="background:var(--bg2);">
  <div class="container">
    <span class="section-tag" data-aos="fade-up">How to Access</span>
    <h2 class="section-title" data-aos="fade-up">
      Getting help is simple
    </h2>
    <p class="section-sub" data-aos="fade-up">
      No paperwork. No judgement. No cost. Just walk in.
    </p>

    <div class="steps-grid">
      <div class="step-card" data-aos="fade-up" data-delay="0">
        <div class="step-num">01</div>
        <h4>Find Your Nearest Site</h4>
        <p>Use our site locator to find the COSUP clinic
           closest to you across Tshwane.</p>
        <a href="<?= BASE_URL ?>/pages/find-a-site.php"
           class="contact-link">Find a Site →</a>
      </div>
      <div class="step-card" data-aos="fade-up" data-delay="100">
        <div class="step-num">02</div>
        <h4>Walk In or Call</h4>
        <p>No referral needed. Walk in during operating hours
           or call the site directly.</p>
      </div>
      <div class="step-card" data-aos="fade-up" data-delay="200">
        <div class="step-num">03</div>
        <h4>Register Online</h4>
        <p>Create a free account and submit a self-referral
           — a caseworker will contact you within 24 hours.</p>
        <button onclick="openModal('register')"
                class="contact-link">Register Now →</button>
      </div>
      <div class="step-card" data-aos="fade-up" data-delay="300">
        <div class="step-num">04</div>
        <h4>Crisis? Call Hopeline</h4>
        <p>Available 7 days a week. Free. Confidential.
           No judgement.</p>
        <a href="tel:0800611197" class="contact-link">
          0800 611 197 →
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ===== CTA ===== -->
<section class="section cta-section">
  <div class="container">
            <!-- Hopeline Banner Image -->
    <div class="hopeline-banner-wrap" data-aos="fade-up">
      <a href="tel:0800611197" onclick="logHopelineClick('call')">
        <img src="<?= BASE_URL ?>/IMAGES/Contact_Banner.png"
             alt="Hopeline 0800 611 197"
             class="hopeline-banner-img"
             onerror="this.style.display='none'"/>
      </a>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>