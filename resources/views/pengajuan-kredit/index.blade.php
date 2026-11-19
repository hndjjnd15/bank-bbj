@extends('layouts.app')

@section('title', 'Formulir Pengajuan Kredit')
@section('description', 'Ajukan kredit Bank BBJ secara online. Lengkapi data pengajuan dan unggah dokumen pendukung dengan aman.')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/pengajuan-kredit.css') }}">
@endpush

@section('content')
  @php
    // Nilai lama dipakai untuk memulihkan bagian bersyarat setelah validasi gagal.
    $nilaiKeluarga = [];
    foreach ([
        'nama_pasangan', 'hubungan_pasangan', 'nik_pasangan', 'agama_pasangan',
        'tempat_lahir_pasangan', 'tanggal_lahir_pasangan', 'npwp_pasangan', 'hp_pasangan',
        'nama_orang_tua', 'hubungan_orang_tua', 'nik_orang_tua', 'agama_orang_tua',
        'tempat_lahir_orang_tua', 'tanggal_lahir_orang_tua', 'npwp_orang_tua', 'hp_orang_tua',
    ] as $kolom) {
        if (old($kolom) !== null) {
            $nilaiKeluarga[$kolom] = old($kolom);
        }
    }

    $nominalLama = old('nominal_kredit')
        ? number_format((float) old('nominal_kredit'), 0, ',', '.')
        : '';
  @endphp

  <div class="pk">
    <div class="pk__wadah">
      <header class="pk__kepala">
        <img src="{{ asset('images/logobbj.png') }}" alt="{{ config('pengajuan-kredit.nama_bank') }}" class="pk__logo">
        <h1>Formulir Pengajuan Kredit</h1>
        <p>Silakan lengkapi formulir berikut.</p>
      </header>

      @if ($errors->any())
        <div class="pk-alert pk-alert--galat" role="alert">
          <strong>Data belum lengkap.</strong>
          <ul>
            @foreach ($errors->all() as $pesan)
              <li>{{ $pesan }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Popup validasi, tampilannya sama dengan formulir native --}}
      <div id="popup" class="popup" aria-hidden="true">
        <div class="popup-content" role="dialog" aria-modal="true" aria-labelledby="popupJudul">
          <div class="popup-icon">!</div>
          <div class="popup-header">
            <h3 id="popupJudul">Data Belum Lengkap</h3>
          </div>
          <div class="popup-body">
            <p id="popupText">Silakan lengkapi data pengajuan Anda.</p>
          </div>
          <div class="popup-footer">
            <button type="button" id="closePopup">Kembali ke Form</button>
          </div>
        </div>
      </div>

      <form id="formKredit"
            action="{{ route('pengajuan-kredit.store') }}"
            method="POST"
            enctype="multipart/form-data"
            novalidate
            autocomplete="off"
            data-nilai-keluarga="{{ json_encode($nilaiKeluarga) }}"
            data-produk-usaha="{{ implode(',', $produkUsaha) }}"
            data-produk-pekerjaan="{{ implode(',', $produkPekerjaan) }}"
            data-segarkan="{{ empty(old()) ? '1' : '0' }}">
        @csrf

        {{-- Jebakan bot: kolom tersembunyi, manusia tidak mengisinya --}}
        <div class="pk-jebakan" aria-hidden="true">
          <label for="website">Website</label>
          <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="card">
          <h2>Informasi Pengajuan</h2>

          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" maxlength="150" required>
            @error('email') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="nominal_kredit">Nominal Pengajuan Kredit</label>
            <input type="text"
                   id="nominal_kredit"
                   name="nominal_kredit"
                   value="{{ $nominalLama }}"
                   placeholder="Contoh: 50.000.000"
                   inputmode="numeric"
                   autocomplete="off"
                   required>
            <small class="form-hint">Masukkan nominal kredit yang ingin Anda ajukan.</small>
            @error('nominal_kredit') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="jenisKredit">Jenis Produk Kredit</label>
            <select id="jenisKredit" name="jenis_kredit" required>
              <option value="">Pilih Produk Kredit</option>
              @foreach ($produkList as $kunci => $label)
                <option value="{{ $kunci }}" @selected(old('jenis_kredit') === $kunci)>{{ $label }}</option>
              @endforeach
            </select>
            @error('jenis_kredit') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>
        </div>

        <div class="card hidden" id="usahaSection">
          <h2>Data Usaha</h2>

          <div class="form-group">
            <label for="nama_usaha">Nama Usaha</label>
            <input type="text" id="nama_usaha" name="nama_usaha" value="{{ old('nama_usaha') }}" maxlength="150">
            @error('nama_usaha') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="lokasi_usaha">Lokasi Usaha</label>
            <textarea id="lokasi_usaha" name="lokasi_usaha" maxlength="500">{{ old('lokasi_usaha') }}</textarea>
            @error('lokasi_usaha') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="file_upload">Upload SKU/NIB</label>
            <input type="file"
                   id="file_upload"
                   class="file-upload"
                   name="file_upload"
                   accept=".pdf,.jpg,.jpeg,.png"
                   data-max-size="5242880">
            <small>Format: PDF, JPG, JPEG, PNG (Maksimal 5 MB)</small>
            @error('file_upload') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>
        </div>

        <div class="card hidden" id="pekerjaanSection">
          <h2>Data Pekerjaan</h2>

          <div class="form-group">
            <label for="instansi">Nama Instansi/Perusahaan</label>
            <input type="text" id="instansi" name="instansi" value="{{ old('instansi') }}" maxlength="150">
            @error('instansi') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="jabatan">Jabatan</label>
            <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan') }}" maxlength="100">
            @error('jabatan') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>
        </div>

        <div class="card">
          <h2>Data Pribadi</h2>

          <div class="form-group">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" maxlength="100" required>
            @error('nama_lengkap') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="nik">Nomor KTP/NIK</label>
            <input type="text" id="nik" name="nik" value="{{ old('nik') }}" inputmode="numeric" maxlength="16" required>
            @error('nik') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="jenis_kelamin">Jenis Kelamin</label>
            <select id="jenis_kelamin" name="jenis_kelamin" required>
              <option value="">--Pilih--</option>
              @foreach ($jenisKelaminList as $jenisKelamin)
                <option value="{{ $jenisKelamin }}" @selected(old('jenis_kelamin') === $jenisKelamin)>{{ $jenisKelamin }}</option>
              @endforeach
            </select>
            @error('jenis_kelamin') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="agama">Agama</label>
            <select id="agama" name="agama" required>
              <option value="">--Pilih--</option>
              @foreach ($agamaList as $agama)
                <option value="{{ $agama }}" @selected(old('agama') === $agama)>{{ $agama }}</option>
              @endforeach
            </select>
            @error('agama') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="tempat_lahir">Tempat Lahir</label>
            <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" maxlength="100" required>
            @error('tempat_lahir') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="tanggal_lahir">Tanggal Lahir</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" max="{{ now()->toDateString() }}" required>
            @error('tanggal_lahir') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="alamat_ktp">Alamat KTP</label>
            <textarea id="alamat_ktp" name="alamat_ktp" maxlength="500" required>{{ old('alamat_ktp') }}</textarea>
            @error('alamat_ktp') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="domisili">Alamat Domisili Saat Ini</label>
            <select id="domisili" name="domisili" required>
              <option value="">--Pilih--</option>
              @foreach ($domisiliList as $kunci => $label)
                <option value="{{ $kunci }}" @selected(old('domisili') === $kunci)>{{ $label }}</option>
              @endforeach
            </select>
            @error('domisili') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group hidden" id="alamatDomisiliContainer">
            <label for="alamatDomisili">Alamat Domisili</label>
            <textarea id="alamatDomisili" name="alamat_domisili" maxlength="500">{{ old('alamat_domisili') }}</textarea>
            @error('alamat_domisili') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="ktp">Upload KTP (Hasil Scan/Foto)</label>
            <input type="file" id="ktp" name="ktp" class="file-upload" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
            <small>Format: PDF, JPG, JPEG, PNG (Maksimal 5 MB)</small>
            @error('ktp') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="npwp">Nomor Pokok Wajib Pajak (NPWP)</label>
            <input type="text" id="npwp" name="npwp" value="{{ old('npwp') }}" maxlength="30">
            @error('npwp') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="file_npwp">Upload NPWP</label>
            <input type="file" id="file_npwp" name="file_npwp" class="file-upload" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
            <small>Format: PDF, JPG, JPEG, PNG (Maksimal 5 MB)</small>
            @error('file_npwp') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>

          <div class="form-group">
            <label for="no_hp">Nomor HP/WA</label>
            <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" maxlength="20" required>
            @error('no_hp') <small class="pk-galat">{{ $message }}</small> @enderror
          </div>
        </div>

        <div class="card">
          <h2>Status Pernikahan</h2>
          <select id="statusNikah" name="status_pernikahan" required>
            <option value="">Pilih Status</option>
            @foreach ($statusList as $kunci => $label)
              <option value="{{ $kunci }}" @selected(old('status_pernikahan') === $kunci)>{{ $label }}</option>
            @endforeach
          </select>
          @error('status_pernikahan') <small class="pk-galat">{{ $message }}</small> @enderror
        </div>

        <div class="card hidden" id="keluargaSection">
          <h2>Data Keluarga</h2>
        </div>

        <div class="card">
          <h2>Persetujuan</h2>

          <div class="pk-disclaimer">
            <strong>Disclaimer Perlindungan Data Pribadi</strong>
            <p>Dengan ini, kami menginformasikan bahwa data pribadi yang Anda masukkan dalam form aplikasi ini akan digunakan secara eksklusif untuk keperluan administrasi dan registrasi pengajuan kredit di PT BPR Bogor Jabar (Perseroda). Kami berkomitmen untuk menjaga kerahasiaan data Anda dan tidak akan menyebarluaskan, menjual, atau membagikan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda, kecuali jika diwajibkan oleh hukum.</p>
            <p>Dengan melanjutkan pengisian form ini, Anda memberikan persetujuan untuk pengolahan data pribadi Anda sesuai dengan ketentuan di atas.</p>
          </div>

          <label class="pk-cek">
            <input type="checkbox" name="persetujuan" value="1" @checked(old('persetujuan'))>
            <span>Saya menyetujui segala persyaratan dan ketentuan yang berlaku di PT BPR Bogor Jabar (Perseroda) dan disclaimer di atas</span>
          </label>
          @error('persetujuan') <small class="pk-galat">{{ $message }}</small> @enderror

          <label class="pk-cek">
            <input type="checkbox" name="persetujuan_ideb" value="1" @checked(old('persetujuan_ideb'))>
            <span>Saya bersedia untuk dilakukan pengecekan Informasi Debitur (IDEB) OJK</span>
          </label>
          @error('persetujuan_ideb') <small class="pk-galat">{{ $message }}</small> @enderror
        </div>

        <button type="submit">Kirim Pengajuan</button>
      </form>
    </div>
  </div>

  {{-- ===== Templat bagian keluarga, dipakai JavaScript sesuai status pernikahan ===== --}}
  <template id="tplMenikah">
    <h2>Data Pasangan</h2>

    <div class="form-group">
      <label>Nama Lengkap (Suami/Istri)</label>
      <input type="text" name="nama_pasangan" maxlength="100">
    </div>
    <div class="form-group">
      <label>Hubungan dengan Pemohon</label>
      <input type="text" name="hubungan_pasangan" maxlength="50">
    </div>
    <div class="form-group">
      <label>Nomor KTP/NIK (Pasangan)</label>
      <input type="text" name="nik_pasangan" maxlength="20">
    </div>
    <div class="form-group">
      <label>Agama (Pasangan)</label>
      <select name="agama_pasangan">
        <option value="">--Pilih--</option>
        @foreach ($agamaList as $agama)
          <option value="{{ $agama }}">{{ $agama }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label>Tempat Lahir (Pasangan)</label>
      <input type="text" name="tempat_lahir_pasangan" maxlength="100">
    </div>
    <div class="form-group">
      <label>Tanggal Lahir (Pasangan)</label>
      <input type="date" name="tanggal_lahir_pasangan" max="{{ now()->toDateString() }}">
    </div>
    <div class="form-group">
      <label>Upload KTP Pasangan (Hasil Scan/Foto)</label>
      <input type="file" name="ktp_pasangan" class="file-upload" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
      <small>Format: PDF, JPG, JPEG, PNG (Maksimal 5 MB)</small>
    </div>
    <div class="form-group">
      <label>Upload Buku Nikah (Hasil Scan/Foto)</label>
      <input type="file" name="buku_nikah" class="file-upload" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
      <small>Format: PDF, JPG, JPEG, PNG (Maksimal 5 MB)</small>
    </div>
    <div class="form-group">
      <label>Nomor Pokok Wajib Pajak (NPWP)</label>
      <input type="text" name="npwp_pasangan" maxlength="30">
    </div>
    <div class="form-group">
      <label>Upload NPWP Pasangan (Hasil Scan/Foto)</label>
      <input type="file" name="file_npwp_pasangan" class="file-upload" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
      <small>Format: PDF, JPG, JPEG, PNG (Maksimal 5 MB)</small>
    </div>
    <div class="form-group">
      <label>Nomor HP/WA Pasangan</label>
      <input type="text" name="hp_pasangan" maxlength="20">
    </div>
  </template>

  <template id="tplBelum">
    <h2>Data Orang Tua</h2>

    <div class="form-group">
      <label>Nama Lengkap Orang Tua (Bapak/Ibu)</label>
      <input type="text" name="nama_orang_tua" maxlength="100">
    </div>
    <div class="form-group">
      <label>Hubungan dengan Pemohon</label>
      <input type="text" name="hubungan_orang_tua" maxlength="50">
    </div>
    <div class="form-group">
      <label>Nomor KTP/NIK Orang Tua</label>
      <input type="text" name="nik_orang_tua" maxlength="20">
    </div>
    <div class="form-group">
      <label>Agama Orang Tua</label>
      <select name="agama_orang_tua">
        <option value="">--Pilih--</option>
        @foreach ($agamaList as $agama)
          <option value="{{ $agama }}">{{ $agama }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label>Tempat Lahir Orang Tua</label>
      <input type="text" name="tempat_lahir_orang_tua" maxlength="100">
    </div>
    <div class="form-group">
      <label>Tanggal Lahir Orang Tua</label>
      <input type="date" name="tanggal_lahir_orang_tua" max="{{ now()->toDateString() }}">
    </div>
    <div class="form-group">
      <label>Upload KTP Orang Tua (Hasil Scan/Foto)</label>
      <input type="file" name="ktp_orang_tua" class="file-upload" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
      <small>Format: PDF, JPG, JPEG, PNG (Maksimal 5 MB)</small>
    </div>
    <div class="form-group">
      <label>Nomor Pokok Wajib Pajak (NPWP)</label>
      <input type="text" name="npwp_orang_tua" maxlength="30">
    </div>
    <div class="form-group">
      <label>Upload NPWP Orang Tua (Hasil Scan/Foto)</label>
      <input type="file" name="file_npwp_orang_tua" class="file-upload" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
      <small>Format: PDF, JPG, JPEG, PNG (Maksimal 5 MB)</small>
    </div>
    <div class="form-group">
      <label>Nomor HP/WA Orang Tua</label>
      <input type="text" name="hp_orang_tua" maxlength="20">
    </div>
  </template>

  <template id="tplCerai">
    <h2>Dokumen Pendukung</h2>

    <div class="form-group">
      <label>Upload Akta Cerai / Akta Kematian Pasangan</label>
      <input type="file" name="akta_cerai_kematian" class="file-upload" accept=".pdf,.jpg,.jpeg,.png" data-max-size="5242880">
      <small>Format: PDF, JPG, JPEG, PNG (Maksimal 5 MB)</small>
    </div>
  </template>
@endsection

@push('scripts')
  <script src="{{ asset('js/pengajuan-kredit.js') }}" defer></script>
@endpush
