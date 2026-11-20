{{-- Animasi loading halaman. GIF diunggah lewat /admin > Pengaturan Situs. --}}
@php($pengaturanLoading = \App\Models\PengaturanSitus::saatIni())

@if ($pengaturanLoading?->loading_tampil)
  <div class="loading-halaman" data-loading-halaman data-loading-durasi="{{ $pengaturanLoading->loading_durasi_detik }}" role="status" aria-live="polite" aria-label="Memuat halaman">
    <img class="loading-halaman__gambar" src="{{ $pengaturanLoading->loading_gif_url }}" alt="" decoding="async">
    <span class="sr-only">Halaman sedang dimuat&hellip;</span>
  </div>
@endif
