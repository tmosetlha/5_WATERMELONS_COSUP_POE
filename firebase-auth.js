// ============================================================
//  COSUP V2 — assets/js/firebase-auth.js
//  Firebase Auth — Login, Register, Logout,
//  Forgot Password, Google Sign-In, Password Strength
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
//  NOTE: Firebase is initialized in header.php
//  cosupAuth is already available globally
// ============================================================

// ---- AUTH STATE OBSERVER ----
cosupAuth.onAuthStateChanged(function(user) {
    if (user) {
        updateNavbarLoggedIn(user);
        user.getIdToken().then(function(idToken) {
            syncUserToDB(idToken, user);
        });
    } else {
        updateNavbarLoggedOut();
    }
});

// ---- REGISTER ----
function handleRegister() {
    const firstName  = document.getElementById('regFirstName').value.trim();
    const lastName   = document.getElementById('regLastName').value.trim();
    const email      = document.getElementById('regEmail').value.trim();
    const phone      = document.getElementById('regPhone').value.trim();
    const password   = document.getElementById('regPassword').value;
    const confirmPwd = document.getElementById('regConfirmPassword').value;
    const language   = document.getElementById('regLanguage').value;
    const consent    = document.getElementById('regConsent').checked;
    const btn        = document.getElementById('registerBtn');
    const msg        = document.getElementById('registerMsg');

    if (!firstName || !lastName) { showAuthMessage(msg,'error','Please enter your first and last name.'); return; }
    if (!email)                  { showAuthMessage(msg,'error','Please enter your email address.'); return; }
    if (password.length < 8)     { showAuthMessage(msg,'error','Password must be at least 8 characters.'); return; }
    if (password !== confirmPwd) { showAuthMessage(msg,'error','Passwords do not match.'); return; }
    if (!consent)                { showAuthMessage(msg,'error','Please accept the POPIA consent.'); return; }

    setButtonLoading(btn, true, 'Creating Account...');
    hideAuthMessage(msg);

    cosupAuth.createUserWithEmailAndPassword(email, password)
        .then(function(cred) {
            return cred.user.updateProfile({ displayName: firstName+' '+lastName })
                .then(function() { return cred.user.sendEmailVerification(); })
                .then(function() {
                    return cred.user.getIdToken().then(function(token) {
                        return syncUserToDB(token, cred.user, {
                            first_name: firstName, last_name: lastName,
                            phone: phone, lang_pref: language
                        });
                    });
                });
        })
        .then(function() {
            closeModal();
            setTimeout(function() {
                var el = document.getElementById('confirmMsg');
                if (el) el.textContent = 'Confirmation email sent to '+email+'. Please verify before signing in.';
                openModal('confirm');
            }, 200);
        })
        .catch(function(err) {
            setButtonLoading(btn, false, 'Create Account');
            showAuthMessage(msg, 'error', getFirebaseErrorMessage(err.code));
        });
}

// ---- LOGIN ----
function handleLogin() {
    const email    = document.getElementById('loginEmail').value.trim();
    const password = document.getElementById('loginPassword').value;
    const btn      = document.getElementById('loginBtn');
    const msg      = document.getElementById('loginMsg');

    if (!email)    { showAuthMessage(msg,'error','Please enter your email address.'); return; }
    if (!password) { showAuthMessage(msg,'error','Please enter your password.'); return; }

    setButtonLoading(btn, true, 'Signing In...');
    hideAuthMessage(msg);

    cosupAuth.signInWithEmailAndPassword(email, password)
        .then(function(cred) {
            if (!cred.user.emailVerified) {
                cosupAuth.signOut();
                setButtonLoading(btn, false, 'Sign In');
                showAuthMessage(msg,'error','Please verify your email before signing in.');
                return;
            }
            showAuthMessage(msg,'success','Welcome back! Redirecting...');
            cred.user.getIdToken().then(function(token) {
                syncUserToDB(token, cred.user).then(function(data) {
                    setTimeout(function() {
                        closeModal();
                        window.location.href = (data && (data.role==='admin'||data.role==='superadmin'))
                            ? COSUP_BASE_URL+'/admin/dashboard.php'
                            : COSUP_BASE_URL+'/user/dashboard.php';
                    }, 1200);
                });
            });
        })
        .catch(function(err) {
            setButtonLoading(btn, false, 'Sign In');
            showAuthMessage(msg,'error', getFirebaseErrorMessage(err.code));
        });
}

// ---- GOOGLE SIGN IN ----
function handleGoogleSignIn() {
    const provider = new firebase.auth.GoogleAuthProvider();
    provider.setCustomParameters({ prompt: 'select_account' });

    cosupAuth.signInWithPopup(provider)
        .then(function(result) {
            showAuthMessage(document.getElementById('loginMsg'),'success','Signed in with Google! Redirecting...');
            result.user.getIdToken().then(function(token) {
                syncUserToDB(token, result.user).then(function(data) {
                    setTimeout(function() {
                        closeModal();
                        window.location.href = (data && (data.role==='admin'||data.role==='superadmin'))
                            ? COSUP_BASE_URL+'/admin/dashboard.php'
                            : COSUP_BASE_URL+'/user/dashboard.php';
                    }, 1200);
                });
            });
        })
        .catch(function(err) {
            showAuthMessage(document.getElementById('loginMsg'),'error', getFirebaseErrorMessage(err.code));
        });
}

// ---- FORGOT PASSWORD ----
function handleForgotPassword() {
    const email = document.getElementById('forgotEmail').value.trim();
    const btn   = document.getElementById('forgotBtn');
    const msg   = document.getElementById('forgotMsg');

    if (!email) { showAuthMessage(msg,'error','Please enter your email address.'); return; }

    setButtonLoading(btn, true, 'Sending...');
    hideAuthMessage(msg);

    cosupAuth.sendPasswordResetEmail(email)
        .then(function() {
            showAuthMessage(msg,'success','Password reset email sent to '+email+'. Check your inbox.');
            setButtonLoading(btn, false, 'Send Reset Email');
        })
        .catch(function(err) {
            setButtonLoading(btn, false, 'Send Reset Email');
            showAuthMessage(msg,'error', getFirebaseErrorMessage(err.code));
        });
}

// ---- PASSWORD STRENGTH ----
function checkPasswordStrength(password) {
    var score = 0;
    if (password.length >= 8)          score++;
    if (password.length >= 12)         score++;
    if (/[A-Z]/.test(password))        score++;
    if (/[0-9]/.test(password))        score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    var bar   = document.getElementById('passwordStrengthBar');
    var label = document.getElementById('passwordStrengthLabel');
    if (!bar || !label) return;

    if (password.length === 0) {
        bar.style.width = '0%';
        label.textContent = '';
        return;
    }
    if (score <= 2) {
        bar.style.width = '33%'; bar.style.background = '#e05a3a';
        label.textContent = 'Weak'; label.style.color = '#e05a3a';
    } else if (score === 3) {
        bar.style.width = '66%'; bar.style.background = '#f0c93a';
        label.textContent = 'Medium'; label.style.color = '#f0c93a';
    } else {
        bar.style.width = '100%'; bar.style.background = '#3BA53E';
        label.textContent = 'Strong'; label.style.color = '#3BA53E';
    }
}

// ---- LOGOUT ----
function logoutCOSUP() {
    var userName     = document.getElementById('navUserName');
    var logoutNameEl = document.getElementById('logoutUserName');
    var logoutSplash = document.getElementById('cosup-logout-splash');
    var mainSite     = document.getElementById('cosup-main');

    if (logoutNameEl && userName) logoutNameEl.textContent = 'Goodbye, '+userName.textContent+'!';
    if (logoutSplash && mainSite) {
        mainSite.style.opacity = '0';
        mainSite.style.transition = 'opacity 0.5s ease';
        setTimeout(function() {
            mainSite.style.display = 'none';
            logoutSplash.style.display = 'flex';
            logoutSplash.style.opacity = '0';
            logoutSplash.style.transition = 'opacity 0.5s ease';
            setTimeout(function() { logoutSplash.style.opacity = '1'; }, 50);
        }, 400);
    }
    cosupAuth.signOut().then(function() {
        fetch(COSUP_BASE_URL+'/auth/logout.php', { method:'POST', headers:{'Content-Type':'application/json'} }).catch(function(){});
        setTimeout(function() { window.location.href = COSUP_BASE_URL+'/index.php'; }, 3000);
    }).catch(function() { window.location.href = COSUP_BASE_URL+'/index.php'; });
}

// ---- SYNC USER TO DB ----
function syncUserToDB(idToken, firebaseUser, extraData) {
    return fetch(COSUP_BASE_URL+'/auth/sync-user.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ idToken: idToken, extra: extraData || {} })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.status === 'success') {
            sessionStorage.setItem('cosup_role',    data.role);
            sessionStorage.setItem('cosup_user_id', data.user_id);
            sessionStorage.setItem('cosup_name',    data.first_name);
            return data;
        }
        return null;
    })
    .catch(function(err) { console.warn('[COSUP] DB sync failed:', err.message); return null; });
}

// ---- NAVBAR ----
function updateNavbarLoggedIn(user) {
    var guestEl  = document.getElementById('navAuthGuest');
    var userEl   = document.getElementById('navAuthUser');
    var nameEl   = document.getElementById('navUserName');
    var avatarEl = document.getElementById('navUserAvatar');
    if (guestEl)  guestEl.style.display = 'none';
    if (userEl)   userEl.style.display  = 'flex';
    var name = (user.displayName || user.email.split('@')[0]).split(' ')[0];
    if (nameEl)   nameEl.textContent   = name;
    if (avatarEl) avatarEl.textContent = name.charAt(0).toUpperCase();
}

function updateNavbarLoggedOut() {
    var guestEl = document.getElementById('navAuthGuest');
    var userEl  = document.getElementById('navAuthUser');
    if (guestEl) guestEl.style.display = 'flex';
    if (userEl)  userEl.style.display  = 'none';
}

function toggleUserMenu() {
    var dropdown = document.getElementById('navUserDropdown');
    if (dropdown) dropdown.classList.toggle('open');
}

document.addEventListener('click', function(e) {
    var chip     = document.querySelector('.nav-user-chip');
    var dropdown = document.getElementById('navUserDropdown');
    if (dropdown && chip && !chip.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('open');
    }
});

function togglePassword(inputId) {
    var input = document.getElementById(inputId);
    if (input) input.type = input.type === 'password' ? 'text' : 'password';
}

function getFirebaseErrorMessage(code) {
    var m = {
        'auth/email-already-in-use'   : 'This email is already registered. Try signing in.',
        'auth/invalid-email'          : 'Please enter a valid email address.',
        'auth/weak-password'          : 'Password must be at least 8 characters.',
        'auth/user-not-found'         : 'No account found with this email.',
        'auth/wrong-password'         : 'Incorrect password. Please try again.',
        'auth/too-many-requests'      : 'Too many attempts. Please wait and try again.',
        'auth/network-request-failed' : 'Network error. Check your connection.',
        'auth/user-disabled'          : 'This account has been disabled.',
        'auth/invalid-credential'     : 'Invalid email or password. Please try again.',
        'auth/popup-closed-by-user'   : 'Sign in was cancelled.',
        'auth/popup-blocked'          : 'Popup blocked. Please allow popups for this site.',
    };
    return m[code] || 'Something went wrong. Please try again.';
}

function showAuthMessage(el, type, text) {
    if (!el) return;
    el.textContent = text; el.className = 'auth-message '+type; el.style.display = 'block';
}

function hideAuthMessage(el) {
    if (!el) return;
    el.style.display = 'none'; el.textContent = '';
}

function setButtonLoading(btn, loading, text) {
    if (!btn) return;
    btn.disabled = loading; btn.textContent = text;
}