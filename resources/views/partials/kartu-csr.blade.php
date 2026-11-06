@php $cover = $program->foto_urls[0] ?? null; @endphp
<a class="kartu kartu--tautan kartu--berita" href="/csr/{{ $program->slug }}">
  <div class="kartu__foto">
    @if ($cover)
      <img src="{{ $cover }}" alt="" loading="lazy">
    @else
      <div class="foto-kosong"><img src="{{ asset('images/logo.svg') }}" alt=""></div>
    @endif
    <span class="lencana lencana--csr">{{ $program->kategori }}</span>
  </div>
  <div class="kartu__isi">
    <small>{{ $program->tanggal->locale('id')->translatedFormat('d F Y') }}@if ($program->lokasi) &middot; {{ $program->lokasi }}@endif</small>
    <h2>{{ $program->judul }}</h2>
    @if ($program->ringkasan)
      <p>{{ $program->ringkasan }}</p>
    @endif
  </div>
</a>
