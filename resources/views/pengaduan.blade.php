@extends('layouts.app')

@section('title', 'Pengaduan & Masukan Nasabah')

@section('content')
  @php $bank = config('pengaduan.nama_bank'); @endphp

  <div class="pengaduan">
    <header class="pengaduan__kepala">
      <p class="pengaduan__label">LAYANAN NASABAH</p>
      <h1>Sampaikan Pengaduan &amp; Masukan Anda</h1>
      <p>{{ $bank }} senantiasa berkomitmen untuk memberikan pelayanan terbaik kepada seluruh nasabah.
         Kami menghargai setiap pengaduan, saran, dan masukan yang Anda sampaikan sebagai bahan evaluasi
         untuk meningkatkan kualitas layanan kami.</p>
    </header>

    @if (session('kode'))
      {{-- Tampilan setelah berhasil mengirim --}}
      <div class="pengaduan__kartu pengaduan__sukses" role="status">
        <h2>Terima kasih, pengaduan Anda sudah kami terima</h2>
        <p>Simpan kode tiket ini untuk pelacakan:</p>
        <strong class="kode">{{ session('kode') }}</strong>
        <p>Tim kami akan menindaklanjuti dan menghubungi Anda melalui nomor HP atau email yang dicantumkan.</p>
        <a class="kembali" href="/pengaduan">Kirim pengaduan lain</a>
      </div>
    @else
      <form class="form pengaduan__kartu" method="POST" action="/pengaduan" enctype="multipart/form-data">
        @csrf

        <h2 class="form__judul">Form Pengaduan &amp; Masukan Nasabah</h2>
        <p class="form__sub">Silakan lengkapi informasi berikut dengan benar.</p>

        <div class="form__grid">
          {{-- Jenis layanan --}}
          <fieldset class="form__penuh">
            <legend>Jenis Layanan <span class="wajib">*</span></legend>
            <div class="pilihan">
              @foreach ($jenisList as $jenis)
                <label class="pilihan__kartu">
                  <input type="radio" name="jenis" value="{{ $jenis }}" @checked(old('jenis', 'Pengaduan') === $jenis) required>
                  <span class="pilihan__judul">{{ $jenis }}</span>
                  <span class="pilihan__ket">
                    {{ $jenis === 'Pengaduan'
                        ? 'Sampaikan kendala atau keluhan atas layanan ' . $bank . '.'
                        : 'Berikan saran untuk meningkatkan kualitas layanan ' . $bank . '.' }}
                  </span>
                </label>
              @endforeach
            </div>
            @error('jenis') <small class="form__galat">{{ $message }}</small> @enderror
          </fieldset>

          <div class="form__baris">
            <label for="nama">Nama lengkap <span class="wajib">*</span></label>
            <input id="nama" name="nama" value="{{ old('nama') }}" maxlength="100" placeholder="Masukkan nama lengkap" required autocomplete="name">
            @error('nama') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris">
            <label for="nik">NIK <span class="wajib">*</span></label>
            <input id="nik" name="nik" value="{{ old('nik') }}" inputmode="numeric" maxlength="16" pattern="[0-9]{16}" placeholder="Masukkan 16 digit NIK" required autocomplete="off">
            @error('nik') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris">
            <label for="no_rekening">Nomor Rekening / Nomor Nasabah</label>
            <input id="no_rekening" name="no_rekening" value="{{ old('no_rekening') }}" inputmode="numeric" maxlength="20" placeholder="Opsional" autocomplete="off">
            @error('no_rekening') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris">
            <label for="telepon">Nomor Handphone <span class="wajib">*</span></label>
            <input id="telepon" name="telepon" type="tel" value="{{ old('telepon') }}" maxlength="20" placeholder="08xxxxxxxxxx" required autocomplete="tel">
            @error('telepon') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris">
            <label for="email">Email <span class="wajib">*</span></label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="150" placeholder="nama@email.com" required autocomplete="email">
            @error('email') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris">
            <label for="cabang">Cabang / Unit Layanan <span class="wajib">*</span></label>
            <select id="cabang" name="cabang" required>
              <option value="">-- Pilih Cabang / Unit --</option>
              @foreach ($cabangList as $cabang)
                <option value="{{ $cabang }}" @selected(old('cabang') === $cabang)>{{ $cabang }}</option>
              @endforeach
            </select>
            @error('cabang') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris">
            <label for="tanggal_kejadian">Tanggal Kejadian</label>
            <input id="tanggal_kejadian" name="tanggal_kejadian" type="date" value="{{ old('tanggal_kejadian') }}" max="{{ now()->toDateString() }}">
            @error('tanggal_kejadian') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris">
            <label for="kategori">Kategori <span class="wajib">*</span></label>
            <select id="kategori" name="kategori" required>
              <option value="">-- Pilih Kategori --</option>
              @foreach ($kategoriList as $kategori)
                <option value="{{ $kategori }}" @selected(old('kategori') === $kategori)>{{ $kategori }}</option>
              @endforeach
            </select>
            @error('kategori') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris form__penuh">
            <label for="isi">Uraian Pengaduan / Saran <span class="wajib">*</span></label>
            <textarea id="isi" name="isi" maxlength="3000" placeholder="Jelaskan pengaduan, saran, atau masukan Anda dengan lengkap..." required>{{ old('isi') }}</textarea>
            <small class="form__petunjuk">Minimal 20 karakter. Jangan mencantumkan PIN, kata sandi, atau nomor kartu.</small>
            @error('isi') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris form__penuh">
            <label for="lampiran">Upload Lampiran Bukti</label>
            <input id="lampiran" name="lampiran" type="file" accept=".jpg,.jpeg,.png,.pdf">
            <small class="form__petunjuk">Format yang diperbolehkan: JPG, JPEG, PNG, PDF. Maksimal {{ config('pengaduan.lampiran_maks_kb') / 1024 }} MB.</small>
            @error('lampiran') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris form__penuh">
            <label class="setuju">
              <input type="checkbox" name="persetujuan" value="1" @checked(old('persetujuan')) required>
              <span>Saya menyatakan bahwa informasi yang saya sampaikan adalah benar dan dapat dipergunakan oleh
                {{ $bank }} untuk kepentingan penanganan pengaduan dan peningkatan kualitas layanan.</span>
            </label>
            @error('persetujuan') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          {{-- Jebakan bot: disembunyikan dengan CSS, jangan dihapus --}}
          <div class="hp" aria-hidden="true">
            <label>Jangan diisi <input name="website" tabindex="-1" autocomplete="off"></label>
          </div>

          <div class="form__aksi form__penuh">
            <button type="submit">Kirim Pengaduan</button>
          </div>
        </div>
      </form>
    @endif

    <div class="pengaduan__info">
      <div class="kartu">
        <h2>Kerahasiaan Data</h2>
        <p>Informasi yang Anda sampaikan akan digunakan untuk keperluan penanganan pengaduan dan peningkatan kualitas layanan {{ $bank }}.</p>
      </div>
      <div class="kartu">
        <h2>Tindak Lanjut</h2>
        <p>Setiap pengaduan akan ditindaklanjuti sesuai prosedur yang berlaku di {{ $bank }}.</p>
      </div>
    </div>
  </div>
@endsection
