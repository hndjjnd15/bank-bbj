@extends('layouts.app')

@section('title', 'Program CSR')
@section('description', 'Program tanggung jawab sosial perusahaan: pendidikan, kesehatan, lingkungan, dan kegiatan untuk masyarakat.')

@section('content')
  @php $bank = config('pengaduan.nama_bank'); @endphp

  <header class="csr__kepala">
    <p class="wbs__label">TANGGUNG JAWAB SOSIAL</p>
    <h1>Program <span class="wbs__sorot">CSR</span></h1>
    <p>Sejalan dengan nilai <strong>peduli</strong>, {{ $bank }} berupaya memberi nilai tambah bagi lingkungan sekitar
       dan menjunjung tinggi nilai kemanusiaan melalui berbagai kegiatan untuk masyarakat.</p>
  </header>

  <form class="cari" method="GET" action="/csr" role="search">
    <input type="search" name="q" value="{{ $filter['q'] }}" placeholder="Cari program atau lokasi" aria-label="Cari program">

    <select name="kategori" aria-label="Kategori">
      <option value="">Semua kategori</option>
      @foreach (\App\Models\ProgramCsr::KATEGORI as $k)
        <option value="{{ $k }}" @selected($filter['kategori'] === $k)>{{ $k }}</option>
      @endforeach
    </select>

    <select name="tahun" aria-label="Tahun">
      <option value="">Semua tahun</option>
      @foreach ($tahunList as $t)
        <option value="{{ $t }}" @selected($filter['tahun'] === $t)>{{ $t }}</option>
      @endforeach
    </select>

    <button type="submit">Cari</button>
    @if ($adaFilter)
      <a class="cari__reset" href="/csr">Reset</a>
    @endif
  </form>

  <p class="hasil">Menampilkan {{ $programs->total() }} program</p>

  <div class="kartu-grid">
    @forelse ($programs as $program)
      @include('partials.kartu-csr', ['program' => $program])
    @empty
      <p>Belum ada program yang cocok. Coba ubah kata kunci atau penyaringnya.</p>
    @endforelse
  </div>

  {{ $programs->links('partials.pagination') }}
@endsection
