@php
  $promo = $popupPromo ?? null;
  $tampilkanDefault = $popupDefault ?? true;
  $promoGambar = $promo?->gambar_url ?? asset('images/pop-up.webp');
  $promoAlt = $promo?->alt ?? 'Promo Bank BBJ: tabungan, kredit, deposito, dan layanan pick-up untuk semua produk';
  $promoTautan = $promo?->tujuan_url ?? '/pengajuan-kredit';
  $promoEksternal = \Illuminate\Support\Str::startsWith($promoTautan, ['http://', 'https://']);
@endphp

@if ($promo || $tampilkanDefault)
  {{-- Pop-up iklan promosi beranda. Dikelola di admin: Informasi > Pop-up Promo. --}}
  <div class="promo-popup" id="promo-popup" data-promo-popup aria-hidden="true" hidden>
    <div class="promo-popup__tirai" data-promo-latar></div>

    <div
      class="promo-popup__bingkai"
      data-promo-dialog
      role="dialog"
      aria-modal="true"
      aria-labelledby="promo-popup-judul"
      aria-describedby="promo-popup-deskripsi"
    >
      <h2 class="sr-only" id="promo-popup-judul">Iklan promosi Bank BBJ</h2>
      <p class="sr-only" id="promo-popup-deskripsi">Kebutuhan produk keuangan Anda tersedia di Bank BBJ. Buka tautan pada iklan ini untuk informasi selengkapnya.</p>

      <button type="button" class="promo-popup__tutup" data-promo-tutup aria-label="Tutup iklan promosi">&times;</button>

      <div class="promo-popup__dialog">
        <a
          class="promo-popup__tautan"
          href="{{ $promoTautan }}"
          @if ($promoEksternal) target="_blank" rel="noopener noreferrer" @endif
        >
          <img
            class="promo-popup__gambar"
            src="{{ $promoGambar }}"
            alt="{{ $promoAlt }}"
            decoding="async"
          >
          <span class="promo-popup__cta">Lihat penawaran sekarang <span aria-hidden="true">&rarr;</span></span>
        </a>
      </div>
    </div>
  </div>
@endif
