@extends('layouts.app')

@section('title', 'Pengajuan Kredit Berhasil')
@section('description', 'Pengajuan kredit Anda telah berhasil dikirim ke Bank BBJ.')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/pengajuan-kredit.css') }}">
@endpush

@section('content')
  <div class="pk-sukses">
    <div class="success-wrapper">
      <div class="success-card">
        <div class="success-icon">&#10003;</div>

        <h1>Pengajuan Berhasil Dikirim</h1>

        <p>Terima kasih telah mengajukan kredit melalui Bank BBJ.</p>

        <p>
          Data pengajuan Anda telah berhasil diterima dan akan diproses oleh<br>
          Bank BBJ.
        </p>

        <div class="ticket-box">
          <span class="ticket-label">Nomor Tiket Pengajuan</span>
          <span class="ticket-number">{{ $pengajuan->kode }}</span>
        </div>

        <p class="success-note">
          Simpan nomor tiket tersebut untuk referensi komunikasi mengenai pengajuan Anda.
          Email konfirmasi juga dikirim ke alamat email yang Anda cantumkan.
        </p>

        <a class="btn-home" href="{{ url('/') }}">Kembali ke Beranda</a>

        <p class="bank-name">{{ config('pengajuan-kredit.nama_bank') }} Amanah Kuat Peduli</p>
      </div>
    </div>
  </div>
@endsection
