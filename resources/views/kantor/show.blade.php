@extends('layouts.app')

@section('title', $kantor->nama)
@section('description', $kantor->nama . ' - ' . \Illuminate\Support\Str::limit($kantor->alamat, 120))

@section('content')
  @php $fotos = $kantor->foto_urls; @endphp

  {{-- Pratinjau saat link dibagikan ke WhatsApp dan sejenisnya --}}
  @push('meta')
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $kantor->nama }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($kantor->alamat, 150) }}">
    <meta property="og:url" content="{{ url('/jaringan-kantor/' . $kantor->slug) }}">
    @if (count($fotos))
      <meta property="og:image" content="{{ url($fotos[0]) }}">
    @endif
  @endpush

  <nav class="remah" aria-label="Jejak halaman">
    <a href="/">Beranda</a>
    <span><a href="/jaringan-kantor">Jaringan kantor</a></span>
    <span>{{ \Illuminate\Support\Str::limit($kantor->nama, 40) }}</span>
  </nav>

  <h1>{{ $kantor->nama }}</h1>
  <p class="meta">
    <span class="lencana lencana--kantor-{{ \Illuminate\Support\Str::slug($kantor->tipe) }} lencana--sebaris">{{ $kantor->tipe }}</span>
    {{ $kantor->kota }}
  </p>

  <div class="berita-layout">
    {{-- Kolom kiri --}}
    <article>
      @if (count($fotos))
        <div class="galeri" data-galeri>
          <div class="galeri__utama">
            <img src="{{ $fotos[0] }}" alt="{{ $kantor->nama }}">
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

      <h2 class="blok-judul" @if (! count($fotos)) style="margin-top:0" @endif>Alamat</h2>
      <p class="alamat">{{ $kantor->alamat }}, {{ $kantor->kota }}</p>

      @if (count($kantor->jam_baris))
        <h2 class="blok-judul">Jam operasional</h2>
        <ul class="jam-daftar">
          @foreach ($kantor->jam_baris as $baris)
            <li>{{ $baris }}</li>
          @endforeach
        </ul>
      @endif

      <h2 class="blok-judul">Lokasi</h2>
      @if ($kantor->peta_embed_url)
        <div class="peta">
          <iframe src="{{ $kantor->peta_embed_url }}" title="Peta lokasi {{ $kantor->nama }}"
                  loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
        </div>
      @endif
      <p><a class="kembali" href="{{ $kantor->peta_buka_url }}" target="_blank" rel="noopener">Buka di Google Maps &rarr;</a></p>
    </article>

    {{-- Kolom kanan: kontak, ikut turun saat di-scroll --}}
    <aside class="sidebar-kolom" aria-label="Kontak kantor">
      <div class="sidebar">
        <div class="harga-kartu">
          <small>Hubungi kantor ini</small>
          <dl class="csr-info">
            @if ($kantor->telepon)
              <div><dt>Telepon</dt><dd><a href="tel:{{ $kantor->telepon_link }}">{{ $kantor->telepon }}</a></dd></div>
            @endif
            @if ($kantor->email)
              <div><dt>Email</dt><dd><a href="mailto:{{ $kantor->email }}">{{ $kantor->email }}</a></dd></div>
            @endif
            <div><dt>Wilayah</dt><dd>{{ $kantor->kota }}</dd></div>
          </dl>

          @if ($kantor->wa_url)
            <a class="tombol-aksi tombol-aksi--wa" href="{{ $kantor->wa_url }}" target="_blank" rel="noopener">Chat via WhatsApp</a>
          @endif
          @if ($kantor->telepon_link)
            <a class="tombol-aksi tombol-aksi--email" style="margin-top:12px" href="tel:{{ $kantor->telepon_link }}">Telepon sekarang</a>
          @endif
          <a class="tombol-aksi tombol-aksi--garis" href="{{ $kantor->petunjuk_arah_url }}" target="_blank" rel="noopener">Petunjuk arah</a>
        </div>

        @include('partials.bagikan', ['judul' => $kantor->nama])
      </div>
    </aside>
  </div>

  {{-- Kantor lainnya --}}
  @if ($lainnya->count())
    <section class="berita-lain" aria-labelledby="judul-lain">
      <div class="berita-lain__kepala">
        <h2 id="judul-lain">Kantor lainnya</h2>
        <a href="/jaringan-kantor">Lihat semua &rarr;</a>
      </div>

      <p class="berita-lain__petunjuk" aria-hidden="true">Geser ke samping untuk melihat lainnya &rarr;</p>

      <div class="kartu-grid kartu-grid--lain" tabindex="0" aria-label="Kantor lainnya">
        @foreach ($lainnya as $item)
          @include('partials.kartu-kantor', ['kantor' => $item])
        @endforeach
      </div>
    </section>
  @endif
@endsection
