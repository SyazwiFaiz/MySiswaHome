{{-- Feature tambahan student. Sertakan fail ini di hujung dashboard (lihat README). --}}

{{-- Bar banding --}}
<div id="sxCompareBar" class="fixed inset-x-0 bottom-0 z-40 hidden border-t border-indigo-100 bg-white/95 px-4 py-3 shadow-lg backdrop-blur">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3">
        <p class="text-sm font-semibold text-gray-700"><span id="sxCompareCount">0</span> / 3 rumah dipilih untuk dibanding</p>
        <div class="flex gap-2">
            <button type="button" id="sxCompareClear" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">Kosongkan</button>
            <button type="button" id="sxCompareOpen" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">Banding sekarang</button>
        </div>
    </div>
</div>

{{-- Modal banding --}}
<div id="sxCompareModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/50 p-4" role="dialog" aria-modal="true" aria-labelledby="sxCompareTitle">
    <div class="max-h-[90vh] w-full max-w-5xl overflow-auto rounded-2xl bg-white p-5 shadow-xl sm:p-6">
        <div class="mb-4 flex items-center justify-between gap-3">
            <h3 id="sxCompareTitle" class="text-xl font-bold text-gray-800">Banding rumah</h3>
            <button type="button" id="sxCompareClose" class="rounded-xl p-2 text-gray-500 hover:bg-gray-100" aria-label="Tutup">✕</button>
        </div>
        <div id="sxCompareBody" class="overflow-x-auto"></div>
    </div>
</div>

<div id="sxToast" class="fixed bottom-24 left-1/2 z-50 hidden -translate-x-1/2 rounded-full bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white shadow-lg" role="status"></div>

<script>
(function () {
    'use strict';
    if (typeof houses === 'undefined') return;

    const BASE = @json(url('/student'));
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || @json(csrf_token());

    // Koordinat anggaran. Betulkan / tambah kampus lain di sini.
    const CAMPUSES = {
        uitm_shah_alam: { name: 'UiTM Shah Alam', lat: 3.0688, lng: 101.5036 },
        um: { name: 'Universiti Malaya', lat: 3.1209, lng: 101.6538 },
        upm: { name: 'UPM Serdang', lat: 2.9927, lng: 101.7090 },
        ukm: { name: 'UKM Bangi', lat: 2.9279, lng: 101.7776 },
        unmc: { name: 'University of Nottingham Malaysia', lat: 2.9453, lng: 101.8748 },
        taylors: { name: "Taylor's University", lat: 3.0622, lng: 101.6172 },
        sunway: { name: 'Sunway University / Monash', lat: 3.0674, lng: 101.6033 },
        utm_kl: { name: 'UTM Kuala Lumpur', lat: 3.1706, lng: 101.7276 },
    };

    const store = {
        get(k) { try { return localStorage.getItem(k); } catch (e) { return null; } },
        set(k, v) { try { localStorage.setItem(k, v); } catch (e) {} },
    };

    const saved = store.get('mysiswa_campus');
    const state = {
        house: null,
        extras: null,
        campus: saved && CAMPUSES[saved] ? saved : '',
        me: null,
        compare: new Map(),
        ratings: {},
        similar: [],
        myRating: 0,
    };

    const $ = (s, r = document) => r.querySelector(s);
    const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const rm = (n) => 'RM' + new Intl.NumberFormat('en-MY', { maximumFractionDigits: 0 }).format(Number(n) || 0);

    let toastTimer;
    function toast(msg) {
        const el = $('#sxToast');
        el.textContent = msg;
        el.classList.remove('hidden');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => el.classList.add('hidden'), 3200);
    }

    async function api(method, url, body) {
        const res = await fetch(url, {
            method,
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: body ? JSON.stringify(body) : undefined,
        });
        let data = {};
        try { data = await res.json(); } catch (e) {}
        if (!res.ok) {
            const first = data.errors ? Object.values(data.errors)[0][0] : null;
            throw new Error(first || data.message || 'Sesuatu tidak kena. Cuba lagi.');
        }
        return data;
    }

    function getHouse(id) {
        id = Number(id);
        return houses.find((h) => Number(h.id) === id)
            || (typeof selectedHouse !== 'undefined' && selectedHouse && Number(selectedHouse.id) === id ? selectedHouse : null)
            || state.compare.get(id)
            || state.similar.find((h) => Number(h.id) === id)
            || null;
    }

    function stars(avg, size) {
        const full = Math.round(avg);
        let out = '';
        for (let i = 1; i <= 5; i++) out += `<span class="${i <= full ? 'text-amber-400' : 'text-gray-300'}">★</span>`;
        return `<span class="${size} leading-none">${out}</span>`;
    }

    // ---------------------------------------------------------------
    // JARAK KE KAMPUS
    // ---------------------------------------------------------------
    function haversine(lat1, lon1, lat2, lon2) {
        const R = 6371, t = (x) => (x * Math.PI) / 180;
        const dLat = t(lat2 - lat1), dLon = t(lon2 - lon1);
        const a = Math.sin(dLat / 2) ** 2 + Math.cos(t(lat1)) * Math.cos(t(lat2)) * Math.sin(dLon / 2) ** 2;
        return 2 * R * Math.asin(Math.sqrt(a));
    }

    function campusPoint() {
        if (state.campus === 'me') return state.me;
        const c = CAMPUSES[state.campus];
        return c || null;
    }

    function distanceInfo(h) {
        const p = campusPoint();
        if (!p || !h || h.latitude == null || h.longitude == null || h.latitude === '' || h.longitude === '') return null;
        const road = haversine(Number(h.latitude), Number(h.longitude), p.lat, p.lng) * 1.3; // anggaran jalan raya
        return { km: road, drive: Math.max(1, Math.round((road / 30) * 60)), walk: Math.round((road / 4.8) * 60), name: p.name };
    }

    function injectCampusBar() {
        const listing = $('#houseListing');
        if (!listing || $('#sxCampus')) return;
        const opts = Object.entries(CAMPUSES).map(([k, c]) => `<option value="${k}" ${state.campus === k ? 'selected' : ''}>${esc(c.name)}</option>`).join('');
        listing.insertAdjacentHTML('beforebegin', `
            <div class="mb-5 flex flex-col gap-3 rounded-2xl border border-indigo-100 bg-white p-4 shadow-sm sm:flex-row sm:items-center">
                <div class="flex-1">
                    <p class="text-sm font-bold text-gray-800">🎓 Jarak ke kampus</p>
                    <p class="text-xs text-gray-500">Pilih kampus untuk lihat anggaran jarak dan masa perjalanan pada setiap rumah.</p>
                </div>
                <select id="sxCampus" class="rounded-xl border-gray-200 text-sm text-gray-800 focus:border-indigo-500 focus:ring-indigo-500" aria-label="Pilih kampus">
                    <option value="">Tiada</option>${opts}
                    <option value="me">📍 Lokasi saya (GPS)</option>
                </select>
            </div>`);
    }

    function setCampus(value) {
        if (value === 'me') {
            if (!navigator.geolocation) { toast('Pelayar anda tidak menyokong lokasi.'); $('#sxCampus').value = ''; return; }
            navigator.geolocation.getCurrentPosition(
                (pos) => { state.me = { lat: pos.coords.latitude, lng: pos.coords.longitude, name: 'lokasi anda' }; state.campus = 'me'; afterCampusChange(); },
                () => { toast('Tidak dapat akses lokasi. Pilih kampus daripada senarai.'); state.campus = ''; $('#sxCampus').value = ''; afterCampusChange(); },
                { timeout: 8000 }
            );
            return;
        }
        state.campus = value;
        store.set('mysiswa_campus', value);
        afterCampusChange();
    }

    function afterCampusChange() {
        paintCards();
        if (state.house) renderCost(state.house);
    }

    // ---------------------------------------------------------------
    // KAD SENARAI: lencana penilaian, jarak, kotak banding
    // ---------------------------------------------------------------
    function enhanceCards() {
        document.querySelectorAll('#houseListing > div').forEach((card) => {
            if (card.dataset.sxId) return;
            const btn = card.querySelector('button[onclick^="showHouseDetail"]');
            if (!btn) return;
            const id = (btn.getAttribute('onclick').match(/\d+/) || [])[0];
            if (!id) return;
            card.dataset.sxId = id;

            const imgBox = card.querySelector('.relative');
            if (imgBox) imgBox.insertAdjacentHTML('beforeend', `<div data-sx-rating="${id}" class="absolute bottom-3 left-3 hidden rounded-full bg-white/95 px-2.5 py-1 text-xs font-bold text-gray-800 shadow"></div>`);

            const area = card.querySelector('p.text-gray-500');
            if (area) area.insertAdjacentHTML('afterend', `<p data-sx-distance="${id}" class="mt-1 text-xs font-semibold text-indigo-600"></p>`);

            btn.insertAdjacentHTML('beforebegin', `
                <label class="mt-4 flex cursor-pointer items-center gap-2 text-sm font-medium text-gray-600">
                    <input type="checkbox" data-sx-compare="${id}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    Tambah untuk banding
                </label>`);
        });
    }

    function paintCards() {
        document.querySelectorAll('[data-sx-distance]').forEach((el) => {
            const d = distanceInfo(getHouse(el.dataset.sxDistance));
            el.textContent = d ? `🎓 ±${d.km.toFixed(1)} km ke ${d.name}` : '';
        });
        document.querySelectorAll('[data-sx-rating]').forEach((el) => {
            const r = state.ratings[el.dataset.sxRating];
            if (r && r.count > 0) {
                el.textContent = `★ ${Number(r.avg).toFixed(1)} (${r.count})`;
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        });
    }

    async function loadRatings() {
        const ids = houses.map((h) => h.id);
        if (!ids.length) return;
        try {
            const data = await api('GET', `${BASE}/ratings?` + ids.map((i) => 'ids[]=' + encodeURIComponent(i)).join('&'));
            Object.assign(state.ratings, data);
            paintCards();
        } catch (e) { /* senyap: lencana adalah tambahan sahaja */ }
    }

    // ---------------------------------------------------------------
    // PAUTAN "PERMINTAAN SAYA"
    // ---------------------------------------------------------------
    function injectInquiryLink() {
        const welcome = $('#listingSection > div.mb-8');
        if (!welcome || $('#sxInquiryLink')) return;
        welcome.insertAdjacentHTML('beforeend', `
            <a id="sxInquiryLink" href="${BASE}/inquiries" class="mt-4 inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-white px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-50">
                📅 Permintaan lawatan saya
            </a>`);
    }

    // ---------------------------------------------------------------
    // PANEL BUTIRAN: slot tambahan
    // ---------------------------------------------------------------
    function ensureDetailSlots() {
        if ($('#sxLeft')) return;
        const left = $('#houseDetail div[class~="lg:col-span-7"]');
        const right = $('#houseDetail div[class~="lg:col-span-5"]');
        if (left) left.insertAdjacentHTML('beforeend', `
            <div id="sxLeft" class="mt-6 space-y-6">
                <section id="sxReviews" class="rounded-2xl border border-slate-200 p-5 sm:p-6"></section>
                <section id="sxSimilar" class="rounded-2xl border border-slate-200 p-5 sm:p-6"></section>
            </div>`);
        if (right) right.insertAdjacentHTML('beforeend', `
            <div id="sxRight" class="mt-6 space-y-6">
                <section id="sxCost" class="rounded-2xl border border-slate-200 p-5 sm:p-6"></section>
                <section id="sxInquiry" class="rounded-2xl border border-slate-200 p-5 sm:p-6"></section>
                <section id="sxShare"></section>
            </div>`);
    }

    function sectionHead(emoji, tint, title, sub) {
        return `<div class="mb-4 flex items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl ${tint} text-lg">${emoji}</div>
            <div><h3 class="font-bold text-slate-900">${title}</h3>${sub ? `<p class="text-xs text-slate-500">${sub}</p>` : ''}</div>
        </div>`;
    }

    // --- Jarak & kalkulator bajet ---------------------------------
    function renderCost(h) {
        const el = $('#sxCost');
        if (!el) return;
        const d = distanceInfo(h);
        let dist;
        if (d) {
            dist = `<div class="rounded-xl bg-indigo-50 p-4">
                <p class="text-sm font-bold text-indigo-800">🎓 ±${d.km.toFixed(1)} km ke ${esc(d.name)}</p>
                <p class="mt-1 text-xs text-indigo-700">Memandu ±${d.drive} minit${d.km <= 2.5 ? `, berjalan kaki ±${d.walk} minit` : ''}. Anggaran sahaja.</p></div>`;
        } else if (h.latitude == null || h.latitude === '') {
            dist = `<p class="rounded-xl bg-gray-50 p-4 text-sm text-gray-500">Pemilik belum menetapkan lokasi peta, jadi jarak tidak dapat dikira.</p>`;
        } else {
            dist = `<p class="rounded-xl bg-gray-50 p-4 text-sm text-gray-500">Pilih kampus di bahagian "Jarak ke kampus" pada senarai rumah untuk lihat jarak.</p>`;
        }

        el.innerHTML = sectionHead('🧮', 'bg-amber-50', 'Jarak & kos bulanan', 'Anggaran untuk bantu anda merancang bajet') + dist + `
            <div class="mt-4 grid grid-cols-2 gap-3">
                <div><label for="sxRoommates" class="mb-1 block text-xs font-semibold text-gray-500">Berkongsi dengan</label>
                    <select id="sxRoommates" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        ${[1, 2, 3, 4, 5, 6].map((n) => `<option value="${n}">${n} orang</option>`).join('')}
                    </select></div>
                <div><label for="sxUtil" class="mb-1 block text-xs font-semibold text-gray-500">Utiliti sebulan (RM)</label>
                    <input id="sxUtil" type="number" min="0" step="10" value="100" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
            </div>
            <div id="sxBudgetOut" class="mt-4 space-y-2 text-sm"></div>
            <p class="mt-3 text-xs text-gray-400">Bayaran awal biasanya 2 bulan deposit + 1 bulan sewa pendahuluan. Sahkan dengan tuan rumah.</p>`;
        calcBudget();
    }

    function calcBudget() {
        const h = state.house, out = $('#sxBudgetOut');
        if (!h || !out) return;
        const n = Number($('#sxRoommates').value) || 1;
        const util = Math.max(0, Number($('#sxUtil').value) || 0);
        const rent = Number(h.monthly_rent) || 0;
        const row = (label, val, strong) => `<div class="flex items-center justify-between ${strong ? 'rounded-xl bg-indigo-600 px-4 py-3 text-white' : 'px-1'}">
            <span class="${strong ? 'font-semibold' : 'text-gray-500'}">${label}</span><span class="${strong ? 'text-lg font-extrabold' : 'font-semibold text-gray-800'}">${val}</span></div>`;
        out.innerHTML =
            row('Sewa seorang', rm(rent / n)) +
            row('Utiliti seorang', rm(util / n)) +
            row('Anggaran sebulan seorang', rm((rent + util) / n), true) +
            row('Bayaran awal seorang (3 bulan sewa)', rm((rent * 3) / n));
    }

    // --- Kongsi ----------------------------------------------------
    function renderShare(h) {
        const el = $('#sxShare');
        if (!el) return;
        const text = `Jom tengok rumah sewa ni: ${h.title} di ${h.area}, ${rm(h.monthly_rent)} sebulan. ${location.origin}${@json(route('student.dashboard', [], false))}`;
        el.innerHTML = `<a href="https://wa.me/?text=${encodeURIComponent(text)}" target="_blank" rel="noopener noreferrer"
            class="flex w-full items-center justify-center gap-2 rounded-xl border border-green-200 bg-white px-5 py-3 text-sm font-semibold text-green-700 transition hover:bg-green-50">
            Kongsi dengan rakan melalui WhatsApp</a>`;
    }

    // --- Ulasan ---------------------------------------------------
    function renderReviews(d) {
        const el = $('#sxReviews');
        if (!el) return;
        const summary = d.count
            ? `<div class="mb-4 flex items-center gap-3 rounded-xl bg-amber-50 p-4">
                <span class="text-3xl font-extrabold text-gray-800">${Number(d.avg).toFixed(1)}</span>
                <div>${stars(d.avg, 'text-xl')}<p class="mt-1 text-xs text-gray-500">${d.count} ulasan</p></div></div>`
            : '';

        const list = d.reviews.length
            ? d.reviews.map((r) => `
                <div class="border-t border-gray-100 py-4 first:border-0">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">${esc(r.name.charAt(0).toUpperCase())}</span>
                            <div><p class="text-sm font-semibold text-gray-800">${esc(r.name)}${r.mine ? ' <span class="text-xs font-semibold text-indigo-600">(anda)</span>' : ''}</p>
                            <p class="text-xs text-gray-400">${esc(r.date)}</p></div>
                        </div>
                        ${stars(r.rating, 'text-sm')}
                    </div>
                    ${r.comment ? `<p class="mt-2 whitespace-pre-line break-words text-sm leading-6 text-gray-600">${esc(r.comment)}</p>` : ''}
                    ${r.reply ? `<div class="mt-3 rounded-xl bg-indigo-50 p-3"><p class="text-xs font-bold text-indigo-700">Balasan tuan rumah</p><p class="mt-1 whitespace-pre-line break-words text-sm leading-6 text-gray-600">${esc(r.reply)}</p></div>` : ''}
                </div>`).join('')
            : `<p class="py-6 text-center text-sm text-gray-500">Belum ada ulasan. Jadi yang pertama berkongsi pengalaman anda.</p>`;

        state.myRating = d.mine ? d.mine.rating : 0;

        const form = d.is_owner
            ? `<p class="mt-4 text-xs text-gray-400">Anda tidak boleh menilai rumah sendiri.</p>`
            : `<div class="mt-5 rounded-2xl bg-indigo-50/60 p-4">
                <p class="text-sm font-bold text-gray-800">${d.mine ? 'Kemas kini ulasan anda' : 'Tulis ulasan'}</p>
                <div id="sxStarPicker" class="mt-2 flex gap-1" role="group" aria-label="Penilaian bintang">
                    ${[1, 2, 3, 4, 5].map((n) => `<button type="button" data-sx-star="${n}" class="text-3xl leading-none text-gray-300 transition hover:scale-110" aria-label="${n} bintang">★</button>`).join('')}
                </div>
                <textarea id="sxComment" rows="3" maxlength="1000" placeholder="Ceritakan pengalaman anda: kemudahan, jiran, jarak ke kampus..."
                    class="mt-3 w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">${esc(d.mine ? d.mine.comment : '')}</textarea>
                <div class="mt-3 flex gap-2">
                    <button type="button" id="sxReviewSave" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Hantar ulasan</button>
                    ${d.mine ? '<button type="button" id="sxReviewDelete" class="rounded-xl border border-rose-200 bg-white px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-50">Padam</button>' : ''}
                </div></div>`;

        el.innerHTML = sectionHead('⭐', 'bg-amber-50', 'Ulasan penyewa', 'Pengalaman pelajar lain di rumah ini') + summary + list + form;
        paintPicker();
    }

    function paintPicker() {
        document.querySelectorAll('[data-sx-star]').forEach((b) => {
            const on = Number(b.dataset.sxStar) <= state.myRating;
            b.classList.toggle('text-amber-400', on);
            b.classList.toggle('text-gray-300', !on);
        });
    }

    // --- Mohon lawatan --------------------------------------------
    const STATUS = { pending: 'Menunggu', contacted: 'Telah dihubungi', closed: 'Ditutup' };

    function renderInquiry(d) {
        const el = $('#sxInquiry'), h = state.house;
        if (!el || !h) return;
        const head = sectionHead('📅', 'bg-indigo-50', 'Mohon lawatan', 'Tuan rumah akan menghubungi anda');
        const today = new Date().toISOString().slice(0, 10);
        let body;

        if (d.is_owner) {
            body = `<p class="text-sm text-gray-500">Ini rumah anda sendiri.</p>`;
        } else if (String(h.status).toLowerCase() !== 'available') {
            body = `<p class="rounded-xl bg-rose-50 p-4 text-sm text-rose-700">Rumah ini sudah disewa. Cuba lihat rumah serupa di atas.</p>`;
        } else if (d.inquiry && d.inquiry.status === 'pending') {
            body = `<div class="rounded-xl bg-amber-50 p-4 text-sm text-amber-800">
                <p class="font-bold">Permintaan anda sedang menunggu</p>
                <p class="mt-1 text-xs">Tuan rumah akan menghubungi anda. Semak status di <a class="font-semibold underline" href="${BASE}/inquiries">Permintaan Saya</a>.</p></div>`;
        } else {
            const prev = d.inquiry ? `<p class="mb-3 rounded-xl bg-gray-50 p-3 text-xs text-gray-500">Permintaan terakhir anda: ${esc(STATUS[d.inquiry.status] || d.inquiry.status)}.</p>` : '';
            body = prev + `
                <div class="space-y-3">
                    <div><label for="sxInqDate" class="mb-1 block text-xs font-semibold text-gray-500">Tarikh lawatan dicadangkan (pilihan)</label>
                        <input id="sxInqDate" type="date" min="${today}" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                    <div><label for="sxInqPhone" class="mb-1 block text-xs font-semibold text-gray-500">Nombor telefon anda</label>
                        <input id="sxInqPhone" type="tel" value="${esc(d.me_phone || '')}" placeholder="01x-xxxxxxx" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"></div>
                    <div><label for="sxInqMsg" class="mb-1 block text-xs font-semibold text-gray-500">Mesej (pilihan)</label>
                        <textarea id="sxInqMsg" rows="3" maxlength="1000" placeholder="Contoh: Saya pelajar tahun 2, nak lawat hujung minggu ni." class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea></div>
                    <button type="button" id="sxInquirySend" class="w-full rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Hantar permintaan</button>
                </div>`;
        }
        el.innerHTML = head + body;
    }

    // --- Rumah serupa ---------------------------------------------
    function renderSimilar(d) {
        const el = $('#sxSimilar');
        if (!el) return;
        if (!d.similar.length) { el.classList.add('hidden'); return; }
        el.classList.remove('hidden');
        el.innerHTML = sectionHead('🏘️', 'bg-violet-50', 'Rumah serupa', 'Kawasan atau harga yang hampir sama') +
            `<div class="grid gap-3 sm:grid-cols-3">${d.similar.map((s) => `
                <button type="button" data-sx-open="${s.id}" class="overflow-hidden rounded-2xl border border-gray-100 bg-white text-left shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="h-24 bg-gray-100">${s.image_urls && s.image_urls[0] ? `<img src="${esc(s.image_urls[0])}" alt="" class="h-full w-full object-cover" loading="lazy">` : '<div class="flex h-full items-center justify-center text-3xl">🏠</div>'}</div>
                    <div class="p-3"><p class="truncate text-sm font-bold text-gray-800">${esc(s.title)}</p>
                    <p class="truncate text-xs text-gray-500">${esc(s.area)}</p>
                    <p class="mt-1 text-sm font-bold text-indigo-600">${rm(s.monthly_rent)}</p></div>
                </button>`).join('')}</div>`;
    }

    function setLoading() {
        const msg = '<p class="py-4 text-sm text-gray-400">Memuatkan...</p>';
        ['#sxReviews', '#sxInquiry', '#sxSimilar'].forEach((s) => { const e = $(s); if (e) { e.classList.remove('hidden'); e.innerHTML = msg; } });
    }

    async function loadExtras(id) {
        setLoading();
        try {
            const d = await api('GET', `${BASE}/houses/${id}/extras`);
            if (!state.house || Number(state.house.id) !== Number(id)) return;
            state.extras = d;
            state.similar = d.similar || [];
            state.ratings[id] = { avg: d.avg, count: d.count };
            paintCards();
            renderReviews(d);
            renderInquiry(d);
            renderSimilar(d);
        } catch (e) {
            ['#sxReviews', '#sxInquiry'].forEach((s) => { const el = $(s); if (el) el.innerHTML = `<p class="text-sm text-rose-600">${esc(e.message)}</p>`; });
        }
    }

    // ---------------------------------------------------------------
    // BANDING
    // ---------------------------------------------------------------
    function updateCompareBar() {
        const n = state.compare.size;
        $('#sxCompareCount').textContent = n;
        $('#sxCompareBar').classList.toggle('hidden', n === 0);
        $('#sxCompareOpen').disabled = n < 2;
    }

    function openCompare() {
        const list = [...state.compare.values()];
        if (list.length < 2) { toast('Pilih sekurang-kurangnya 2 rumah.'); return; }
        const min = Math.min(...list.map((h) => Number(h.monthly_rent)));
        const row = (label, fn) => `<tr class="border-t border-gray-100"><th class="w-32 bg-gray-50 px-3 py-3 text-left text-xs font-semibold text-gray-500">${label}</th>${list.map((h) => `<td class="px-3 py-3 text-sm text-gray-800">${fn(h)}</td>`).join('')}</tr>`;
        const img = (h) => { const u = (h.image_urls && h.image_urls[0]) || null; return u ? `<img src="${esc(u)}" alt="" class="h-28 w-full rounded-xl object-cover">` : '<div class="flex h-28 items-center justify-center rounded-xl bg-gray-100 text-3xl">🏠</div>'; };

        $('#sxCompareBody').innerHTML = `<table class="w-full min-w-[34rem] table-fixed border-collapse">
            <thead><tr><th class="w-32"></th>${list.map((h) => `<th class="px-3 pb-3 text-left align-top">${img(h)}<p class="mt-2 text-sm font-bold text-gray-800">${esc(h.title)}</p></th>`).join('')}</tr></thead>
            <tbody>
                ${row('Sewa bulanan', (h) => { const best = Number(h.monthly_rent) === min; return `<span class="${best ? 'rounded-full bg-green-100 px-2 py-1 font-bold text-green-700' : 'font-semibold'}">${rm(h.monthly_rent)}</span>${best ? '<span class="ml-1 text-xs text-green-700">termurah</span>' : ''}`; })}
                ${row('Kawasan', (h) => esc(h.area))}
                ${row('Bilik tidur', (h) => esc(h.bedrooms))}
                ${row('Bilik air', (h) => esc(h.bathrooms))}
                ${row('Jenis', (h) => esc(h.property_type || '-'))}
                ${row('Perabot', (h) => esc(h.furnished || '-'))}
                ${row('Status', (h) => String(h.status).toLowerCase() === 'available' ? '<span class="font-semibold text-green-700">Tersedia</span>' : '<span class="font-semibold text-rose-700">Disewa</span>')}
                ${row('Penilaian', (h) => { const r = state.ratings[h.id]; return r && r.count ? `${stars(r.avg, 'text-sm')} <span class="text-xs text-gray-500">${Number(r.avg).toFixed(1)} (${r.count})</span>` : '<span class="text-xs text-gray-400">Belum ada</span>'; })}
                ${row('Jarak kampus', (h) => { const d = distanceInfo(h); return d ? `±${d.km.toFixed(1)} km` : '<span class="text-xs text-gray-400">Pilih kampus</span>'; })}
                ${row('', (h) => `<button type="button" data-sx-open="${h.id}" class="w-full rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700">Lihat butiran</button>`)}
            </tbody></table>`;
        const m = $('#sxCompareModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }

    function closeCompare() {
        const m = $('#sxCompareModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    function clearCompare() {
        state.compare.clear();
        document.querySelectorAll('[data-sx-compare]').forEach((c) => { c.checked = false; });
        updateCompareBar();
    }

    // ---------------------------------------------------------------
    // PAUT KE FUNGSI SEDIA ADA (tanpa ubah kod asal)
    // ---------------------------------------------------------------
    const origShow = window.showHouseDetail;
    window.showHouseDetail = function (id) {
        origShow(id);
        const h = getHouse(id);
        if (!h) return;
        state.house = h;
        ensureDetailSlots();
        renderCost(h);
        renderShare(h);
        loadExtras(h.id);
    };

    const origBack = window.backToHouseList;
    window.backToHouseList = function () {
        state.house = null;
        origBack();
    };

    // Elak anak panah / Esc dalam kotak taip mengganggu galeri
    document.addEventListener('keydown', (e) => {
        if (['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) e.stopImmediatePropagation();
    }, true);

    // ---------------------------------------------------------------
    // EVENT
    // ---------------------------------------------------------------
    document.addEventListener('click', async (e) => {
        const t = e.target.closest('button, a');
        if (!t) return;

        if (t.dataset.sxStar) { state.myRating = Number(t.dataset.sxStar); paintPicker(); return; }

        if (t.dataset.sxOpen) {
            const id = Number(t.dataset.sxOpen);
            const h = getHouse(id);
            if (h && !houses.find((x) => Number(x.id) === id)) houses.push(h);
            closeCompare();
            window.showHouseDetail(id);
            return;
        }

        if (t.id === 'sxReviewSave') {
            if (!state.myRating) { toast('Pilih bilangan bintang dahulu.'); return; }
            t.disabled = true;
            try {
                await api('POST', `${BASE}/houses/${state.house.id}/review`, { rating: state.myRating, comment: $('#sxComment').value.trim() || null });
                toast('Ulasan disimpan. Terima kasih!');
                await loadExtras(state.house.id);
            } catch (err) { toast(err.message); t.disabled = false; }
            return;
        }

        if (t.id === 'sxReviewDelete') {
            if (!confirm('Padam ulasan anda?')) return;
            try {
                await api('DELETE', `${BASE}/houses/${state.house.id}/review`);
                toast('Ulasan dipadam.');
                await loadExtras(state.house.id);
            } catch (err) { toast(err.message); }
            return;
        }

        if (t.id === 'sxInquirySend') {
            t.disabled = true;
            try {
                await api('POST', `${BASE}/houses/${state.house.id}/inquiry`, {
                    preferred_date: $('#sxInqDate').value || null,
                    phone: $('#sxInqPhone').value.trim() || null,
                    message: $('#sxInqMsg').value.trim() || null,
                });
                toast('Permintaan dihantar. Tuan rumah akan menghubungi anda.');
                await loadExtras(state.house.id);
            } catch (err) { toast(err.message); t.disabled = false; }
            return;
        }

        if (t.id === 'sxCompareOpen') return openCompare();
        if (t.id === 'sxCompareClear') return clearCompare();
        if (t.id === 'sxCompareClose') return closeCompare();
    });

    $('#sxCompareModal').addEventListener('click', (e) => { if (e.target.id === 'sxCompareModal') closeCompare(); });

    document.addEventListener('change', (e) => {
        const t = e.target;
        if (t.matches('[data-sx-compare]')) {
            const id = Number(t.dataset.sxCompare);
            if (t.checked) {
                if (state.compare.size >= 3) { t.checked = false; toast('Maksimum 3 rumah untuk dibanding.'); return; }
                const h = getHouse(id);
                if (h) state.compare.set(id, h);
            } else {
                state.compare.delete(id);
            }
            updateCompareBar();
        }
        if (t.id === 'sxCampus') setCampus(t.value);
        if (t.id === 'sxRoommates') calcBudget();
    });

    document.addEventListener('input', (e) => { if (e.target.id === 'sxUtil') calcBudget(); });

    // ---------------------------------------------------------------
    // MULA
    // ---------------------------------------------------------------
    injectCampusBar();
    injectInquiryLink();
    enhanceCards();
    paintCards();
    loadRatings();
})();
</script>
