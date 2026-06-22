<?php
// ============================================================
//  COSUP V2 — pages/find-a-site.php
//  Find a Site Page — OpenStreetMap + Leaflet
//  Community Oriented Substance Use Programme
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

$page_title = 'Find a Site';
require_once __DIR__ . '/../includes/header.php';

// Fetch all active sites with services from DB
$conn     = getDBConnection();
$allSites = $conn->query(
    "SELECT * FROM vw_sites_with_services
     ORDER BY is_main_centre DESC, site_name ASC"
)->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!-- Leaflet CSS -->
<link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<!-- ===== PAGE HERO ===== -->
<section class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-text" data-aos="fade-up">
      <span class="section-tag">Our Locations</span>
      <h1 class="page-hero-title">
        <?= count($allSites) ?> Sites Across Tshwane
      </h1>
      <p class="page-hero-desc">
        Click any pin on the map to get directions.
        All services are free. No referral needed.
      </p>
    </div>
  </div>
</section>

<!-- ===== MAP + SITES ===== -->
<section class="section sites-section">
  <div class="container">

    <!-- Map -->
    <div class="map-wrapper" data-aos="fade-up">
      <div class="map-header-bar">
        <span class="map-bar-title">
          <i class="fa-solid fa-location-dot"></i>
          COSUP Sites — Tshwane Metropolitan Area
        </span>
        <span class="map-badge">
          <?= count($allSites) ?> Active Sites
        </span>
      </div>

      <!-- Leaflet Map Container -->
      <div id="cosup-leaflet-map"
           style="width:100%;height:500px;z-index:1;">
      </div>

      <div class="map-footer-bar">
        <i class="fa-solid fa-circle-info"></i>
        Click any pin → get driving, walking or transit directions.
        Gold pin = Main Centre.
      </div>
    </div>

    <!-- Search -->
    <div class="site-search-bar" data-aos="fade-up">
      <input type="text"
             id="siteSearch"
             placeholder="Search by area, site name or service..."
             oninput="filterSites(this.value)"/>
    </div>

    <!-- All Sites Grid -->
    <div class="sites-grid" id="sitesGrid">
      <?php foreach ($allSites as $idx => $site): ?>
      <div class="site-card
                  <?= $site['is_main_centre'] ? 'site-main' : '' ?>"
           data-name="<?= strtolower(htmlspecialchars(
               $site['site_name'] . ' ' . $site['address']
           )) ?>"
           data-site-id="<?= $site['site_id'] ?>"
           onclick="focusMapOnSite(<?= $site['site_id'] ?>)"
           style="cursor:pointer;">
        <div class="site-num <?= $site['is_main_centre'] ? 'main' : '' ?>">
          <?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?>
        </div>
        <div class="site-info">
          <h4>
            <?= htmlspecialchars($site['site_name']) ?>
            <?php if ($site['is_main_centre']): ?>
              <span class="main-badge">Main Centre</span>
            <?php endif; ?>
          </h4>
          <p class="site-addr">
            <i class="fa-solid fa-location-dot"></i>
            <?= htmlspecialchars($site['address']) ?>
          </p>
          <p class="site-hours">
            <i class="fa-regular fa-clock"></i>
            <?= htmlspecialchars($site['operating_hours']) ?>
          </p>
          <?php if (!empty($site['services_offered'])): ?>
          <p class="site-services-offered">
            <i class="fa-solid fa-stethoscope"></i>
            <?= htmlspecialchars($site['services_offered']) ?>
          </p>
          <?php endif; ?>
          <a href="tel:<?= preg_replace('/\s+/', '', $site['phone']) ?>"
             class="site-call"
             onclick="event.stopPropagation();">
            <i class="fa-solid fa-phone"></i>
            <?= htmlspecialchars($site['phone']) ?>
          </a>
          <!-- Directions buttons -->
          <div class="site-directions">
            <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $site['latitude'] ?>,<?= $site['longitude'] ?>&travelmode=driving"
               target="_blank"
               class="dir-btn dir-drive"
               onclick="event.stopPropagation();">
              <i class="fa-solid fa-car"></i> Drive
            </a>
            <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $site['latitude'] ?>,<?= $site['longitude'] ?>&travelmode=walking"
               target="_blank"
               class="dir-btn dir-walk"
               onclick="event.stopPropagation();">
              <i class="fa-solid fa-person-walking"></i> Walk
            </a>
            <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $site['latitude'] ?>,<?= $site['longitude'] ?>&travelmode=transit"
               target="_blank"
               class="dir-btn dir-transit"
               onclick="event.stopPropagation();">
              <i class="fa-solid fa-bus"></i> Transit
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ===== HOPELINE ===== -->
<section class="section" style="background:var(--bg2);">
  <div class="container">
    <div class="hopeline-banner-wrap" data-aos="fade-up">
      <a href="tel:0800611197" onclick="logHopelineClick('call')">
        <img src="<?= BASE_URL ?>/IMAGES/Contact_Banner.png"
             alt="Hopeline 0800 611 197"
             class="hopeline-banner-img"
             onerror="this.style.display='none'"/>
      </a>
    </div>
  </div>
</section>

<!-- Leaflet JS — loaded before footer -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- COSUP Maps JS -->
<script src="<?= BASE_URL ?>/assets/js/maps.js"></script>
<script>
// Re-init map after splash closes
var _origEnter = window.enterCOSUP;
window.enterCOSUP = function() {
    if (_origEnter) _origEnter();
    setTimeout(function() {
        if (window.cosupMap) window.cosupMap.invalidateSize();
        else initCOSUPMap();
    }, 900);
};
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>