<?php
// ============================================================
//  COSUP V2 — pages/contact.php
//  Contact Page
//  Community Oriented Substance Use Programme
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

$page_title = 'Contact Us';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- ===== PAGE HERO ===== -->
<section class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-text" data-aos="fade-up">
      <span class="section-tag">Need Support?</span>
      <h1 class="page-hero-title">
        We're here for you —<br/>no judgement, no barriers
      </h1>
      <p class="page-hero-desc">
        All COSUP services are free of charge.
        You do not need a referral. Just reach out.
      </p>
    </div>
  </div>
</section>

<!-- ===== CONTACT OPTIONS ===== -->
<section class="section contact-section">
  <div class="container">

    <div class="contact-grid">
      <div class="contact-card" data-aos="fade-up" data-delay="0">
        <img src="<?= BASE_URL ?>/IMAGES/Find_a_clinic.png"
             alt="Find a Clinic"
             class="contact-icon-img"
             onerror="this.style.display='none'"/>
        <h4>Find a Clinic</h4>
        <p>16 sites across Tshwane — find the one nearest to you
           using our interactive map.</p>
        <a href="<?= BASE_URL ?>/pages/find-a-site.php"
           class="contact-link">
          View All Sites
        </a>
      </div>

      <div class="contact-card" data-aos="fade-up" data-delay="100">
        <img src="<?= BASE_URL ?>/IMAGES/Referral.png"
             alt="Self Referral"
             class="contact-icon-img"
             onerror="this.style.display='none'"/>
        <h4>Self-Referral</h4>
        <p>Register and submit a referral request — a COSUP team
           member will contact you within 24 hours.</p>
        <button class="contact-link"
                onclick="openModal('register')">
          Register Now
        </button>
      </div>

      <div class="contact-card" data-aos="fade-up" data-delay="200">
        <img src="<?= BASE_URL ?>/IMAGES/Email.png"
             alt="Email Us"
             class="contact-icon-img"
             onerror="this.style.display='none'"/>
        <h4>Email Us</h4>
        <p>Research and programme enquiries. We respond to all
           enquiries within 2 business days.</p>
        <a href="https://up.ac.za/up-copc-research-unit"
           target="_blank"
           class="contact-link">
          Research Unit
        </a>
      </div>
    </div>

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