@php $cover = $kantor->foto_urls[0] ?? null; @endphp
<a class="kartu kartu--tautan kartu--berita" href="/jaringan-kantor/{{ $kantor->slug }}">
  <div class="kartu__foto">
    @if ($cover)
      <img src="{{ $cover }}" alt="" loading="lazy">
    @else
      <div class="foto-kosong"><img src="{{ asset('images/logo.svg') }}" alt=""></div>
    @endif
    <span class="lencana lencana--kantor-{{ \Illuminate\Support\Str::slug($kantor->tipe) }}">{{ $kantor->tipe }}</span>
  </div>
  <div class="kartu__isi">
    <small>{{ $kantor->kota }}</small>
    <h2>{{ $kantor->nama }}</h2>
    <p>{{ \Illuminate\Support\Str::limit($kantor->alamat, 90) }}</p>
    <ul class="kantor-ringkas">
      @if ($kantor->telepon)
        <li><span aria-hidden="true">&#9742;</span> {{ $kantor->telepon }}</li>
      @endif
      @if (count($kantor->jam_baris))
        <li><span aria-hidden="true">&#9719;</span> {{ $kantor->jam_baris[0] }}</li>
      @endif
    </ul>
  </div>
</a>
