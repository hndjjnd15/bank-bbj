@php
    $daftar = $berkas ?? [];
@endphp

@if (count($daftar))
    <ul style="margin:0;padding:0;list-style:none;display:grid;gap:10px;">
        @foreach ($daftar as $kunci => $info)
            <li style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;border:1px solid #e5e7eb;border-radius:8px;padding:10px 12px;">
                <a href="{{ route('pengajuan-kredit.berkas', ['pengajuan' => $pengajuan, 'kunci' => $kunci]) }}"
                   target="_blank"
                   rel="noopener"
                   style="font-weight:600;color:#1d4ed8;text-decoration:underline;">
                    {{ $info['label'] }}
                </a>
                <span style="color:#4b5563;font-size:0.875rem;">
                    {{ $info['nama'] }} ({{ $info['ukuran'] }})
                </span>
            </li>
        @endforeach
    </ul>
@else
    <p style="margin:0;color:#6b7280;">Tidak ada dokumen yang diunggah.</p>
@endif
