<?php
require_once __DIR__ . '/koneksi.php';

// Ambil semua data cagar budaya dengan koordinat
$query_peta = "SELECT * FROM cagar_budaya WHERE latitude IS NOT NULL AND longitude IS NOT NULL ORDER BY id ASC";
$res_peta = mysqli_query($koneksi, $query_peta);
$cagar_list = [];
while ($row = mysqli_fetch_assoc($res_peta)) {
    $cagar_list[] = $row;
}

$cagar_json = json_encode($cagar_list, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
?>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-5">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-bold bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-400 border border-brand-200/50 mb-2">
                    <i class="bi bi-geo-alt-fill text-gold-500"></i>
                    <span>Sistem Informasi Geospasial Kebudayaan (WebGIS)</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight font-serif">
                    Peta Interaktif Sebaran Cagar Budaya Kota Ternate
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-2xl leading-relaxed">
                    Eksplorasi geospasial situs bersejarah benteng era kolonial, Kedaton Kesultanan Ternate, dan warisan budaya jalur rempah dunia.
                </p>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <a href="index.php?halaman=cagar-budaya" class="inline-flex items-center px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 transition">
                    <i class="bi bi-grid-3x3-gap-fill mr-1.5"></i> Lihat Katalog Card
                </a>
            </div>
        </div>

        <!-- Filter Kategori Geospasial -->
        <div class="flex flex-wrap items-center gap-2 pt-4">
            <span class="text-xs font-bold text-slate-400 mr-2 uppercase tracking-wider">Filter Situs:</span>
            <button onclick="filterMapCategory('all')" id="btn-cat-all" class="map-cat-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-brand-800 text-white shadow-sm transition">
                Semua (<?= count($cagar_list) ?>)
            </button>
            <button onclick="filterMapCategory('Benteng')" id="btn-cat-benteng" class="map-cat-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-50 transition">
                Benteng Bersejarah
            </button>
            <button onclick="filterMapCategory('Istana')" id="btn-cat-istana" class="map-cat-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-50 transition">
                Kedaton Kesultanan
            </button>
            <button onclick="filterMapCategory('Masjid')" id="btn-cat-masjid" class="map-cat-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-50 transition">
                Tempat Ibadah Kuno
            </button>
            <button onclick="filterMapCategory('Alam')" id="btn-cat-alam" class="map-cat-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-50 transition">
                Situs Alam & Rempah
            </button>
        </div>
    </div>

    <!-- Peta & List Interaktif Split View -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Container Peta Leaflet (8 Kolom) -->
        <div class="lg:col-span-8 bg-white dark:bg-slate-900 rounded-3xl p-3 sm:p-4 shadow-sm border border-slate-100 dark:border-slate-800">
            <div id="culturalMap" class="w-full h-[540px] sm:h-[620px] rounded-2xl z-10 overflow-hidden shadow-inner border border-slate-200 dark:border-slate-800"></div>
            
            <div class="mt-3 px-3 py-2 flex flex-wrap items-center justify-between text-xs text-slate-400 border-t border-slate-100 dark:border-slate-800">
                <span class="flex items-center space-x-1.5">
                    <i class="bi bi-info-circle-fill text-brand-600"></i>
                    <span>Klik pada pin atau daftar di samping untuk melihat rincian situs cagar budaya.</span>
                </span>
                <span class="font-mono text-[11px] text-slate-400">Peta: OpenStreetMap | Geospasial Kota Ternate</span>
            </div>
        </div>

        <!-- Sidebar Daftar Titik Situs (4 Kolom) -->
        <div class="lg:col-span-4 space-y-3 max-h-[660px] overflow-y-auto pr-1" id="sitesSidebarList">
            <?php foreach ($cagar_list as $idx => $site): ?>
                <div class="site-item-card bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-800 hover:border-gold-400 dark:hover:border-gold-500 cursor-pointer transition duration-200 group"
                     data-cat="<?= htmlspecialchars($site['kategori']) ?>"
                     data-lat="<?= $site['latitude'] ?>"
                     data-lng="<?= $site['longitude'] ?>"
                     data-idx="<?= $idx ?>"
                     onclick="focusOnMarker(<?= $idx ?>)">
                    <div class="flex items-start space-x-3">
                        <img src="asset/gmbr/<?= htmlspecialchars($site['gambar']) ?>" 
                             alt="<?= htmlspecialchars($site['nama']) ?>" 
                             class="w-16 h-16 rounded-xl object-cover shrink-0 shadow-sm group-hover:scale-105 transition-transform duration-300"
                             onerror="this.src='asset/gmbr/diskeb.png';">
                        <div class="flex-grow min-w-0">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 mb-1">
                                <?= htmlspecialchars($site['kategori']) ?>
                            </span>
                            <h4 class="font-bold text-slate-900 dark:text-white text-xs leading-snug group-hover:text-brand-700 dark:group-hover:text-gold-400 transition truncate">
                                <?= htmlspecialchars($site['nama']) ?>
                            </h4>
                            <p class="text-[11px] text-slate-400 mt-1 flex items-center truncate">
                                <i class="bi bi-geo-alt text-rose-500 mr-1 shrink-0"></i>
                                <span class="truncate"><?= htmlspecialchars($site['lokasi']) ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const rawData = <?= $cagar_json ?>;
    
    // Inisialisasi Peta - Titik Tengah Kota Ternate
    const map = L.map('culturalMap', {
        center: [0.7893, 127.3620],
        zoom: 13,
        zoomControl: true
    });

    // Tile Layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Custom Icon Maker SVG Budaya
    const customCultureIcon = L.divIcon({
        className: 'custom-culture-pin',
        html: `
            <div style="background: linear-gradient(135deg, #0b2545, #134074); width: 36px; height: 36px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid #e5a93c; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-bank2" style="transform: rotate(45deg); color: #ffffff; font-size: 14px;"></i>
            </div>
        `,
        iconSize: [36, 36],
        iconAnchor: [18, 36],
        popupAnchor: [0, -36]
    });

    const markers = [];

    // Tambahkan marker untuk setiap situs cagar budaya
    rawData.forEach((site, index) => {
        if (!site.latitude || !site.longitude) return;

        const popupContent = `
            <div style="min-width: 240px; max-width: 280px; font-family: 'Plus Jakarta Sans', sans-serif;">
                <div style="height: 120px; border-radius: 12px; overflow: hidden; margin-bottom: 8px;">
                    <img src="asset/gmbr/${site.gambar}" alt="${site.nama}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='asset/gmbr/diskeb.png';">
                </div>
                <span style="font-size: 10px; font-weight: bold; background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">${site.kategori}</span>
                <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 4px 0 2px 0; line-height: 1.2;">${site.nama}</h4>
                <p style="font-size: 11px; color: #64748b; margin: 0 0 6px 0;"><strong>Periode:</strong> ${site.era_sejarah || 'Abad ke-16'}</p>
                <p style="font-size: 11px; color: #475569; margin: 0 0 8px 0; line-height: 1.4;">${site.deskripsi ? site.deskripsi.substring(0, 95) + '...' : ''}</p>
                <div style="display: flex; gap: 6px;">
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${site.latitude},${site.longitude}" target="_blank" style="flex: 1; text-align: center; background: #0b2545; color: #ffffff; font-size: 11px; font-weight: bold; padding: 6px 10px; border-radius: 8px; text-decoration: none;">
                        <i class="bi bi-geo-alt-fill"></i> Petunjuk Arah
                    </a>
                </div>
            </div>
        `;

        const marker = L.marker([site.latitude, site.longitude], { icon: customCultureIcon }).addTo(map);
        marker.bindPopup(popupContent);
        markers.push({
            marker: marker,
            category: site.kategori,
            data: site
        });
    });

    // Fungsi klik kartu sidebar fokus ke marker
    window.focusOnMarker = function(index) {
        if (markers[index]) {
            const m = markers[index].marker;
            map.flyTo(m.getLatLng(), 16, { animate: true, duration: 1.2 });
            setTimeout(() => {
                m.openPopup();
            }, 1200);
        }
    };

    // Fungsi Filter Kategori
    window.filterMapCategory = function(catKey) {
        // Update UI Button
        document.querySelectorAll('.map-cat-btn').forEach(btn => {
            btn.classList.remove('bg-brand-800', 'text-white');
            btn.classList.add('bg-slate-100', 'text-slate-600', 'dark:bg-slate-800', 'dark:text-slate-300');
        });

        const activeBtn = document.getElementById('btn-cat-' + catKey.toLowerCase());
        if (activeBtn) {
            activeBtn.classList.remove('bg-slate-100', 'text-slate-600', 'dark:bg-slate-800', 'dark:text-slate-300');
            activeBtn.classList.add('bg-brand-800', 'text-white');
        }

        const cards = document.querySelectorAll('.site-item-card');

        markers.forEach((item, idx) => {
            const match = (catKey === 'all' || item.category.toLowerCase().includes(catKey.toLowerCase()));
            if (match) {
                if (!map.hasLayer(item.marker)) map.addLayer(item.marker);
                if (cards[idx]) cards[idx].style.display = 'block';
            } else {
                if (map.hasLayer(item.marker)) map.removeLayer(item.marker);
                if (cards[idx]) cards[idx].style.display = 'none';
            }
        });
    };
});
</script>
