<!DOCTYPE html>
<html lang="id">
<body style="margin:0;padding:24px;background:#f3f7f6;font-family:Arial,Helvetica,sans-serif;color:#1c2b29;">
  <div style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e3e9e7;border-radius:12px;overflow:hidden;">

    <div style="background:#0f4c45;color:#ffffff;padding:20px 24px;">
      <div style="font-size:12px;letter-spacing:1px;opacity:.8;">{{ strtoupper($bank) }}</div>
      <div style="font-size:20px;font-weight:bold;margin-top:4px;">{{ $pengaduan->jenis }} Anda telah kami terima</div>
    </div>

    <div style="padding:24px;font-size:14px;line-height:1.65;">
      <p style="margin:0 0 12px;">Yth. Bapak/Ibu <strong>{{ $pengaduan->nama }}</strong>,</p>
      <p style="margin:0 0 16px;">
        Terima kasih telah menghubungi {{ $bank }}. {{ $pengaduan->jenis }} Anda sudah kami terima
        dan akan ditindaklanjuti sesuai prosedur yang berlaku.
      </p>

      <div style="background:#f3f7f6;border:1px solid #e3e9e7;border-radius:10px;padding:16px;text-align:center;margin-bottom:18px;">
        <div style="font-size:12px;color:#5b6b68;">Kode tiket Anda</div>
        <div style="font-size:22px;font-weight:bold;letter-spacing:1px;color:#0f4c45;margin-top:4px;">{{ $pengaduan->kode }}</div>
        <div style="font-size:12px;color:#5b6b68;margin-top:4px;">Simpan kode ini untuk keperluan pelacakan.</div>
      </div>

      <table style="width:100%;border-collapse:collapse;font-size:14px;margin-bottom:18px;">
        @php
          $baris = [
            'Jenis layanan' => $pengaduan->jenis,
            'Kategori' => $pengaduan->kategori ?: '-',
            'Cabang / unit layanan' => $pengaduan->cabang ?: '-',
            'Tanggal kejadian' => $pengaduan->tanggal_kejadian?->format('d-m-Y') ?? '-',
            'Waktu diterima' => $pengaduan->created_at->format('d-m-Y H:i') . ' WIB',
          ];
        @endphp
        @foreach ($baris as $label => $nilai)
          <tr>
            <td style="padding:8px 0;border-bottom:1px solid #eef2f1;color:#5b6b68;width:45%;">{{ $label }}</td>
            <td style="padding:8px 0;border-bottom:1px solid #eef2f1;">{{ $nilai }}</td>
          </tr>
        @endforeach
      </table>

      <p style="margin:0 0 12px;">
        Jika ada informasi tambahan yang ingin disampaikan, balas email ini dengan mencantumkan kode tiket di atas
        @if ($emailBantuan)
          (atau kirim ke <a href="mailto:{{ $emailBantuan }}" style="color:#0f4c45;">{{ $emailBantuan }}</a>)
        @endif.
      </p>

      <p style="margin:0;padding:12px 14px;background:#fff8e6;border:1px solid #f1deaa;border-radius:8px;font-size:13px;">
        <strong>Waspada penipuan.</strong> {{ $bank }} tidak pernah meminta PIN, kata sandi, atau kode OTP Anda melalui email maupun pesan.
      </p>
    </div>

    <div style="padding:14px 24px;background:#f3f7f6;font-size:12px;color:#5b6b68;">
      Anda menerima email ini karena alamat email ini dicantumkan pada formulir pengaduan di website {{ $bank }}.
      Jika Anda tidak merasa mengirimkannya, abaikan email ini.
    </div>
  </div>
</body>
</html>
