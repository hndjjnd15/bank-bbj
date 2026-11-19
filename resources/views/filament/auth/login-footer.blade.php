{{-- Keterangan tambahan di bawah form masuk. --}}
@php($tautanBantuan = \App\Models\PengaturanSitus::saatIni()?->tautan_whatsapp)

<p class="admin-login-bantuan">
    Belum punya akses?
    @if ($tautanBantuan)
        <a href="{{ $tautanBantuan }}" target="_blank" rel="noopener">Hubungi administrator</a>
    @else
        Hubungi administrator Bank BBJ.
    @endif
</p>
