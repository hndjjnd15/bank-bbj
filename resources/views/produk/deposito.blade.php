@extends('layouts.app')

@section('title', $produk->judul)
@section('description', \Illuminate\Support\Str::limit($produk->tagline ?: strip_tags($produk->deskripsi), 155))

@section('content')
  @php
    $bank = config('pengaduan.nama_bank');
    $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $flyers = $produk->flyer_urls;
    // Kolom kanan hanya dipakai kalau ada flyer atau brosur yang bisa ditampilkan
    $adaFlyerBrosur = count($flyers) > 0 || filled($produk->brosur_url);
  @endphp

  {{-- Pratinjau saat link dibagikan ke WhatsApp dan sejenisnya --}}
  @push('meta')
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $produk->judul }} {{ $bank }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($produk->tagline ?: strip_tags($produk->deskripsi), 155) }}">
    <meta property="og:url" content="{{ url('/deposito') }}">
    @if ($produk->foto_url)
      <meta property="og:image" content="{{ url($produk->foto_url) }}">
    @endif
  @endpush

  <div class="produk">
    {{-- ===== Pengantar produk ===== --}}
    <section class="produk__hero">
      <div class="produk__teks">
        <p class="wbs__label">PRODUK SIMPANAN</p>
        <h1>{{ $produk->judul }} <span class="wbs__sorot">{{ $bank }}</span></h1>
        @if ($produk->tagline)
          <p class="produk__tagline">{{ $produk->tagline }}</p>
        @endif

        @if (count($produk->deskripsi_paragraf))
          <div class="{{ \App\Support\Perataan::kelas($produk->deskripsi_rata) }}">
            @foreach ($produk->deskripsi_paragraf as $paragraf)
              <p class="produk__isi">{!! nl2br(e($paragraf)) !!}</p>
            @endforeach
          </div>
        @endif

        <div class="ppid__aksi produk__aksi">
          <a class="ppid__tombol" href="#kalkulator">Hitung Simulasi</a>
          @if ($produk->brosur_url)
            <a class="ppid__tombol ppid__tombol--garis" href="{{ $produk->brosur_url }}" target="_blank" rel="noopener" download>Unduh Brosur</a>
          @endif
        </div>
      </div>

      <div class="produk__foto">
        @if ($produk->foto_url)
          <img src="{{ $produk->foto_url }}" alt="{{ $produk->judul }}" loading="lazy">
        @else
          <div class="foto-kosong"><img src="{{ asset('images/logo.svg') }}" alt=""></div>
        @endif
      </div>
    </section>

    {{-- ===== Ringkasan cepat ===== --}}
    <section class="produk__bagian produk__fakta" aria-label="Ringkasan produk">
      @if ($produk->setoran_minimal)
        <div><span>Setoran minimal</span><strong>{{ $rp($produk->setoran_minimal) }}</strong></div>
      @endif
      @if ($ringkasan['tenor_terpendek'])
        <div><span>Jangka waktu</span><strong>{{ $ringkasan['tenor_terpendek'] }} - {{ $ringkasan['tenor_terpanjang'] }} bulan</strong></div>
      @endif
      @if ($ringkasan['bunga_tertinggi'])
        <div><span>Suku bunga hingga</span><strong>{{ \App\Models\SukuBungaDeposito::persen($ringkasan['bunga_tertinggi']) }} p.a.</strong></div>
      @endif
      <div><span>Penjaminan</span><strong>Dijamin LPS</strong></div>
    </section>

    {{-- ===== Isi utama (kiri) + flyer dan brosur (kanan, ikut turun saat di-scroll) ===== --}}
    <div @class(['produk-layout', 'produk-layout--tunggal' => ! $adaFlyerBrosur])>
      <div class="produk-utama">
      {{-- ===== Keunggulan ===== --}}
      @if (count($produk->keunggulan_poin))
        <section class="produk__bagian" aria-labelledby="judul-keunggulan">
          <h2 id="judul-keunggulan" class="profil__judul profil__judul--tengah">Keunggulan {{ $produk->judul }}</h2>
          <div class="produk__keunggulan">
            @foreach ($produk->keunggulan_poin as $poin)
              <div class="produk__unggul">
                <span aria-hidden="true">&#10003;</span>
                <p>{{ $poin }}</p>
              </div>
            @endforeach
          </div>
        </section>
      @endif

      {{-- ===== Tabel suku bunga ===== --}}
      <section class="produk__bagian" aria-labelledby="judul-bunga">
        <h2 id="judul-bunga" class="profil__judul profil__judul--tengah">Suku Bunga {{ $produk->judul }}</h2>

        @if ($baris->count())
          <div class="laporan__tabel-kartu">
            <div class="laporan__gulir">
              <table class="tabel-laporan tabel-bunga">
                <caption class="sr-only">Suku bunga {{ $produk->judul }} per tahun berdasarkan tenor</caption>
                <thead>
                  <tr>
                    <th scope="col">Tenor</th>
                    <th scope="col">Suku Bunga (% / Thn)</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($baris as $r)
                    <tr>
                      <th scope="row" class="tabel-laporan__tahun">{{ $r->tenor_bulan }} bulan</th>
                      <td>{{ \App\Models\SukuBungaDeposito::persen($r->bunga) }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <p class="produk__catatan">Suku bunga per tahun (p.a.). Suku bunga dapat berubah sewaktu-waktu sesuai ketentuan bank. {{ config('deposito.lps_catatan') }}</p>
        @else
          <p class="laporan__kosong">Suku bunga belum diisi.</p>
        @endif
      </section>

      {{-- ===== Kalkulator ===== --}}
      @if ($baris->count())
        <section id="kalkulator" class="produk__bagian" aria-labelledby="judul-kalkulator">
          <h2 id="judul-kalkulator" class="profil__judul profil__judul--tengah">Kalkulator Simulasi Deposito</h2>

          <div class="kalkulator" data-kalkulator
               data-pajak="{{ config('deposito.pajak_persen') }}" data-batas="{{ config('deposito.batas_bebas_pajak') }}"
               data-minimal="{{ $ringkasan['nominal_terkecil'] }}">
            <form class="kalkulator__form" onsubmit="return false">
              <div class="form__baris">
                <label for="kal-nominal">Nominal penempatan</label>
                <div class="kalkulator__rp">
                  <span>Rp</span>
                  <input id="kal-nominal" data-nominal inputmode="numeric" autocomplete="off" value="{{ number_format(max(10000000, (int) $ringkasan['nominal_terkecil']), 0, ',', '.') }}">
                </div>
                @if ($ringkasan['nominal_terkecil'] > 0)
                  <small class="form__petunjuk">Minimal {{ $rp($ringkasan['nominal_terkecil']) }}</small>
                @endif
              </div>

              <div class="form__baris">
                <label for="kal-tenor">Jangka waktu</label>
                <select id="kal-tenor" data-tenor>
                  @foreach ($tenor as $t)
                    <option value="{{ $t }}">{{ $t }} bulan</option>
                  @endforeach
                </select>
              </div>

              <label class="setuju">
                <input type="checkbox" data-pakai-pajak checked>
                <span>Hitung dengan pajak penghasilan (PPh final {{ config('deposito.pajak_persen') }}%)</span>
              </label>
            </form>

            <div class="kalkulator__hasil" aria-live="polite">
              <p class="kalkulator__pesan" data-pesan hidden></p>

              <div data-hasil>
                <p class="kalkulator__label">Total yang diterima saat jatuh tempo</p>
                <p class="kalkulator__total" data-total>-</p>

                <dl class="kalkulator__rincian">
                  <div><dt>Suku bunga</dt><dd data-bunga>-</dd></div>
                  <div><dt>Bunga bruto</dt><dd data-bruto>-</dd></div>
                  <div><dt>Pajak bunga</dt><dd data-pajak>-</dd></div>
                  <div><dt>Bunga bersih</dt><dd data-bersih>-</dd></div>
                  <div><dt>Perkiraan bunga bersih per bulan</dt><dd data-perbulan>-</dd></div>
                </dl>
              </div>
            </div>
          </div>

          <script type="application/json" id="data-bunga">{!! json_encode($dataKalkulator, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>

          <p class="produk__catatan">{{ config('deposito.catatan_simulasi') }}</p>
        </section>
      @endif

      {{-- ===== Persyaratan dan ketentuan ===== --}}
      @if (count($produk->persyaratan_poin) || count($produk->ketentuan_poin))
        <section class="produk__bagian" aria-labelledby="judul-syarat">
          <h2 id="judul-syarat" class="profil__judul profil__judul--tengah">Persyaratan dan Ketentuan</h2>
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
      </div>

      @if ($adaFlyerBrosur)
        <aside class="sidebar-kolom" aria-label="Flyer dan brosur">
          <div class="sidebar sidebar--flyer">
            <div class="harga-kartu flyer-kartu">
              <small>Flyer dan Brosur</small>

              @if (count($flyers))
                {{-- data-galeri memakai pop-up foto yang sama dengan galeri lelang dan berita --}}
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
              @endif

              @if ($produk->brosur_url)
                <a class="tombol-aksi tombol-aksi--email" href="{{ $produk->brosur_url }}" target="_blank" rel="noopener" download>Unduh Brosur (PDF)</a>
              @endif
            </div>
          </div>
        </aside>
      @endif
    </div>

    {{-- ===== Pertanyaan umum (kiri) + ajakan menghubungi kantor (kanan) ===== --}}
    <div @class(['produk__bantuan', 'produk__bantuan--tunggal' => $faq->isEmpty()])>
      @if ($faq->isNotEmpty())
        <section class="produk__faq" aria-labelledby="judul-faq">
          <h2 id="judul-faq" class="profil__judul">Pertanyaan yang Sering Diajukan</h2>
          @foreach ($faq as $item)
            <details class="akordeon">
              <summary>
                <span>{{ $item->tanya }}</span>
                <span class="akordeon__panah" aria-hidden="true">&#9660;</span>
              </summary>
              <div class="akordeon__isi">
                <p>{!! nl2br(e($item->jawab)) !!}</p>
              </div>
            </details>
          @endforeach
        </section>
      @endif

      <section class="produk__ajakan">
        <h2>Tertarik membuka {{ strtolower($produk->judul) }}?</h2>
        <p>Hubungi kantor cabang terdekat atau kunjungi langsung untuk informasi dan pembukaan rekening.</p>
        <div class="ppid__aksi">
          <a class="ppid__tombol" href="/jaringan-kantor">Cari Kantor Terdekat</a>
          <a class="ppid__tombol ppid__tombol--garis" href="#kalkulator">Hitung Simulasi</a>
        </div>
      </section>
    </div>
  </div>
@endsection
