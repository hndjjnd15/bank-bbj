{{-- Navigasi halaman bergaya situs ini (menggantikan tampilan bawaan Laravel) --}}
@if ($paginator->hasPages())
  <nav class="halaman" role="navigation" aria-label="Halaman">
    @if ($paginator->onFirstPage())
      <span class="halaman__item mati">&lsaquo; Sebelumnya</span>
    @else
      <a class="halaman__item" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo; Sebelumnya</a>
    @endif

    @foreach ($elements as $element)
      @if (is_string($element))
        <span class="halaman__item mati">{{ $element }}</span>
      @endif

      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span class="halaman__item aktif" aria-current="page">{{ $page }}</span>
          @else
            <a class="halaman__item" href="{{ $url }}">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    @if ($paginator->hasMorePages())
      <a class="halaman__item" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya &rsaquo;</a>
    @else
      <span class="halaman__item mati">Berikutnya &rsaquo;</span>
    @endif
  </nav>
@endif
