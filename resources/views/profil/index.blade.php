@extends('layouts.app')

@section('title', 'Profil Perusahaan')
@section('description', 'Sejarah, nilai perusahaan, struktur permodalan, visi dan misi, serta pengurus ' . config('pengaduan.nama_bank') . '.')

@section('content')
  @php
    $bank = config('pengaduan.nama_bank');

    // Tiga nilai perusahaan (ubah teksnya di sini)
    $nilai = [
      'AMANAH' => [
        'Dapat dipercaya dan mematuhi ketentuan perusahaan.',
        'Melaksanakan fungsi dan tanggung jawab secara profesional.',
      ],
      'KUAT' => [
        'Menjadikan perusahaan profit, maju, dan bernilai positif.',
        'Fokus pada nasabah dan memberikan solusi terbaik.',
      ],
      'PEDULI' => [
        'Memberi nilai tambah pada lingkungan sekitar.',
        'Menjunjung tinggi nilai kemanusiaan dan menjadi teladan dalam bermasyarakat.',
      ],
    ];
  @endphp

  <div class="profil">
    <header class="profil__kepala">
      <p class="wbs__label">PROFIL PERUSAHAAN</p>
      <h1>Tentang <span class="wbs__sorot">{{ $bank }}</span></h1>
    </header>

    {{-- ===== Sejarah: teks kiri, foto gedung kanan ===== --}}
    <section class="profil__bagian profil__dua" aria-labelledby="judul-sejarah">
      <div class="profil__teks">
        <h2 id="judul-sejarah" class="profil__judul">Sejarah {{ $bank }}</h2>
        @if ($profil && filled($profil->sejarah))
          <div class="{{ \App\Models\ProfilPerusahaan::kelasRata($profil->sejarah_rata) }}">
            @foreach ($profil->sejarah_paragraf as $paragraf)
              <p>{!! nl2br(e($paragraf)) !!}</p>
            @endforeach
          </div>
        @else
          <p>Sejarah perusahaan belum diisi.</p>
        @endif
      </div>

      <div class="profil__foto">
        @if ($profil && $profil->foto_gedung_url)
          <img src="{{ $profil->foto_gedung_url }}" alt="Gedung kantor {{ $bank }}" loading="lazy">
        @else
          <div class="foto-kosong"><img src="{{ asset('images/logo.svg') }}" alt=""></div>
        @endif
      </div>
    </section>

    {{-- ===== Tagline dan tiga nilai ===== --}}
    <section class="profil__bagian" aria-labelledby="judul-nilai">
      <h2 id="judul-nilai" class="profil__tagline">
        <span>Amanah</span><i aria-hidden="true">&middot;</i><span>Kuat</span><i aria-hidden="true">&middot;</i><span>Peduli</span>
      </h2>

      <div class="nilai-grid">
        @foreach ($nilai as $judul => $poin)
          <article class="nilai">
            <h3>{{ $judul }}</h3>
            <ul class="daftar-poin">
              @foreach ($poin as $p)
                <li>{{ $p }}</li>
              @endforeach
            </ul>
          </article>
        @endforeach
      </div>
    </section>

    {{-- ===== Struktur permodalan ===== --}}
    <section class="profil__bagian" aria-labelledby="judul-modal">
      <h2 id="judul-modal" class="profil__judul profil__judul--tengah">Struktur Permodalan {{ $bank }}</h2>

      <div class="laporan__tabel-kartu">
        <div class="laporan__gulir">
          <table class="tabel-laporan tabel-saham">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Pemegang Saham</th>
                <th scope="col">Modal Dasar</th>
                <th scope="col">Persentase Kepemilikan</th>
                <th scope="col">Nilai Modal Disetor</th>
                <th scope="col">Persentase Modal Disetor</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($pemegangSaham as $i => $saham)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td class="kiri">{{ $saham->nama }}</td>
                  <td class="kanan">{{ \App\Models\PemegangSaham::rupiah($saham->modal_dasar) }}</td>
                  <td>{{ \App\Models\PemegangSaham::persen($saham->modal_dasar, $totalDasar) }}</td>
                  <td class="kanan">{{ \App\Models\PemegangSaham::rupiah($saham->modal_disetor) }}</td>
                  <td>{{ \App\Models\PemegangSaham::persen($saham->modal_disetor, $totalDisetor) }}</td>
                </tr>
              @empty
                <tr><td colspan="6">Data permodalan belum diisi.</td></tr>
              @endforelse
            </tbody>
            @if ($pemegangSaham->count())
              <tfoot>
                <tr>
                  <th scope="row" colspan="2" class="kiri">Total</th>
                  <td class="kanan">{{ \App\Models\PemegangSaham::rupiah($totalDasar) }}</td>
                  <td>100%</td>
                  <td class="kanan">{{ \App\Models\PemegangSaham::rupiah($totalDisetor) }}</td>
                  <td>100%</td>
                </tr>
              </tfoot>
            @endif
          </table>
        </div>
      </div>
    </section>

    {{-- ===== Visi (kiri) dan Misi (kanan) ===== --}}
    <section class="profil__bagian" aria-labelledby="judul-visimisi">
      <h2 id="judul-visimisi" class="profil__judul profil__judul--tengah">Visi dan Misi</h2>

      <div class="visimisi">
        <article class="visimisi__kartu">
          <h3>Visi</h3>
          @if ($profil && filled($profil->visi))
            <p class="visimisi__visi {{ \App\Models\ProfilPerusahaan::kelasRata($profil->visi_rata) }}">{!! nl2br(e($profil->visi)) !!}</p>
          @else
            <p>Visi belum diisi.</p>
          @endif
        </article>

        <article class="visimisi__kartu">
          <h3>Misi</h3>
          @if ($profil && count($profil->misi_poin))
            <ul class="daftar-poin {{ \App\Models\ProfilPerusahaan::kelasRata($profil->misi_rata) }}">
              @foreach ($profil->misi_poin as $poin)
                <li>{{ $poin }}</li>
              @endforeach
            </ul>
          @else
            <p>Misi belum diisi.</p>
          @endif
        </article>
      </div>
    </section>

    {{-- ===== Pengurus: Direksi lebih dulu, lalu Dewan Komisaris ===== --}}
    <section class="profil__bagian" aria-labelledby="judul-pengurus">
      <h2 id="judul-pengurus" class="profil__judul profil__judul--tengah">Pengurus PT BPR Bogor Jabar (Perseroda)</h2>

      @php $adaPengurus = false; @endphp
      @foreach (\App\Models\PengurusPerusahaan::KELOMPOK as $kunci => $judulKelompok)
        @if (isset($pengurus[$kunci]) && $pengurus[$kunci]->count())
          @php $adaPengurus = true; @endphp
          <h3 class="pengurus__kelompok">{{ $judulKelompok }}</h3>

          <div @class(['pengurus-grid', 'pengurus-grid--satu' => $pengurus[$kunci]->count() === 1])>
            @foreach ($pengurus[$kunci] as $orang)
              <article class="pengurus-kartu">
                <div @class(['pengurus-kartu__foto', 'pengurus-kartu__foto--kosong' => ! $orang->foto_url])>
                  @if ($orang->foto_url)
                    <img src="{{ $orang->foto_url }}" alt="{{ $orang->nama }}" loading="lazy">
                  @else
                    <span class="pengurus-kartu__inisial" aria-hidden="true">{{ $orang->inisial }}</span>
                  @endif
                </div>

                <div class="pengurus-kartu__isi">
                  <span class="pengurus-kartu__jabatan">{{ $orang->jabatan }}</span>
                  <h4>{{ $orang->nama }}</h4>

                  @if (filled($orang->latar_belakang))
                    <section>
                      <h5>Latar Belakang</h5>
                      <p>{!! nl2br(e($orang->latar_belakang)) !!}</p>
                    </section>
                  @endif

                  @if (count($orang->pendidikan_poin))
                    <section>
                      <h5>Pendidikan</h5>
                      <ul>
                        @foreach ($orang->pendidikan_poin as $jenjang)
                          <li>{{ $jenjang }}</li>
                        @endforeach
                      </ul>
                    </section>
                  @endif
                </div>
              </article>
            @endforeach
          </div>
        @endif
      @endforeach

      @unless ($adaPengurus)
        <p class="laporan__kosong">Data pengurus belum diisi.</p>
      @endunless
    </section>

    {{-- ===== Struktur organisasi (klik untuk memperbesar) ===== --}}
    @if ($profil && $profil->foto_struktur_url)
      <section class="profil__bagian" aria-labelledby="judul-struktur">
        <h2 id="judul-struktur" class="profil__judul profil__judul--tengah">Struktur Organisasi</h2>

        {{-- data-galeri memakai pop-up foto yang sama dengan galeri lelang dan berita --}}
        <div class="galeri galeri--struktur" data-galeri>
          <div class="galeri__utama">
            <img src="{{ $profil->foto_struktur_url }}" alt="Struktur organisasi {{ $bank }}">
          </div>
        </div>
        <p class="profil__petunjuk">Klik gambar untuk memperbesar.</p>
      </section>
    @endif
  </div>
@endsection
