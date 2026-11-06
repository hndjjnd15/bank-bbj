@extends('layouts.app')

@section('title', $berita->judul)
@section('description', \Illuminate\Support\Str::limit($berita->ringkasan ?: strip_tags($berita->isi), 155))

@section('content')
  {{-- Pratinjau saat link dibagikan ke WhatsApp, Instagram, dan sejenisnya --}}
  @push('meta')
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $berita->judul }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($berita->ringkasan ?: strip_tags($berita->isi), 155) }}">
    <meta property="og:url" content="{{ url('/berita/' . $berita->slug) }}">
    @if (count($berita->foto_urls))
      <meta property="og:image" content="{{ url($berita->foto_urls[0]) }}">
    @endif
  @endpush

  {{-- Jejak halaman --}}
  <nav class="remah" aria-label="Jejak halaman">
    <a href="/">Beranda</a>
    <span><a href="/berita">Berita</a></span>
    <span>{{ \Illuminate\Support\Str::limit($berita->judul, 40) }}</span>
  </nav>

  <h1>{{ $berita->judul }}</h1>
  <p class="meta">{{ $berita->tanggal->locale('id')->translatedFormat('l, d F Y') }}</p>

  <div class="berita-layout">
    {{-- Kolom kiri: foto, deskripsi, isi --}}
    <article>
      @php $fotos = $berita->foto_urls; @endphp

      @if (count($fotos))
        <div class="galeri" data-galeri>
          <div class="galeri__utama">
            <img src="{{ $fotos[0] }}" alt="{{ $berita->judul }}">
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

      @if ($berita->ringkasan)
        <p class="berita-ringkasan">{{ $berita->ringkasan }}</p>
      @endif

      {{-- e() mengamankan teks, nl2br() mengubah baris baru menjadi <br> --}}
      <div class="isi-berita">{!! nl2br(e($berita->isi)) !!}</div>

      @include('partials.bagikan', ['judul' => $berita->judul])
    </article>

    {{-- Kolom kanan: setinggi artikel. Promo di dalamnya menempel (sticky) dan ikut
         turun saat di-scroll, sampai artikel habis. --}}
    <aside class="sidebar-kolom" aria-label="Promo">
      <div class="sidebar">
        @include('partials.promo-sidebar')
      </div>
    </aside>
  </div>

  {{-- Di bawah: 3 berita lainnya --}}
  @if ($lainnya->count())
    <section class="berita-lain" aria-labelledby="judul-lain">
      <div class="berita-lain__kepala">
        <h2 id="judul-lain">Berita lainnya</h2>
        <a href="/berita">Lihat semua berita &rarr;</a>
      </div>

      <p class="berita-lain__petunjuk" aria-hidden="true">Geser ke samping untuk melihat berita lainnya &rarr;</p>

      {{-- Desktop: 3 kartu. Smartphone: semua kartu bisa digeser ke samping. --}}
      <div class="kartu-grid kartu-grid--lain" tabindex="0" aria-label="Berita lainnya">
        @foreach ($lainnya as $b)
          @include('partials.kartu-berita', ['berita' => $b])
        @endforeach
      </div>
    </section>
  @endif
@endsection
