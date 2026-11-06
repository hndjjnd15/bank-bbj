@extends('layouts.app')

@section('title', 'Whistleblowing System')
@section('description', 'Sampaikan laporan dugaan pelanggaran secara aman dan rahasia melalui Whistleblowing System.')

@section('content')
  @php $bank = config('pengaduan.nama_bank'); @endphp

  <div class="wbs">
    {{-- Kepala halaman --}}
    <header class="wbs__kepala">
      <p class="wbs__label">WHISTLEBLOWING SYSTEM</p>
      <h1>Sampaikan Laporan Secara <span class="wbs__sorot">Aman &amp; Rahasia</span></h1>
      <p>{{ $bank }} menyediakan Whistleblowing System sebagai sarana bagi pihak internal maupun eksternal untuk
         menyampaikan laporan mengenai dugaan pelanggaran, penyimpangan, atau tindakan yang tidak sesuai dengan
         ketentuan yang berlaku.</p>
    </header>

    {{-- Tiga jaminan --}}
    <div class="wbs__tiga">
      <div class="wbs__jaminan">
        <span class="wbs__ikon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 3v6c0 4.5-3.2 7.8-8 9-4.8-1.2-8-4.5-8-9V6z"/><path d="M8.5 12l2.5 2.5 4.5-5"/></svg>
        </span>
        <div>
          <h2>Terlindungi</h2>
          <p>Informasi pelapor dijaga kerahasiaannya sesuai ketentuan yang berlaku.</p>
        </div>
      </div>
      <div class="wbs__jaminan">
        <span class="wbs__ikon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 10h6M9 14h6M9 18h3"/></svg>
        </span>
        <div>
          <h2>Terstruktur</h2>
          <p>Setiap laporan dicatat dan diproses sesuai prosedur Whistleblowing System {{ $bank }}.</p>
        </div>
      </div>
      <div class="wbs__jaminan">
        <span class="wbs__ikon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l3 3 5-6"/></svg>
        </span>
        <div>
          <h2>Ditindaklanjuti</h2>
          <p>Laporan yang masuk akan ditelaah dan ditindaklanjuti oleh pihak yang berwenang.</p>
        </div>
      </div>
    </div>

    <div class="wbs__isi">
      {{-- Kiri: tentang WBS --}}
      <section class="wbs__tentang" aria-labelledby="judul-tentang">
        <p class="wbs__label">TENTANG WBS</p>
        <h2 id="judul-tentang">Bersama Membangun {{ $bank }} yang Berintegritas</h2>
        <p>Whistleblowing System merupakan sarana penyampaian laporan atas dugaan pelanggaran, penyimpangan, atau
           tindakan yang berpotensi merugikan {{ $bank }}.</p>
        <p>Laporan dapat disampaikan berdasarkan informasi atau fakta yang diketahui oleh pelapor. Setiap laporan
           diperlakukan dengan menjaga kerahasiaan informasi dan identitas pelapor sesuai dengan ketentuan yang berlaku.</p>

        <div class="wbs__info" role="note">
          <strong>Mohon sampaikan laporan secara bertanggung jawab.</strong>
          Pastikan informasi yang disampaikan berdasarkan data atau fakta yang dapat dipertanggungjawabkan.
        </div>

        <ol class="wbs__langkah">
          <li><span>1</span><div><strong>Sampaikan</strong><p>Isi formulir laporan secara lengkap.</p></div></li>
          <li><span>2</span><div><strong>Penerimaan</strong><p>Laporan diterima oleh pihak yang berwenang.</p></div></li>
          <li><span>3</span><div><strong>Tindak lanjut</strong><p>Laporan ditelaah dan ditindaklanjuti sesuai prosedur.</p></div></li>
        </ol>
      </section>

      {{-- Kanan: formulir atau pesan sukses --}}
      @if (session('kode_wbs'))
        <div class="pengaduan__kartu pengaduan__sukses" role="status">
          <h2>Laporan Anda sudah kami terima</h2>
          <p>Simpan kode laporan ini untuk keperluan rujukan:</p>
          <strong class="kode">{{ session('kode_wbs') }}</strong>
          <p>Laporan akan ditelaah oleh pihak yang berwenang. Terima kasih atas keberanian dan kepedulian Anda.</p>
          @if (config('wbs.balas_pelapor'))
            <p>Tanda terima berisi kode ini juga dikirim ke email Anda. Periksa folder spam jika belum terlihat.</p>
          @endif
          <a class="kembali" href="/wbs">Kirim laporan lain</a>
        </div>
      @else
        <form class="form pengaduan__kartu wbs__form" method="POST" action="/wbs" enctype="multipart/form-data">
          @csrf

          <p class="wbs__label">FORMULIR WBS</p>
          <h2 class="form__judul">Sampaikan Laporan</h2>
          <p class="form__sub">Silakan lengkapi informasi berikut dengan benar.</p>

          <div class="form__grid">
            <div class="form__baris">
              <label for="nama_pelapor">Nama Pelapor <span class="form__petunjuk">(Opsional)</span></label>
              <input id="nama_pelapor" name="nama_pelapor" value="{{ old('nama_pelapor') }}" maxlength="100" placeholder="Masukkan nama" autocomplete="off">
              @error('nama_pelapor') <small class="form__galat">{{ $message }}</small> @enderror
            </div>

            <div class="form__baris">
              <label for="email">Email <span class="wajib">*</span></label>
              <input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="150" placeholder="nama@email.com" required autocomplete="off">
              @error('email') <small class="form__galat">{{ $message }}</small> @enderror
            </div>

            <div class="form__baris">
              <label for="telepon">Nomor Telepon <span class="form__petunjuk">(Opsional)</span></label>
              <input id="telepon" name="telepon" type="tel" value="{{ old('telepon') }}" maxlength="20" placeholder="08xxxxxxxxxx" autocomplete="off">
              @error('telepon') <small class="form__galat">{{ $message }}</small> @enderror
            </div>

            <div class="form__baris">
              <label for="hubungan">Hubungan dengan {{ $bank }} <span class="wajib">*</span></label>
              <select id="hubungan" name="hubungan" required>
                <option value="">Pilih hubungan</option>
                @foreach ($hubunganList as $h)
                  <option value="{{ $h }}" @selected(old('hubungan') === $h)>{{ $h }}</option>
                @endforeach
              </select>
              @error('hubungan') <small class="form__galat">{{ $message }}</small> @enderror
            </div>

            <div class="form__baris">
              <label for="kategori">Kategori Laporan <span class="wajib">*</span></label>
              <select id="kategori" name="kategori" required>
                <option value="">Pilih kategori laporan</option>
                @foreach ($kategoriList as $k)
                  <option value="{{ $k }}" @selected(old('kategori') === $k)>{{ $k }}</option>
                @endforeach
              </select>
              @error('kategori') <small class="form__galat">{{ $message }}</small> @enderror
            </div>

            <div class="form__baris">
              <label for="tanggal_kejadian">Tanggal Kejadian</label>
              <input id="tanggal_kejadian" name="tanggal_kejadian" type="date" value="{{ old('tanggal_kejadian') }}" max="{{ now()->toDateString() }}">
              @error('tanggal_kejadian') <small class="form__galat">{{ $message }}</small> @enderror
            </div>

            <div class="form__baris">
              <label for="lokasi">Lokasi / Unit Kerja <span class="form__petunjuk">(Jika diketahui)</span></label>
              <input id="lokasi" name="lokasi" value="{{ old('lokasi') }}" maxlength="150" placeholder="Contoh: Kantor Cabang / Unit Kerja" autocomplete="off">
              @error('lokasi') <small class="form__galat">{{ $message }}</small> @enderror
            </div>

            <div class="form__baris">
              <label for="uraian">Uraian Laporan <span class="wajib">*</span></label>
              <textarea id="uraian" name="uraian" maxlength="5000" placeholder="Jelaskan dugaan pelanggaran yang Anda ketahui secara jelas: apa yang terjadi, siapa yang terlibat, kapan, dan di mana." required>{{ old('uraian') }}</textarea>
              <small class="form__petunjuk">Minimal 30 karakter. Laporan yang jelas dan berdasarkan fakta lebih mudah ditindaklanjuti.</small>
              @error('uraian') <small class="form__galat">{{ $message }}</small> @enderror
            </div>

            <div class="form__baris">
              <label for="lampiran">Lampiran Bukti <span class="form__petunjuk">(Opsional)</span></label>
              <input id="lampiran" name="lampiran" type="file" accept=".jpg,.jpeg,.png,.pdf">
              <small class="form__petunjuk">Format yang diperbolehkan: JPG, JPEG, PNG, PDF. Maksimal {{ config('wbs.lampiran_maks_kb') / 1024 }} MB.</small>
              @error('lampiran') <small class="form__galat">{{ $message }}</small> @enderror
            </div>

            <div class="form__baris">
              <label class="setuju">
                <input type="checkbox" name="persetujuan" value="1" @checked(old('persetujuan')) required>
                <span>Saya menyatakan bahwa laporan yang saya sampaikan adalah benar dan dapat dipertanggungjawabkan
                  berdasarkan data dan fakta yang saya ketahui.</span>
              </label>
              @error('persetujuan') <small class="form__galat">{{ $message }}</small> @enderror
            </div>

            {{-- Jebakan bot: disembunyikan dengan CSS, jangan dihapus --}}
            <div class="hp" aria-hidden="true">
              <label>Jangan diisi <input name="website" tabindex="-1" autocomplete="off"></label>
            </div>

            <div class="form__aksi">
              <button type="submit" class="wbs__kirim">Kirim Laporan WBS</button>
            </div>

            <p class="wbs__catatan">Laporan Anda akan diterima dan diproses oleh pihak yang berwenang. Identitas pelapor
              dijaga kerahasiaannya sesuai ketentuan yang berlaku.</p>
          </div>
        </form>
      @endif
    </div>
  </div>
@endsection
