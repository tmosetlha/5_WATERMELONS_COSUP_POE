// ============================================================
//  COSUP V2 — assets/js/maps.js
//  OpenStreetMap + Leaflet.js — All 16 COSUP Sites
//  No API key needed — 100% Free
//  The 5 Watermelons | XISD5319 | IIE Rosebank College
// ============================================================

// All 16 COSUP site coordinates
const COSUP_MAP_SITES = [
    { id:1,  name:'COSUP Sediba Hope — Bosman St', lat:-25.7450, lng:28.1880, phone:'082 858 4304', hours:'Mon–Fri 08:00–16:00', addr:'173 Bosman Street, Pretoria CBD', main:true  },
    { id:2,  name:'COSUP Bronkhorstspruit',         lat:-25.7990, lng:28.7420, phone:'076 560 1389', hours:'Tue & Thur 09:30–14:00', addr:'Zithobeni Clinic, Bronkhorstspruit', main:false },
    { id:3,  name:'COSUP Hammanskraal',             lat:-25.4490, lng:28.2730, phone:'066 300 8338', hours:'Mon & Fri 08:00–16:00',  addr:'Mandisa Shiceka Clinic, Old Warmbaths Road', main:false },
    { id:4,  name:'COSUP Daspoort Poli Clinic',     lat:-25.7350, lng:28.1560, phone:'082 857 0922', hours:'Mon–Fri 08:00–16:00',    addr:'Cnr Market & Camp Street, Pretoria West', main:false },
    { id:5,  name:'COSUP Attridgeville Clinic',     lat:-25.7800, lng:28.0960, phone:'082 858 2450', hours:'Mon–Fri 08:00–16:00',    addr:'1A Mareka Street, Attridgeville', main:false },
    { id:6,  name:'COSUP Laudium Community',        lat:-25.7950, lng:28.0740, phone:'081 725 2462', hours:'Mon–Fri 08:00–16:00',    addr:'405 Bengal Street, Laudium', main:false },
    { id:7,  name:'COSUP Olievenhoutbosch',         lat:-25.9100, lng:28.0850, phone:'066 472 5740', hours:'Mon–Fri 08:00–16:00',    addr:'Cnr Imbovane & Imbongolo Street', main:false },
    { id:8,  name:'COSUP Winterveldt',              lat:-25.5100, lng:28.0700, phone:'071 573 5883', hours:'Mon–Fri 08:00–16:00',    addr:'1/4 Cemetery Road, Slovo Gardens', main:false },
    { id:9,  name:'COSUP Mamelodi Lusaka',          lat:-25.7000, lng:28.3700, phone:'082 858 2553', hours:'Mon–Fri 08:00–16:00',    addr:'Cnr Millenyane & Ratshwene Street', main:false },
    { id:10, name:'COSUP Mamelodi Ikageng',         lat:-25.7150, lng:28.3900, phone:'076 560 1389', hours:'Mon–Fri 08:00–16:00',    addr:'21882 Molokoloko Circle, Mamelodi', main:false },
    { id:11, name:'COSUP Mamelodi Regional Hospital',lat:-25.7300, lng:28.4100, phone:'066 472 5580', hours:'Mon–Fri 08:00–16:00',   addr:'Cnr Serapeng & Tsarnaya Road, Mamelodi', main:false },
    { id:12, name:'COSUP Eersterust CHC',           lat:-25.7200, lng:28.2900, phone:'082 941 6038', hours:'Mon–Fri 08:00–16:00',    addr:'Cnr PS Fourie Drive & Hans Coverdale Road', main:false },
    { id:13, name:'COSUP Soshanguve Block V',       lat:-25.5500, lng:28.1000, phone:'066 300 8338', hours:'Mon–Thur 08:00–16:00',   addr:'Elim Tabernacle Church, Inkanyezi Street', main:false },
    { id:14, name:'COSUP Soshanguve Block K POPUP', lat:-25.5700, lng:28.0800, phone:'076 587 6770', hours:'Mon–Fri 08:00–16:00',    addr:'Cnr Aubrey Matlala & Tlou Street', main:false },
    { id:15, name:'COSUP Soshanguve M17',           lat:-25.5900, lng:28.0650, phone:'082 858 2563', hours:'Mon–Fri 08:00–16:00',    addr:'Dream Team Foundation, M17 Road, Soshanguve South', main:false },
    { id:16, name:'COSUP Garankuwa',                lat:-25.6000, lng:28.0200, phone:'079 321 4152', hours:'Mon–Fri 08:00–16:00',    addr:'Reatlegile Centre, Stand 05030', main:false },
];

var cosupMap    = null;
var markersMap  = {};
var activePopup = null;

// ============================================================
//  INIT MAP
// ============================================================
function initCOSUPMap() {
    if (document.getElementById('cosup-leaflet-map') === null) return;

    // Create map centred on Tshwane
    cosupMap = L.map('cosup-leaflet-map', {
        center     : [-25.7479, 28.2293],
        zoom       : 10,
        zoomControl: true,
    });

    // OpenStreetMap tile layer — free, no key needed
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution : '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom     : 19,
    }).addTo(cosupMap);

    // Plot all 16 pins
    COSUP_MAP_SITES.forEach(function(site) {
        var pinColor = site.main ? '#f0c93a' : '#3BA53E';
        var pinSize  = site.main ? 18 : 14;

        // Custom pin icon
        var icon = L.divIcon({
            className  : '',
            html       : '<div style="width:' + pinSize + 'px;height:' + pinSize + 'px;' +
                         'border-radius:50% 50% 50% 0;background:' + pinColor + ';' +
                         'border:2px solid white;transform:rotate(-45deg);' +
                         'box-shadow:0 2px 8px rgba(0,0,0,0.3);"></div>',
            iconSize   : [pinSize, pinSize],
            iconAnchor : [pinSize / 2, pinSize],
            popupAnchor: [0, -pinSize],
        });

        var marker = L.marker([site.lat, site.lng], { icon: icon }).addTo(cosupMap);

        // Popup content
        var popupContent =
            '<div style="font-family:DM Sans,sans-serif;min-width:220px;padding:4px;">' +
            '<strong style="color:#3BA53E;font-size:13px;display:block;margin-bottom:6px;">' +
            site.name + (site.main ? ' ⭐' : '') + '</strong>' +
            '<p style="font-size:12px;color:#555;margin-bottom:3px;">📍 ' + site.addr + '</p>' +
            '<p style="font-size:12px;color:#555;margin-bottom:3px;">🕐 ' + site.hours + '</p>' +
            '<p style="font-size:12px;color:#555;margin-bottom:10px;">' +
            '<a href="tel:' + site.phone.replace(/\s/g,'') + '" ' +
            'style="color:#3BA53E;font-weight:700;">📞 ' + site.phone + '</a></p>' +
            '<div style="display:flex;gap:6px;flex-wrap:wrap;">' +
            // Drive directions
            '<a href="https://www.google.com/maps/dir/?api=1&destination=' +
            site.lat + ',' + site.lng + '&travelmode=driving" ' +
            'target="_blank" ' +
            'style="flex:1;text-align:center;padding:7px 4px;background:#3BA53E;' +
            'color:white;border-radius:6px;font-size:11px;font-weight:700;' +
            'text-decoration:none;">🚗 Drive</a>' +
            // Walk directions
            '<a href="https://www.google.com/maps/dir/?api=1&destination=' +
            site.lat + ',' + site.lng + '&travelmode=walking" ' +
            'target="_blank" ' +
            'style="flex:1;text-align:center;padding:7px 4px;background:#152b1e;' +
            'color:white;border-radius:6px;font-size:11px;font-weight:700;' +
            'text-decoration:none;">🚶 Walk</a>' +
            // Transit directions
            '<a href="https://www.google.com/maps/dir/?api=1&destination=' +
            site.lat + ',' + site.lng + '&travelmode=transit" ' +
            'target="_blank" ' +
            'style="flex:1;text-align:center;padding:7px 4px;background:#0d1f15;' +
            'color:white;border-radius:6px;font-size:11px;font-weight:700;' +
            'text-decoration:none;">🚌 Transit</a>' +
            '</div></div>';

        marker.bindPopup(popupContent, { maxWidth: 280 });

        // Store marker reference by site id
        markersMap[site.id] = marker;

        // Highlight site card on marker click
        marker.on('click', function() {
            highlightSiteCard(site.id);
        });
    });
}

// ============================================================
//  FOCUS MAP ON SITE — called when user clicks a site card
// ============================================================
function focusMapOnSite(siteId) {
    var site   = COSUP_MAP_SITES.find(function(s) { return s.id === siteId; });
    var marker = markersMap[siteId];
    if (!site || !marker || !cosupMap) return;

    // Smooth zoom to site
    cosupMap.flyTo([site.lat, site.lng], 15, { duration: 1.2 });

    // Open popup after fly
    setTimeout(function() {
        marker.openPopup();
    }, 1300);
}

// ============================================================
//  HIGHLIGHT SITE CARD
// ============================================================
function highlightSiteCard(siteId) {
    // Remove existing highlights
    document.querySelectorAll('.site-card').forEach(function(card) {
        card.style.borderColor = '';
        card.style.boxShadow   = '';
    });

    // Find and highlight matching card
    var card = document.querySelector('.site-card[data-site-id="' + siteId + '"]');
    if (card) {
        card.style.borderColor = '#3BA53E';
        card.style.boxShadow   = '0 0 0 3px rgba(59,165,62,0.3)';
        card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

// ============================================================
//  INIT — runs after page fully loads
// ============================================================
window.addEventListener('load', function() {
    initCOSUPMap();
    // Force map to recalculate size after splash
    setTimeout(function() {
        if (cosupMap) cosupMap.invalidateSize();
    }, 1000);
});