@extends('layouts.app')

@section('title', 'Daftar Informasi Publik')
@section('description', 'Daftar informasi publik: informasi berkala, serta merta, dan setiap saat.')

@section('content')
  <nav class="remah" aria-label="Jejak halaman">
    <a href="/">Beranda</a>
    <span><a href="/ppid">PPID</a></span>
    <span>Daftar informasi publik</span>
  </nav>

  <h1>Daftar Informasi Publik</h1>
  <p>Dokumen informasi publik yang dapat diunduh.</p>

  <form class="cari" method="GET" action="/ppid/informasi" role="search">
    <input type="search" name="q" value="{{ $filter['q'] }}" placeholder="Cari judul atau keterangan dokumen" aria-label="Cari dokumen">

    <select name="kategori" aria-label="Kategori">
      <option value="">Semua kategori</option>
      @foreach (config('ppid.kategori') as $kunci => $label)
        <option value="{{ $kunci }}" @selected($filter['kategori'] === $kunci)>{{ $label }}</option>
      @endforeach
    </select>

    <select name="tahun" aria-label="Tahun">
      <option value="">Semua tahun</option>
      @foreach ($tahunList as $t)
        <option value="{{ $t }}" @selected($filter['tahun'] === $t)>{{ $t }}</option>
      @endforeach
    </select>

    <button type="submit">Cari</button>
    @if ($adaFilter)
      <a class="cari__reset" href="/ppid/informasi">Reset</a>
    @endif
  </form>

  <p class="hasil">Menampilkan {{ $dokumens->total() }} dokumen</p>

  <div class="dokumen-daftar">
    @forelse ($dokumens as $dokumen)
      <article class="dokumen">
        <span class="dokumen__jenis" aria-hidden="true">{{ $dokumen->ekstensi }}</span>
        <div class="dokumen__isi">
          <h2>{{ $dokumen->judul }}</h2>
          <p class="dokumen__meta">{{ $dokumen->kategori_label }} &middot; {{ $dokumen->tahun }}</p>
          @if ($dokumen->deskripsi)
            <p class="dokumen__ket">{{ $dokumen->deskripsi }}</p>
          @endif
        </div>
        @if ($dokumen->file_url)
          <a class="dokumen__unduh" href="{{ $dokumen->file_url }}" target="_blank" rel="noopener" download>Unduh</a>
        @else
          <span class="dokumen__belum">Berkas belum tersedia</span>
        @endif
      </article>
    @empty
      <p>Belum ada dokumen yang cocok. Coba ubah kata kunci atau penyaringnya.</p>
    @endforelse
  </div>

  {{ $dokumens->links('partials.pagination') }}
@endsection
