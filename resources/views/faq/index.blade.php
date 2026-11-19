@extends('layouts.app')

@section('title', 'FAQ')
@section('description', 'Pertanyaan yang sering diajukan seputar produk dan layanan Bank BBJ.')

@section('content')
  <div class="faq">
    {{-- Kepala halaman --}}
    <header class="csr__kepala">
      <p class="wbs__label">FAQ</p>
      <h1>Pertanyaan yang <span class="wbs__sorot">Sering Diajukan</span></h1>
      <p>
        Kumpulan pertanyaan umum seputar produk dan layanan kami. Klik pertanyaan untuk melihat
        jawabannya. Pertanyaan yang belum terjawab bisa disampaikan lewat pengaduan nasabah atau
        kantor cabang terdekat.
      </p>
    </header>

    @if ($daftar->isEmpty())
      <div class="faq__kosong">
        <p>Belum ada pertanyaan yang ditambahkan. Silakan kembali lagi nanti.</p>
        <a class="ppid__tombol" href="/produk">Lihat Produk</a>
      </div>
    @else
      <p class="faq__jumlah">{{ $total }} pertanyaan dari {{ $daftar->count() }} kategori</p>

      @foreach ($daftar as $grup)
        <section class="faq__grup" aria-labelledby="faq-{{ $grup['slug'] }}">
          <div class="faq__kepala">
            <h2 id="faq-{{ $grup['slug'] }}">{{ $grup['judul'] }}</h2>
            <span class="faq__hitung">{{ $grup['faq']->count() }} pertanyaan</span>
          </div>

          @foreach ($grup['faq'] as $item)
            <details class="akordeon">
              <summary>
                <span>{{ $item['tanya'] }}</span>
                <span class="akordeon__panah" aria-hidden="true">&#9660;</span>
              </summary>
              <div class="akordeon__isi">
                <p>{!! nl2br(e($item['jawab'])) !!}</p>
              </div>
            </details>
          @endforeach
        </section>
      @endforeach

      {{-- Ajakan bila pertanyaan belum terjawab --}}
      <section class="produk__ajakan faq__ajakan">
        <h2>Pertanyaan Anda belum terjawab?</h2>
        <p>Hubungi kami lewat pengaduan nasabah atau kunjungi kantor cabang terdekat.</p>
        <div class="ppid__aksi">
          <a class="ppid__tombol" href="/pengaduan">Kirim Pengaduan</a>
          <a class="ppid__tombol ppid__tombol--garis" href="/jaringan-kantor">Cari Kantor Terdekat</a>
        </div>
      </section>
    @endif
  </div>
@endsection
