@extends('layouts.app')

@section('title', 'Permohonan Informasi Publik')
@section('description', 'Ajukan permohonan informasi publik secara online.')

@section('content')
  @php $bank = config('pengaduan.nama_bank'); @endphp

  <div class="pengaduan">
    <nav class="remah" aria-label="Jejak halaman">
      <a href="/">Beranda</a>
      <span><a href="/ppid">PPID</a></span>
      <span>Permohonan informasi</span>
    </nav>

    <header class="pengaduan__kepala">
      <p class="pengaduan__label">PPID {{ strtoupper($bank) }}</p>
      <h1>Permohonan Informasi Publik</h1>
      <p>Isi formulir berikut untuk meminta informasi publik. Tanggapan tertulis diberikan paling lambat
         {{ config('ppid.tenggat_hari_kerja') }} hari kerja sejak permohonan diterima.</p>
    </header>

    @if (session('kode_ppid'))
      <div class="pengaduan__kartu pengaduan__sukses" role="status">
        <h2>Permohonan Anda sudah kami terima</h2>
        <p>Simpan nomor registrasi ini untuk keperluan pelacakan:</p>
        <strong class="kode">{{ session('kode_ppid') }}</strong>
        <p>Tanggapan tertulis diberikan paling lambat {{ config('ppid.tenggat_hari_kerja') }} hari kerja sejak permohonan diterima,
           dan dapat diperpanjang paling lama {{ config('ppid.perpanjangan_hari_kerja') }} hari kerja.</p>
        @if (config('ppid.balas_pemohon'))
          <p>Tanda terima juga dikirim ke email Anda. Periksa folder spam jika belum terlihat.</p>
        @endif
        <a class="kembali" href="/ppid">Kembali ke halaman PPID</a>
      </div>
    @else
      <form class="form pengaduan__kartu" method="POST" action="/ppid/permohonan">
        @csrf

        <h2 class="form__judul">Formulir Permohonan Informasi Publik</h2>
        <p class="form__sub">Silakan lengkapi informasi berikut dengan benar.</p>

        <div class="form__grid">
          <div class="form__baris">
            <label for="jenis_pemohon">Jenis pemohon <span class="wajib">*</span></label>
            <select id="jenis_pemohon" name="jenis_pemohon" required>
              <option value="">Pilih jenis pemohon</option>
              @foreach ($jenisList as $j)
                <option value="{{ $j }}" @selected(old('jenis_pemohon') === $j)>{{ $j }}</option>
              @endforeach
            </select>
            @error('jenis_pemohon') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris">
            <label for="nama">Nama lengkap <span class="wajib">*</span></label>
            <input id="nama" name="nama" value="{{ old('nama') }}" maxlength="100" placeholder="Nama pemohon atau badan hukum" required autocomplete="name">
            @error('nama') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris">
            <label for="nomor_identitas">Nomor identitas (KTP / NPWP) <span class="wajib">*</span></label>
            <input id="nomor_identitas" name="nomor_identitas" value="{{ old('nomor_identitas') }}" inputmode="numeric" maxlength="20" placeholder="Hanya angka" required autocomplete="off">
            @error('nomor_identitas') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris">
            <label for="pekerjaan">Pekerjaan <span class="form__petunjuk">(Opsional)</span></label>
            <input id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan') }}" maxlength="100" autocomplete="organization-title">
            @error('pekerjaan') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris form__penuh">
            <label for="alamat">Alamat <span class="wajib">*</span></label>
            <textarea id="alamat" name="alamat" maxlength="300" rows="3" style="min-height:90px" placeholder="Alamat lengkap pemohon" required autocomplete="street-address">{{ old('alamat') }}</textarea>
            @error('alamat') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris">
            <label for="telepon">Nomor HP <span class="wajib">*</span></label>
            <input id="telepon" name="telepon" type="tel" value="{{ old('telepon') }}" maxlength="20" placeholder="08xxxxxxxxxx" required autocomplete="tel">
            @error('telepon') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris">
            <label for="email">Email <span class="wajib">*</span></label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="150" placeholder="nama@email.com" required autocomplete="email">
            @error('email') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris form__penuh">
            <label for="rincian">Rincian informasi yang dibutuhkan <span class="wajib">*</span></label>
            <textarea id="rincian" name="rincian" maxlength="2000" placeholder="Sebutkan informasi atau dokumen yang Anda minta sejelas mungkin." required>{{ old('rincian') }}</textarea>
            <small class="form__petunjuk">Minimal 20 karakter.</small>
            @error('rincian') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris form__penuh">
            <label for="tujuan">Tujuan penggunaan informasi <span class="wajib">*</span></label>
            <textarea id="tujuan" name="tujuan" maxlength="1000" rows="3" style="min-height:90px" placeholder="Jelaskan untuk apa informasi tersebut akan digunakan." required>{{ old('tujuan') }}</textarea>
            @error('tujuan') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris form__penuh">
            <label for="cara_memperoleh">Cara memperoleh informasi <span class="wajib">*</span></label>
            <select id="cara_memperoleh" name="cara_memperoleh" required>
              <option value="">Pilih cara memperoleh informasi</option>
              @foreach ($caraList as $c)
                <option value="{{ $c }}" @selected(old('cara_memperoleh') === $c)>{{ $c }}</option>
              @endforeach
            </select>
            @error('cara_memperoleh') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          <div class="form__baris form__penuh">
            <label class="setuju">
              <input type="checkbox" name="persetujuan" value="1" @checked(old('persetujuan')) required>
              <span>Saya menyatakan bahwa data yang saya isi adalah benar dan informasi yang diminta akan digunakan sesuai
                tujuan yang saya sebutkan, serta tunduk pada ketentuan perundang-undangan.</span>
            </label>
            @error('persetujuan') <small class="form__galat">{{ $message }}</small> @enderror
          </div>

          {{-- Jebakan bot: disembunyikan dengan CSS, jangan dihapus --}}
          <div class="hp" aria-hidden="true">
            <label>Jangan diisi <input name="website" tabindex="-1" autocomplete="off"></label>
          </div>

          <div class="form__aksi form__penuh">
            <button type="submit">Kirim Permohonan</button>
          </div>
        </div>
      </form>
    @endif
  </div>
@endsection
