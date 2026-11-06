{{-- Navbar + overlay + tombol hamburger. Logo: public/images/logo.svg --}}
<!-- Navbar -->
<header class="nav" id="nav">
  <!-- Overlay dipindah ke sini (sebelumnya di luar header) supaya tidak menutupi sidebar -->
  <div class="tirai" id="tirai"></div>

  <div class="nav__bar">
  <div class="nav__isi">
    <a href="/" class="logo" aria-label="Bank Serang, ke beranda">
      <img class="logo__gambar" alt="" width="46" height="46"
           src="{{ asset('images/logobbj.png') }}">
      <span class="logo__teks">
      </span>
    </a>

    <nav aria-label="Navigasi utama">
      <ul class="menu" id="menu">
        <li class="menu__item"><a class="menu__tautan" href="/" @if(request()->is('/')) aria-current="page" @endif>Beranda</a></li>

        <li class="menu__item">
          <button class="menu__tautan" aria-expanded="false" aria-haspopup="true">
            Produk
            <svg class="panah" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1.5 3.5 5 7l3.5-3.5"/></svg>
          </button>
          <ul class="dropdown">
            <li><a href="/tabungan"><strong>Tabungan Tamasa</strong><small>Mulai Rp 25.000, akses mudah</small></a></li>
            <li><a href="/deposito"><strong>Deposito Pikat</strong><small>Bunga s.d 5,25% p.a., tenor 1–24 bulan</small></a></li>
            <li><a href="/kredit/multiguna"><strong>Kredit Multiguna</strong><small>Dana s.d Rp 300 juta</small></a></li>
          </ul>
        </li>

        <li class="menu__item">
          <button class="menu__tautan" aria-expanded="false" aria-haspopup="true">
            Tentang kami
            <svg class="panah" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1.5 3.5 5 7l3.5-3.5"/></svg>
          </button>
          <ul class="dropdown">
            <li><a href="/profil-perusahaan"><strong>Profil perusahaan</strong><small>Sejarah dan kepemilikan</small></a></li>
            <li><a href="/laporan"><strong>Laporan</strong><small>Laporan keuangan dan tahunan</small></a></li>
            <li><a href="/jaringan-kantor"><strong>Jaringan kantor</strong><small>Alamat kantor pusat dan cabang</small></a></li>
            <li><a href="/csr"><strong>Program CSR</strong><small>Kegiatan untuk masyarakat</small></a></li>
          </ul>
        </li>

        <li class="menu__item">
          <button class="menu__tautan" aria-expanded="false" aria-haspopup="true">
            Informasi
            <svg class="panah" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1.5 3.5 5 7l3.5-3.5"/></svg>
          </button>
          <ul class="dropdown">
            <li><a href="/berita"><strong>Berita dan Kegiatan</strong><small>Update Berita tentang BPR</small></a></li>
            <li><a href="/lelang"><strong>Lelang dan AYDA</strong><small>Jual dan Lelang Jaminan</small></a></li>
            <li><a href="/karir"><strong>Karir</strong><small>Informasi Lowongan Kerja</small></a></li>
          </ul>
        </li>

        <li class="menu__item">
          <button class="menu__tautan" aria-expanded="false" aria-haspopup="true">
            Bantuan
            <svg class="panah" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1.5 3.5 5 7l3.5-3.5"/></svg>
          </button>
          <ul class="dropdown">
            <li><a href="/#faq"><strong>FAQ</strong><small>Jawaban cepat seputar produk</small></a></li>
            <li><a href="/pengaduan"><strong>Pengaduan nasabah</strong><small>Sampaikan keluhan Anda</small></a></li>
            <li><a href="/wbs"><strong>Whistleblowing (WBS)</strong><small>Laporkan pelanggaran</small></a></li>
            <li><a href="/ppid"><strong>PPID</strong><small>Informasi publik</small></a></li>
          </ul>
        </li>
      </ul>
    </nav>

    <!-- Tombol hamburger (sebelumnya hilang dari file, padahal dibutuhkan JS) -->
    <div class="aksi">
      <a class="btn-ajukan" href="/pengajuan-kredit">Pengajuan kredit</a>
      <button class="hamburger" id="hamburger" aria-label="Buka menu" aria-expanded="false" aria-controls="menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
  </div>
</header>
