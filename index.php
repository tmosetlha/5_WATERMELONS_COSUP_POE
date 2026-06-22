<?php
// ============================================================
//  COSUP V2 — index.php
//  Homepage
//  Community Oriented Substance Use Programme
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

$page_title = 'Home';
require_once __DIR__ . '/includes/header.php';

// ---- Fetch live stats from DB ----
$conn        = getDBConnection();
$totalSites  = $conn->query("SELECT COUNT(*) as cnt FROM sites WHERE is_active = 1")->fetch_assoc()['cnt'];
$totalUsers  = $conn->query("SELECT COUNT(*) as cnt FROM users")->fetch_assoc()['cnt'];
$latestNews  = $conn->query("SELECT * FROM vw_published_news LIMIT 3")->fetch_all(MYSQLI_ASSOC);
$allServices = $conn->query("SELECT * FROM services WHERE is_active = 1")->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!-- ============================================================
     HERO SECTION
     ============================================================ -->
<section class="cosup-hero" id="home">
  <div class="hero-bg-grid"></div>
  <div class="hero-bg-glow"></div>

  <div class="hero-inner">

    <!-- Left: Text -->
    <div class="hero-text" data-aos="fade-up">
      <div class="hero-chip">
        Tshwane, South Africa · <?= $totalSites ?> Active Sites
      </div>
      <h1 class="hero-title">
        Recovery is a<br/>
        <em>community</em><br/>
        journey.
      </h1>
      <p class="hero-desc">
        COSUP provides free, compassionate harm reduction services
        to people who use drugs in Tshwane. We combine healthcare,
        human rights and community support — because everyone
        deserves a path forward.
      </p>
      <div class="hero-actions">
        <a href="<?= BASE_URL ?>/pages/find-a-site.php"
           class="btn-primary">
          Find Help Near You
        </a>
        <button class="btn-outline" onclick="openModal('register')">
          Join Our Community
        </button>
      </div>

      <!-- Trust Stats -->
      <div class="hero-trust">
        <div class="trust-item">
          <span class="trust-num">10+</span>
          <span class="trust-lbl">Years</span>
        </div>
        <div class="trust-div"></div>
        <div class="trust-item">
          <span class="trust-num">5,000+</span>
          <span class="trust-lbl">People Helped</span>
        </div>
        <div class="trust-div"></div>
        <div class="trust-item">
          <span class="trust-num"><?= $totalSites ?></span>
          <span class="trust-lbl">Active Sites</span>
        </div>
        <div class="trust-div"></div>
        <div class="trust-item">
          <span class="trust-num">100%</span>
          <span class="trust-lbl">Free Services</span>
        </div>
      </div>
    </div>

    <!-- Right: Hero Card -->
    <div class="hero-visual" data-aos="fade-left">
      <div class="hero-card">
        <div class="hero-card-badge">Est. 2015 · Tshwane</div>
        <img src="<?= BASE_URL ?>/IMAGES/COSUP LOGO.png"
             alt="COSUP"
             class="hero-main-img"
             onerror="this.style.display='none'"/>
        <div class="hero-card-body">
          <h3>Community Oriented Substance Use Programme</h3>
          <p>City of Tshwane × University of Pretoria —
             pioneering harm reduction since 2015.</p>
        </div>
      </div>
      <div class="hero-float-1">
        <div class="float-dot pulse"></div>
        Services open now
      </div>
      <div class="hero-float-2">🏆 Nationally recognised</div>
    </div>

  </div>
</section>

<!-- ============================================================
     STATS BAR
     ============================================================ -->
<div class="stats-bar">
  <div class="stat-item" data-aos="fade-up" data-delay="0">
    <span class="stat-num"
          data-target="10"
          data-suffix="+">10+</span>
    <span class="stat-lbl">Years of Operation</span>
  </div>
  <div class="stat-item" data-aos="fade-up" data-delay="100">
    <span class="stat-num"
          data-target="5000"
          data-suffix="+">5,000+</span>
    <span class="stat-lbl">People Supported</span>
  </div>
  <div class="stat-item" data-aos="fade-up" data-delay="200">
    <span class="stat-num"
          data-target="100"
          data-suffix="%">100%</span>
    <span class="stat-lbl">Free Services</span>
  </div>
  <div class="stat-item" data-aos="fade-up" data-delay="300">
    <span class="stat-num"
          data-target="<?= $totalSites ?>"
          data-suffix=""><?= $totalSites ?></span>
    <span class="stat-lbl">Active Sites</span>
  </div>
  <div class="stat-item" data-aos="fade-up" data-delay="400">
    <span class="stat-num"
          data-target="2"
          data-suffix="">2</span>
    <span class="stat-lbl">Institutional Partners</span>
  </div>
</div>

<!-- ============================================================
     SERVICES SECTION
     ============================================================ -->
<section class="section services-section" id="services">
  <div class="container">
    <span class="section-tag" data-aos="fade-up">What We Offer</span>
    <h2 class="section-title" data-aos="fade-up">
      Comprehensive care for every step of your journey
    </h2>
    <p class="section-sub" data-aos="fade-up">
      All services are free of charge. No referral needed.
      Walk in or contact us directly.
    </p>

    <div class="services-grid">
      <?php foreach ($allServices as $i => $service): ?>
      <div class="service-card"
           data-aos="fade-up"
           data-delay="<?= $i * 100 ?>">
        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($service['icon_path']) ?>"
             alt="<?= htmlspecialchars($service['service_name']) ?>"
             class="service-icon-img"
             onerror="this.style.display='none'"/>
        <h3><?= htmlspecialchars($service['service_name']) ?></h3>
        <p><?= htmlspecialchars($service['service_description']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align:center; margin-top:48px;" data-aos="fade-up">
      <a href="<?= BASE_URL ?>/pages/services.php" class="btn-outline">
        View All Services
      </a>
    </div>
  </div>
</section>

<!-- ============================================================
     FIND A SITE PREVIEW — Map + top 4 sites
     ============================================================ -->
<section class="section sites-section" id="sites"
         style="background:var(--bg2);">
  <div class="container">
    <span class="section-tag" data-aos="fade-up">Our Locations</span>
    <h2 class="section-title" data-aos="fade-up">
      <?= $totalSites ?> Sites Across Tshwane
    </h2>
    <p class="section-sub" data-aos="fade-up">
      All services are free. No referral needed.
      Walk in during operating hours.
    </p>

    <!-- Google Map -->
    <div class="map-wrapper" data-aos="fade-up">
      <div class="map-header-bar">
        <span class="map-bar-title">
          <i class="fa-solid fa-location-dot"></i> COSUP Sites — Tshwane Metropolitan Area
        </span>
        <span class="map-badge"><?= $totalSites ?> Active Sites</span>
      </div>
      <!-- Google Maps iframe embed (always works, no API key needed) -->
      <iframe
        src="https://maps.google.com/maps?q=Jubilee+Centre+288+Burgers+Park+Lane+Pretoria+Central&t=&z=13&ie=UTF8&iwloc=&output=embed"
        width="100%"
        height="480"
        style="border:0; display:block;"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        title="COSUP Main Centre Location Map"
        id="cosup-map">
      </iframe>
      <div class="map-footer-bar">
        ⚡ COSUP Main Centre — 173 Bosman Street, Pretoria CBD
      </div>
    </div>

    <!-- Top 4 site cards preview -->
    <?php
    $conn      = getDBConnection();
    $sitesPreview = $conn->query(
        "SELECT * FROM vw_sites_with_services
         ORDER BY is_main_centre DESC
         LIMIT 4"
    )->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    ?>

    <div class="sites-grid" id="sitesGrid">
      <?php foreach ($sitesPreview as $idx => $site): ?>
      <div class="site-card <?= $site['is_main_centre'] ? 'site-main' : '' ?>"
           data-name="<?= strtolower(htmlspecialchars($site['site_name'])) ?>">
        <div class="site-num <?= $site['is_main_centre'] ? 'main' : '' ?>">
          <?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?>
        </div>
        <div class="site-info">
          <h4>
            <?= htmlspecialchars($site['site_name']) ?>
            <?php if ($site['is_main_centre']): ?>
              <span class="main-badge">Main</span>
            <?php endif; ?>
          </h4>
          <p class="site-addr">
            <i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($site['address']) ?>
          </p>
          <p class="site-hours">
            <i class="fa-regular fa-clock"></i> <?= htmlspecialchars($site['operating_hours']) ?>
          </p>
          <a href="tel:<?= preg_replace('/\s+/', '', $site['phone']) ?>"
             class="site-call">
            <i class="fa-solid fa-phone"></i> <?= htmlspecialchars($site['phone']) ?>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align:center; margin-top:36px;" data-aos="fade-up">
      <a href="<?= BASE_URL ?>/pages/find-a-site.php"
         class="btn-primary">
        View All <?= $totalSites ?> Sites
      </a>
    </div>
  </div>
</section>

<!-- ============================================================
     ABOUT SECTION
     ============================================================ -->
<section class="section about-section" id="about">
  <div class="container">
    <div class="about-grid">

      <!-- Left: Text -->
      <div class="about-text-col" data-aos="fade-right">
        <span class="section-tag">Our Story</span>
        <h2 class="section-title white">
          A decade of compassionate,<br/>evidence-based care
        </h2>
        <p class="about-body">
          COSUP was established in response to the growing limitations
          of abstinence-based and punitive approaches to substance use
          in Tshwane. We believe that people who use drugs deserve
          dignified, rights-based healthcare.
        </p>
        <p class="about-body">
          Through a partnership between the City of Tshwane and the
          University of Pretoria's Department of Family Medicine, COSUP
          has become a nationally and internationally recognised model
          for harm reduction.
        </p>
        <a href="<?= BASE_URL ?>/pages/about.php"
           class="btn-primary"
           style="margin-top:8px;">
          Read Our Story
        </a>
      </div>

      <!-- Right: Image + Partners -->
      <div class="about-cards-col" data-aos="fade-left">
        <img src="<?= BASE_URL ?>/IMAGES/IN ACTION.jpeg"
             alt="COSUP Programme in Action"
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
              <p>Primary funder and operational partner</p>
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

<!-- ============================================================
     NEWS SECTION
     ============================================================ -->
<?php if (!empty($latestNews)): ?>
<section class="section" id="news"
         style="background:var(--bg);">
  <div class="container">
    <span class="section-tag" data-aos="fade-up">
      Latest Updates
    </span>
    <h2 class="section-title" data-aos="fade-up">
      News &amp; Announcements
    </h2>

    <div class="services-grid">
      <?php foreach ($latestNews as $i => $item): ?>
      <div class="cosup-card"
           data-aos="fade-up"
           data-delay="<?= $i * 100 ?>">
        <?php if (!empty($item['cover_img'])): ?>
        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($item['cover_img']) ?>"
             alt="<?= htmlspecialchars($item['title']) ?>"
             style="width:100%;height:140px;object-fit:cover;
                    border-radius:6px;margin-bottom:16px;"
             onerror="this.style.display='none'"/>
        <?php endif; ?>
        <div style="margin-bottom:8px;">
          <span style="font-family:'Orbitron',sans-serif;
                       font-size:10px;color:var(--muted);
                       letter-spacing:2px;">
            <?= date('d M Y', strtotime($item['published_at'])) ?>
          </span>
        </div>
        <h3 style="font-family:'Playfair Display',serif;
                   font-size:17px;color:var(--text);
                   margin-bottom:10px;line-height:1.3;">
          <?= htmlspecialchars($item['title']) ?>
        </h3>
        <p style="font-size:13px;color:var(--muted);
                  line-height:1.7;">
          <?= htmlspecialchars(substr($item['body'], 0, 120)) ?>...
        </p>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align:center;margin-top:40px;"
         data-aos="fade-up">
      <a href="<?= BASE_URL ?>/pages/news.php"
         class="btn-outline">
        View All News
      </a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============================================================
     CONTACT / HOPELINE SECTION
     ============================================================ -->
<section class="section" id="contact"
         style="background:var(--bg2);">
  <div class="container">
    <span class="section-tag" data-aos="fade-up">
      Need Support?
    </span>
    <h2 class="section-title" data-aos="fade-up">
      We're here for you —<br/>no judgement, no barriers
    </h2>
    <p class="section-sub" data-aos="fade-up">
      All COSUP services are free of charge.
      You do not need a referral. Just reach out.
    </p>

    <div class="contact-grid">
      <div class="contact-card" data-aos="fade-up" data-delay="0">
        <img src="<?= BASE_URL ?>/IMAGES/Find_a_clinic.png"
             alt="Find a Clinic"
             class="contact-icon-img"
             onerror="this.style.display='none'"/>
        <h4>Find a Clinic</h4>
        <p><?= $totalSites ?> sites across Tshwane — find the
           one nearest to you.</p>
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
        <p>Register and submit a referral — a COSUP team member
           will contact you within 24 hours.</p>
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
        <p>Research and programme enquiries welcome.</p>
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>