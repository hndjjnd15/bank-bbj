@php $cover = $berita->foto_urls[0] ?? null; @endphp
<a class="kartu kartu--tautan kartu--berita" href="/berita/{{ $berita->slug }}">
  <div class="kartu__foto">
    @if ($cover)
      <img src="{{ $cover }}" alt="" loading="lazy">
    @else
      <div class="foto-kosong"><img src="{{ asset('images/logo.svg') }}" alt=""></div>
    @endif
  </div>
  <div class="kartu__isi">
    <small>{{ $berita->tanggal->locale('id')->translatedFormat('d F Y') }}</small>
    <h2>{{ $berita->judul }}</h2>
    <p>{{ $berita->ringkasan }}</p>
  </div>
</a>
