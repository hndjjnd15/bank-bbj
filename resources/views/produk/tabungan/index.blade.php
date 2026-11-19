@extends('layouts.app')

@section('title', 'Produk Tabungan')
@section('description', 'Pilihan produk tabungan Bank BBJ untuk kebutuhan dan rencana keuangan Anda.')

@section('content')
  <header class="kredit-kepala">
    <p class="wbs__label">PRODUK TABUNGAN</p>
    <h1>Wujudkan rencana keuangan Anda</h1>
    <p>Pilih produk tabungan untuk melihat informasi, persyaratan, brosur, dan simulasi yang tersedia.</p>
  </header>

  <div class="kartu-grid kredit-grid">
    @forelse ($produk as $item)
      @include('partials.kartu-produk-tabungan', ['produk' => $item])
    @empty
      <div class="faq__kosong kredit-kosong">
        <p>Informasi produk tabungan belum tersedia. Silakan hubungi kantor cabang untuk mendapatkan informasi.</p>
        <a class="ppid__tombol" href="/jaringan-kantor">Lihat Kantor Cabang</a>
      </div>
    @endforelse
  </div>
@endsection
