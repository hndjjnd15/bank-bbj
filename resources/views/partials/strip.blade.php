{{-- Strip informasi bergerak. Teks diedit di sini; JS mengulang isinya otomatis. --}}
@php($waSitus = \App\Models\PengaturanSitus::saatIni()?->tautan_whatsapp ?? 'https://wa.me/'.\App\Models\PengaturanSitus::WHATSAPP_BAWAAN)
<!-- Strip informasi -->
<div class="strip" id="strip" role="region" aria-label="Informasi bank">
  <div class="strip__lintasan" id="stripLintasan">
    <ul class="strip__daftar">
      <li class="strip__ok">Terdaftar OJK</li>
      <li class="strip__ok">Dijamin LPS</li>
      <li>Senin–Jumat 08.00–16.00 WIB</li>
      <li><a href="tel:0254430000">(0254) 430000</a></li>
      <li><a href="{{ $waSitus }}">WhatsApp</a></li>
    </ul>
  </div>
</div>
