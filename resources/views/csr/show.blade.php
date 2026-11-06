@extends('layouts.app')

@section('title', $program->judul)
@section('description', \Illuminate\Support\Str::limit($program->ringkasan ?: strip_tags($program->isi), 155))

@section('content')
  @php
    $fotos = $program->foto_urls;
    $tanggal = $program->tanggal->locale('id')->translatedFormat('d F Y');
  @endphp

  {{-- Pratinjau saat link dibagikan ke WhatsApp, Instagram, dan sejenisnya --}}
  @push('meta')
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $program->judul }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($program->ringkasan ?: strip_tags($program->isi), 155) }}">
    <meta property="og:url" content="{{ url('/csr/' . $program->slug) }}">
    @if (count($fotos))
      <meta property="og:image" content="{{ url($fotos[0]) }}">
    @endif
  @endpush

  <nav class="remah" aria-label="Jejak halaman">
    <a href="/">Beranda</a>
    <span><a href="/csr">Program CSR</a></span>
    <span>{{ \Illuminate\Support\Str::limit($program->judul, 40) }}</span>
  </nav>

  <h1>{{ $program->judul }}</h1>
  <p class="meta">
    <span class="lencana lencana--csr lencana--sebaris">{{ $program->kategori }}</span>
    {{ $tanggal }}@if ($program->lokasi) &middot; {{ $program->lokasi }}@endif
  </p>

  <div class="berita-layout">
    {{-- Kolom kiri: foto dan deskripsi --}}
    <article>
      @if (count($fotos))
        <div class="galeri" data-galeri>
          <div class="galeri__utama">
            <img src="{{ $fotos[0] }}" alt="{{ $program->judul }}">
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

      @if ($program->ringkasan)
        <p class="berita-ringkasan">{{ $program->ringkasan }}</p>
      @endif

      <div class="isi-berita isi-csr {{ \App\Support\Perataan::kelas($program->isi_rata) }}">
        @foreach ($program->isi_paragraf as $paragraf)
          <p>{!! nl2br(e($paragraf)) !!}</p>
        @endforeach
      </div>

      @include('partials.bagikan', ['judul' => $program->judul])
    </article>

    {{-- Kolom kanan: ringkasan program, ikut turun saat di-scroll --}}
    <aside class="sidebar-kolom" aria-label="Ringkasan program">
      <div class="sidebar">
        <div class="harga-kartu">
          <small>Ringkasan program</small>
          <dl class="csr-info">
            <div><dt>Kategori</dt><dd>{{ $program->kategori }}</dd></div>
            <div><dt>Tanggal pelaksanaan</dt><dd>{{ $tanggal }}</dd></div>
            @if ($program->lokasi)
              <div><dt>Lokasi</dt><dd>{{ $program->lokasi }}</dd></div>
            @endif
            @if ($program->penerima_manfaat)
              <div><dt>Penerima manfaat</dt><dd>{{ $program->penerima_manfaat }}</dd></div>
            @endif
            @if ($program->mitra)
              <div><dt>Mitra</dt><dd>{{ $program->mitra }}</dd></div>
            @endif
          </dl>
        </div>
      </div>
    </aside>
  </div>

  {{-- Program lainnya --}}
  @if ($lainnya->count())
    <section class="berita-lain" aria-labelledby="judul-lain">
      <div class="berita-lain__kepala">
        <h2 id="judul-lain">Program CSR lainnya</h2>
        <a href="/csr">Lihat semua &rarr;</a>
      </div>

      <p class="berita-lain__petunjuk" aria-hidden="true">Geser ke samping untuk melihat lainnya &rarr;</p>

      <div class="kartu-grid kartu-grid--lain" tabindex="0" aria-label="Program CSR lainnya">
        @foreach ($lainnya as $item)
          @include('partials.kartu-csr', ['program' => $item])
        @endforeach
      </div>
    </section>
  @endif
@endsection
