@extends('layouts.app')

@section('title', 'Jaringan Kantor')
@section('description', 'Alamat, kontak, dan jam operasional kantor pusat dan kantor cabang.')

@section('content')
  <header class="csr__kepala">
    <p class="wbs__label">JARINGAN KANTOR</p>
    <h1>Kantor <span class="wbs__sorot">Kami</span></h1>
    <p>Temukan kantor pusat dan kantor cabang terdekat beserta alamat, kontak, dan jam operasional.</p>
  </header>

  <form class="cari" method="GET" action="/jaringan-kantor" role="search">
    <input type="search" name="q" value="{{ $filter['q'] }}" placeholder="Cari nama kantor, alamat, atau kota" aria-label="Cari kantor">

    <select name="tipe" aria-label="Jenis kantor">
      <option value="">Semua jenis</option>
      @foreach (\App\Models\KantorCabang::TIPE as $t)
        <option value="{{ $t }}" @selected($filter['tipe'] === $t)>{{ $t }}</option>
      @endforeach
    </select>

    <select name="kota" aria-label="Kota atau kabupaten">
      <option value="">Semua wilayah</option>
      @foreach ($kotaList as $k)
        <option value="{{ $k }}" @selected($filter['kota'] === $k)>{{ $k }}</option>
      @endforeach
    </select>

    <button type="submit">Cari</button>
    @if ($adaFilter)
      <a class="cari__reset" href="/jaringan-kantor">Reset</a>
    @endif
  </form>

  <p class="hasil">Menampilkan {{ $kantors->count() }} kantor</p>

  <div class="kartu-grid">
    @forelse ($kantors as $kantor)
      @include('partials.kartu-kantor', ['kantor' => $kantor])
    @empty
      <p>Tidak ada kantor yang cocok. Coba ubah kata kunci atau penyaringnya.</p>
    @endforelse
  </div>
@endsection
