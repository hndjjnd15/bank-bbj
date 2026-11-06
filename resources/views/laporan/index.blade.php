@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('description', 'Laporan keuangan triwulan dan tahunan yang dapat diunduh.')

@section('content')
  @php
    $bank = config('pengaduan.nama_bank');
    $kolom = \App\Models\LaporanTahunan::KOLOM;
  @endphp

  <div class="laporan" data-laporan>
    <header class="laporan__kepala">
      <h1>Laporan Keuangan {{ $bank }}</h1>
      <p>Pilih jenis laporan keuangan yang ingin Anda lihat.</p>

      <div class="laporan__tab" role="tablist" aria-label="Jenis laporan keuangan">
        <button type="button" role="tab" id="tab-triwulan" aria-controls="panel-triwulan" data-tab="triwulan"
                aria-selected="{{ $tab === 'triwulan' ? 'true' : 'false' }}" tabindex="{{ $tab === 'triwulan' ? '0' : '-1' }}"
                @class(['laporan__tombol', 'aktif' => $tab === 'triwulan'])>Laporan Triwulan</button>
        <button type="button" role="tab" id="tab-tahunan" aria-controls="panel-tahunan" data-tab="tahunan"
                aria-selected="{{ $tab === 'tahunan' ? 'true' : 'false' }}" tabindex="{{ $tab === 'tahunan' ? '0' : '-1' }}"
                @class(['laporan__tombol', 'aktif' => $tab === 'tahunan'])>Laporan Tahunan</button>
      </div>
    </header>

    {{-- ===== Laporan Triwulan ===== --}}
    <section id="panel-triwulan" role="tabpanel" aria-labelledby="tab-triwulan" data-panel="triwulan" @if ($tab !== 'triwulan') hidden @endif>
      @forelse ($triwulan as $tahun => $periodes)
        <div class="laporan__tahun">
          <h2>Laporan Triwulan {{ $tahun }}</h2>

          @foreach ($periodes as $lap)
            <details class="akordeon">
              <summary>
                <span>Periode: {{ $lap->nama_periode }} {{ $lap->tahun }}</span>
                <span class="akordeon__panah" aria-hidden="true">&#9660;</span>
              </summary>

              <div class="akordeon__isi">
                @forelse ($lap->berkas_siap as $berkas)
                  <div class="berkas">
                    <span class="berkas__jenis" aria-hidden="true">{{ $berkas['ekstensi'] }}</span>
                    <span class="berkas__judul">{{ $berkas['judul'] }}</span>
                    <a class="btn-unduh" href="{{ $berkas['url'] }}" target="_blank" rel="noopener" download>Download</a>
                  </div>
                @empty
                  <p class="berkas__kosong">Belum ada berkas untuk periode ini.</p>
                @endforelse
              </div>
            </details>
          @endforeach
        </div>
      @empty
        <p class="laporan__kosong">Belum ada laporan triwulan.</p>
      @endforelse
    </section>

    {{-- ===== Laporan Tahunan ===== --}}
    <section id="panel-tahunan" role="tabpanel" aria-labelledby="tab-tahunan" data-panel="tahunan" @if ($tab !== 'tahunan') hidden @endif>
      <div class="laporan__tahun">
        <h2>Laporan Tahunan</h2>
        <p class="laporan__ket">Unduh laporan tahunan, laporan tata kelola, dan laporan keuangan berkelanjutan {{ $bank }}.</p>

        <div class="laporan__tabel-kartu">
          <div class="laporan__gulir">
            <table class="tabel-laporan">
              <thead>
                <tr>
                  <th scope="col">Tahun</th>
                  @foreach ($kolom as $judul)
                    <th scope="col">{{ $judul }}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @forelse ($tahunan as $baris)
                  <tr>
                    <th scope="row" class="tabel-laporan__tahun">{{ $baris->tahun }}</th>
                    @foreach ($kolom as $namaKolom => $judul)
                      <td>
                        @if ($url = $baris->urlFile($namaKolom))
                          <a class="btn-unduh" href="{{ $url }}" target="_blank" rel="noopener" download
                             aria-label="Download {{ $judul }} {{ $baris->tahun }}">Download</a>
                        @else
                          <span class="kosong">Tidak Ada Data</span>
                        @endif
                      </td>
                    @endforeach
                  </tr>
                @empty
                  <tr><td colspan="{{ count($kolom) + 1 }}">Belum ada laporan tahunan.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
