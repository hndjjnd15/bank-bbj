<!DOCTYPE html>
<html lang="id">
<body style="margin:0;padding:24px;background:#f3f6fa;font-family:Arial,Helvetica,sans-serif;color:#333;line-height:1.6;">
  <div style="max-width:760px;margin:0 auto;background:#ffffff;border:1px solid #dddddd;">
    <div style="background:#073b74;color:#ffffff;padding:20px;">
      <h2 style="margin:0;font-size:20px;">Pengajuan Kredit Bank BBJ</h2>
      <p style="margin:8px 0 0;font-size:14px;">Formulir pengajuan kredit nasabah</p>
    </div>

    <div style="padding:20px;">
      <p style="margin:0 0 16px;">Pengajuan kredit baru telah diterima.</p>

      <p style="margin:0 0 20px;">
        <strong>Nomor Tiket:</strong> {{ $pengajuan->kode }}<br>
        <strong>Tanggal Pengajuan:</strong> {{ $pengajuan->created_at?->format('d-m-Y H:i:s') }}<br>
        <strong>Produk Kredit:</strong> {{ $pengajuan->nama_produk }}<br>
        <strong>Nominal Pengajuan:</strong> {{ $pengajuan->nominal_rupiah }}
      </p>

      <h3 style="color:#073b74;margin:0 0 12px;">Data Pengajuan</h3>

      <table cellpadding="9" cellspacing="0" style="width:100%;border-collapse:collapse;border:1px solid #dddddd;font-size:14px;">
        <tbody>
          @foreach ($pengajuan->dataTampil($samarkanNik ?? false) as $label => $nilai)
            <tr>
              <td style="width:35%;border:1px solid #dddddd;background:#f5f7fa;vertical-align:top;"><strong>{{ $label }}</strong></td>
              <td style="border:1px solid #dddddd;">{!! nl2br(e($nilai)) !!}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <h3 style="color:#073b74;margin:22px 0 10px;">Dokumen Terlampir</h3>
      @if (count($pengajuan->berkasTampil()))
        <p style="margin:0 0 8px;font-size:14px;">
          Seluruh dokumen pengajuan ikut terlampir pada email ini dengan nama aslinya.
        </p>
        <ul style="margin:0;padding-left:20px;font-size:14px;">
          @foreach ($pengajuan->berkasTampil() as $berkas)
            <li>{{ $berkas['label'] }} &mdash; {{ $berkas['nama'] }} ({{ $berkas['ukuran'] }})</li>
          @endforeach
        </ul>
      @else
        <p style="margin:0;font-size:14px;color:#666;">Tidak ada dokumen yang diunggah.</p>
      @endif

      @unless ($denganLampiran ?? true)
        <p style="margin:12px 0 0;font-size:14px;color:#b45309;">
          Lampiran tidak dapat disertakan karena ukuran berkas melebihi batas server email.
          Dokumen dapat diunduh melalui panel admin.
        </p>
      @endunless

      <p style="margin:20px 0 0;font-size:14px;">
        Mohon lakukan pemeriksaan lebih lanjut terhadap data dan dokumen yang dilampirkan.
      </p>

      @if (filled($urlAdmin ?? null))
        <p style="margin:16px 0 24px;">
          <a href="{{ $urlAdmin }}" style="display:inline-block;background:#073b74;color:#ffffff;text-decoration:none;font-weight:bold;padding:11px 22px;border-radius:6px;font-size:14px;">Buka di panel admin</a>
        </p>
      @endif

      @if ($samarkanNik ?? false)
        <p style="margin:16px 0 0;font-size:12px;color:#666;">NIK disamarkan di email ini. Panel admin dipakai untuk mencatat tindak lanjut pengajuan.</p>
      @else
        <p style="margin:16px 0 0;font-size:12px;color:#666;">NIK ditampilkan lengkap di email ini agar dapat diverifikasi, termasuk saat email diteruskan ke cabang. Mohon jaga kerahasiaan data nasabah.</p>
      @endif

      <p style="margin:16px 0 0;">Bank BBJ Amanah Kuat Peduli</p>
    </div>
  </div>
</body>
</html>
