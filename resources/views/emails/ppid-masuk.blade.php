<!DOCTYPE html>
<html lang="id">
<body style="margin:0;padding:24px;background:#f3f7f6;font-family:Arial,Helvetica,sans-serif;color:#1c2b29;">
  <div style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #e3e9e7;border-radius:12px;overflow:hidden;">
    <div style="background:#0f4c45;color:#ffffff;padding:18px 24px;">
      <div style="font-size:12px;letter-spacing:1px;opacity:.8;">PERMOHONAN INFORMASI PUBLIK</div>
      <div style="font-size:20px;font-weight:bold;margin-top:4px;">{{ $permohonan->kode }}</div>
    </div>

    <div style="padding:12px 24px;background:#fff8e6;border-bottom:1px solid #f1deaa;font-size:13px;color:#6b5200;">
      Batas tanggapan tertulis (perkiraan {{ config('ppid.tenggat_hari_kerja') }} hari kerja):
      <strong>{{ $permohonan->batas_tanggapan->format('d-m-Y') }}</strong>
    </div>

    <table style="width:100%;border-collapse:collapse;font-size:14px;">
      @php
        $baris = [
          'Jenis pemohon' => $permohonan->jenis_pemohon,
          'Nama' => $permohonan->nama,
          'Nomor identitas' => \Illuminate\Support\Str::mask($permohonan->nomor_identitas, '*', 0, max(strlen($permohonan->nomor_identitas) - 4, 0)),
          'Alamat' => $permohonan->alamat,
          'Pekerjaan' => $permohonan->pekerjaan ?: '-',
          'Nomor HP' => $permohonan->telepon,
          'Email' => $permohonan->email,
          'Cara memperoleh informasi' => $permohonan->cara_memperoleh,
        ];
      @endphp
      @foreach ($baris as $label => $nilai)
        <tr>
          <td style="padding:10px 24px;border-bottom:1px solid #eef2f1;color:#5b6b68;width:38%;vertical-align:top;">{{ $label }}</td>
          <td style="padding:10px 24px;border-bottom:1px solid #eef2f1;">{{ $nilai }}</td>
        </tr>
      @endforeach
    </table>

    <div style="padding:20px 24px 8px;">
      <div style="font-weight:bold;margin-bottom:6px;">Informasi yang diminta</div>
      <div style="line-height:1.6;font-size:14px;">{!! nl2br(e($permohonan->rincian)) !!}</div>
    </div>
    <div style="padding:12px 24px 20px;">
      <div style="font-weight:bold;margin-bottom:6px;">Tujuan penggunaan informasi</div>
      <div style="line-height:1.6;font-size:14px;">{!! nl2br(e($permohonan->tujuan)) !!}</div>
    </div>

    <div style="padding:0 24px 24px;">
      <a href="{{ $urlAdmin }}" style="display:inline-block;background:#e3a82b;color:#2b2100;text-decoration:none;font-weight:bold;padding:10px 20px;border-radius:999px;font-size:14px;">Buka di panel admin</a>
      <p style="font-size:12px;color:#5b6b68;margin-top:16px;">Nomor identitas disamarkan di email. Data lengkap tersedia di panel admin.</p>
    </div>
  </div>
</body>
</html>
