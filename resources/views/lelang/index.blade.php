@extends('layouts.app')

@section('title', 'Lelang dan AYDA')
@section('description', 'Daftar jaminan yang dilelang dan agunan yang diambil alih (AYDA) yang dijual.')

@section('content')
  <h1>Lelang dan AYDA</h1>
  <p>Informasi jaminan yang dilelang dan agunan yang diambil alih (AYDA) yang dijual.</p>

  <form class="cari" method="GET" action="/lelang" role="search">
    <input type="search" name="q" value="{{ $filter['q'] }}" placeholder="Cari nama jaminan, alamat, atau kota" aria-label="Cari jaminan">

    <select name="tipe" aria-label="Tipe aset">
      <option value="">Semua tipe</option>
      @foreach (\App\Models\Lelang::TIPE_ASET as $t)
        <option value="{{ $t }}" @selected($filter['tipe'] === $t)>{{ $t }}</option>
      @endforeach
    </select>

    <select name="kota" aria-label="Kota atau kabupaten">
      <option value="">Semua kota</option>
      @foreach ($kotaList as $k)
        <option value="{{ $k }}" @selected($filter['kota'] === $k)>{{ $k }}</option>
      @endforeach
    </select>

    <select name="status" aria-label="Status">
      <option value="">Semua status</option>
      @foreach (\App\Models\Lelang::STATUS as $kunci => $label)
        <option value="{{ $kunci }}" @selected($filter['status'] === $kunci)>{{ $label }}</option>
      @endforeach
    </select>

    <select name="urut" aria-label="Urutan">
      <option value="terbaru" @selected($filter['urut'] === 'terbaru')>Terbaru</option>
      <option value="harga_asc" @selected($filter['urut'] === 'harga_asc')>Harga terendah</option>
      <option value="harga_desc" @selected($filter['urut'] === 'harga_desc')>Harga tertinggi</option>
    </select>

    <button type="submit">Cari</button>
    @if ($adaFilter)
      <a class="cari__reset" href="/lelang">Reset</a>
    @endif
  </form>

  <p class="hasil">Menampilkan {{ $lelangs->total() }} jaminan</p>

  <div class="kartu-grid">
    @forelse ($lelangs as $lelang)
      @include('partials.kartu-lelang', ['lelang' => $lelang])
    @empty
      <p>Tidak ada jaminan yang cocok. Coba ubah kata kunci atau penyaringnya.</p>
    @endforelse
  </div>

  {{ $lelangs->links('partials.pagination') }}
@endsection
