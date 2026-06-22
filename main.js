// ============================================================
//  COSUP V2 — assets/js/main.js
//  Main JavaScript — Splash, Theme, Navbar, Maps, Animations
//  Community Oriented Substance Use Programme
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

// ============================================================
//  ALL 16 COSUP SITE COORDINATES
//  Used by Google Maps on website AND Android app API
// ============================================================
const COSUP_SITES = [
    { id:1,  name:'COSUP Sediba Hope — Bosman St', lat:-25.7450000, lng:28.1880000, phone:'082 858 4304', hours:'Mon–Fri 08:00–16:00', addr:'173 Bosman Street, Pretoria CBD', main:true  },
    { id:2,  name:'COSUP Bronkhorstspruit',         lat:-25.7990000, lng:28.7420000, phone:'076 560 1389', hours:'Tue & Thur 09:30–14:00', addr:'Zithobeni Clinic, Bronkhorstspruit', main:false },
    { id:3,  name:'COSUP Hammanskraal',             lat:-25.4490000, lng:28.2730000, phone:'066 300 8338', hours:'Mon & Fri 08:00–16:00',  addr:'Mandisa Shiceka Clinic, Old Warmbaths Road', main:false },
    { id:4,  name:'COSUP Daspoort Poli Clinic',     lat:-25.7350000, lng:28.1560000, phone:'082 857 0922', hours:'Mon–Fri 08:00–16:00',    addr:'Cnr Market & Camp Street, Pretoria West', main:false },
    { id:5,  name:'COSUP Attridgeville Clinic',     lat:-25.7800000, lng:28.0960000, phone:'082 858 2450', hours:'Mon–Fri 08:00–16:00',    addr:'1A Mareka Street, Attridgeville', main:false },
    { id:6,  name:'COSUP Laudium Community',        lat:-25.7950000, lng:28.0740000, phone:'081 725 2462', hours:'Mon–Fri 08:00–16:00',    addr:'405 Bengal Street, Laudium', main:false },
    { id:7,  name:'COSUP Olievenhoutbosch',         lat:-25.9100000, lng:28.0850000, phone:'066 472 5740', hours:'Mon–Fri 08:00–16:00',    addr:'Cnr Imbovane & Imbongolo Street', main:false },
    { id:8,  name:'COSUP Winterveldt',              lat:-25.5100000, lng:28.0700000, phone:'071 573 5883', hours:'Mon–Fri 08:00–16:00',    addr:'1/4 Cemetery Road, Slovo Gardens', main:false },
    { id:9,  name:'COSUP Mamelodi Lusaka',          lat:-25.7000000, lng:28.3700000, phone:'082 858 2553', hours:'Mon–Fri 08:00–16:00',    addr:'Cnr Millenyane & Ratshwene Street', main:false },
    { id:10, name:'COSUP Mamelodi Ikageng',         lat:-25.7150000, lng:28.3900000, phone:'076 560 1389', hours:'Mon–Fri 08:00–16:00',    addr:'21882 Molokoloko Circle, Mamelodi', main:false },
    { id:11, name:'COSUP Mamelodi Regional Hospital',lat:-25.7300000,lng:28.4100000, phone:'066 472 5580', hours:'Mon–Fri 08:00–16:00',    addr:'Cnr Serapeng & Tsarnaya Road, Mamelodi', main:false },
    { id:12, name:'COSUP Eersterust CHC',           lat:-25.7200000, lng:28.2900000, phone:'082 941 6038', hours:'Mon–Fri 08:00–16:00',    addr:'Cnr PS Fourie Drive & Hans Coverdale Road', main:false },
    { id:13, name:'COSUP Soshanguve Block V',       lat:-25.5500000, lng:28.1000000, phone:'066 300 8338', hours:'Mon–Thur 08:00–16:00',   addr:'Elim Tabernacle Church, Inkanyezi Street', main:false },
    { id:14, name:'COSUP Soshanguve Block K POPUP', lat:-25.5700000, lng:28.0800000, phone:'076 587 6770', hours:'Mon–Fri 08:00–16:00',    addr:'Cnr Aubrey Matlala & Tlou Street', main:false },
    { id:15, name:'COSUP Soshanguve M17',           lat:-25.5900000, lng:28.0650000, phone:'082 858 2563', hours:'Mon–Fri 08:00–16:00',    addr:'Dream Team Foundation, M17 Road, Soshanguve South', main:false },
    { id:16, name:'COSUP Garankuwa',                lat:-25.6000000, lng:28.0200000, phone:'079 321 4152', hours:'Mon–Fri 08:00–16:00',    addr:'Reatlegile Centre, Stand 05030', main:false },
];

// ============================================================
//  SPLASH SCREEN
// ============================================================
function enterCOSUP() {
    const splash = document.getElementById('cosup-splash');
    const main   = document.getElementById('cosup-main');

    if (!splash || !main) return;

    splash.style.transition = 'opacity 0.8s ease';
    splash.style.opacity    = '0';

    setTimeout(function() {
        splash.style.display = 'none';
        main.style.display   = 'block';
        main.style.opacity   = '0';
        main.style.transition = 'opacity 0.6s ease';

        setTimeout(function() {
            main.style.opacity = '1';
            // Init everything after reveal
            initAOS();
            initNavbarScroll();
            initMap();
        }, 50);

        // Save splash seen in sessionStorage
        sessionStorage.setItem('cosup_splash_seen', '1');

    }, 800);
}

// Auto-enter after 6 seconds if user doesn't click
window.addEventListener('load', function() {
    // If already seen in this session skip splash
    if (sessionStorage.getItem('cosup_splash_seen') === '1') {
        const splash = document.getElementById('cosup-splash');
        const main   = document.getElementById('cosup-main');
        if (splash) splash.style.display = 'none';
        if (main)   main.style.display   = 'block';
        initAOS();
        initNavbarScroll();
        initMap();
        return;
    }

    setTimeout(function() {
        const splash = document.getElementById('cosup-splash');
        if (splash && splash.style.display !== 'none') {
            enterCOSUP();
        }
    }, 6000);
});

// ============================================================
//  THEME TOGGLE — Dark / Light
// ============================================================
function toggleTheme() {
    const html    = document.documentElement;
    const icon    = document.getElementById('themeIconEl');
    const isDark  = html.getAttribute('data-theme') === 'dark';

    if (isDark) {
        html.setAttribute('data-theme', 'light');
        if (icon) {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
        localStorage.setItem('cosup_theme', 'light');
    } else {
        html.setAttribute('data-theme', 'dark');
        if (icon) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }
        localStorage.setItem('cosup_theme', 'dark');
    }
}

// Restore saved theme on page load
(function() {
    const saved = localStorage.getItem('cosup_theme') || 'dark';
    document.documentElement.setAttribute('data-theme', saved);
    document.addEventListener('DOMContentLoaded', function() {
        const icon = document.getElementById('themeIconEl');
        if (icon) {
            icon.classList.remove('fa-moon', 'fa-sun');
            icon.classList.add(saved === 'dark' ? 'fa-sun' : 'fa-moon');
        }
    });
})();

// ============================================================
//  NAVBAR — Scroll shadow effect
// ============================================================
function initNavbarScroll() {
    const navbar = document.getElementById('cosupNavbar');
    if (!navbar) return;

    window.addEventListener('scroll', function() {
        if (window.scrollY > 40) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}

// ============================================================
//  MOBILE MENU TOGGLE
// ============================================================
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    if (menu) menu.classList.toggle('open');
}

// Close mobile menu on outside click
document.addEventListener('click', function(e) {
    const menu      = document.getElementById('mobileMenu');
    const hamburger = document.getElementById('hamburgerBtn');
    if (menu && hamburger &&
        !menu.contains(e.target) &&
        !hamburger.contains(e.target)) {
        menu.classList.remove('open');
    }
});

// ============================================================
//  MODAL SYSTEM
// ============================================================
function openModal(type) {
    closeModal();

    const overlay = document.getElementById('modalOverlay');
    const modal   = document.getElementById(type + 'Modal');

    if (!overlay || !modal) return;

    overlay.classList.add('active');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    // Clear messages
    const msg = document.getElementById(type + 'Msg');
    if (msg) {
        msg.style.display = 'none';
        msg.textContent   = '';
    }
}

function closeModal() {
    document.querySelectorAll('.cosup-modal').forEach(function(m) {
        m.classList.remove('active');
    });
    const overlay = document.getElementById('modalOverlay');
    if (overlay) overlay.classList.remove('active');
    document.body.style.overflow = '';
}

function switchModal(type) {
    closeModal();
    setTimeout(function() { openModal(type); }, 150);
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

// ============================================================
//  GOOGLE MAPS INIT
//  Called after splash screen closes
//  Uses COSUP_SITES array above
// ============================================================
function initMap() {
    const mapEl = document.getElementById('cosup-map');
    if (!mapEl || typeof google === 'undefined') return;

    // Remove placeholder text
    mapEl.style.cssText = 'width:100%; height:480px;';

    const tshwaneCentre = { lat: -25.7479, lng: 28.2293 };

    const map = new google.maps.Map(mapEl, {
        center          : tshwaneCentre,
        zoom            : 10,
        styles          : getMapStyle(),
        mapTypeControl  : false,
        streetViewControl: false,
        fullscreenControl: true,
        zoomControl     : true,
    });

    const infoWindow = new google.maps.InfoWindow();

    COSUP_SITES.forEach(function(site) {
        // Main centre = gold pin, others = green pin
        const pinColor  = site.main ? '%23f0c93a' : '%233BA53E';
        const pinSize   = site.main ? 36 : 30;

        const marker = new google.maps.Marker({
            position  : { lat: site.lat, lng: site.lng },
            map       : map,
            title     : site.name,
            animation : google.maps.Animation.DROP,
            icon      : {
                url: 'data:image/svg+xml;charset=UTF-8,' +
                    encodeURIComponent(
                        '<svg width="' + pinSize + '" height="' + (pinSize + 8) + '" ' +
                        'viewBox="0 0 30 38" xmlns="http://www.w3.org/2000/svg">' +
                        '<path d="M15 0C6.7 0 0 6.7 0 15c0 11.25 15 23 15 23s15-11.75 15-23C30 6.7 23.3 0 15 0z" ' +
                        'fill="' + pinColor + '"/>' +
                        '<circle cx="15" cy="15" r="7" fill="white"/>' +
                        '</svg>'
                    ),
                scaledSize: new google.maps.Size(pinSize, pinSize + 8),
            },
        });

        marker.addListener('click', function() {
            infoWindow.setContent(
                '<div style="font-family:DM Sans,sans-serif;padding:10px;max-width:240px;">' +
                '<strong style="color:#3BA53E;font-size:13px;">' + site.name + '</strong><br/>' +
                '<span style="font-size:11px;color:#666;display:block;margin-top:6px;">📍 ' + site.addr + '</span>' +
                '<span style="font-size:11px;color:#666;display:block;margin-top:3px;">🕐 ' + site.hours + '</span>' +
                '<a href="tel:' + site.phone.replace(/\s/g,'') + '" ' +
                'style="font-size:11px;color:#3BA53E;font-weight:700;display:block;margin-top:6px;">📞 ' + site.phone + '</a>' +
                '</div>'
            );
            infoWindow.open(map, marker);
        });
    });
}

// Google Maps callback — called by Maps API script
function cosupMapCallback() {
    // Only init map if main site is visible
    const main = document.getElementById('cosup-main');
    if (main && main.style.display !== 'none') {
        initMap();
    }
}

// Map style — matches COSUP dark/light theme
function getMapStyle() {
    const isDark = document.documentElement
        .getAttribute('data-theme') === 'dark';

    if (!isDark) return [];

    return [
        { elementType:'geometry',           stylers:[{ color:'#0d1f15' }] },
        { elementType:'labels.text.stroke', stylers:[{ color:'#0d1f15' }] },
        { elementType:'labels.text.fill',   stylers:[{ color:'#5a8a5e' }] },
        { featureType:'road', elementType:'geometry',
          stylers:[{ color:'#152b1e' }] },
        { featureType:'road', elementType:'geometry.stroke',
          stylers:[{ color:'#1a3a24' }] },
        { featureType:'road', elementType:'labels.text.fill',
          stylers:[{ color:'#4a7a4e' }] },
        { featureType:'water', elementType:'geometry',
          stylers:[{ color:'#0a1f15' }] },
        { featureType:'poi.park', elementType:'geometry',
          stylers:[{ color:'#0d2416' }] },
        { featureType:'transit', elementType:'geometry',
          stylers:[{ color:'#0d1f15' }] },
    ];
}

// ============================================================
//  SITE SEARCH / FILTER
// ============================================================
function filterSites(query) {
    const cards = document.querySelectorAll('#sitesGrid .site-card');
    const q     = query.toLowerCase().trim();

    cards.forEach(function(card) {
        const name = card.getAttribute('data-name') || '';
        const text = card.textContent.toLowerCase();
        if (q === '' || name.includes(q) || text.includes(q)) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
}

// ============================================================
//  HOPELINE LOG
//  Logs anonymous click to cosup_db hopeline_logs table
//  Works from both website and is called by Android API too
// ============================================================
function logHopelineClick(channel) {
    fetch(COSUP_BASE_URL + '/api/hopeline-log.php', {
        method  : 'POST',
        headers : { 'Content-Type': 'application/json' },
        body    : JSON.stringify({
            channel_type : channel || 'call',
            platform     : 'website'
        })
    }).catch(function() {});
}

// ============================================================
//  AOS — Scroll Animations
//  Triggered after splash screen closes
// ============================================================
function initAOS() {
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const delay = parseInt(
                    entry.target.getAttribute('data-delay') || '0'
                );
                setTimeout(function() {
                    entry.target.classList.add('aos-visible');
                }, delay);
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold   : 0.12,
        rootMargin  : '0px 0px -40px 0px'
    });

    document.querySelectorAll('[data-aos]').forEach(function(el) {
        observer.observe(el);
    });
}

// ============================================================
//  SMOOTH SCROLL — For all anchor links
// ============================================================
document.addEventListener('click', function(e) {
    const link = e.target.closest('a[href^="#"]');
    if (!link) return;

    const target = document.querySelector(link.getAttribute('href'));
    if (target) {
        e.preventDefault();
        const offset = 80;
        const top    = target.getBoundingClientRect().top +
                       window.scrollY - offset;
        window.scrollTo({ top: top, behavior: 'smooth' });
    }
});

// ============================================================
//  COUNTER ANIMATION — Stats bar numbers
// ============================================================
function animateCounter(el, target, duration) {
    let start     = 0;
    const step    = target / (duration / 16);
    const isFloat = target % 1 !== 0;

    const timer = setInterval(function() {
        start += step;
        if (start >= target) {
            start = target;
            clearInterval(timer);
        }
        el.textContent = isFloat
            ? start.toFixed(1)
            : Math.floor(start).toLocaleString() +
              (el.dataset.suffix || '');
    }, 16);
}

function initCounters() {
    document.querySelectorAll('.stat-num[data-target]')
        .forEach(function(el) {
            const target = parseFloat(el.getAttribute('data-target'));
            animateCounter(el, target, 1500);
        });
}

// ============================================================
//  CONSOLE BRANDING
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    console.log(
        '%cCOSUP V2',
        'color:#3BA53E;font-size:24px;font-weight:900;' +
        'font-family:monospace;'
    );
    console.log(
        '%cCommunity Oriented Substance Use Programme\n' +
        'The 5 Watermelons · XISD5319 · IIE Rosebank College',
        'color:#4dc951;font-size:12px;'
    );
    console.log(
        '%cDatabase: cosup_db · Firebase: cosup-5d9f6 · ' +
        'Version: 2.0',
        'color:#f0c93a;font-size:11px;'
    );
});