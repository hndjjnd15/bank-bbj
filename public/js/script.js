/* ===== Navbar ===== */
(function () {
  const nav = document.getElementById("nav");
  const menu = document.getElementById("menu");
  const hamburger = document.getElementById("hamburger");
  const tirai = document.getElementById("tirai");
  const pemicu = menu.querySelectorAll("button.menu__tautan");
  const mobile = window.matchMedia("(max-width: 1024px)");

  function cekGulir() { nav.classList.toggle("nav--bayang", window.scrollY > 8); }
  window.addEventListener("scroll", cekGulir, { passive: true });
  cekGulir();

  function setDropdown(tombol, buka) {
    tombol.setAttribute("aria-expanded", String(buka));
    tombol.parentElement.classList.toggle("terbuka", buka);
  }
  function tutupSemuaDropdown(kecuali) {
    pemicu.forEach(function (t) { if (t !== kecuali) setDropdown(t, false); });
  }

  pemicu.forEach(function (tombol) {
    const item = tombol.parentElement;

    tombol.addEventListener("click", function (e) {
      e.stopPropagation();
      // Di desktop dropdown sudah terbuka karena hover; klik pada tombolnya
      // jangan langsung menutup (dulu terasa seperti menu "menghilang").
      if (!mobile.matches && item.matches(":hover") && tombol.getAttribute("aria-expanded") === "true") return;

      const buka = tombol.getAttribute("aria-expanded") !== "true";
      tutupSemuaDropdown(tombol);
      setDropdown(tombol, buka);
    });

    item.addEventListener("mouseenter", function () {
      if (!mobile.matches) { tutupSemuaDropdown(tombol); setDropdown(tombol, true); }
    });
    item.addEventListener("mouseleave", function () {
      if (!mobile.matches) setDropdown(tombol, false);
    });
  });

  // Sidebar dan overlay dimulai tepat di bawah header, jadi logo dan hamburger tetap terlihat
  function aturBawahNav() {
    document.documentElement.style.setProperty("--nav-bawah", Math.round(nav.getBoundingClientRect().bottom) + "px");
  }
  window.addEventListener("resize", function () {
    if (menu.classList.contains("menu--buka")) aturBawahNav();
  });

  function setMenuMobile(buka) {
    menu.classList.toggle("menu--buka", buka);
    tirai.classList.toggle("tirai--buka", buka);
    hamburger.setAttribute("aria-expanded", String(buka));
    hamburger.setAttribute("aria-label", buka ? "Tutup menu" : "Buka menu");
    if (buka) aturBawahNav();
    // Kunci gulir lewat <html> (lihat html.menu-buka di style.css), bukan <body>:
    // body yang di-overflow:hidden mematikan sticky navbar sehingga header hilang.
    document.documentElement.classList.toggle("menu-buka", buka);
    if (!buka) tutupSemuaDropdown();
  }
  hamburger.addEventListener("click", function () {
    setMenuMobile(!menu.classList.contains("menu--buka"));
  });
  tirai.addEventListener("click", function () { setMenuMobile(false); });

  document.addEventListener("click", function (e) {
    if (!menu.contains(e.target)) tutupSemuaDropdown();
  });

  document.addEventListener("keydown", function (e) {
    if (e.key !== "Escape") return;
    const terbuka = menu.querySelector(".terbuka > button");
    tutupSemuaDropdown();
    if (menu.classList.contains("menu--buka")) { setMenuMobile(false); hamburger.focus(); }
    else if (terbuka) terbuka.focus();
  });

  menu.querySelectorAll("a").forEach(function (a) {
    a.addEventListener("click", function () { if (mobile.matches) setMenuMobile(false); });
  });

  mobile.addEventListener("change", function () { setMenuMobile(false); tutupSemuaDropdown(); });
})();

/* ===== Strip informasi: teks bergerak tanpa putus ===== */
(function () {
  const strip = document.getElementById("strip");
  const lintasan = document.getElementById("stripLintasan");
  if (!strip || !lintasan) return;
  const daftar = lintasan.querySelector(".strip__daftar");
  const asli = Array.from(daftar.children).map(function (li) { return li.cloneNode(true); });
  const KECEPATAN = 50; // piksel per detik

  function sembunyikan(el) {
    el.setAttribute("aria-hidden", "true");
    el.querySelectorAll("a").forEach(function (a) { a.tabIndex = -1; });
  }

  function bangun() {
    lintasan.querySelectorAll(".strip__daftar:not(:first-child)").forEach(function (d) { d.remove(); });
    daftar.innerHTML = "";
    asli.forEach(function (li) { daftar.appendChild(li.cloneNode(true)); });

    // Ulangi isi sampai satu daftar lebih lebar dari layar, supaya tidak ada celah kosong
    let ulang = 0;
    while (daftar.offsetWidth < window.innerWidth && ulang < 12) {
      asli.forEach(function (li) {
        const salinan = li.cloneNode(true);
        sembunyikan(salinan);
        daftar.appendChild(salinan);
      });
      ulang++;
    }

    // Daftar kedua identik, supaya putaran terlihat menyambung
    const kedua = daftar.cloneNode(true);
    sembunyikan(kedua);
    lintasan.appendChild(kedua);

    lintasan.style.setProperty("--durasi", Math.max(10, Math.round(daftar.offsetWidth / KECEPATAN)) + "s");
    strip.classList.add("strip--aktif");
  }

  let tunda;
  window.addEventListener("resize", function () { clearTimeout(tunda); tunda = setTimeout(bangun, 200); });
  bangun();
})();

/* ===== Tombol bantuan melayang (bisa digeser, menempel ke tepi kiri/kanan) ===== */
(function () {
  const sumber = document.querySelectorAll(".samping li");
  if (!sumber.length) return;

  const UKURAN = 56, TEPI = 10, JARAK = 12, KUNCI = "bantuan-posisi";

  // Bangun tombol dan panel dari daftar .samping (satu sumber data)
  const wrap = document.createElement("div");
  wrap.className = "bantuan";
  wrap.innerHTML =
    '<button type="button" class="bantuan__tombol" aria-label="Tautan cepat" aria-expanded="false" aria-controls="bantuan-panel">' +
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<circle cx="12" cy="12" r="9"/><path d="M9.6 9.6a2.5 2.5 0 1 1 3.6 2.2c-.8.4-1.2 1-1.2 1.9"/><path d="M12 17h.01"/></svg>' +
    '</button>' +
    '<nav class="bantuan__panel" id="bantuan-panel" aria-label="Tautan cepat"></nav>';
  const tombol = wrap.querySelector(".bantuan__tombol");
  const panel = wrap.querySelector(".bantuan__panel");

  sumber.forEach(function (li) {
    const a = li.querySelector("a");
    const item = document.createElement("a");
    item.className = "bantuan__item " + li.className;
    item.href = a.getAttribute("href");
    const ikon = document.createElement("span");
    ikon.className = "bantuan__ikon";
    ikon.appendChild(a.querySelector("svg").cloneNode(true));
    const teks = document.createElement("span");
    teks.textContent = li.querySelector(".label").textContent;
    item.appendChild(ikon);
    item.appendChild(teks);
    panel.appendChild(item);
  });
  document.body.appendChild(wrap);

  // Posisi tersimpan: sisi (kiri/kanan) + tinggi relatif (0 sampai 1)
  let pos = { sisi: "kanan", y: 0.65 };
  try {
    const s = JSON.parse(localStorage.getItem(KUNCI));
    if (s && (s.sisi === "kiri" || s.sisi === "kanan") && typeof s.y === "number") {
      pos = { sisi: s.sisi, y: Math.min(1, Math.max(0, s.y)) };
    }
  } catch (e) {}

  let cx = 0, cy = 0, terbuka = false, tarik = null, terakhirGeser = 0, timer = 0;

  function jepit(n, a, b) { return Math.min(b, Math.max(a, n)); }
  function batas() {
    return {
      minX: TEPI, maxX: Math.max(TEPI, window.innerWidth - UKURAN - TEPI),
      minY: TEPI, maxY: Math.max(TEPI, window.innerHeight - UKURAN - TEPI)
    };
  }
  function tempatkan(x, y, anim) {
    cx = x; cy = y;
    wrap.classList.toggle("bantuan--anim", !!anim);
    wrap.style.transform = "translate3d(" + x + "px," + y + "px,0)";
  }
  function tempatkanTersimpan(anim) {
    const b = batas();
    tempatkan(pos.sisi === "kiri" ? b.minX : b.maxX, b.minY + pos.y * (b.maxY - b.minY), anim);
  }

  // Panel membuka ke arah yang lebih lapang
  function aturPanel() {
    const ruangAtas = cy - TEPI - JARAK;
    const ruangBawah = window.innerHeight - (cy + UKURAN) - TEPI - JARAK;
    const keAtas = ruangAtas >= ruangBawah;
    wrap.classList.toggle("bantuan--atas", keAtas);
    wrap.classList.toggle("bantuan--kanan", cx + UKURAN / 2 > window.innerWidth / 2);
    panel.style.maxHeight = Math.max(160, keAtas ? ruangAtas : ruangBawah) + "px";
  }

  // Tombol memudar saat tidak dipakai
  function bangunkan() {
    wrap.classList.remove("bantuan--diam");
    clearTimeout(timer);
    timer = setTimeout(function () {
      if (!terbuka && !tarik) wrap.classList.add("bantuan--diam");
    }, 3000);
  }

  function setBuka(buka) {
    terbuka = buka;
    if (buka) aturPanel();
    wrap.classList.toggle("bantuan--buka", buka);
    tombol.setAttribute("aria-expanded", String(buka));
    bangunkan();
  }

  // Geser dengan jari atau mouse
  tombol.addEventListener("pointerdown", function (e) {
    if (e.pointerType === "mouse" && e.button !== 0) return;
    tarik = { id: e.pointerId, sx: e.clientX, sy: e.clientY, ox: cx, oy: cy, geser: false };
    try { tombol.setPointerCapture(e.pointerId); } catch (err) {}
    bangunkan();
  });

  tombol.addEventListener("pointermove", function (e) {
    if (!tarik || e.pointerId !== tarik.id) return;
    const dx = e.clientX - tarik.sx, dy = e.clientY - tarik.sy;
    if (!tarik.geser) {
      if (Math.hypot(dx, dy) < 6) return;   // di bawah 6px dianggap ketukan biasa
      tarik.geser = true;
      setBuka(false);
      wrap.classList.add("bantuan--tarik");
    }
    const b = batas();
    tempatkan(jepit(tarik.ox + dx, b.minX, b.maxX), jepit(tarik.oy + dy, b.minY, b.maxY), false);
  });

  function selesai(e) {
    if (!tarik || e.pointerId !== tarik.id) return;
    const geser = tarik.geser;
    tarik = null;
    wrap.classList.remove("bantuan--tarik");
    if (!geser) return;
    terakhirGeser = Date.now();
    const b = batas();
    pos = {
      sisi: cx + UKURAN / 2 < window.innerWidth / 2 ? "kiri" : "kanan",
      y: b.maxY > b.minY ? jepit((cy - b.minY) / (b.maxY - b.minY), 0, 1) : 0.5
    };
    try { localStorage.setItem(KUNCI, JSON.stringify(pos)); } catch (err) {}
    tempatkanTersimpan(true);               // menempel ke tepi terdekat
    bangunkan();
  }
  tombol.addEventListener("pointerup", selesai);
  tombol.addEventListener("pointercancel", selesai);

  // Ketuk = buka/tutup panel (diabaikan tepat setelah menggeser)
  tombol.addEventListener("click", function () {
    if (Date.now() - terakhirGeser < 350) return;
    setBuka(!terbuka);
  });

  // Ketuk di luar menutup panel
  document.addEventListener("pointerdown", function (e) {
    if (terbuka && !wrap.contains(e.target)) setBuka(false);
  });
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && terbuka) { setBuka(false); tombol.focus(); }
  });

  // Sesuaikan saat layar diputar atau ukurannya berubah
  window.addEventListener("resize", function () {
    if (tarik) return;
    tempatkanTersimpan(false);
    if (terbuka) aturPanel();
  });

  tempatkanTersimpan(false);
  bangunkan();
})();

/* ===== Galeri foto + pop-up (lightbox) =====
   - Klik thumbnail: ganti foto utama.
   - Klik foto utama: buka pop-up layar penuh.
   - Di pop-up: klik foto untuk memperbesar ke ukuran asli (geser untuk melihat bagian lain),
     tombol panah / geser jari untuk foto berikutnya, Esc atau klik area gelap untuk menutup. */
(function () {
  const galeri = document.querySelector("[data-galeri]");
  if (!galeri) return;

  const kotakUtama = galeri.querySelector(".galeri__utama");
  const utama = kotakUtama.querySelector("img");
  const tombol = Array.from(galeri.querySelectorAll(".galeri__thumb button"));
  const foto = tombol.length ? tombol.map(function (t) { return t.dataset.foto; }) : [utama.getAttribute("src")];
  let indeks = 0;

  function pilih(i) {
    indeks = i;
    utama.src = foto[i];
    tombol.forEach(function (t, n) { t.setAttribute("aria-current", String(n === i)); });
  }
  tombol.forEach(function (t, i) { t.addEventListener("click", function () { pilih(i); }); });

  // Foto utama bisa diklik dan dioperasikan dengan keyboard
  kotakUtama.setAttribute("role", "button");
  kotakUtama.setAttribute("tabindex", "0");
  kotakUtama.setAttribute("aria-label", "Perbesar foto");
  kotakUtama.addEventListener("click", function () { buka(indeks); });
  kotakUtama.addEventListener("keydown", function (e) {
    if (e.key === "Enter" || e.key === " ") { e.preventDefault(); buka(indeks); }
  });

  /* ----- Pop-up ----- */
  let lb, panggung, gambar, hitung, btnTutup, btnSebelum, btnBerikut;
  let terbuka = false, zoom = false, fokusSebelum = null, tekan = null, geser = false;

  function bangun() {
    lb = document.createElement("div");
    lb.className = "lightbox";
    lb.hidden = true;
    lb.setAttribute("role", "dialog");
    lb.setAttribute("aria-modal", "true");
    lb.setAttribute("aria-label", "Tampilan foto");
    lb.innerHTML =
      '<button type="button" class="lightbox__tombol lightbox__tutup" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>' +
      '<button type="button" class="lightbox__tombol lightbox__nav lightbox__nav--prev" aria-label="Foto sebelumnya"><span aria-hidden="true">&#8249;</span></button>' +
      '<div class="lightbox__panggung"><img alt=""></div>' +
      '<button type="button" class="lightbox__tombol lightbox__nav lightbox__nav--next" aria-label="Foto berikutnya"><span aria-hidden="true">&#8250;</span></button>' +
      '<div class="lightbox__info"><span class="lightbox__hitung"></span> &middot; Klik foto untuk memperbesar atau memperkecil</div>';
    document.body.appendChild(lb);

    panggung = lb.querySelector(".lightbox__panggung");
    gambar = panggung.querySelector("img");
    hitung = lb.querySelector(".lightbox__hitung");
    btnTutup = lb.querySelector(".lightbox__tutup");
    btnSebelum = lb.querySelector(".lightbox__nav--prev");
    btnBerikut = lb.querySelector(".lightbox__nav--next");
    gambar.alt = utama.alt || "";

    btnTutup.addEventListener("click", tutup);
    btnSebelum.addEventListener("click", function () { tampil(indeks - 1); });
    btnBerikut.addEventListener("click", function () { tampil(indeks + 1); });

    // Klik di area gelap menutup pop-up
    lb.addEventListener("click", function (e) { if (e.target === lb || e.target === panggung) tutup(); });

    // Klik foto: perbesar atau perkecil (diabaikan jika baru saja digeser)
    gambar.addEventListener("click", function () { if (!geser) aturZoom(!zoom); });

    // Geser dengan mouse saat diperbesar; geser jari ke samping untuk ganti foto
    panggung.addEventListener("pointerdown", function (e) {
      if (e.pointerType === "mouse" && e.button !== 0) return;
      tekan = { x: e.clientX, y: e.clientY, sl: panggung.scrollLeft, st: panggung.scrollTop };
      geser = false;
    });
    panggung.addEventListener("pointermove", function (e) {
      if (!tekan) return;
      const dx = e.clientX - tekan.x, dy = e.clientY - tekan.y;
      if (Math.hypot(dx, dy) > 6) geser = true;
      if (zoom && geser && e.pointerType === "mouse") {   // jari memakai scroll bawaan browser
        panggung.scrollLeft = tekan.sl - dx;
        panggung.scrollTop = tekan.st - dy;
      }
    });
    panggung.addEventListener("pointerup", function (e) {
      if (!tekan) return;
      const dx = e.clientX - tekan.x, dy = e.clientY - tekan.y;
      if (!zoom && geser && Math.abs(dx) > 50 && Math.abs(dy) < 90) tampil(indeks + (dx < 0 ? 1 : -1));
      tekan = null;
    });
    panggung.addEventListener("pointercancel", function () { tekan = null; });

    document.addEventListener("keydown", function (e) {
      if (!terbuka) return;
      if (e.key === "Escape") { tutup(); }
      else if (e.key === "ArrowLeft") { tampil(indeks - 1); }
      else if (e.key === "ArrowRight") { tampil(indeks + 1); }
      else if (e.key === "Tab") {                     // fokus tetap di dalam pop-up
        const daftar = [btnTutup, btnSebelum, btnBerikut].filter(function (b) { return b.offsetParent !== null; });
        const i = daftar.indexOf(document.activeElement);
        e.preventDefault();
        daftar[(i + (e.shiftKey ? -1 : 1) + daftar.length) % daftar.length].focus();
      }
    });
  }

  function aturZoom(nyala) {
    zoom = nyala;
    lb.classList.toggle("lightbox--zoom", nyala);
    if (nyala) {   // mulai dari bagian tengah foto
      requestAnimationFrame(function () {
        panggung.scrollLeft = (panggung.scrollWidth - panggung.clientWidth) / 2;
        panggung.scrollTop = (panggung.scrollHeight - panggung.clientHeight) / 2;
      });
    }
  }

  function tampil(i) {
    indeks = (i + foto.length) % foto.length;
    aturZoom(false);
    gambar.src = foto[indeks];
    hitung.textContent = (indeks + 1) + " / " + foto.length;
    pilih(indeks);   // foto di halaman ikut berganti
  }

  function buka(i) {
    if (!lb) bangun();
    fokusSebelum = document.activeElement;
    lb.hidden = false;
    terbuka = true;
    document.documentElement.classList.add("lightbox-buka");
    const banyak = foto.length > 1;
    btnSebelum.hidden = !banyak;
    btnBerikut.hidden = !banyak;
    tampil(i);
    btnTutup.focus();
  }

  function tutup() {
    lb.hidden = true;
    terbuka = false;
    zoom = false;
    lb.classList.remove("lightbox--zoom");
    document.documentElement.classList.remove("lightbox-buka");
    if (fokusSebelum && fokusSebelum.focus) fokusSebelum.focus();
  }
})();

/* ===== Tombol "Salin link" ===== */
(function () {
  document.querySelectorAll("[data-salin]").forEach(function (btn) {
    const labelAwal = btn.textContent;

    function berhasil() {
      btn.textContent = "Link disalin \u2713";
      setTimeout(function () { btn.textContent = labelAwal; }, 2000);
    }

    // Cadangan untuk browser lama atau halaman tanpa HTTPS
    function salinManual(teks) {
      const t = document.createElement("textarea");
      t.value = teks;
      t.style.position = "fixed";
      t.style.opacity = "0";
      document.body.appendChild(t);
      t.select();
      try { if (document.execCommand("copy")) berhasil(); } catch (e) {}
      document.body.removeChild(t);
    }

    btn.addEventListener("click", function () {
      const teks = window.location.href.split("#")[0];
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(teks).then(berhasil, function () { salinManual(teks); });
      } else {
        salinManual(teks);
      }
    });
  });
})();

/* ===== Tab laporan keuangan (Triwulan / Tahunan) ===== */
(function () {
  const wadah = document.querySelector("[data-laporan]");
  if (!wadah) return;
  const tombol = Array.from(wadah.querySelectorAll("[data-tab]"));
  const panel = Array.from(wadah.querySelectorAll("[data-panel]"));

  function pilih(nama, ubahUrl) {
    tombol.forEach(function (t) {
      const aktif = t.dataset.tab === nama;
      t.classList.toggle("aktif", aktif);
      t.setAttribute("aria-selected", String(aktif));
      t.tabIndex = aktif ? 0 : -1;
    });
    panel.forEach(function (p) {
      const tampil = p.dataset.panel === nama;
      p.hidden = !tampil;
      if (tampil) {   // ulangi animasi masuk
        p.classList.remove("panel-muncul");
        void p.offsetWidth;
        p.classList.add("panel-muncul");
      }
    });
    if (ubahUrl && window.history && window.history.replaceState) {   // link bisa dibagikan: /laporan?jenis=tahunan
      const url = new URL(window.location.href);
      url.searchParams.set("jenis", nama);
      window.history.replaceState(null, "", url);
    }
  }

  tombol.forEach(function (t) {
    t.addEventListener("click", function () { pilih(t.dataset.tab, true); });
    t.addEventListener("keydown", function (e) {   // panah kiri/kanan berpindah tab
      if (e.key !== "ArrowRight" && e.key !== "ArrowLeft") return;
      const i = tombol.indexOf(t);
      const tujuan = tombol[(i + (e.key === "ArrowRight" ? 1 : -1) + tombol.length) % tombol.length];
      tujuan.focus();
      pilih(tujuan.dataset.tab, true);
    });
  });
})();

/* ===== Animasi muncul perlahan (fade) saat di-scroll, untuk semua halaman =====
   Bagian halaman yang masih di bawah layar disembunyikan lebih dulu, lalu muncul saat terlihat.
   Bagian yang sudah terlihat saat halaman dibuka tidak disembunyikan (tanpa kedipan).
   Tanpa JavaScript atau dengan "kurangi gerakan" aktif: semua tampil biasa.
   Untuk mengecualikan elemen tertentu, tambahkan atribut data-reveal="off". */
(function () {
  if (!("IntersectionObserver" in window)) return;
  if (window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;

  const sasaran = [
    // judul dan teks pembuka halaman
    ".konten > h1", ".konten > p", ".konten > .remah", ".konten > .meta",
    // daftar kartu, pencarian, penyaring, halaman
    ".konten > .kartu-grid > *", ".konten > .cari", ".konten > .hasil", ".konten > .saring", ".konten > .halaman",
    ".konten > .dokumen-daftar > *", ".kartu-grid--lain",
    // halaman detail (berita, lelang, karir)
    ".berita-layout article > *", ".sidebar .harga-kartu", ".sidebar .promo", ".sidebar .bagikan", ".berita-lain",
    // pengaduan
    ".pengaduan > .remah", ".pengaduan__kepala", ".pengaduan__kartu", ".pengaduan__info > *",
    // WBS
    ".wbs__kepala", ".wbs__jaminan", ".wbs__tentang", ".wbs__form",
    // PPID
    ".ppid__kategori > *", ".ppid__bagian",
    // profil perusahaan
    ".profil__kepala", ".profil__bagian",
    // produk (deposito)
    ".produk__bagian", ".produk__bantuan > *",
    // footer
    ".footer__isi > *", ".footer__bawah"
  ].join(",");

  const tinggiLayar = window.innerHeight || document.documentElement.clientHeight;
  const urutan = new Map();   // hitung urutan saudara agar muncul bergantian
  const daftar = [];

  document.querySelectorAll(sasaran).forEach(function (el) {
    if (el.closest("[data-reveal='off']")) return;
    const posisi = el.getBoundingClientRect();
    if (posisi.top < tinggiLayar * 0.92) return;   // sudah terlihat: biarkan

    const i = urutan.get(el.parentElement) || 0;
    urutan.set(el.parentElement, i + 1);
    el.style.setProperty("--tunda", Math.min(i, 6) * 0.07 + "s");
    el.classList.add("reveal");
    daftar.push(el);
  });
  if (!daftar.length) return;

  const pengamat = new IntersectionObserver(function (entri) {
    entri.forEach(function (e) {
      if (!e.isIntersecting) return;
      const el = e.target;
      pengamat.unobserve(el);
      el.classList.add("terlihat");

      // Setelah selesai, lepas kelasnya agar efek hover bawaan elemen berfungsi normal
      const tunda = parseFloat(el.style.getPropertyValue("--tunda")) || 0;
      setTimeout(function () {
        el.classList.remove("reveal", "terlihat");
        el.style.removeProperty("--tunda");
      }, (0.7 + tunda) * 1000 + 150);
    });
  }, { threshold: 0.1, rootMargin: "0px 0px -40px 0px" });

  daftar.forEach(function (el) { pengamat.observe(el); });
})();

/* ===== Kalkulator simulasi deposito =====
   Data suku bunga dibaca dari <script id="data-bunga"> (dikirim controller). Perhitungan di browser, tanpa kirim data ke server.
   Bunga bruto = nominal x bunga p.a. x (jangka waktu / 12). Pajak = PPh final atas bunga jika nominal di atas batas bebas pajak. */
(function () {
  const wadah = document.querySelector("[data-kalkulator]");
  const dataEl = document.getElementById("data-bunga");
  if (!wadah || !dataEl) return;

  let rates;
  try { rates = JSON.parse(dataEl.textContent); } catch (e) { return; }
  if (!Array.isArray(rates) || !rates.length) return;

  const pajakPersen = parseFloat(wadah.getAttribute("data-pajak")) || 0;
  const batasPajak = parseInt(wadah.getAttribute("data-batas"), 10) || 0;
  const q = function (s) { return wadah.querySelector(s); };

  const inputNominal = q("[data-nominal]");
  const pilihTenor = q("[data-tenor]");
  const cekPajak = q("[data-pakai-pajak]");
  const pesan = q("[data-pesan]");
  const hasil = q("[data-hasil]");
  const el = {
    total: q("[data-total]"),
    bunga: q("dd[data-bunga]"),
    bruto: q("dd[data-bruto]"),
    pajak: q("dd[data-pajak]"),
    bersih: q("dd[data-bersih]"),
    perbulan: q("dd[data-perbulan]")
  };

  const titik = function (n) { return String(Math.round(n)).replace(/\B(?=(\d{3})+(?!\d))/g, "."); };
  const rp = function (n) { return "Rp " + titik(n); };
  const persen = function (n) { return n.toFixed(2).replace(".", ",") + "%"; };
  const angka = function (s) { return parseInt(String(s).replace(/\D/g, ""), 10) || 0; };
  const minimal = parseInt(wadah.getAttribute("data-minimal"), 10) || 0;

  function tampilPesan(teks) {
    pesan.textContent = teks;
    pesan.hidden = false;
    hasil.hidden = true;
  }

  function hitung() {
    const nominal = angka(inputNominal.value);
    const tenor = parseInt(pilihTenor.value, 10);

    if (minimal > 0 && nominal < minimal) {
      tampilPesan("Nominal penempatan minimal " + rp(minimal) + ".");
      return;
    }

    const baris = rates.find(function (r) {
      return r.tenor === tenor;
    });
    if (!baris) {
      tampilPesan("Suku bunga untuk jangka waktu ini belum tersedia. Silakan hubungi kantor kami.");
      return;
    }

    const bruto = nominal * (baris.bunga / 100) * (tenor / 12);
    const kenaPajak = cekPajak.checked && pajakPersen > 0 && nominal > batasPajak;
    const pajak = kenaPajak ? bruto * pajakPersen / 100 : 0;
    const bersih = bruto - pajak;

    pesan.hidden = true;
    hasil.hidden = false;
    el.total.textContent = rp(nominal + bersih);
    el.bunga.textContent = persen(baris.bunga) + " p.a.";
    el.bruto.textContent = rp(bruto);
    el.pajak.textContent = kenaPajak ? "- " + rp(pajak) : (cekPajak.checked ? "Rp 0 (tidak dikenai pajak)" : "Tidak dihitung");
    el.bersih.textContent = rp(bersih);
    el.perbulan.textContent = rp(bersih / tenor);
  }

  // Format titik ribuan saat mengetik; posisi kursor dijaga
  inputNominal.addEventListener("input", function () {
    const kursor = inputNominal.selectionStart;
    const digitSebelum = inputNominal.value.slice(0, kursor).replace(/\D/g, "").length;
    const n = angka(inputNominal.value);
    inputNominal.value = n ? titik(n) : "";

    let pos = 0, hitungDigit = 0;
    while (pos < inputNominal.value.length && hitungDigit < digitSebelum) {
      if (/\d/.test(inputNominal.value[pos])) hitungDigit++;
      pos++;
    }
    inputNominal.setSelectionRange(pos, pos);
    hitung();
  });
  pilihTenor.addEventListener("change", hitung);
  cekPajak.addEventListener("change", hitung);

  hitung();
})();

/* ===== Simulasi cicilan kredit (metode anuitas) ===== */
(function () {
  const wadah = document.querySelector("[data-kalkulator-kredit]");
  if (!wadah) return;

  const bungaTahunan = Number(wadah.getAttribute("data-suku-bunga"));
  const biayaAdminPersen = Number(wadah.getAttribute("data-biaya-admin-persen")) || 0;
  const inputNominal = wadah.querySelector("[data-kredit-nominal]");
  const nominalMin = Number(wadah.getAttribute("data-nominal-min"));
  const nominalMaks = Number(wadah.getAttribute("data-nominal-maks"));
  const langkahNominal = Number(wadah.getAttribute("data-nominal-langkah"));
  const tampilanNominal = wadah.querySelector("[data-kredit-nominal-tampil]");
  const tombolKurang = wadah.querySelector("[data-kredit-nominal-kurang]");
  const tombolTambah = wadah.querySelector("[data-kredit-nominal-tambah]");
  const tombolTenor = Array.from(wadah.querySelectorAll("[data-kredit-tenor]"));
  const tampilanTenor = wadah.querySelector("[data-kredit-tenor-terpilih]");
  const pesan = wadah.querySelector("[data-kredit-pesan]");
  const hasil = wadah.querySelector("[data-kredit-hasil]");
  const elCicilan = wadah.querySelector("[data-kredit-cicilan]");
  const elPencairan = wadah.querySelector("[data-kredit-pencairan]");
  const elBunga = wadah.querySelector("[data-kredit-bunga]");
  const elAdmin = wadah.querySelector("[data-kredit-admin]");
  const elTotal = wadah.querySelector("[data-kredit-total]");

  if (!inputNominal || !tampilanNominal || !tombolKurang || !tombolTambah || !tombolTenor.length || !tampilanTenor || !pesan || !hasil || !elCicilan || !elPencairan || !elBunga || !elAdmin || !elTotal || !Number.isFinite(bungaTahunan)) return;

  const rupiah = new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", maximumFractionDigits: 0 });
  let tenor = 12;

  function hitung() {
    const pokok = Number(inputNominal.value);
    const biayaAdmin = pokok * biayaAdminPersen / 100;
    const pencairan = Math.max(0, pokok - biayaAdmin);

    tampilanNominal.textContent = rupiah.format(pokok);
    tampilanTenor.textContent = tenor + " bulan";

    const bungaBulanan = bungaTahunan / 1200;
    const cicilan = bungaBulanan === 0
      ? pencairan / tenor
      : pencairan * bungaBulanan / (1 - Math.pow(1 + bungaBulanan, -tenor));
    const totalPokokBunga = cicilan * tenor;
    const totalBunga = Math.max(0, totalPokokBunga - pencairan);

    pesan.hidden = true;
    hasil.hidden = false;
    elCicilan.textContent = rupiah.format(cicilan);
    elPencairan.textContent = rupiah.format(pencairan);
    elBunga.textContent = rupiah.format(totalBunga);
    elAdmin.textContent = rupiah.format(biayaAdmin);
    elTotal.textContent = rupiah.format(totalPokokBunga + biayaAdmin);
  }

  function ubahNominal(nilai) {
    inputNominal.value = String(Math.min(nominalMaks, Math.max(nominalMin, nilai)));
    hitung();
  }

  function pilihTenor(tombol) {
    tenor = Number(tombol.getAttribute("data-kredit-tenor"));
    tombolTenor.forEach(function (item) {
      item.setAttribute("aria-pressed", String(item === tombol));
    });
    hitung();
  }

  inputNominal.addEventListener("input", hitung);
  tombolKurang.addEventListener("click", function () {
    ubahNominal(Number(inputNominal.value) - langkahNominal);
  });
  tombolTambah.addEventListener("click", function () {
    ubahNominal(Number(inputNominal.value) + langkahNominal);
  });
  tombolTenor.forEach(function (tombol) {
    tombol.addEventListener("click", function () { pilihTenor(tombol); });
  });

  if (nominalMin > 0 && nominalMaks >= nominalMin && langkahNominal > 0) {
    inputNominal.min = String(nominalMin);
    inputNominal.max = String(nominalMaks);
    inputNominal.step = String(langkahNominal);
    inputNominal.value = String(Math.min(nominalMaks, Math.max(nominalMin, Number(inputNominal.value))));
  }
  hitung();
})();

/* ===== Simulasi tabungan berjangka ===== */
(function () {
  document.querySelectorAll("[data-kalkulator-tabungan]").forEach(function (kalkulator) {
    const targetInput = kalkulator.querySelector("[data-tabungan-target]");
    const tenorSelect = kalkulator.querySelector("[data-tabungan-tenor]");
    const pesan = kalkulator.querySelector("[data-tabungan-pesan]");
    if (!targetInput || !tenorSelect) return;

    let jadwal = [
      { tenor: 12, bunga: 3 },
      { tenor: 24, bunga: 3.2 },
      { tenor: 36, bunga: 3.4 },
      { tenor: 48, bunga: 3.6 },
      { tenor: 60, bunga: 3.8 },
      { tenor: 72, bunga: 4 },
      { tenor: 84, bunga: 4.2 }
    ];
    const dataJadwal = document.getElementById("data-bunga-tabungan");
    if (dataJadwal) {
      const jadwalAdmin = JSON.parse(dataJadwal.textContent);
      if (Array.isArray(jadwalAdmin) && jadwalAdmin.length) jadwal = jadwalAdmin;
    }
    const konfigurasiTapen = (kalkulator.getAttribute("data-produk-tabungan") || "").includes("tapen");
    const maksimalTarget = konfigurasiTapen ? 25000000 : 100000000;
    const formatNominal = function (nilai) {
      return new Intl.NumberFormat("id-ID", { maximumFractionDigits: 0 }).format(Math.round(nilai));
    };
    const formatRupiah = function (nilai) {
      return "Rp " + formatNominal(nilai);
    };
    const tampilkan = function (selector, teks) {
      const elemen = kalkulator.querySelector(selector);
      if (elemen) elemen.textContent = teks;
    };
    const formatInputNominal = function () {
      const teks = targetInput.value;
      const posisi = targetInput.selectionStart === null ? teks.length : targetInput.selectionStart;
      const digitSebelumPosisi = teks.slice(0, posisi).replace(/\D/g, "").length;
      const digit = teks.replace(/\D/g, "");
      const terformat = digit ? formatNominal(Number(digit)) : "";
      targetInput.value = terformat;

      if (document.activeElement === targetInput) {
        let posisiBaru = 0;
        let digitTerhitung = 0;
        while (posisiBaru < terformat.length && digitTerhitung < digitSebelumPosisi) {
          if (/\d/.test(terformat.charAt(posisiBaru))) digitTerhitung++;
          posisiBaru++;
        }
        targetInput.setSelectionRange(posisiBaru, posisiBaru);
      }
    };

    jadwal.forEach(function (paket, indeks) {
      const opsi = new Option(
        paket.tenor + " bulan (Bunga " + paket.bunga.toLocaleString("id-ID", { maximumFractionDigits: 2 }) + "%)",
        String(paket.tenor),
        indeks === 0,
        indeks === 0
      );
      opsi.dataset.bunga = String(paket.bunga);
      tenorSelect.add(opsi);
    });
    tampilkan("[data-tabungan-batas]", formatRupiah(maksimalTarget));

    function hitung() {
      const target = Number(targetInput.value.replace(/\D/g, ""));
      const pilihan = tenorSelect.selectedOptions[0];
      const tenor = Number(pilihan && pilihan.value);
      const bungaTahunan = Number(pilihan && pilihan.getAttribute("data-bunga"));

      if (!Number.isFinite(target) || target < 100000 || !Number.isFinite(tenor) || tenor <= 0 || !Number.isFinite(bungaTahunan)) {
        ["[data-tabungan-setoran]", "[data-tabungan-hasil-target]", "[data-tabungan-hasil-tenor]", "[data-tabungan-hasil-bunga]", "[data-tabungan-total-setoran]", "[data-tabungan-bunga]"]
          .forEach(function (selector) { tampilkan(selector, "-"); });
        if (pesan) {
          pesan.textContent = "Masukkan target minimal Rp 100.000 dan pilih jangka waktu yang tersedia.";
          pesan.hidden = false;
        }
        return;
      }

      if (target > maksimalTarget) {
        targetInput.value = formatNominal(maksimalTarget);
        if (pesan) {
          pesan.textContent = "Target disesuaikan ke batas maksimal paket sebesar " + formatRupiah(maksimalTarget) + ".";
          pesan.hidden = false;
        }
      } else if (pesan) {
        pesan.hidden = true;
      }
      const bungaBulanan = bungaTahunan / 100 / 12;
      const faktorPertumbuhan = Math.pow(1 + bungaBulanan, tenor);
      const targetDihitung = Math.min(target, maksimalTarget);
      const setoran = bungaBulanan === 0
        ? targetDihitung / tenor
        : targetDihitung * bungaBulanan / (faktorPertumbuhan - 1);
      const totalSetoran = setoran * tenor;
      const estimasiBunga = Math.max(0, targetDihitung - totalSetoran);

      tampilkan("[data-tabungan-setoran]", formatRupiah(setoran));
      tampilkan("[data-tabungan-hasil-target]", formatRupiah(targetDihitung));
      tampilkan("[data-tabungan-hasil-tenor]", tenor + " bulan");
      tampilkan("[data-tabungan-hasil-bunga]", bungaTahunan.toLocaleString("id-ID", { maximumFractionDigits: 2 }) + "% p.a.");
      tampilkan("[data-tabungan-total-setoran]", formatRupiah(totalSetoran));
      tampilkan("[data-tabungan-bunga]", formatRupiah(estimasiBunga));
    }

    targetInput.addEventListener("input", function () {
      formatInputNominal();
      hitung();
    });
    tenorSelect.addEventListener("change", hitung);
    formatInputNominal();
    hitung();
  });
})();

/* ===== Geser kartu produk ===== */
(function () {
  document.querySelectorAll("[data-produk-slider]").forEach(function (slider) {
    const track = slider.querySelector("[data-produk-track]");
    const sebelumnya = slider.querySelector("[data-produk-prev]");
    const berikutnya = slider.querySelector("[data-produk-next]");
    if (!track || !sebelumnya || !berikutnya) return;

    function perbaruiTombol() {
      sebelumnya.disabled = track.scrollLeft <= 2;
      berikutnya.disabled = track.scrollLeft + track.clientWidth >= track.scrollWidth - 2;
    }

    function geser(arah) {
      const kartu = track.querySelector(".kartu");
      if (!kartu) return;
      const jarak = kartu.getBoundingClientRect().width + 16;
      track.scrollBy({ left: arah * jarak, behavior: "smooth" });
    }

    sebelumnya.addEventListener("click", function () { geser(-1); });
    berikutnya.addEventListener("click", function () { geser(1); });
    track.addEventListener("scroll", perbaruiTombol, { passive: true });
    window.addEventListener("resize", perbaruiTombol);
    perbaruiTombol();
  });
})();

/* ===== Geser kartu beranda dengan mouse atau sentuhan ===== */
(function () {
  document.querySelectorAll("[data-drag-scroll]").forEach(function (track) {
    let sedangDitarik = false;
    let sudahBergerak = false;
    let posisiAwal = 0;
    let scrollAwal = 0;
    let abaikanKlikSampai = 0;

    track.addEventListener("pointerdown", function (event) {
      if (event.pointerType !== "mouse" || event.button !== 0) return;

      sedangDitarik = true;
      sudahBergerak = false;
      posisiAwal = event.clientX;
      scrollAwal = track.scrollLeft;
    });

    window.addEventListener("pointermove", function (event) {
      if (!sedangDitarik) return;

      const jarak = event.clientX - posisiAwal;
      if (!sudahBergerak && Math.abs(jarak) > 5) {
        sudahBergerak = true;
        track.classList.add("is-dragging");
      }

      if (sudahBergerak) {
        track.scrollLeft = scrollAwal - jarak;
        event.preventDefault();
      }
    });

    function selesaiMenarik() {
      if (!sedangDitarik) return;
      sedangDitarik = false;

      if (sudahBergerak) {
        abaikanKlikSampai = Date.now() + 250;
        track.classList.remove("is-dragging");
      }
    }

    window.addEventListener("pointerup", selesaiMenarik);
    window.addEventListener("pointercancel", selesaiMenarik);

    track.addEventListener("click", function (event) {
      if (Date.now() < abaikanKlikSampai) {
        event.preventDefault();
        event.stopPropagation();
      }
    }, true);

    track.addEventListener("dragstart", function (event) {
      event.preventDefault();
    });
  });
})();

/* ===== Carousel banner beranda ===== */
(function () {
  const carousel = document.querySelector("[data-banner-carousel]");
  if (!carousel) return;

  const slides = Array.from(carousel.querySelectorAll("[data-banner-slide]"));
  const indikator = Array.from(carousel.querySelectorAll("[data-banner-go]"));
  const sebelumnya = carousel.querySelector("[data-banner-prev]");
  const berikutnya = carousel.querySelector("[data-banner-next]");
  if (slides.length < 2) return;

  let aktif = 0;
  const gerakDikurangi = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  let diarahkan = false;
  let fokus = false;
  let timer;

  function jadwalkan() {
    window.clearTimeout(timer);
    if (gerakDikurangi || diarahkan || fokus || document.hidden) return;

    const durasi = Number(slides[aktif].dataset.bannerDuration) || 6;
    timer = window.setTimeout(function () {
      tampilkan(aktif + 1);
    }, Math.max(3, Math.min(30, durasi)) * 1000);
  }

  function tampilkan(index) {
    slides[aktif].setAttribute("aria-hidden", "true");
    slides[aktif].inert = true;
    indikator[aktif].removeAttribute("aria-current");

    aktif = (index + slides.length) % slides.length;
    slides[aktif].setAttribute("aria-hidden", "false");
    slides[aktif].inert = false;
    indikator[aktif].setAttribute("aria-current", "true");
    jadwalkan();
  }

  sebelumnya.addEventListener("click", function () {
    tampilkan(aktif - 1);
  });

  berikutnya.addEventListener("click", function () {
    tampilkan(aktif + 1);
  });

  indikator.forEach(function (tombol, index) {
    tombol.addEventListener("click", function () {
      tampilkan(index);
    });
  });

  carousel.addEventListener("mouseenter", function () {
    diarahkan = true;
    jadwalkan();
  });

  carousel.addEventListener("mouseleave", function () {
    diarahkan = false;
    jadwalkan();
  });

  carousel.addEventListener("focusin", function () {
    fokus = true;
    jadwalkan();
  });

  carousel.addEventListener("focusout", function (event) {
    fokus = carousel.contains(event.relatedTarget);
    jadwalkan();
  });

  document.addEventListener("visibilitychange", jadwalkan);
  jadwalkan();
})();

/* ===== Daftar putar video YouTube beranda ===== */
(function () {
  const wadah = document.querySelector("[data-video-utama]");
  if (!wadah) return;

  const frame = wadah.querySelector("[data-video-frame]");
  const kartu = Array.from(wadah.querySelectorAll("[data-video-item]"));
  if (!frame || kartu.length === 0) return;

  function putar(terpilih) {
    if (terpilih.classList.contains("is-aktif")) return;

    const sumber = terpilih.dataset.videoSrc;
    if (!sumber) return;

    try {
      const tautan = new URL(sumber, window.location.origin);
      tautan.searchParams.set("autoplay", "1");
      tautan.searchParams.set("rel", "0");
      frame.src = tautan.toString();
    } catch (error) {
      frame.src = sumber;
    }

    frame.title = terpilih.dataset.videoJudul || frame.title;

    kartu.forEach(function (lain) {
      const aktif = lain === terpilih;
      lain.classList.toggle("is-aktif", aktif);

      const tombol = lain.querySelector("[data-video-play]");
      if (tombol) tombol.setAttribute("aria-pressed", aktif ? "true" : "false");
    });
  }

  kartu.forEach(function (item) {
    item.addEventListener("click", function (event) {
      // Tautan YouTube tetap membuka tab baru, bukan mengganti pemutar.
      if (event.target.closest && event.target.closest("a")) return;
      putar(item);
    });
  });
})();

/* ===== Pop-up iklan promosi beranda ===== */
(function () {
  const popup = document.querySelector("[data-promo-popup]");
  if (!popup) return;

  const dialog = popup.querySelector("[data-promo-dialog]");
  if (!dialog) return;

  const tombolTutup = popup.querySelector("button[data-promo-tutup]");
  const DURASI_TUTUP = 400; // samakan dengan durasi transisi di style.css
  let fokusTerakhir = null;
  let pewaktu = null;

  function terbuka() { return popup.classList.contains("is-buka"); }

  function buka() {
    if (terbuka()) return;
    window.clearTimeout(pewaktu);
    fokusTerakhir = document.activeElement;
    popup.hidden = false;
    popup.setAttribute("aria-hidden", "false");
    void popup.offsetWidth; // paksa reflow supaya animasi mulai dari kondisi tertutup
    popup.classList.add("is-buka");
    document.documentElement.classList.add("promo-terbuka");
    if (tombolTutup) tombolTutup.focus({ preventScroll: true });
  }

  function tutup() {
    if (!terbuka()) return;
    popup.classList.remove("is-buka");
    popup.setAttribute("aria-hidden", "true");
    document.documentElement.classList.remove("promo-terbuka");
    pewaktu = window.setTimeout(function () { popup.hidden = true; }, DURASI_TUTUP);
    if (fokusTerakhir && typeof fokusTerakhir.focus === "function") {
      fokusTerakhir.focus({ preventScroll: true });
    }
  }

  popup.addEventListener("click", function (event) {
    // Latar gelap dan tombol X menutup; klik pada gambar/tautan tetap jalan.
    if (event.target === popup || (event.target.closest && event.target.closest("[data-promo-latar], [data-promo-tutup]"))) {
      tutup();
    }
  });

  // Fokus tetap di dalam dialog selama pop-up terbuka.
  document.addEventListener("keydown", function (event) {
    if (!terbuka()) return;

    if (event.key === "Escape") {
      event.preventDefault();
      tutup();
      return;
    }

    if (event.key !== "Tab") return;

    const fokusable = Array.from(dialog.querySelectorAll("a[href], button:not([disabled])"));
    if (fokusable.length === 0) return;

    const pertama = fokusable[0];
    const terakhir = fokusable[fokusable.length - 1];

    if (event.shiftKey && document.activeElement === pertama) {
      event.preventDefault();
      terakhir.focus();
    } else if (!event.shiftKey && document.activeElement === terakhir) {
      event.preventDefault();
      pertama.focus();
    }
  });

  window.setTimeout(buka, 450);
})();

/* ===== Animasi loading halaman ===== */
(function () {
  const overlay = document.querySelector("[data-loading-halaman]");
  if (!overlay) return;

  const KUNCI_MULAI = "bbj-loading-mulai";
  const MAKS_UMUR_MULAI = 30000; // abaikan penanda lama yang tertinggal (ms)
  const durasi = Math.max(1, Math.min(15, Number(overlay.dataset.loadingDurasi) || 2)) * 1000;

  let pewaktu;
  let sedangPindah = false;

  function tampilkan() {
    window.clearTimeout(pewaktu);
    overlay.classList.remove("is-tersembunyi");
    document.documentElement.classList.add("halaman-memuat");
  }

  function sembunyikan() {
    window.clearTimeout(pewaktu);
    overlay.classList.add("is-tersembunyi");
    document.documentElement.classList.remove("halaman-memuat");
    sedangPindah = false;
    try { sessionStorage.removeItem(KUNCI_MULAI); } catch (e) {}
  }

  function sembunyikanSetelah(jeda) {
    window.clearTimeout(pewaktu);
    pewaktu = window.setTimeout(sembunyikan, Math.max(0, jeda));
  }

  // Tampilkan overlay dulu, pastikan sudah dilukis, baru jalankan perpindahan
  // halaman. Dengan begitu pengunjung melihat loading lebih dulu, sedangkan
  // proses pindah halaman berjalan di belakangnya.
  function mulaiNavigasi(aksi) {
    if (sedangPindah) return;
    sedangPindah = true;

    try { sessionStorage.setItem(KUNCI_MULAI, String(Date.now())); } catch (e) {}
    tampilkan();
    // Pengaman bila navigasi batal: overlay tidak boleh menutupi layar selamanya.
    pewaktu = window.setTimeout(sembunyikan, durasi + 5000);

    window.requestAnimationFrame(function () {
      window.setTimeout(aksi, 0);
    });
  }

  // Saat halaman dibuka, overlay sudah terlihat dari awal (lihat style.css).
  // Jika datang dari navigasi, tampilkan hanya selama sisa durasinya.
  let mulai = 0;
  try { mulai = Number(sessionStorage.getItem(KUNCI_MULAI)) || 0; } catch (e) {}
  const berlalu = mulai > 0 ? Date.now() - mulai : Infinity;
  const sisa = berlalu >= 0 && berlalu < MAKS_UMUR_MULAI ? durasi - berlalu : durasi;

  tampilkan();
  sembunyikanSetelah(sisa);

  document.addEventListener("click", function (event) {
    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

    const tautan = event.target.closest && event.target.closest("a[href]");
    if (!tautan) return;
    if (tautan.target && tautan.target !== "_self") return;
    if (tautan.hasAttribute("download")) return;
    if (tautan.dataset.tanpaLoading !== undefined) return;

    const alamat = tautan.getAttribute("href");
    if (!alamat || alamat.startsWith("#")) return;

    const url = new URL(tautan.href, window.location.href);
    if (url.origin !== window.location.origin) return;
    if (url.protocol !== "http:" && url.protocol !== "https:") return;
    // Hanya pindah anchor di halaman yang sama: tidak perlu overlay.
    if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) return;

    event.preventDefault();
    mulaiNavigasi(function () {
      window.location.assign(url.href);
    });
  });

  document.addEventListener("submit", function (event) {
    if (event.defaultPrevented) return;

    const form = event.target;
    if (!form || form.tagName !== "FORM") return;
    if (form.target && form.target !== "_self") return;
    if (form.dataset.tanpaLoading !== undefined) return;

    event.preventDefault();
    mulaiNavigasi(function () {
      HTMLFormElement.prototype.submit.call(form);
    });
  });

  // Tombol Back/Forward bisa memulihkan halaman dari cache tanpa memuat ulang,
  // jadi jangan tampilkan animasi loading lagi.
  window.addEventListener("pageshow", function (event) {
    sedangPindah = false;
    if (event.persisted) sembunyikan();
  });
})();
