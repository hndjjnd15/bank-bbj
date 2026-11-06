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
    tombol.addEventListener("click", function (e) {
      e.stopPropagation();
      const buka = tombol.getAttribute("aria-expanded") !== "true";
      tutupSemuaDropdown(tombol);
      setDropdown(tombol, buka);
    });
    const item = tombol.parentElement;
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
    document.body.style.overflow = buka ? "hidden" : "";
    document.documentElement.classList.toggle("menu-buka", buka); // menyembunyikan tombol bantuan
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
