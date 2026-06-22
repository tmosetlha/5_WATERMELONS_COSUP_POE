<?php
// ============================================================
//  COSUP V2 — pages/about.php
//  About Page
//  Community Oriented Substance Use Programme
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

$page_title = 'About COSUP';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- ===== PAGE HERO ===== -->
<section class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-text" data-aos="fade-up">
      <span class="section-tag">Our Story</span>
      <h1 class="page-hero-title">
        A decade of compassionate,<br/>evidence-based care
      </h1>
      <p class="page-hero-desc">
        Serving Tshwane since 2015. Nationally and internationally
        recognised. 100% free. No referral needed.
      </p>
    </div>
  </div>
</section>

<!-- ===== ABOUT MAIN ===== -->
<section class="section about-section">
  <div class="container">
    <div class="about-grid">

      <!-- Left -->
      <div data-aos="fade-right">
        <span class="section-tag">Who We Are</span>
        <h2 class="section-title white">
          Rights-based harm reduction for Tshwane
        </h2>
        <p class="about-body">
          COSUP was established in 2015 in response to the growing
          limitations of abstinence-based and punitive approaches to
          substance use in Tshwane. We believe that people who use
          drugs deserve dignified, rights-based healthcare —
          regardless of their circumstances.
        </p>
        <p class="about-body">
          Through a formal partnership between the City of Tshwane
          and the University of Pretoria's Department of Family
          Medicine, COSUP has become a nationally and internationally
          recognised model for community-oriented harm reduction.
        </p>
        <p class="about-body">
          Our approach is grounded in evidence, guided by human
          rights, and delivered by a dedicated team of healthcare
          workers, social workers, and peer counsellors embedded
          within existing public health infrastructure.
        </p>
        <a href="<?= BASE_URL ?>/pages/contact.php"
           class="btn-primary"
           style="margin-top:16px;display:inline-flex;">
          Get Involved
        </a>
      </div>

      <!-- Right -->
      <div data-aos="fade-left">
        <img src="<?= BASE_URL ?>/IMAGES/IN ACTION.jpeg"
             alt="COSUP in Action"
             class="about-programme-img"
             onerror="this.style.background='var(--card)';
                      this.style.minHeight='200px';"/>
        <div class="about-partners">
          <div class="partner-card">
            <img src="<?= BASE_URL ?>/IMAGES/City_Of_Tshwane.jpeg"
                 alt="City of Tshwane"
                 class="partner-logo-img"
                 onerror="this.style.display='none'"/>
            <div>
              <h4>City of Tshwane</h4>
              <p>Primary funder and operational partner since 2015</p>
            </div>
          </div>
          <div class="partner-card">
            <img src="<?= BASE_URL ?>/IMAGES/university_of_pretoria.png"
                 alt="University of Pretoria"
                 class="partner-logo-img"
                 onerror="this.style.display='none'"/>
            <div>
              <h4>University of Pretoria</h4>
              <p>Department of Family Medicine · Research partner</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ===== STATS ===== -->
<div class="stats-bar">
  <div class="stat-item" data-aos="fade-up" data-delay="0">
    <span class="stat-num">10+</span>
    <span class="stat-lbl">Years of Operation</span>
  </div>
  <div class="stat-item" data-aos="fade-up" data-delay="100">
    <span class="stat-num">5,000+</span>
    <span class="stat-lbl">People Supported</span>
  </div>
  <div class="stat-item" data-aos="fade-up" data-delay="200">
    <span class="stat-num">100%</span>
    <span class="stat-lbl">Free Services</span>
  </div>
  <div class="stat-item" data-aos="fade-up" data-delay="300">
    <span class="stat-num">16</span>
    <span class="stat-lbl">Active Sites</span>
  </div>
  <div class="stat-item" data-aos="fade-up" data-delay="400">
    <span class="stat-num">2</span>
    <span class="stat-lbl">Institutional Partners</span>
  </div>
</div>

<!-- ===== VALUES ===== -->
<section class="section" style="background:var(--bg);">
  <div class="container">
    <span class="section-tag" data-aos="fade-up">Our Values</span>
    <h2 class="section-title" data-aos="fade-up">
      What guides everything we do
    </h2>
    <p class="section-sub" data-aos="fade-up">
      Our work is rooted in respect, evidence, and community.
    </p>

    <div class="services-grid">
      <div class="service-card" data-aos="fade-up" data-delay="0">
        <div class="service-icon-space">
          <div style="font-size:40px;"><i class="fa-solid fa-scale-balanced"></i></div>
        </div>
        <h3>Human Rights</h3>
        <p>Every person who uses drugs has the right to dignified,
           non-judgemental healthcare. We uphold this unconditionally.</p>
      </div>
      <div class="service-card" data-aos="fade-up" data-delay="100">
        <div class="service-icon-space">
          <div style="font-size:40px;"><i class="fa-solid fa-flask"></i></div>
        </div>
        <h3>Evidence-Based</h3>
        <p>Our interventions are grounded in the best available
           scientific evidence for harm reduction and recovery support.</p>
      </div>
      <div class="service-card" data-aos="fade-up" data-delay="200">
        <div class="service-icon-space">
          <div style="font-size:40px;"><i class="fa-solid fa-handshake"></i></div>
        </div>
        <h3>Community-Led</h3>
        <p>People with lived experience of substance use are central
           to our service design, delivery, and continuous improvement.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== CTA ===== -->
<section class="section" style="background:var(--bg2);">
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