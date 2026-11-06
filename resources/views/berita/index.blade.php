@extends('layouts.app')

@section('title', 'Berita')

@section('content')
  <h1>Berita dan kegiatan</h1>
  <p>Informasi terbaru dari Bank BBJ.</p>

  <div class="kartu-grid">
    @forelse ($beritas as $berita)
      @include('partials.kartu-berita', ['berita' => $berita])
    @empty
      <p>Belum ada berita.</p>
    @endforelse
  </div>
@endsection
