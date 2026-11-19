@extends('layouts.app')

@section('title', $produk->judul . ' | Produk Tabungan')
@section('description', \Illuminate\Support\Str::limit($produk->tagline ?: strip_tags($produk->deskripsi), 155))

@section('content')
  @php($flyers = $produk->flyer_urls)

  <nav class="remah" aria-label="Jejak halaman">
    <a href="/">Beranda</a>
    <span><a href="{{ route('tabungan.index') }}">Produk Tabungan</a></span>
    <span>{{ $produk->judul }}</span>
  </nav>

  <div class="kredit-layout tabungan-layout">
    <main class="kredit-detail tabungan-detail">
      <header class="kredit-detail__kepala">
        <p class="wbs__label">PRODUK TABUNGAN</p>
        <h1>{{ $produk->judul }}</h1>
        @if ($produk->tagline)
          <p class="produk__tagline">{{ $produk->tagline }}</p>
        @endif
      </header>

      @if (count($produk->deskripsi_paragraf))
        <div class="produk-deskripsi {{ \App\Support\Perataan::kelas($produk->deskripsi_rata) }}">
          @foreach ($produk->deskripsi_paragraf as $paragraf)
            <p class="produk__isi">{!! nl2br(e($paragraf)) !!}</p>
          @endforeach
        </div>
      @endif

      @if ($sukuBunga->isNotEmpty())
        <section class="produk__bagian" aria-labelledby="judul-bunga-tabungan">
          <h2 id="judul-bunga-tabungan" class="profil__judul">Suku Bunga {{ $produk->judul }}</h2>
          <div class="laporan__tabel-kartu">
            <div class="laporan__gulir">
              <table class="tabel-laporan">
                <caption class="sr-only">Suku bunga {{ $produk->judul }} per tahun berdasarkan tenor</caption>
                <thead>
                  <tr>
                    <th scope="col">Tenor</th>
                    <th scope="col">Suku Bunga (% / Thn)</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($sukuBunga as $baris)
                    <tr>
                      <th scope="row" class="tabel-laporan__tahun">{{ $baris->tenor_bulan }} bulan</th>
                      <td>{{ \App\Models\SukuBungaDeposito::persen($baris->bunga) }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <p class="produk__catatan">Suku bunga per tahun (p.a.) dan dapat berubah sewaktu-waktu sesuai ketentuan bank.</p>
        </section>
      @endif

      <div class="ppid__aksi kredit-detail__aksi">
        @if ($produk->simulasi_aktif)
          <a class="ppid__tombol" href="#kalkulator-tabungan">Simulasi Tabungan Berjangka</a>
        @endif
        @if ($produk->whatsapp_url)
          <a class="ppid__tombol ppid__tombol--garis" href="{{ $produk->whatsapp_url }}" target="_blank" rel="noopener">Hubungi via WhatsApp</a>
        @endif
        <a class="ppid__tombol ppid__tombol--garis" href="/jaringan-kantor">Cari Kantor Cabang</a>
      </div>

      @if (count($produk->persyaratan_poin) || count($produk->keunggulan_poin) || count($produk->ketentuan_poin))
        <section class="produk__bagian" aria-labelledby="judul-syarat-tabungan">
          <h2 id="judul-syarat-tabungan" class="profil__judul">Persyaratan dan Ketentuan</h2>
          <div class="visimisi tabungan-syarat">
            @if (count($produk->persyaratan_poin))
              <article class="visimisi__kartu">
                <h3>Persyaratan Berkas</h3>
                <ul class="daftar-poin">
                  @foreach ($produk->persyaratan_poin as $poin)
                    <li>{{ $poin }}</li>
                  @endforeach
                </ul>
              </article>
            @endif
            @if (count($produk->keunggulan_poin))
              <article class="visimisi__kartu">
                <h3>Persyaratan Khusus Produk</h3>
                <ul class="daftar-poin">
                  @foreach ($produk->keunggulan_poin as $poin)
                    <li>{{ $poin }}</li>
                  @endforeach
                </ul>
              </article>
            @endif
            @if (count($produk->ketentuan_poin))
              <article class="visimisi__kartu">
                <h3>Ketentuan Produk</h3>
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

      @if ($produk->simulasi_aktif)
        <section id="kalkulator-tabungan" class="produk__bagian" aria-labelledby="judul-kalkulator-tabungan">
          <h2 id="judul-kalkulator-tabungan" class="profil__judul">Simulasi {{ $produk->judul }}</h2>

          <div class="tabungan-kalkulator" data-kalkulator-tabungan
               data-produk-tabungan="{{ \Illuminate\Support\Str::lower($produk->slug.' '.$produk->judul) }}">
            <div class="tabungan-kalkulator__kontrol">
              <div>
                <label class="tabungan-kalkulator__label" for="tabungan-target">Target tabungan diterima</label>
                <div class="tabungan-kalkulator__nominal">
                  <span aria-hidden="true">Rp</span>
                  <input id="tabungan-target" data-tabungan-target type="text" value="10.000.000" inputmode="numeric" autocomplete="off">
                </div>
                <p class="tabungan-kalkulator__bantuan">Masukkan target dana yang ingin diterima saat jatuh tempo.</p>
              </div>

              <div>
                <label class="tabungan-kalkulator__label" for="tabungan-tenor">Pilih jangka waktu</label>
                <select id="tabungan-tenor" data-tabungan-tenor>
                </select>
                <p class="tabungan-kalkulator__bantuan">Batas maksimal paket: <strong data-tabungan-batas>Rp 100.000.000</strong></p>
              </div>
            </div>

            <div class="tabungan-kalkulator__hasil" aria-live="polite">
              <p>Perkiraan setoran per bulan</p>
              <strong data-tabungan-setoran>-</strong>
              <dl>
                <div><dt>Target saat jatuh tempo</dt><dd data-tabungan-hasil-target>-</dd></div>
                <div><dt>Jangka waktu</dt><dd data-tabungan-hasil-tenor>-</dd></div>
                <div><dt>Suku bunga</dt><dd data-tabungan-hasil-bunga>-</dd></div>
                <div><dt>Total perkiraan setoran</dt><dd data-tabungan-total-setoran>-</dd></div>
                <div><dt>Perkiraan bunga</dt><dd data-tabungan-bunga>-</dd></div>
              </dl>
              <p class="tabungan-kalkulator__pesan" data-tabungan-pesan hidden></p>
            </div>
          </div>

          @if ($sukuBunga->isNotEmpty())
            <script type="application/json" id="data-bunga-tabungan">{!! json_encode($jadwalBunga, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
          @endif

          <p class="produk__catatan">Simulasi menggunakan setoran rutin setiap akhir bulan dan perhitungan bunga majemuk bulanan. Hasil hanya perkiraan; perhitungan akhir mengikuti program, jadwal setoran, dan ketentuan Bank BBJ yang berlaku.</p>
        </section>
      @endif

      <section class="produk__bagian kredit-faq" aria-labelledby="judul-faq-tabungan">
        <h2 id="judul-faq-tabungan" class="profil__judul">FAQ Tabungan</h2>
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
          <p class="faq__kosong kredit-faq__kosong">Belum ada FAQ tabungan yang ditambahkan.</p>
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
    <section class="berita-lain kredit-lain" aria-labelledby="judul-tabungan-lain">
      <div class="berita-lain__kepala">
        <h2 id="judul-tabungan-lain">Produk tabungan lainnya</h2>
        <a href="{{ route('tabungan.index') }}">Lihat semua &rarr;</a>
      </div>
      <p class="berita-lain__petunjuk" aria-hidden="true">Geser untuk melihat produk lainnya &rarr;</p>

      <div class="produk-slider" data-produk-slider>
        <button class="produk-slider__tombol" type="button" data-produk-prev aria-label="Geser ke produk sebelumnya" disabled>&larr;</button>
        <div class="produk-slider__track" data-produk-track tabindex="0" aria-label="Produk tabungan lainnya">
          @foreach ($lainnya as $item)
            @include('partials.kartu-produk-tabungan', ['produk' => $item])
          @endforeach
        </div>
        <button class="produk-slider__tombol" type="button" data-produk-next aria-label="Geser ke produk berikutnya">&rarr;</button>
      </div>
    </section>
  @endif
@endsection
