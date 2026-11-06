<!DOCTYPE html>
<html lang="id">
<body style="margin:0;padding:24px;background:#f3f7f6;font-family:Arial,Helvetica,sans-serif;color:#1c2b29;">
  <div style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #e3e9e7;border-radius:12px;overflow:hidden;">
    <div style="background:#0f4c45;color:#ffffff;padding:18px 24px;">
      <div style="font-size:12px;letter-spacing:1px;opacity:.8;">{{ strtoupper($pengaduan->jenis) }} BARU</div>
      <div style="font-size:20px;font-weight:bold;margin-top:4px;">{{ $pengaduan->kode }}</div>
    </div>

    <table style="width:100%;border-collapse:collapse;font-size:14px;">
      @php
        $baris = [
          'Nama lengkap' => $pengaduan->nama,
          'NIK' => \App\Models\Pengaduan::samarkan($pengaduan->nik),
          'Nomor rekening / nasabah' => \App\Models\Pengaduan::samarkan($pengaduan->no_rekening),
          'Nomor HP' => $pengaduan->telepon,
          'Email' => $pengaduan->email ?: '-',
          'Cabang / unit layanan' => $pengaduan->cabang ?: '-',
          'Tanggal kejadian' => $pengaduan->tanggal_kejadian?->format('d-m-Y') ?? '-',
          'Kategori' => $pengaduan->kategori ?: '-',
          'Lampiran' => $pengaduan->lampiran ? 'Terlampir di email ini' : 'Tidak ada',
        ];
      @endphp
      @foreach ($baris as $label => $nilai)
        <tr>
          <td style="padding:10px 24px;border-bottom:1px solid #eef2f1;color:#5b6b68;width:40%;">{{ $label }}</td>
          <td style="padding:10px 24px;border-bottom:1px solid #eef2f1;">{{ $nilai }}</td>
        </tr>
      @endforeach
    </table>

    <div style="padding:20px 24px;">
      <div style="font-weight:bold;margin-bottom:8px;">Uraian</div>
      <div style="line-height:1.6;font-size:14px;">{!! nl2br(e($pengaduan->isi)) !!}</div>
    </div>

    <div style="padding:0 24px 24px;">
      <a href="{{ $urlAdmin }}" style="display:inline-block;background:#e3a82b;color:#2b2100;text-decoration:none;font-weight:bold;padding:10px 20px;border-radius:999px;font-size:14px;">Buka di panel admin</a>
      <p style="font-size:12px;color:#5b6b68;margin-top:16px;">NIK dan nomor rekening disamarkan di email. Data lengkap hanya tersedia di panel admin.</p>
    </div>
  </div>
</body>
</html>
