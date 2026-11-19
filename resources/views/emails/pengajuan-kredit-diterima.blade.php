<!DOCTYPE html>
<html lang="id">
<body style="margin:0;padding:24px;background:#f3f6fa;font-family:Arial,Helvetica,sans-serif;color:#333;line-height:1.7;">
  <div style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #dddddd;">
    <div style="background:#073b74;color:#ffffff;padding:20px;">
      <h2 style="margin:0;font-size:20px;">Bank BBJ</h2>
      <p style="margin:5px 0 0;font-size:14px;">Konfirmasi Pengajuan Kredit</p>
    </div>

    <div style="padding:24px;">
      <p>Yth. {{ $pengajuan->nama_lengkap }},</p>

      <p>
        Terima kasih telah mengajukan kredit melalui formulir resmi Bank BBJ.
        Pengajuan Anda telah kami terima dan akan ditindaklanjuti untuk proses pemeriksaan.
      </p>

      <div style="background:#f3f6fa;padding:16px;border-radius:6px;">
        <p style="margin:0 0 12px;">
          <strong>Nomor Tiket:</strong><br>
          {{ $pengajuan->kode }}
        </p>

        <p style="margin:0 0 12px;">
          <strong>Produk Kredit:</strong><br>
          {{ $pengajuan->nama_produk }}
        </p>

        <p style="margin:0 0 12px;">
          <strong>Nominal Pengajuan:</strong><br>
          {{ $pengajuan->nominal_rupiah }}
        </p>

        <p style="margin:0;">
          <strong>Tanggal Pengajuan:</strong><br>
          {{ $pengajuan->created_at?->format('d-m-Y H:i:s') }}
        </p>
      </div>

      <p>
        Simpan nomor tiket tersebut untuk referensi komunikasi mengenai pengajuan Anda.
        Email ini merupakan konfirmasi penerimaan pengajuan, bukan persetujuan pemberian kredit.
      </p>

      <p>Terima kasih telah mempercayai Bank BBJ.</p>

      <p>
        Hormat kami,<br>
        <strong>Bank BBJ Amanah Kuat Peduli</strong>
      </p>
    </div>
  </div>
</body>
</html>
