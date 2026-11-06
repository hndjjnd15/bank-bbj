@php $cover = $lelang->foto_urls[0] ?? null; @endphp
<a class="kartu kartu--tautan kartu--berita" href="/lelang/{{ $lelang->slug }}">
  <div class="kartu__foto">
    @if ($cover)
      <img src="{{ $cover }}" alt="" loading="lazy">
    @else
      <div class="foto-kosong"><img src="{{ asset('images/logo.svg') }}" alt=""></div>
    @endif
    <span class="lencana lencana--{{ $lelang->status }}">{{ $lelang->status_label }}</span>
  </div>
  <div class="kartu__isi">
    <small>{{ $lelang->tipe_aset }} &middot; {{ $lelang->kota }}</small>
    <h2>{{ $lelang->judul }}</h2>
    <p class="kartu__harga">
      <span>Harga limit</span>
      <strong>{{ $lelang->harga_limit_rp }}</strong>
    </p>
  </div>
</a>
