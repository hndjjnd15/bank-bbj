@extends('layouts.app')

@section('title', $lowongan->judul . ' - ' . $lowongan->penempatan)
@section('description', \Illuminate\Support\Str::limit('Lowongan ' . $lowongan->judul . ' (' . $lowongan->tipe_kerja . ') di ' . $lowongan->penempatan . '. ' . strip_tags($lowongan->deskripsi), 155))

@section('content')
  @php
    $ditutup = $lowongan->sudah_ditutup;
    $sisa = $lowongan->sisa_hari;
  @endphp

  {{-- Pratinjau saat link dibagikan ke WhatsApp dan sejenisnya --}}
  @push('meta')
    <meta property="og:type" content="website">
    <meta property="og:title" content="Lowongan {{ $lowongan->judul }} - {{ $lowongan->penempatan }}">
    <meta property="og:description" content="{{ $lowongan->tipe_kerja }}{{ $lowongan->batas_lamaran ? ' | Batas lamaran ' . $lowongan->batas_lamaran->locale('id')->translatedFormat('d M Y') : '' }}">
    <meta property="og:url" content="{{ url('/karir/' . $lowongan->slug) }}">
  @endpush

  <nav class="remah" aria-label="Jejak halaman">
    <a href="/">Beranda</a>
    <span><a href="/karir">Karir</a></span>
    <span>{{ \Illuminate\Support\Str::limit($lowongan->judul, 40) }}</span>
  </nav>

  <h1>{{ $lowongan->judul }}</h1>
  <p class="meta">
    <span class="lencana-tenggat lencana-tenggat--{{ $ditutup ? 'tutup' : 'buka' }}">{{ $ditutup ? 'Ditutup' : 'Dibuka' }}</span>
    {{ $lowongan->penempatan }} &middot; {{ $lowongan->tipe_kerja }}
  </p>

  <div class="berita-layout">
    {{-- Kolom kiri --}}
    <article>
      <h2 class="blok-judul" style="margin-top:0">Ringkasan</h2>
      <dl class="spek">
        <div><dt>Penempatan</dt><dd>{{ $lowongan->penempatan }}</dd></div>
        <div><dt>Tipe pekerjaan</dt><dd>{{ $lowongan->tipe_kerja }}</dd></div>
        <div><dt>Divisi</dt><dd>{{ $lowongan->divisi ?: '-' }}</dd></div>
        <div><dt>Pendidikan minimal</dt><dd>{{ $lowongan->pendidikan_minimal ?: '-' }}</dd></div>
        <div><dt>Pengalaman</dt><dd>{{ $lowongan->pengalaman ?: '-' }}</dd></div>
        <div>
          <dt>Batas lamaran</dt>
          <dd>{{ $lowongan->batas_lamaran ? $lowongan->batas_lamaran->locale('id')->translatedFormat('d M Y') : 'Sampai posisi terisi' }}</dd>
        </div>
      </dl>

      <h2 class="blok-judul">Deskripsi pekerjaan</h2>
      <div class="isi-berita">{!! nl2br(e($lowongan->deskripsi)) !!}</div>

      <h2 class="blok-judul">Persyaratan</h2>
      <ul class="daftar-poin">
        @foreach (\App\Models\Lowongan::poin($lowongan->persyaratan) as $poin)
          <li>{{ $poin }}</li>
        @endforeach
      </ul>

      @if (filled($lowongan->benefit))
        <h2 class="blok-judul">Yang Anda dapatkan</h2>
        <ul class="daftar-poin">
          @foreach (\App\Models\Lowongan::poin($lowongan->benefit) as $poin)
            <li>{{ $poin }}</li>
          @endforeach
        </ul>
      @endif
    </article>

    {{-- Kolom kanan: cara melamar, ikut turun saat di-scroll --}}
    <aside class="sidebar-kolom" aria-label="Cara melamar">
      <div class="sidebar">
        <div class="harga-kartu">
          <small>Lamar posisi ini</small>
          <strong class="harga" style="font-size:1.375rem">{{ $lowongan->judul }}</strong>

          @if ($ditutup)
            <p class="catatan">Lowongan ini sudah ditutup.</p>
            <a class="tombol-aksi tombol-aksi--abu" href="/karir">Lihat lowongan lain</a>
          @else
            @if ($lowongan->batas_lamaran)
              <p class="harga-pasar">Batas lamaran <strong>{{ $lowongan->batas_lamaran->locale('id')->translatedFormat('d F Y') }}</strong></p>
              @if ($sisa !== null)
                <span class="sisa-hari {{ $sisa <= 3 ? 'sisa-hari--segera' : '' }}">
                  {{ $sisa === 0 ? 'Berakhir hari ini' : 'Sisa ' . $sisa . ' hari' }}
                </span>
              @endif
            @endif

            <a class="tombol-aksi tombol-aksi--email" href="{{ $lowongan->mailto_url }}">Kirim lamaran via email</a>

            @if ($lowongan->wa_url)
              <a class="tombol-aksi tombol-aksi--wa" style="margin-top:12px" href="{{ $lowongan->wa_url }}" target="_blank" rel="noopener">Tanya via WhatsApp</a>
            @endif

            @if ($lowongan->catatan_lamaran)
              <p class="harga-pasar" style="margin-top:16px">{{ $lowongan->catatan_lamaran }}</p>
            @endif

            <p class="pic">Email HRD: <strong>{{ $lowongan->email_lamaran }}</strong></p>
          @endif
        </div>

        @include('partials.bagikan', ['judul' => 'Lowongan ' . $lowongan->judul . ' - ' . $lowongan->penempatan])
      </div>
    </aside>
  </div>

  {{-- Lowongan lainnya --}}
  @if ($lainnya->count())
    <section class="berita-lain" aria-labelledby="judul-lain">
      <div class="berita-lain__kepala">
        <h2 id="judul-lain">Lowongan lainnya</h2>
        <a href="/karir">Lihat semua &rarr;</a>
      </div>

      <p class="berita-lain__petunjuk" aria-hidden="true">Geser ke samping untuk melihat lainnya &rarr;</p>

      <div class="kartu-grid kartu-grid--lain" tabindex="0" aria-label="Lowongan lainnya">
        @foreach ($lainnya as $item)
          @include('partials.kartu-lowongan', ['lowongan' => $item])
        @endforeach
      </div>
    </section>
  @endif
@endsection
