@php $ditutup = $lowongan->sudah_ditutup; @endphp
<a class="kartu kartu--tautan kartu--lowongan {{ $ditutup ? 'ditutup' : '' }}" href="/karir/{{ $lowongan->slug }}">
  <div class="kartu--lowongan__atas">
    <span class="lencana-tenggat lencana-tenggat--{{ $ditutup ? 'tutup' : 'buka' }}">{{ $ditutup ? 'Ditutup' : 'Dibuka' }}</span>
    @if ($lowongan->divisi)
      <small>{{ $lowongan->divisi }}</small>
    @endif
  </div>

  <h2>{{ $lowongan->judul }}</h2>

  <ul class="chip-daftar">
    <li>{{ $lowongan->penempatan }}</li>
    <li>{{ $lowongan->tipe_kerja }}</li>
    @if ($lowongan->pendidikan_minimal)
      <li>Min. {{ $lowongan->pendidikan_minimal }}</li>
    @endif
  </ul>

  <p class="kartu__tenggat">
    @if ($ditutup)
      Lowongan sudah ditutup
    @elseif ($lowongan->batas_lamaran)
      Batas lamaran {{ $lowongan->batas_lamaran->locale('id')->translatedFormat('d M Y') }}
    @else
      Dibuka sampai posisi terisi
    @endif
  </p>
</a>
