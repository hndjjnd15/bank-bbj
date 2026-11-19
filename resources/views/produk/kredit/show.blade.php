@extends('layouts.app')

@section('title', $produk->judul . ' | Produk Kredit')
@section('description', \Illuminate\Support\Str::limit($produk->tagline ?: strip_tags($produk->deskripsi), 155))

@section('content')
  @php($flyers = $produk->flyer_urls)

  <nav class="remah" aria-label="Jejak halaman">
    <a href="/">Beranda</a>
    <span><a href="{{ route('kredit.index') }}">Produk Kredit</a></span>
    <span>{{ $produk->judul }}</span>
  </nav>

  <div class="kredit-layout">
    <main class="kredit-detail">
      <header class="kredit-detail__kepala">
        <p class="wbs__label">PRODUK KREDIT</p>
        <h1>{{ $produk->judul }}</h1>
        @if ($produk->tagline)
          <p class="produk__tagline">{{ $produk->tagline }}</p>
        @endif
      </header>

      @if (filled($produk->deskripsi))
        <div class="produk-deskripsi prose-deskripsi {{ \App\Support\Perataan::kelas($produk->deskripsi_rata) }}">
          {!! \App\Support\HtmlDeskripsiProduk::bersihkan($produk->deskripsi) !!}
        </div>
      @endif

      <div class="ppid__aksi kredit-detail__aksi">
        @if ($produk->simulasi_aktif && $produk->suku_bunga_kredit !== null)
          <a class="ppid__tombol" href="#kalkulator-kredit">Simulasi Cicilan</a>
        @endif
        @if ($produk->whatsapp_url)
          <a class="ppid__tombol ppid__tombol--garis" href="{{ $produk->whatsapp_url }}" target="_blank" rel="noopener">Hubungi via WhatsApp</a>
        @endif
        <a class="ppid__tombol ppid__tombol--garis" href="/jaringan-kantor">Cari Kantor Cabang</a>
      </div>

      @if (count($produk->persyaratan_poin) || count($produk->ketentuan_poin))
        <section class="produk__bagian" aria-labelledby="judul-syarat-kredit">
          <h2 id="judul-syarat-kredit" class="profil__judul">Persyaratan dan Ketentuan</h2>
          <div class="visimisi">
            @if (count($produk->persyaratan_poin))
              <article class="visimisi__kartu">
                <h3>Persyaratan</h3>
                <ul class="daftar-poin">
                  @foreach ($produk->persyaratan_poin as $poin)
                    <li>{{ $poin }}</li>
                  @endforeach
                </ul>
              </article>
            @endif
            @if (count($produk->ketentuan_poin))
              <article class="visimisi__kartu">
                <h3>Ketentuan</h3>
                <ul class="daftar-poin">
                  @foreach ($produk->ketentuan_poin as $poin)
                    <li>{{ $poin }}</li>
                  @endforeach
                </ul>
              </article>
            @endif
          </div>
        </section>
      @endif

      @if ($produk->simulasi_aktif && $produk->suku_bunga_kredit !== null)
        <section id="kalkulator-kredit" class="produk__bagian" aria-labelledby="judul-kalkulator-kredit">
          <h2 id="judul-kalkulator-kredit" class="profil__judul">Simulasi Cicilan {{ $produk->judul }}</h2>

          <div class="kalkulator kredit-kalkulator" data-kalkulator-kredit
               data-suku-bunga="{{ $produk->suku_bunga_kredit }}"
               data-biaya-admin-persen="{{ $produk->biaya_admin_persen ?? 0 }}"
               data-nominal-min="1000000"
               data-nominal-maks="300000000"
               data-nominal-langkah="1000000">
            <div class="kredit-kalkulator__kontrol">
              <div class="kredit-kalkulator__bagian">
                <label class="kredit-kalkulator__label" for="kredit-nominal">Jumlah pinjaman</label>
                <div class="kredit-kalkulator__pilih-nominal">
                  <button type="button" data-kredit-nominal-kurang aria-label="Kurangi nominal pinjaman">&minus;</button>
                  <output for="kredit-nominal" data-kredit-nominal-tampil>Rp 10.000.000</output>
                  <button type="button" data-kredit-nominal-tambah aria-label="Tambah nominal pinjaman">+</button>
                </div>
                <input id="kredit-nominal" class="kredit-kalkulator__slider" data-kredit-nominal
                       type="range" min="1000000" max="300000000" step="1000000" value="10000000"
                       aria-label="Geser jumlah pinjaman">
                <div class="kredit-kalkulator__batas">
                  <span>Rp 1 juta</span>
                  <span>Rp 300 juta</span>
                </div>
              </div>

              <div class="kredit-kalkulator__bagian">
                <span class="kredit-kalkulator__label">Jangka waktu</span>
                <div class="kredit-kalkulator__tenor" role="group" aria-label="Pilih jangka waktu">
                  @foreach ([3, 6, 12, 24, 36, 48, 60] as $tenor)
                    <button type="button" data-kredit-tenor="{{ $tenor }}"
                            aria-pressed="{{ $tenor === 12 ? 'true' : 'false' }}">
                      {{ $tenor }}Bln
                    </button>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="kredit-kalkulator__hasil" aria-live="polite">
              <p class="kredit-kalkulator__pesan" data-kredit-pesan hidden></p>
              <div data-kredit-hasil>
                <p class="kredit-kalkulator__label">Perkiraan cicilan per bulan</p>
                <p class="kredit-kalkulator__total" data-kredit-cicilan>-</p>
                <p class="kredit-kalkulator__tenor-pilihan" data-kredit-tenor-terpilih>12 bulan</p>
                <dl class="kredit-kalkulator__rincian">
                  <div><dt>Suku bunga</dt><dd>{{ number_format((float) $produk->suku_bunga_kredit, 2, ',', '.') }}% p.a.</dd></div>
                  <div><dt>Pencairan bersih</dt><dd data-kredit-pencairan>-</dd></div>
                  <div><dt>Total bunga</dt><dd data-kredit-bunga>-</dd></div>
                  <div><dt>Biaya administrasi ({{ number_format((float) ($produk->biaya_admin_persen ?? 0), 2, ',', '.') }}%)</dt><dd data-kredit-admin>-</dd></div>
                  <div><dt>Total pembayaran</dt><dd data-kredit-total>-</dd></div>
                </dl>
              </div>
            </div>
          </div>

          <p class="produk__catatan">Biaya administrasi dipotong saat pencairan. Cicilan disimulasikan dari dana bersih yang diterima dengan metode anuitas. Rentang nominal simulasi Rp 1 juta–Rp 300 juta dan hasil bukan persetujuan kredit; ketentuan akhir mengikuti kebijakan bank.</p>
        </section>
      @endif

      <section class="produk__bagian kredit-faq" aria-labelledby="judul-faq-kredit">
        <h2 id="judul-faq-kredit" class="profil__judul">FAQ Kredit</h2>
        @forelse ($faq as $item)
            <details class="akordeon">
              <summary>
                <span>{{ $item->tanya }}</span>
                <span class="akordeon__panah" aria-hidden="true">&#9660;</span>
              </summary>
              <div class="akordeon__isi">
                <p>{!! nl2br(e($item->jawab)) !!}</p>
              </div>
            </details>
        @empty
          <p class="faq__kosong kredit-faq__kosong">Belum ada FAQ kredit yang ditambahkan.</p>
        @endforelse
      </section>
    </main>

    <aside class="kredit-brosur-kolom" aria-label="Flyer dan brosur">
      <div class="sidebar sidebar--flyer kredit-brosur">
        <section class="kredit-brosur__kartu">
          <h2>Flyer dan Brosur {{ $produk->judul }}</h2>
          @if (count($flyers))
            <div class="galeri galeri--struktur galeri--flyer" data-galeri>
              <div class="galeri__utama">
                <img src="{{ $flyers[0] }}" alt="Flyer {{ $produk->judul }}">
              </div>
              @if (count($flyers) > 1)
                <div class="galeri__thumb">
                  @foreach ($flyers as $i => $flyer)
                    <button type="button" data-foto="{{ $flyer }}"
                            aria-label="Tampilkan flyer {{ $i + 1 }}"
                            aria-current="{{ $i === 0 ? 'true' : 'false' }}">
                      <img src="{{ $flyer }}" alt="">
                    </button>
                  @endforeach
                </div>
              @endif
            </div>
            <p class="profil__petunjuk">Klik gambar untuk memperbesar.</p>
          @else
            <div class="kredit-brosur__kosong">
              <img src="{{ asset('images/logo.svg') }}" alt="" aria-hidden="true">
              <p>Gambar flyer belum tersedia.</p>
            </div>
          @endif

          @if ($produk->brosur_url)
            <a class="tombol-aksi tombol-aksi--email" href="{{ $produk->brosur_url }}" target="_blank" rel="noopener" download>Unduh Brosur (PDF)</a>
          @endif
        </section>
      </div>
    </aside>
  </div>

  @if ($lainnya->isNotEmpty())
    <section class="berita-lain kredit-lain" aria-labelledby="judul-kredit-lain">
      <div class="berita-lain__kepala">
        <h2 id="judul-kredit-lain">Produk kredit lainnya</h2>
        <a href="{{ route('kredit.index') }}">Lihat semua &rarr;</a>
      </div>
      <p class="berita-lain__petunjuk" aria-hidden="true">Geser untuk melihat produk lainnya &rarr;</p>

      <div class="kredit-slider" data-produk-slider>
        <button class="kredit-slider__tombol" type="button" data-produk-prev aria-label="Geser ke produk sebelumnya" disabled>&larr;</button>
        <div class="kredit-slider__track" data-produk-track tabindex="0" aria-label="Produk kredit lainnya">
          @foreach ($lainnya as $item)
            @include('partials.kartu-produk-kredit', ['produk' => $item])
          @endforeach
        </div>
        <button class="kredit-slider__tombol" type="button" data-produk-next aria-label="Geser ke produk berikutnya">&rarr;</button>
      </div>
    </section>
  @endif
@endsection
