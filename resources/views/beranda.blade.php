@extends('layouts.app')

@section('title', 'Beranda')
@section('description', 'Kenali layanan tabungan, deposito, kredit, lelang, dan informasi Bank BBJ.')

@section('content')
  <section class="beranda-banner" data-banner-carousel role="region" aria-roledescription="carousel" aria-label="Banner beranda">
    @foreach ($bannerSlides as $banner)
      <article
        class="beranda-banner__slide"
        data-banner-slide
        data-banner-duration="{{ $banner['durasi_detik'] }}"
        role="group"
        aria-roledescription="slide"
        aria-label="{{ $loop->iteration }} dari {{ $bannerSlides->count() }}"
        aria-hidden="{{ $loop->first ? 'false' : 'true' }}"
        @unless ($loop->first) inert @endunless
      >
        <picture>
          <source media="(max-width: 640px)" srcset="{{ $banner['gambar_mobile'] }}">
          <img
            class="beranda-banner__gambar"
            src="{{ $banner['gambar'] }}"
            alt="{{ $banner['alt'] }}"
            @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif
          >
        </picture>
        <div class="beranda-banner__lapisan">
          <p class="beranda-eyebrow">PT BPR Bogor Jabar (Perseroda)</p>
          @if ($loop->first)
            <h1>{{ $banner['judul'] }}</h1>
          @else
            <h2>{{ $banner['judul'] }}</h2>
          @endif
          @if (filled($banner['teks']))
            <p>{{ $banner['teks'] }}</p>
          @endif
          <a class="beranda-tombol" href="#produk-layanan">Jelajahi Produk</a>
        </div>
      </article>
    @endforeach

    @if ($bannerSlides->count() > 1)
      <div class="beranda-banner__kontrol" aria-label="Kontrol banner">
        <button type="button" data-banner-prev aria-label="Banner sebelumnya">&larr;</button>
        <div class="beranda-banner__indikator">
          @foreach ($bannerSlides as $banner)
            <button
              type="button"
              data-banner-go="{{ $loop->index }}"
              aria-label="Tampilkan banner {{ $loop->iteration }}"
              @if ($loop->first) aria-current="true" @endif
            ></button>
          @endforeach
        </div>
        <button type="button" data-banner-next aria-label="Banner berikutnya">&rarr;</button>
      </div>
    @endif
  </section>

  <section class="beranda-bagian" id="produk-layanan" aria-labelledby="judul-produk-layanan">
    <header class="beranda-kepala">
      <div>
        <p class="beranda-eyebrow">Layanan Bank BBJ</p>
        <h2 id="judul-produk-layanan">Produk dan layanan</h2>
        <p>Pilih layanan yang sesuai dengan kebutuhan Anda.</p>
      </div>
    </header>

    <div class="beranda-produk">
      @foreach ($layanan as $jenis => $item)
        <a class="beranda-produk__kartu beranda-produk__kartu--{{ $jenis }}" href="{{ $item['url'] }}">
          <div class="beranda-produk__gambar">
            <img src="{{ $item['gambar'] }}" alt="" loading="lazy">
            <span>{{ $item['nama'] }}</span>
          </div>
          <div class="beranda-produk__isi">
            <h3>{{ $item['judul'] }}</h3>
            <p>{{ $item['tagline'] }}</p>
            <span class="beranda-tautan">Lihat produk <span aria-hidden="true">&rarr;</span></span>
          </div>
        </a>
      @endforeach
    </div>
  </section>

  <section class="beranda-bagian beranda-bagian--lembut" aria-labelledby="judul-suku-bunga">
    <header class="beranda-kepala beranda-suku-bunga__kepala">
      <div>
        <p class="beranda-eyebrow">Informasi simpanan</p>
        <h2 id="judul-suku-bunga">Suku Bunga Produk dan Deposito</h2>
        <p>Periode berlaku: 1 November 2026 - 31 Januari 2026.<br> Semua tingkat bunga mengikuti ketentuan & penjaminan LPS</p>
      </div>
      <div class="beranda-suku-bunga__aksi">
        <span class="beranda-lps"><span aria-hidden="true">✓<a href="https://lps.go.id/" target="_blank" rel="noopener noreferrer">
        </span> Jaminan: LPS</span></a>
        <a class="beranda-tautan" href="/deposito">Informasi deposito <span aria-hidden="true">&rarr;</span></a>
      </div>
    </header>

    @if ($sukuBunga->isNotEmpty())
      <div class="laporan__tabel-kartu beranda-tabel-kartu">
        <div class="laporan__gulir" tabindex="0" aria-label="Tabel suku bunga produk simpanan, geser ke samping untuk melihat seluruh kolom">
          <table class="tabel-laporan beranda-tabel-bunga">
            <caption class="sr-only">Produk, tenor, dan suku bunga produk simpanan per tahun</caption>
            <thead>
              <tr>
                <th scope="col">Produk</th>
                <th scope="col">Tenor</th>
                <th scope="col">Suku Bunga (% / Thn)</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($sukuBunga as $baris)
                <tr>
                  <th scope="row" class="beranda-tabel-bunga__produk">
                    <span>{{ $baris->produk->judul }}</span>
                  </th>
                  <td>{{ $baris->tenor_bulan }} Bulan</td>
                  <td><strong>{{ \App\Models\SukuBungaDeposito::persen($baris->bunga) }}</strong></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <p class="beranda-catatan">Suku bunga dapat berubah sewaktu-waktu sesuai ketentuan bank. {{ config('deposito.lps_catatan') }}</p>
    @else
      <p class="beranda-kosong">Informasi suku bunga sedang diperbarui.</p>
    @endif
  </section>

  <section class="beranda-bagian" aria-labelledby="judul-lelang">
    <header class="beranda-kepala">
      <div>
        <p class="beranda-eyebrow">Penawaran properti</p>
        <h2 id="judul-lelang">Lelang dan AYDA</h2>
        <p>Lihat pilihan jaminan yang sedang ditawarkan.</p>
      </div>
      <a class="beranda-tautan" href="/lelang">Lihat semua <span aria-hidden="true">&rarr;</span></a>
    </header>

    @if ($lelang->isNotEmpty())
      <div class="beranda-carousel" data-drag-scroll tabindex="0" aria-label="Kartu lelang dan AYDA, geser menggunakan mouse atau sentuhan">
        @foreach ($lelang as $item)
          @include('partials.kartu-lelang', ['lelang' => $item])
        @endforeach
      </div>
    @else
      <p class="beranda-kosong">Belum ada informasi lelang yang ditayangkan.</p>
    @endif
  </section>

  <section class="beranda-bagian beranda-bagian--video" aria-labelledby="judul-video">
    <header class="beranda-kepala beranda-kepala--tengah">
      <div>
        <p class="beranda-eyebrow">Video Bank BBJ</p>
        <h2 id="judul-video">Channel YouTube</h2>
        <p>Saksikan berbagai informasi, edukasi keuangan, dan kegiatan Bank BBJ melalui kanal YouTube resmi kami. Dapatkan insight seputar produk, layanan, dan inovasi terbaru untuk kemudahan transaksi Anda. </p>
      </div>
    </header>

    @if ($videoYoutube->isNotEmpty())
      @php($videoUtama = $videoYoutube->first())
      <div class="beranda-video" data-video-utama>
        <div class="beranda-video__pemutar">
          <iframe
            data-video-frame
            src="{{ $videoUtama->embed_url }}"
            title="{{ $videoUtama->judul ?: 'Video Bank BBJ 1' }}"
            loading="lazy"
            referrerpolicy="strict-origin-when-cross-origin"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen
          ></iframe>
        </div>

        <div class="beranda-video__sisi">
          <p class="beranda-video__label">
            <span>Daftar putar</span>
            <span>{{ $videoYoutube->count() }} video</span>
          </p>

          <div class="beranda-video__daftar" data-video-daftar aria-label="Daftar putar video Bank BBJ">
            @foreach ($videoYoutube as $video)
              @php($judulVideo = $video->judul ?: 'Video Bank BBJ '.$loop->iteration)
              <article
                class="beranda-video__item {{ $loop->first ? 'is-aktif' : '' }}"
                data-video-item
                data-video-src="{{ $video->embed_url }}"
                data-video-judul="{{ $judulVideo }}"
              >
                <button
                  type="button"
                  class="beranda-video__pilih"
                  data-video-play
                  aria-label="Putar {{ $judulVideo }}"
                  aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                >
                  <span class="beranda-video__gambar">
                    @if ($video->thumbnail_url)
                      <img src="{{ $video->thumbnail_url }}" alt="" width="320" height="180" loading="lazy">
                    @endif
                    <span class="beranda-video__putar" aria-hidden="true"></span>
                  </span>
                </button>

                <span class="beranda-video__teks">
                  <strong>{{ $judulVideo }}</strong>
                  <a
                    class="beranda-video__tautan"
                    href="{{ $video->url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                  >
                    Tonton di YouTube
                    <span aria-hidden="true">&#8599;</span>
                  </a>
                </span>
              </article>
            @endforeach
          </div>
        </div>
      </div>
    @else
      <p class="beranda-kosong beranda-kosong--video">Video akan tampil setelah tautan ditambahkan melalui menu Video YouTube di admin.</p>
    @endif
  </section>

  <section class="beranda-bagian" aria-labelledby="judul-berita">
    <header class="beranda-kepala">
      <div>
        <p class="beranda-eyebrow">Kabar terbaru</p>
        <h2 id="judul-berita">Berita dan kegiatan</h2>
        <p>Ikuti informasi dan kegiatan terbaru Bank BBJ.</p>
      </div>
      <a class="beranda-tautan" href="/berita">Lihat semua berita <span aria-hidden="true">&rarr;</span></a>
    </header>

    @if ($berita->isNotEmpty())
      <div class="beranda-carousel" data-drag-scroll tabindex="0" aria-label="Kartu berita dan kegiatan, geser menggunakan mouse atau sentuhan">
        @foreach ($berita as $item)
          @include('partials.kartu-berita', ['berita' => $item])
        @endforeach
      </div>
    @else
      <p class="beranda-kosong">Belum ada berita dan kegiatan yang ditayangkan.</p>
    @endif
  </section>

  <section class="faq-ringkas beranda-bagian" id="faq" aria-labelledby="judul-faq-beranda">
    <header class="beranda-kepala beranda-kepala--tengah">
      <div>
        <p class="beranda-eyebrow">Bantuan dan informasi</p>
        <h2 id="judul-faq-beranda">Pertanyaan tentang Bank BBJ</h2>
        <p>Informasi singkat tentang Bank BBJ, BPR, dan produk layanan.</p>
      </div>
    </header>

    @foreach ($faq as $item)
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

    <p class="faq-ringkas__tautan">
      <a class="ppid__tombol" href="/faq">Lihat semua FAQ</a>
    </p>
  </section>

  @include('partials.popup-promo')
@endsection
