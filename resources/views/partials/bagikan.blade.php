{{-- Tombol bagikan. Pakai: @include('partials.bagikan', ['judul' => '...']) --}}
<div class="bagikan">
  <span>Bagikan:</span>
  <a class="bagikan__tombol" href="https://wa.me/?text={{ rawurlencode($judul . ' ' . url()->current()) }}" target="_blank" rel="noopener">WhatsApp</a>
  <button type="button" class="bagikan__tombol" data-salin>Salin link</button>
</div>
