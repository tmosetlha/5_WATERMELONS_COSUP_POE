<?php
// ============================================================
//  COSUP V2 — includes/footer.php
//  Global Footer — included on every page
//  Community Oriented Substance Use Programme
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
//  Design: Metallic Futuristic 2090
// ============================================================
?>

<!-- ============================================================
     AUTH MODALS (Login + Register + Email Confirm)
     Included here so available on every page
     ============================================================ -->

<!-- MODAL OVERLAY -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>

<!-- ===== LOGIN MODAL ===== -->
<div class="cosup-modal" id="loginModal">
  <button class="modal-close-btn" onclick="closeModal()">✕</button>

  <div class="modal-header">
    <img src="<?= BASE_URL ?>/IMAGES/COSUP_LOGO_4.png"
         alt="COSUP" class="modal-logo-img"
         onerror="this.style.display='none'"/>
    <div class="modal-logo-text">COSUP</div>
    <h2 class="modal-title">Welcome Back</h2>
    <p class="modal-subtitle">Sign in to your COSUP account</p>
  </div>

  <div id="loginMsg" class="auth-message" style="display:none;"></div>

  <div class="modal-body">
    <!-- Google Sign In -->
    <button class="btn-google-signin" onclick="handleGoogleSignIn()">
      <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
           alt="Google" width="20" height="20"/>
      Continue with Google
    </button>
    <div class="auth-divider"><span>or sign in with email</span></div>

    <div class="form-group">
      <label>Email Address</label>
      <div class="input-wrap">
        <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
        <input type="email" id="loginEmail"
               placeholder="your@email.com"
               autocomplete="email" required/>
      </div>
    </div>
    <div class="form-group">
      <label>Password</label>
      <div class="input-wrap">
        <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
        <input type="password" id="loginPassword"
               placeholder="••••••••"
               autocomplete="current-password" required/>
        <button type="button" class="password-toggle"
                onclick="togglePassword('loginPassword')">
          <i class="fa-regular fa-eye"></i>
        </button>
      </div>
    </div>
    <div style="text-align:right;margin-bottom:16px;margin-top:-8px;">
      <button onclick="switchModal('forgot')"
              style="background:none;border:none;color:var(--green);
                     font-size:12px;cursor:pointer;font-weight:600;">
        Forgot password?
      </button>
    </div>
    <button class="btn-form-submit" id="loginBtn"
            onclick="handleLogin()">
      Sign In
    </button>
  </div>

  <div class="modal-footer">
    <p>Don't have an account?
      <button onclick="switchModal('register')">Register here</button>
    </p>
    <p class="modal-disclaimer">
      All information is confidential and protected under POPIA (Act 4 of 2013).
    </p>
  </div>
</div>

<!-- ===== REGISTER MODAL ===== -->
<div class="cosup-modal" id="registerModal">
  <button class="modal-close-btn" onclick="closeModal()">✕</button>

  <div class="modal-header">
    <img src="<?= BASE_URL ?>/IMAGES/COSUP_LOGO_4.png"
         alt="COSUP" class="modal-logo-img"
         onerror="this.style.display='none'"/>
    <div class="modal-logo-text">COSUP</div>
    <h2 class="modal-title">Create Account</h2>
    <p class="modal-subtitle">Join COSUP — free and confidential</p>
  </div>

  <div id="registerMsg" class="auth-message" style="display:none;"></div>

  <div class="modal-body">
    <!-- Google Register -->
    <button class="btn-google-signin" onclick="handleGoogleSignIn()">
      <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
           alt="Google" width="20" height="20"/>
      Continue with Google
    </button>
    <div class="auth-divider"><span>or register with email</span></div>

    <div class="form-row">
      <div class="form-group">
        <label>First Name</label>
        <div class="input-wrap">
          <span class="input-icon"><i class="fa-regular fa-user"></i></span>
          <input type="text" id="regFirstName"
                 placeholder="First name"
                 autocomplete="given-name" required/>
        </div>
      </div>
      <div class="form-group">
        <label>Last Name</label>
        <div class="input-wrap">
          <span class="input-icon"><i class="fa-regular fa-user"></i></span>
          <input type="text" id="regLastName"
                 placeholder="Last name"
                 autocomplete="family-name" required/>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label>Email Address</label>
      <div class="input-wrap">
        <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
        <input type="email" id="regEmail"
               placeholder="your@email.com"
               autocomplete="email" required/>
      </div>
    </div>

    <div class="form-group">
      <label>Phone Number</label>
      <div class="input-wrap">
        <span class="input-icon"><i class="fa-solid fa-mobile-screen"></i></span>
        <input type="tel" id="regPhone"
               placeholder="+27 XX XXX XXXX"
               autocomplete="tel"/>
      </div>
    </div>

    <div class="form-group">
      <label>Password</label>
      <div class="input-wrap">
        <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
        <input type="password" id="regPassword"
               placeholder="Min 8 characters"
               minlength="8"
               autocomplete="new-password"
               oninput="checkPasswordStrength(this.value)"
               required/>
        <button type="button" class="password-toggle"
                onclick="togglePassword('regPassword')">
          <i class="fa-regular fa-eye"></i>
        </button>
      </div>
      <!-- Password Strength Bar -->
      <div class="password-strength-wrap">
        <div class="password-strength-track">
          <div class="password-strength-bar" id="passwordStrengthBar"></div>
        </div>
        <span class="password-strength-label" id="passwordStrengthLabel"></span>
      </div>
    </div>

    <div class="form-group">
      <label>Confirm Password</label>
      <div class="input-wrap">
        <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
        <input type="password" id="regConfirmPassword"
               placeholder="Repeat your password"
               autocomplete="new-password" required/>
        <button type="button" class="password-toggle"
                onclick="togglePassword('regConfirmPassword')">
          <i class="fa-regular fa-eye"></i>
        </button>
      </div>
    </div>

    <div class="form-group">
      <label>Preferred Language</label>
      <div class="input-wrap">
        <span class="input-icon"><i class="fa-solid fa-globe"></i></span>
        <select id="regLanguage" autocomplete="language">
          <option value="en">English</option>
          <option value="zu">IsiZulu</option>
          <option value="ts">Xitsonga</option>
          <option value="ve">TshiVenda</option>
          <option value="tn">SeTswana / Sepedi</option>
          <option value="af">Afrikaans</option>
          <option value="xh">Xhosa</option>
        </select>
      </div>
    </div>

    <div class="form-check">
      <input type="checkbox" id="regConsent" required/>
      <label for="regConsent">
        I consent to COSUP contacting me. My data is protected
        under POPIA (Act 4 of 2013).
      </label>
    </div>

    <button class="btn-form-submit" id="registerBtn"
            onclick="handleRegister()">
      Create Account
    </button>
  </div>

  <div class="modal-footer">
    <p>Already have an account?
      <button onclick="switchModal('login')">Sign in here</button>
    </p>
    <p class="modal-disclaimer">
      A confirmation email will be sent to verify your address.
      All data is encrypted and protected under POPIA (Act 4 of 2013).
    </p>
  </div>
</div>

<!-- ===== EMAIL CONFIRMATION MODAL ===== -->
<div class="cosup-modal" id="confirmModal">
  <div class="modal-header">
    <div class="confirm-icon"><i class="fa-regular fa-envelope"></i></div>
    <h2 class="modal-title">Check Your Email</h2>
    <p class="modal-subtitle" id="confirmMsg">
      We've sent a confirmation email. Please click the link to verify your account.
    </p>
  </div>
  <div class="modal-body">
    <button class="btn-form-submit" onclick="closeModal()">Done</button>
  </div>
  <div class="modal-footer">
    <p class="modal-disclaimer">
      Didn't receive it? Check your spam folder or contact us at
      <a href="https://up.ac.za/up-copc-research-unit" target="_blank">
        up.ac.za/up-copc-research-unit
      </a>
    </p>
  </div>
</div>

<!-- ===== FORGOT PASSWORD MODAL ===== -->
<div class="cosup-modal" id="forgotModal">
  <button class="modal-close-btn" onclick="closeModal()">✕</button>

  <div class="modal-header">
    <img src="<?= BASE_URL ?>/IMAGES/COSUP_LOGO_4.png"
         alt="COSUP" class="modal-logo-img"
         onerror="this.style.display='none'"/>
    <div class="modal-logo-text">COSUP</div>
    <h2 class="modal-title">Reset Password</h2>
    <p class="modal-subtitle">
      Enter your email and we'll send you a reset link.
    </p>
  </div>

  <div id="forgotMsg" class="auth-message" style="display:none;"></div>

  <div class="modal-body">
    <div class="form-group">
      <label>Email Address</label>
      <div class="input-wrap">
        <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
        <input type="email" id="forgotEmail"
               placeholder="your@email.com"
               autocomplete="email" required/>
      </div>
    </div>
    <button class="btn-form-submit" id="forgotBtn"
            onclick="handleForgotPassword()">
      Send Reset Email
    </button>
  </div>

  <div class="modal-footer">
    <p>Remember your password?
      <button onclick="switchModal('login')">Sign in here</button>
    </p>
    <p class="modal-disclaimer">
      The reset link will be sent to your registered email address.
    </p>
  </div>
</div>
  <div class="modal-footer">
    <p class="modal-disclaimer">
      Didn't receive it? Check your spam folder or contact us at
      <a href="https://up.ac.za/up-copc-research-unit" target="_blank">
        up.ac.za/up-copc-research-unit
      </a>
    </p>
  </div>
</div>

<!-- ============================================================
     ACTUAL FOOTER
     ============================================================ -->
<footer class="cosup-footer">
  <div class="footer-glow"></div>
  <div class="footer-grid-overlay"></div>

  <div class="footer-inner">

    <!-- Brand Column -->
    <div class="footer-brand">
      <img src="<?= BASE_URL ?>/IMAGES/COSUP_LOGO_4.png"
           alt="COSUP Logo"
           class="footer-logo-img" onerror="this.style.display='none'" 
           onerror="this.style.display='none';this.style.background='transparent'"/>
      <div class="footer-logo-text">COSUP</div>
      <p class="footer-desc">
        Community Oriented Substance Use Programme.<br/>
        Serving Tshwane since 2015 across 16 sites.<br/>
        A partnership between the City of Tshwane<br/>
        and the University of Pretoria.
      </p>
      <!-- Partner logos -->
      <div class="footer-partners">
        <img src="<?= BASE_URL ?>/IMAGES/City_Of_Tshwane.jpeg"
             alt="City of Tshwane"
             class="footer-partner-img"
             onerror="this.style.display='none';this.style.background='transparent'"/>
        <img src="<?= BASE_URL ?>/IMAGES/university_of_pretoria.png"
             alt="University of Pretoria"
             class="footer-partner-img"
             onerror="this.style.display='none';this.style.background='transparent'"/>
      </div>
    </div>

    <!-- Services Column -->
    <div class="footer-col">
      <h5 class="footer-col-title">Services</h5>
      <ul>
        <li><a href="<?= BASE_URL ?>/pages/services.php">Opioid Agonist Therapy</a></li>
        <li><a href="<?= BASE_URL ?>/pages/services.php">Needle &amp; Syringe</a></li>
        <li><a href="<?= BASE_URL ?>/pages/services.php">Psychosocial Support</a></li>
        <li><a href="<?= BASE_URL ?>/pages/services.php">Skills Development</a></li>
        <li><a href="<?= BASE_URL ?>/pages/services.php">HIV &amp; HCV Prevention</a></li>
        <li><a href="<?= BASE_URL ?>/pages/services.php">Community Integration</a></li>
      </ul>
    </div>

    <!-- Organisation Column -->
    <div class="footer-col">
      <h5 class="footer-col-title">Organisation</h5>
      <ul>
        <li><a href="<?= BASE_URL ?>/pages/about.php">About COSUP</a></li>
        <li><a href="<?= BASE_URL ?>/pages/about.php">Our Partners</a></li>
        <li><a href="<?= BASE_URL ?>/pages/find-a-site.php">Find a Site</a></li>
        <li><a href="<?= BASE_URL ?>/pages/news.php">News &amp; Updates</a></li>
        <li><a href="<?= BASE_URL ?>/pages/contact.php">Contact Us</a></li>
        <li>
          <a href="https://up.ac.za/up-copc-research-unit" target="_blank">
            Research Unit
          </a>
        </li>
      </ul>
    </div>

    <!-- Get Help Column -->
    <div class="footer-col">
      <h5 class="footer-col-title">Get Help</h5>
      <ul>
        <li>
          <a href="tel:0800611197" class="footer-hopeline-link">
            <i class="fa-solid fa-circle-exclamation"></i> Hopeline: 0800 611 197
          </a>
        </li>
        <li><a href="<?= BASE_URL ?>/pages/find-a-site.php">16 Sites Across Tshwane</a></li>
        <li>
          <a href="<?= BASE_URL ?>/user/referral-form.php">
            Self-Referral Form
          </a>
        </li>
        <li>
          <button onclick="openModal('register')"
                  class="footer-register-btn">
            Register / Join
          </button>
        </li>
      </ul>

      <!-- Hopeline image in footer -->
      <div class="footer-hopeline-wrap">
        <a href="tel:0800611197">
          <img src="<?= BASE_URL ?>/IMAGES/Contact_Banner.png"
               alt="Hopeline 0800 611 197"
               class="footer-hopeline-img"
               onerror="this.style.display='none'"/>
        </a>
      </div>
    </div>

  </div><!-- end footer-inner -->

  <!-- Footer Bottom Bar -->
  <div class="footer-bottom">
    <div class="footer-bottom-inner">
      <span>© 2026 COSUP — Community Oriented Substance Use Programme, Tshwane</span>
      <span class="footer-built-by">
        Built by <strong>The 5 Watermelons</strong> · XISD5319 · IIE Rosebank College
      </span>
    </div>
  </div>

</footer><!-- end cosup-footer -->

</div><!-- end #cosup-main -->

<!-- ============================================================
     SCRIPTS — loaded at bottom for performance
     ============================================================ -->

<!-- Firebase Auth JS -->
<script src="<?= BASE_URL ?>/assets/js/firebase-auth.js"></script>

<!-- Main JS -->
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>

</body>
</html>