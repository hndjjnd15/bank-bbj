@extends('layouts.app')

@section('title', $lelang->judul)
@section('description', \Illuminate\Support\Str::limit($lelang->harga_limit_rp . ' | ' . $lelang->tipe_aset . ' di ' . $lelang->kota . '. ' . strip_tags($lelang->deskripsi), 155))

@section('content')
  @php
    $fotos = $lelang->foto_urls;
    $terjual = $lelang->status === 'terjual';
    $jadwalLewat = $lelang->status === 'lelang' && $lelang->jadwal_lelang && $lelang->jadwal_lelang->isPast();
  @endphp

  {{-- Pratinjau saat link dibagikan ke WhatsApp, Instagram, dan sejenisnya --}}
  @push('meta')
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $lelang->judul }} ({{ $lelang->status_label }})">
    <meta property="og:description" content="{{ $lelang->harga_limit_rp }} | {{ $lelang->tipe_aset }} di {{ $lelang->kota }}">
    <meta property="og:url" content="{{ url('/lelang/' . $lelang->slug) }}">
    @if (count($fotos))
      <meta property="og:image" content="{{ url($fotos[0]) }}">
    @endif
  @endpush

  <nav class="remah" aria-label="Jejak halaman">
    <a href="/">Beranda</a>
    <span><a href="/lelang">Lelang dan AYDA</a></span>
    <span>{{ \Illuminate\Support\Str::limit($lelang->judul, 40) }}</span>
  </nav>

  <h1>{{ $lelang->judul }}</h1>
  <p class="meta">
    <span class="lencana lencana--{{ $lelang->status }} lencana--sebaris">{{ $lelang->status_label }}</span>
    {{ $lelang->tipe_aset }} &middot; {{ $lelang->kota }}
  </p>

  <div class="berita-layout">
    {{-- Kolom kiri --}}
    <article>
      @if (count($fotos))
        <div class="galeri" data-galeri>
          <div class="galeri__utama">
            <img src="{{ $fotos[0] }}" alt="{{ $lelang->judul }}">
          </div>
          @if (count($fotos) > 1)
            <div class="galeri__thumb">
              @foreach ($fotos as $i => $foto)
                <button type="button" data-foto="{{ $foto }}"
                        aria-label="Tampilkan foto {{ $i + 1 }}"
                        aria-current="{{ $i === 0 ? 'true' : 'false' }}">
                  <img src="{{ $foto }}" alt="">
                </button>
              @endforeach
            </div>
          @endif
        </div>
      @endif

      {{-- Spesifikasi --}}
      <h2 class="blok-judul">Spesifikasi</h2>
      <dl class="spek">
        {{-- Baris 1: identitas. Baris 2: ukuran dan jadwal --}}
        <div><dt>Tipe aset</dt><dd>{{ $lelang->tipe_aset }}</dd></div>
        <div><dt>Sertifikat</dt><dd>{{ $lelang->sertifikat }}</dd></div>
        <div><dt>Status</dt><dd>{{ $lelang->status_label }}</dd></div>
        <div><dt>Luas tanah</dt><dd>{{ \App\Models\Lelang::formatLuas($lelang->luas_tanah) }}</dd></div>
        <div><dt>Luas bangunan</dt><dd>{{ \App\Models\Lelang::formatLuas($lelang->luas_bangunan) }}</dd></div>
        <div>
          <dt>Jadwal lelang</dt>
          <dd>{{ $lelang->jadwal_lelang ? $lelang->jadwal_lelang->locale('id')->translatedFormat('d M Y, H:i') . ' WIB' : 'Belum dijadwalkan' }}</dd>
        </div>
      </dl>

      {{-- Deskripsi --}}
      <h2 class="blok-judul">Keterangan dan kondisi fisik</h2>
      <div class="isi-berita">{!! nl2br(e($lelang->deskripsi)) !!}</div>

      {{-- Lokasi --}}
      <h2 class="blok-judul">Lokasi</h2>
      <p class="alamat">{{ $lelang->alamat }}, {{ $lelang->kota }}</p>
      @if ($lelang->peta_embed_url)
        <div class="peta">
          <iframe src="{{ $lelang->peta_embed_url }}" title="Peta lokasi {{ $lelang->judul }}"
                  loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
        </div>
      @endif
      <p><a class="kembali" href="{{ $lelang->peta_buka_url }}" target="_blank" rel="noopener">Buka di Google Maps &rarr;</a></p>
    </article>

    {{-- Kolom kanan: harga dan kontak, ikut turun saat di-scroll --}}
    <aside class="sidebar-kolom" aria-label="Harga dan kontak">
      <div class="sidebar">
        <div class="harga-kartu">
          <small>Harga limit lelang</small>
          <strong class="harga">{{ $lelang->harga_limit_rp }}</strong>

          @if ($lelang->harga_per_m2_rp)
            <p class="harga-pasar">Sekitar <strong>{{ $lelang->harga_per_m2_rp }}</strong> per m² tanah</p>
          @endif

          @if ($lelang->harga_pasar_rp)
            <p class="harga-pasar">
              Estimasi harga pasar <strong>{{ $lelang->harga_pasar_rp }}</strong>
              @if ($lelang->selisih_pasar_persen && ! $terjual)
                <span class="hemat">{{ $lelang->selisih_pasar_persen }}% di bawah estimasi pasar</span>
              @endif
            </p>
          @endif

          @if ($lelang->jadwal_lelang)
            <p class="harga-pasar">Jadwal lelang <strong>{{ $lelang->jadwal_lelang->locale('id')->translatedFormat('d F Y, H:i') }} WIB</strong></p>
          @endif

          @if ($terjual)
            <p class="catatan">Jaminan ini sudah terjual.</p>
            <a class="tombol-aksi tombol-aksi--abu" href="/lelang">Lihat jaminan lain</a>
          @else
            @if ($jadwalLewat)
              <p class="catatan">Jadwal lelang sudah lewat. Hubungi PIC untuk informasi terbaru.</p>
            @endif

            @if ($lelang->wa_url)
              <a class="tombol-aksi tombol-aksi--wa" href="{{ $lelang->wa_url }}" target="_blank" rel="noopener">Tanya via WhatsApp</a>
            @endif

            @if ($lelang->link_instagram)
              <a class="tombol-aksi tombol-aksi--ig" href="{{ $lelang->link_instagram }}" target="_blank" rel="noopener noreferrer">Lihat di Instagram</a>
            @endif

            <p class="pic">PIC: <strong>{{ $lelang->pic_nama }}</strong></p>
          @endif
        </div>

        @include('partials.bagikan', ['judul' => $lelang->judul])
      </div>
    </aside>
  </div>

  {{-- Lelang dan AYDA lainnya --}}
  @if ($lainnya->count())
    <section class="berita-lain" aria-labelledby="judul-lain">
      <div class="berita-lain__kepala">
        <h2 id="judul-lain">Lelang dan AYDA lainnya</h2>
        <a href="/lelang">Lihat semua &rarr;</a>
      </div>

      <p class="berita-lain__petunjuk" aria-hidden="true">Geser ke samping untuk melihat lainnya &rarr;</p>

      <div class="kartu-grid kartu-grid--lain" tabindex="0" aria-label="Lelang dan AYDA lainnya">
        @foreach ($lainnya as $item)
          @include('partials.kartu-lelang', ['lelang' => $item])
        @endforeach
      </div>
    </section>
  @endif
@endsection
