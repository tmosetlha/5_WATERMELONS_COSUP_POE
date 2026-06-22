<?php
// ============================================================
//  COSUP V2 — includes/header.php
//  Global Header — included on every page
//  Community Oriented Substance Use Programme
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
//  Design: Metallic Futuristic 2090
//  Colours: Primary #3BA53E | White #FFFFFF
// ============================================================

require_once __DIR__ . '/../config/DBConn.php';
require_once __DIR__ . '/../config/firebase.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Current page for active nav highlighting
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="COSUP — Community Oriented Substance Use Programme. Free harm reduction services across 16 sites in Tshwane, South Africa."/>
  <meta name="theme-color" content="#3BA53E"/>
  <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' — COSUP V2' : 'COSUP V2 — Community Oriented Substance Use Programme' ?></title>

  <!-- ===== FONTS ===== -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;800;900&family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet"/>

  <!-- ===== FIREBASE CDN ===== -->
  <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-auth-compat.js"></script>

  <!-- ===== FIREBASE INIT — done once here, before any other scripts ===== -->
  <script>
    // Only initialize if not already initialized
    if (!firebase.apps.length) {
      firebase.initializeApp(<?= json_encode($firebaseConfig) ?>);
    }
    const cosupAuth = firebase.auth();
    const COSUP_BASE_URL   = '<?= BASE_URL ?>';
    const COSUP_IMAGES_PATH = '<?= IMAGES_PATH ?>';
    // Make firebaseConfig available globally for firebase-auth.js
    const COSUP_FIREBASE_CONFIG = <?= json_encode($firebaseConfig) ?>;
  </script>

  <!-- ===== FONT AWESOME ===== -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <!-- ===== MAIN STYLESHEET ===== -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css"/>
</head>
<body>

<!-- ============================================================
     WELCOME SPLASH SCREEN
     Shows on first page load with spinner
     ============================================================ -->
<div id="cosup-splash" class="splash-screen">
  <div class="splash-bg"></div>
  <div class="splash-grid"></div>
  <div class="splash-content">

    <!-- Animated rings -->
    <div class="splash-rings">
      <div class="ring ring-1"></div>
      <div class="ring ring-2"></div>
      <div class="ring ring-3"></div>
    </div>

    <!-- Logo -->
    <div class="splash-logo-wrap">
      <img src="<?= BASE_URL ?>/IMAGES/COSUP_LOGO_4.png"
           alt="COSUP Logo"
           class="splash-logo-img"
           onerror="this.style.display='none'"/>
      <div class="splash-logo-text">COSUP</div>
    </div>

    <!-- Spinner -->
    <div class="splash-spinner">
      <div class="spinner-ring"></div>
      <div class="spinner-dot"></div>
    </div>

    <div class="splash-tagline">Community Oriented Substance Use Programme</div>
    <div class="splash-sub">Tshwane, South Africa · Est. 2015 · 16 Active Sites</div>

    <!-- Scanning line animation -->
    <div class="splash-scan-line"></div>

    <div class="splash-meta">FREE · CONFIDENTIAL · NO REFERRAL NEEDED</div>

    <button class="splash-enter-btn" onclick="enterCOSUP()">
      <span>ENTER</span>
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </button>

    <div class="splash-hopeline">
      <i class="fa-solid fa-circle-exclamation"></i> Emergency? <a href="tel:0800611197"><strong>0800 611 197</strong></a>
    </div>
  </div>
</div>

<!-- ============================================================
     LOGOUT SPLASH SCREEN
     Shows when user logs out — farewell screen
     ============================================================ -->
<div id="cosup-logout-splash" class="logout-splash" style="display:none;">
  <div class="logout-bg"></div>
  <div class="logout-content">
    <div class="logout-rings">
      <div class="ring ring-1"></div>
      <div class="ring ring-2"></div>
    </div>
    <img src="<?= BASE_URL ?>/IMAGES/COSUP_LOGO_4.png"
         alt="COSUP Logo"
         class="logout-logo-img" onerror="this.style.display='none'" 
         onerror="this.style.display='none';this.style.background='transparent'"/>
    <div class="logout-logo-text">COSUP</div>
    <div class="logout-farewell">See You Soon!</div>
    <div class="logout-name" id="logoutUserName"></div>
    <div class="logout-msg">You have been successfully signed out.<br/>Stay safe out there. 👋</div>
    <div class="logout-spinner">
      <div class="spinner-ring"></div>
    </div>
    <div class="logout-redirect">Redirecting to homepage...</div>
  </div>
</div>

<!-- ============================================================
     MAIN SITE WRAPPER
     ============================================================ -->
<div id="cosup-main" style="display:none;">

<!-- ===== NAVBAR ===== -->
<nav class="cosup-navbar" id="cosupNavbar">
  <div class="navbar-glow"></div>
  <div class="navbar-inner">

    <!-- Logo -->
    <a href="<?= BASE_URL ?>/index.php" class="navbar-logo">
      <img src="<?= BASE_URL ?>/IMAGES/COSUP_LOGO_4.png"
           alt="COSUP"
           class="navbar-logo-img" onerror="this.style.display='none'" 
           onerror="this.style.display='none';this.style.background='transparent'"/>
      <span class="navbar-logo-text">COSUP</span>
      <span 
    </a>

    <!-- Desktop Nav Links -->
    <ul class="navbar-links">
      <li>
        <a href="<?= BASE_URL ?>/index.php"
           class="<?= $current_page === 'index' ? 'active' : '' ?>">
          Home
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/pages/services.php"
           class="<?= $current_page === 'services' ? 'active' : '' ?>">
          Services
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/pages/find-a-site.php"
           class="<?= $current_page === 'find-a-site' ? 'active' : '' ?>">
          Find a Site
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/pages/about.php"
           class="<?= $current_page === 'about' ? 'active' : '' ?>">
          About
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/pages/news.php"
           class="<?= $current_page === 'news' ? 'active' : '' ?>">
          News
        </a>
      </li>
      <li>
        <a href="<?= BASE_URL ?>/pages/contact.php"
           class="<?= $current_page === 'contact' ? 'active' : '' ?>">
          Contact
        </a>
      </li>
    </ul>

    <!-- Nav Actions -->
    <div class="navbar-actions">

      <!-- Theme Toggle -->
      <button class="theme-toggle-btn" id="themeToggleBtn"
              onclick="toggleTheme()" title="Toggle theme">
        <span class="theme-icon">
          <i class="fa-solid fa-moon" id="themeIconEl"></i>
        </span>
      </button>

      <!-- Logged OUT state -->
      <div class="nav-auth-guest" id="navAuthGuest">
        <button class="btn-nav-ghost" onclick="openModal('login')">
          Sign In
        </button>
        <button class="btn-nav-primary" onclick="openModal('register')">
          Register
        </button>
      </div>

      <!-- Logged IN state -->
      <div class="nav-auth-user" id="navAuthUser" style="display:none;">
        <div class="nav-user-chip" onclick="toggleUserMenu()">
          <div class="nav-user-avatar" id="navUserAvatar">U</div>
          <span class="nav-user-name" id="navUserName">User</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="2">
            <path d="M6 9l6 6 6-6"/>
          </svg>
        </div>
        <div class="nav-user-dropdown" id="navUserDropdown">
          <a href="<?= BASE_URL ?>/user/dashboard.php">
            <span><i class="fa-solid fa-house"></i></span> My Dashboard
          </a>
          <a href="<?= BASE_URL ?>/user/profile.php">
            <span><i class="fa-regular fa-user"></i></span> My Profile
          </a>
          <a href="<?= BASE_URL ?>/user/my-referrals.php">
            <span><i class="fa-regular fa-clipboard"></i></span> My Referrals
          </a>
          <div class="dropdown-divider"></div>
          <!-- Admin link — only shown if user is admin -->
          <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin','superadmin'])): ?>
          <a href="<?= BASE_URL ?>/admin/dashboard.php" class="dropdown-admin-link">
            <span><i class="fa-solid fa-gear"></i></span> Admin Dashboard
          </a>
          <div class="dropdown-divider"></div>
          <?php endif; ?>
          <button onclick="logoutCOSUP()" class="dropdown-logout-btn">
            <span><i class="fa-solid fa-right-from-bracket"></i></span> Sign Out
          </button>
        </div>
      </div>

      <!-- Hopeline Image Button -->
      <a href="tel:0800611197" class="hopeline-img-btn" title="Hopeline: 0800 611 197">
        <img src="<?= BASE_URL ?>/IMAGES/Contact_Banner.png"
             alt="Hopeline 0800 611 197"
             class="hopeline-nav-img"
             onerror="this.style.display='none'"/>
      </a>

    </div><!-- end navbar-actions -->

    <!-- Hamburger -->
    <button class="hamburger-btn" id="hamburgerBtn"
            onclick="toggleMobileMenu()" aria-label="Toggle menu">
      <span></span>
      <span></span>
      <span></span>
    </button>

  </div><!-- end navbar-inner -->

  <!-- Mobile Menu -->
  <div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-inner">
      <a href="<?= BASE_URL ?>/index.php" onclick="toggleMobileMenu()">Home</a>
      <a href="<?= BASE_URL ?>/pages/services.php" onclick="toggleMobileMenu()">Services</a>
      <a href="<?= BASE_URL ?>/pages/find-a-site.php" onclick="toggleMobileMenu()">Find a Site</a>
      <a href="<?= BASE_URL ?>/pages/about.php" onclick="toggleMobileMenu()">About</a>
      <a href="<?= BASE_URL ?>/pages/news.php" onclick="toggleMobileMenu()">News</a>
      <a href="<?= BASE_URL ?>/pages/contact.php" onclick="toggleMobileMenu()">Contact</a>
      <div class="mobile-menu-auth">
        <button onclick="openModal('login'); toggleMobileMenu()">Sign In</button>
        <button onclick="openModal('register'); toggleMobileMenu()" class="mobile-register-btn">Register</button>
      </div>
      <a href="tel:0800611197" class="mobile-hopeline-link"><i class="fa-solid fa-circle-exclamation"></i> Hopeline: 0800 611 197</a>
    </div>
  </div>

</nav><!-- end cosup-navbar -->