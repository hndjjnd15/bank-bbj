<!DOCTYPE html>
<html lang="id">
<body style="margin:0;padding:24px;background:#f3f7f6;font-family:Arial,Helvetica,sans-serif;color:#1c2b29;">
  <div style="max-width:560px;margin:0 auto;background:#ffffff;border:1px solid #e3e9e7;border-radius:12px;overflow:hidden;">

    <div style="background:#0f4c45;color:#ffffff;padding:18px 24px;">
      <div style="font-size:12px;letter-spacing:1px;opacity:.8;">{{ strtoupper($bank) }}</div>
      <div style="font-size:18px;font-weight:bold;margin-top:4px;">Tanda terima</div>
    </div>

    <div style="padding:24px;font-size:14px;line-height:1.65;">
      <p style="margin:0 0 14px;">Terima kasih. Kami telah menerima kiriman Anda melalui formulir di website {{ $bank }}.</p>

      <div style="background:#f3f7f6;border:1px solid #e3e9e7;border-radius:10px;padding:16px;text-align:center;margin-bottom:16px;">
        <div style="font-size:12px;color:#5b6b68;">Kode rujukan Anda</div>
        <div style="font-size:22px;font-weight:bold;letter-spacing:1px;color:#0f4c45;margin-top:4px;">{{ $laporan->kode }}</div>
        <div style="font-size:12px;color:#5b6b68;margin-top:4px;">Simpan kode ini jika diperlukan sebagai rujukan.</div>
      </div>

      <p style="margin:0;">Kiriman Anda akan ditelaah oleh pihak yang berwenang sesuai prosedur yang berlaku.</p>
    </div>

    <div style="padding:14px 24px;background:#f3f7f6;font-size:12px;color:#5b6b68;line-height:1.55;">
      Email ini dikirim otomatis, mohon tidak membalasnya. Jika Anda tidak merasa mengirimkan sesuatu melalui website {{ $bank }}, abaikan email ini.
    </div>
  </div>
</body>
</html>
