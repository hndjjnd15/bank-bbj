@extends('layouts.app')

@section('title', 'Produk Kredit')
@section('description', 'Pilihan produk kredit Bank BBJ. Lihat informasi dan persyaratan setiap produk.')

@section('content')
  <header class="kredit-kepala">
    <p class="wbs__label">PRODUK KREDIT</p>
    <h1>Solusi pembiayaan untuk berbagai kebutuhan</h1>
    <p>Pilih produk untuk melihat informasi, persyaratan, brosur, dan simulasi yang tersedia.</p>
  </header>

  <div class="kartu-grid kredit-grid">
    @forelse ($produk as $item)
      @include('partials.kartu-produk-kredit', ['produk' => $item])
    @empty
      <div class="faq__kosong kredit-kosong">
        <p>Informasi produk kredit belum tersedia. Silakan hubungi kantor cabang untuk mendapatkan informasi.</p>
        <a class="ppid__tombol" href="/jaringan-kantor">Lihat Kantor Cabang</a>
      </div>
    @endforelse
  </div>
@endsection
