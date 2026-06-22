<?php
// ============================================================
//  COSUP V2 — pages/news.php
//  News & Updates Page
//  Community Oriented Substance Use Programme
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

$page_title = 'News & Updates';
require_once __DIR__ . '/../includes/header.php';

// Fetch all published news from DB
$conn     = getDBConnection();
$allNews  = $conn->query(
    "SELECT * FROM vw_published_news ORDER BY published_at DESC"
)->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!-- ===== PAGE HERO ===== -->
<section class="page-hero">
  <div class="page-hero-inner">
    <div class="page-hero-text" data-aos="fade-up">
      <span class="section-tag">Latest Updates</span>
      <h1 class="page-hero-title">
        News &amp; Announcements
      </h1>
      <p class="page-hero-desc">
        Stay informed about COSUP services, events,
        and programme updates across Tshwane.
      </p>
    </div>
  </div>
</section>

<!-- ===== NEWS GRID ===== -->
<section class="section" style="background:var(--bg);">
  <div class="container">

    <?php if (empty($allNews)): ?>
    <div style="text-align:center; padding:60px 0; color:var(--muted);">
      <div style="font-size:48px; margin-bottom:16px;"><i class="fa-regular fa-newspaper"></i></div>
      <h3 style="font-family:'Playfair Display',serif;
                 margin-bottom:12px; color:var(--text);">
        No news yet
      </h3>
      <p>Check back soon for updates from the COSUP team.</p>
    </div>
    <?php else: ?>

    <div class="services-grid">
      <?php foreach ($allNews as $i => $item): ?>
      <div class="cosup-card"
           data-aos="fade-up"
           data-delay="<?= $i * 100 ?>">
        <?php if (!empty($item['cover_img'])): ?>
        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($item['cover_img']) ?>"
             alt="<?= htmlspecialchars($item['title']) ?>"
             style="width:100%;height:160px;object-fit:cover;
                    border-radius:12px;margin-bottom:16px;"
             onerror="this.style.display='none'"/>
        <?php endif; ?>

        <div style="margin-bottom:10px;">
          <span style="font-size:11px;color:var(--muted);
                       letter-spacing:1px;text-transform:uppercase;">
            <?= date('d F Y', strtotime($item['published_at'])) ?>
          </span>
          <span style="font-size:11px;color:var(--muted);
                       margin-left:12px;">
            By <?= htmlspecialchars($item['author']) ?>
          </span>
        </div>

        <h3 style="font-family:'Playfair Display',serif;
                   font-size:19px;font-weight:700;
                   color:var(--text);margin-bottom:12px;
                   line-height:1.3;">
          <?= htmlspecialchars($item['title']) ?>
        </h3>

        <p style="font-size:14px;color:var(--muted);
                  line-height:1.7;">
          <?= htmlspecialchars($item['body']) ?>
        </p>
      </div>
      <?php endforeach; ?>
    </div>

    <?php endif; ?>
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