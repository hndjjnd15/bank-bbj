@extends('layouts.app')

@section('title', 'PPID')
@section('description', 'Layanan informasi publik: daftar informasi publik dan permohonan informasi melalui PPID.')

@section('content')
  @php
    $bank = config('pengaduan.nama_bank');
    $kontak = array_filter(config('ppid.kontak'));
  @endphp

  <div class="ppid">
    {{-- Kepala --}}
    <header class="wbs__kepala">
      <p class="wbs__label">PPID</p>
      <h1>Layanan <span class="wbs__sorot">Informasi Publik</span></h1>
      <p>Pejabat Pengelola Informasi dan Dokumentasi (PPID) {{ $bank }} melayani permintaan dan penyediaan informasi
         publik sesuai Undang-Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik.</p>
      <div class="ppid__aksi">
        <a class="ppid__tombol" href="/ppid/permohonan">Ajukan Permohonan Informasi</a>
        <a class="ppid__tombol ppid__tombol--garis" href="/ppid/informasi">Lihat Daftar Informasi</a>
      </div>
    </header>

    {{-- Tiga kategori informasi --}}
    <div class="ppid__kategori">
      @foreach (config('ppid.kategori') as $kunci => $label)
        <a class="ppid__kartu" href="/ppid/informasi?kategori={{ $kunci }}">
          <h2>{{ $label }}</h2>
          <p>
            @switch($kunci)
              @case('berkala')
                Informasi yang wajib diumumkan secara berkala, misalnya profil, laporan keuangan, dan laporan kinerja.
                @break
              @case('serta_merta')
                Informasi yang berkaitan dengan hajat hidup orang banyak dan ketertiban umum, diumumkan seketika.
                @break
              @default
                Informasi yang wajib tersedia setiap saat dan dapat diakses publik, misalnya daftar informasi dan prosedur layanan.
            @endswitch
          </p>
          <span class="ppid__jumlah">{{ $jumlah[$kunci] ?? 0 }} dokumen &rarr;</span>
        </a>
      @endforeach
    </div>

    {{-- Alur permohonan --}}
    <section class="ppid__bagian" aria-labelledby="judul-alur">
      <h2 id="judul-alur">Alur permohonan informasi</h2>
      <ol class="wbs__langkah ppid__alur">
        <li><span>1</span><div><strong>Isi formulir</strong><p>Lengkapi formulir permohonan informasi publik secara online dan sebutkan informasi yang dibutuhkan serta tujuannya.</p></div></li>
        <li><span>2</span><div><strong>Pencatatan</strong><p>Permohonan dicatat dan Anda menerima nomor registrasi.</p></div></li>
        <li><span>3</span><div><strong>Penelaahan</strong><p>PPID menelaah permohonan dan menyiapkan informasi yang diminta.</p></div></li>
        <li><span>4</span><div><strong>Tanggapan tertulis</strong><p>Pemberitahuan tertulis disampaikan paling lambat {{ config('ppid.tenggat_hari_kerja') }} hari kerja sejak permohonan diterima.</p></div></li>
      </ol>
    </section>

    {{-- Hak dan ketentuan waktu --}}
    <section class="ppid__bagian" aria-labelledby="judul-hak">
      <h2 id="judul-hak">Hak pemohon dan ketentuan waktu</h2>
      <ul class="daftar-poin">
        <li>Tanggapan tertulis atas permohonan diberikan paling lambat <strong>{{ config('ppid.tenggat_hari_kerja') }} hari kerja</strong> sejak permohonan diterima, dan dapat diperpanjang paling lama {{ config('ppid.perpanjangan_hari_kerja') }} hari kerja.</li>
        <li>Jika permohonan ditolak atau tidak ditanggapi sebagaimana mestinya, pemohon dapat mengajukan <strong>keberatan kepada atasan PPID</strong> paling lambat 30 hari kerja, dan atasan PPID menanggapinya paling lambat 30 hari kerja sejak keberatan diterima.</li>
        <li>Jika tanggapan atasan PPID belum memuaskan, pemohon dapat mengajukan <strong>penyelesaian sengketa informasi kepada Komisi Informasi</strong> paling lambat 14 hari kerja sejak menerima tanggapan atasan PPID.</li>
        <li>Informasi yang dikecualikan menurut ketentuan perundang-undangan tidak dapat diberikan.</li>
      </ul>
    </section>

    {{-- Kontak --}}
    <section class="ppid__bagian ppid__kontak" aria-labelledby="judul-kontak">
      <h2 id="judul-kontak">Kontak PPID</h2>
      @if ($kontak)
        <dl class="spek">
          @if (! empty($kontak['alamat'])) <div><dt>Alamat</dt><dd>{{ $kontak['alamat'] }}</dd></div> @endif
          @if (! empty($kontak['email'])) <div><dt>Email</dt><dd><a href="mailto:{{ $kontak['email'] }}">{{ $kontak['email'] }}</a></dd></div> @endif
          @if (! empty($kontak['telepon'])) <div><dt>Telepon</dt><dd><a href="tel:{{ preg_replace('/[^0-9+]/', '', $kontak['telepon']) }}">{{ $kontak['telepon'] }}</a></dd></div> @endif
          @if (! empty($kontak['jam'])) <div><dt>Jam layanan</dt><dd>{{ $kontak['jam'] }}</dd></div> @endif
        </dl>
      @else
        <p>Kontak PPID belum diatur.</p>
      @endif
    </section>
  </div>
@endsection
