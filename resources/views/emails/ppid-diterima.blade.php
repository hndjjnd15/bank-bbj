<!DOCTYPE html>
<html lang="id">
<body style="margin:0;padding:24px;background:#f3f7f6;font-family:Arial,Helvetica,sans-serif;color:#1c2b29;">
  <div style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e3e9e7;border-radius:12px;overflow:hidden;">
    <div style="background:#0f4c45;color:#ffffff;padding:20px 24px;">
      <div style="font-size:12px;letter-spacing:1px;opacity:.8;">{{ strtoupper($bank) }} - PPID</div>
      <div style="font-size:20px;font-weight:bold;margin-top:4px;">Permohonan informasi Anda telah kami terima</div>
    </div>

    <div style="padding:24px;font-size:14px;line-height:1.65;">
      <p style="margin:0 0 12px;">Yth. Bapak/Ibu <strong>{{ $permohonan->nama }}</strong>,</p>
      <p style="margin:0 0 16px;">Terima kasih. Permohonan informasi publik Anda sudah tercatat dan akan diproses oleh PPID {{ $bank }}.</p>

      <div style="background:#f3f7f6;border:1px solid #e3e9e7;border-radius:10px;padding:16px;text-align:center;margin-bottom:18px;">
        <div style="font-size:12px;color:#5b6b68;">Nomor registrasi</div>
        <div style="font-size:22px;font-weight:bold;letter-spacing:1px;color:#0f4c45;margin-top:4px;">{{ $permohonan->kode }}</div>
        <div style="font-size:12px;color:#5b6b68;margin-top:4px;">Diterima {{ $permohonan->created_at->format('d-m-Y H:i') }} WIB</div>
      </div>

      <p style="margin:0 0 8px;font-weight:bold;">Ketentuan waktu</p>
      <p style="margin:0 0 16px;">
        Tanggapan tertulis atas permohonan Anda diberikan paling lambat <strong>{{ $tenggat }} hari kerja</strong> sejak permohonan diterima,
        dan dapat diperpanjang paling lama {{ $perpanjangan }} hari kerja sesuai ketentuan Undang-Undang Nomor 14 Tahun 2008
        tentang Keterbukaan Informasi Publik.
      </p>

      <p style="margin:0;">
        Sertakan nomor registrasi di atas jika Anda menghubungi kami
        @if ($kontakEmail)
          melalui <a href="mailto:{{ $kontakEmail }}" style="color:#0f4c45;">{{ $kontakEmail }}</a>
        @endif.
      </p>
    </div>

    <div style="padding:14px 24px;background:#f3f7f6;font-size:12px;color:#5b6b68;">
      Email ini dikirim otomatis. Jika Anda tidak merasa mengajukan permohonan ini, abaikan email ini.
    </div>
  </div>
</body>
</html>
