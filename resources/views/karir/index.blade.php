@extends('layouts.app')

@section('title', 'Karir')
@section('description', 'Informasi lowongan kerja dan peluang karier terbaru.')

@section('content')
  <h1>Karir</h1>
  <p>Bergabunglah bersama kami. Berikut informasi lowongan kerja yang tersedia.</p>

  <form class="cari" method="GET" action="/karir" role="search">
    <input type="search" name="q" value="{{ $filter['q'] }}" placeholder="Cari posisi, divisi, atau penempatan" aria-label="Cari lowongan">

    <select name="tipe" aria-label="Tipe pekerjaan">
      <option value="">Semua tipe</option>
      @foreach (\App\Models\Lowongan::TIPE_KERJA as $t)
        <option value="{{ $t }}" @selected($filter['tipe'] === $t)>{{ $t }}</option>
      @endforeach
    </select>

    <select name="penempatan" aria-label="Penempatan">
      <option value="">Semua penempatan</option>
      @foreach ($penempatanList as $p)
        <option value="{{ $p }}" @selected($filter['penempatan'] === $p)>{{ $p }}</option>
      @endforeach
    </select>

    <button type="submit">Cari</button>
    @if ($adaFilter)
      <a class="cari__reset" href="/karir">Reset</a>
    @endif
  </form>

  <p class="hasil">Menampilkan {{ $lowongans->total() }} lowongan</p>

  <div class="kartu-grid">
    @forelse ($lowongans as $lowongan)
      @include('partials.kartu-lowongan', ['lowongan' => $lowongan])
    @empty
      <p>Belum ada lowongan yang cocok. Silakan cek kembali di lain waktu.</p>
    @endforelse
  </div>

  {{ $lowongans->links('partials.pagination') }}
@endsection
