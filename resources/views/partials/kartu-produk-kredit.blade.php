<a class="kartu kartu--tautan kartu--berita kartu--kredit" href="{{ route('kredit.show', $produk->slug) }}">
  <div class="kartu__foto">
    @if ($produk->foto_url)
      <img src="{{ $produk->foto_url }}" alt="" loading="lazy">
    @else
      <div class="foto-kosong"><img src="{{ asset('images/logo.svg') }}" alt=""></div>
    @endif
    <span class="lencana lencana--kredit">Kredit</span>
  </div>
  <div class="kartu__isi">
    @if ($produk->tagline)
      <small>{{ \Illuminate\Support\Str::limit($produk->tagline, 70) }}</small>
    @else
      <small>Produk pembiayaan Bank BBJ</small>
    @endif
    <h2>{{ $produk->judul }}</h2>
    <p>{{ \Illuminate\Support\Str::limit(strip_tags($produk->deskripsi) ?: 'Informasi produk dapat diperoleh melalui kantor cabang.', 120) }}</p>
    <span class="kredit-card__tautan">Lihat detail &rarr;</span>
  </div>
</a>
