<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>@yield('title', 'Bank BBJ') | PT BPR Bogor Jabar (Perseroda)</title>
<meta name="description" content="@yield('description', 'Layanan perbankan untuk masyarakat: tabungan, deposito, dan kredit.')">
@stack('meta')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/jaringan-kantor.css') }}">
<link href="images/logo-bbj-1x1.ico" rel="shortcut icon" type="images/x-icon">
</head>
<body>

@include('partials.strip')
@include('partials.header')

<main class="konten" id="konten">
  @yield('content')
</main>

@include('partials.footer')
@include('partials.side-links')

<script src="{{ asset('js/script.js') }}" defer></script>
</body>
</html>
