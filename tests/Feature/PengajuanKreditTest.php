<?php

namespace Tests\Feature;

use App\Mail\PengajuanKreditDiterima;
use App\Mail\PengajuanKreditMasuk;
use App\Models\PengajuanKredit;
use App\Models\PengaturanSitus;
use App\Models\User;
use App\Services\PengajuanKreditNotifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class PengajuanKreditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Mail::fake();
        Http::fake();
    }

    // Isian minimal yang valid untuk produk pegawai dengan status "belum menikah"
    private function isian(array $ganti = []): array
    {
        return array_merge([
            'email' => 'pemohon@example.com',
            'nominal_kredit' => '50.000.000',
            'jenis_kredit' => 'pns',
            'nama_lengkap' => 'Budi Santoso',
            'nik' => '3201010101010001',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'tempat_lahir' => 'Bogor',
            'tanggal_lahir' => '1990-01-01',
            'alamat_ktp' => 'Jl. Contoh No. 1',
            'domisili' => 'ktp',
            'ktp' => UploadedFile::fake()->image('ktp.jpg', 20, 20),
            'npwp' => '123456789012345',
            'file_npwp' => UploadedFile::fake()->image('npwp.jpg', 20, 20),
            'no_hp' => '081234567890',
            'instansi' => 'Dinas Contoh',
            'jabatan' => 'Staf',
            'status_pernikahan' => 'belum',
            'nama_orang_tua' => 'Sutrisno',
            'hubungan_orang_tua' => 'Ayah',
            'nik_orang_tua' => '3201010101010002',
            'agama_orang_tua' => 'Islam',
            'tempat_lahir_orang_tua' => 'Bogor',
            'tanggal_lahir_orang_tua' => '1960-01-01',
            'ktp_orang_tua' => UploadedFile::fake()->image('ktp-orang-tua.jpg', 20, 20),
            'npwp_orang_tua' => '123456789012346',
            'file_npwp_orang_tua' => UploadedFile::fake()->image('npwp-orang-tua.jpg', 20, 20),
            'hp_orang_tua' => '081234567891',
            'persetujuan' => '1',
            'persetujuan_ideb' => '1',
        ], $ganti);
    }

    public function test_formulir_pengajuan_kredit_dapat_dibuka(): void
    {
        $this->get('/pengajuan-kredit')
            ->assertOk()
            ->assertSee('Formulir Pengajuan Kredit')
            ->assertSee('Nominal Pengajuan Kredit')
            ->assertSee('Kredit UMKM')
            ->assertSee('Kredit Petani/Peternak')
            ->assertSee('Data Pasangan', false)
            ->assertSee('Persetujuan');
    }

    public function test_pengajuan_valid_tersimpan_dan_diarahkan_ke_halaman_sukses(): void
    {
        $response = $this->post('/pengajuan-kredit', $this->isian());

        $pengajuan = PengajuanKredit::first();

        $this->assertNotNull($pengajuan);
        $this->assertSame('Budi Santoso', $pengajuan->nama_lengkap);
        $this->assertSame('3201010101010001', $pengajuan->nik);
        $this->assertSame('pemohon@example.com', $pengajuan->email);
        $this->assertSame('pns', $pengajuan->jenis_kredit);
        $this->assertSame('Kredit PNS', $pengajuan->nama_produk);
        $this->assertSame(50000000, $pengajuan->nominal_kredit);
        $this->assertSame('belum', $pengajuan->status_pernikahan);
        $this->assertSame('baru', $pengajuan->status);

        // Data formulir tersimpan lengkap dan bisa dibaca kembali
        $this->assertSame('Dinas Contoh', $pengajuan->data['instansi']);
        $this->assertSame('Sutrisno', $pengajuan->data['nama_orang_tua']);

        // Semua dokumen tersimpan di disk privat
        $this->assertArrayHasKey('ktp', $pengajuan->berkas);
        $this->assertArrayHasKey('file_npwp', $pengajuan->berkas);
        $this->assertArrayHasKey('ktp_orang_tua', $pengajuan->berkas);
        $this->assertArrayHasKey('file_npwp_orang_tua', $pengajuan->berkas);
        Storage::disk('local')->assertExists($pengajuan->berkas['ktp']['path']);

        $response->assertRedirect(route('pengajuan-kredit.sukses', $pengajuan->kode));
        $this->assertMatchesRegularExpression('/^BBJ-\d{8}-[0-9A-F]{6}$/', $pengajuan->kode);

        // Halaman sukses menampilkan nomor tiket
        $this->get(route('pengajuan-kredit.sukses', $pengajuan->kode))
            ->assertOk()
            ->assertSee($pengajuan->kode)
            ->assertSee('Pengajuan Berhasil Dikirim');

        // Email internal (lengkap dengan lampiran) dan email konfirmasi dikirim
        Mail::assertSent(PengajuanKreditMasuk::class, function (PengajuanKreditMasuk $mail) {
            return count($mail->attachments()) === 4;
        });
        Mail::assertSent(PengajuanKreditDiterima::class);
        $this->assertNotNull($pengajuan->fresh()->email_terkirim_at);

        // Email ke pemohon hanya berisi konfirmasi tiket, tanpa lampiran dokumen
        Mail::assertSent(PengajuanKreditDiterima::class, function (PengajuanKreditDiterima $mail) {
            return ! method_exists($mail, 'attachments');
        });
    }

    public function test_email_internal_tidak_menampilkan_link_admin_secara_bawaan(): void
    {
        $this->post('/pengajuan-kredit', $this->isian());

        $html = (new PengajuanKreditMasuk(PengajuanKredit::first()))->render();

        $this->assertStringNotContainsString('Buka di panel admin', $html);
        $this->assertStringContainsString('Seluruh dokumen pengajuan ikut terlampir', $html);
    }

    public function test_email_internal_menampilkan_link_admin_saat_diaktifkan(): void
    {
        config(['pengajuan-kredit.link_admin' => true]);

        $this->post('/pengajuan-kredit', $this->isian());

        $pengajuan = PengajuanKredit::first();
        $html = (new PengajuanKreditMasuk($pengajuan))->render();

        $this->assertStringContainsString('Buka di panel admin', $html);
        $this->assertStringContainsString($pengajuan->urlAdmin(), $html);
    }

    public function test_email_internal_menampilkan_nik_lengkap_untuk_verifikasi(): void
    {
        $this->post('/pengajuan-kredit', $this->isian());

        $html = (new PengajuanKreditMasuk(PengajuanKredit::first()))->render();

        // NIK tampil penuh supaya cabang bisa memverifikasi saat email diteruskan
        $this->assertStringContainsString('3201010101010001', $html);
        $this->assertStringNotContainsString('************0001', $html);
        $this->assertStringContainsString('NIK ditampilkan lengkap', $html);
    }

    public function test_nik_di_email_internal_bisa_disamarkan_dari_pengaturan_situs(): void
    {
        PengaturanSitus::saatIni()->update(['pengajuan_kredit_samarkan_nik' => true]);

        $this->post('/pengajuan-kredit', $this->isian());

        $html = (new PengajuanKreditMasuk(PengajuanKredit::first()))->render();

        $this->assertStringNotContainsString('3201010101010001', $html);
        $this->assertStringContainsString('************0001', $html);
        $this->assertStringContainsString('NIK disamarkan', $html);
    }

    public function test_pesan_whatsapp_memuat_email_kredit_dan_tautan_webmail(): void
    {
        $this->post('/pengajuan-kredit', $this->isian());

        $pesan = app(PengajuanKreditNotifier::class)->pesan(PengajuanKredit::first());

        $this->assertStringContainsString('kredit@bprbogorjabar.co.id', $pesan);
        $this->assertStringContainsString('https://mail.bprbogorjabar.co.id', $pesan);
        $this->assertStringContainsString('Buka email:', $pesan);
    }

    public function test_email_internal_dikirim_tanpa_lampiran_saat_lampiran_ditolak_server(): void
    {
        $this->post('/pengajuan-kredit', $this->isian());

        $pengajuan = PengajuanKredit::first();
        $pengajuan->forceFill(['email_terkirim_at' => null])->save();

        $jumlahLampiran = [];

        Mail::shouldReceive('to')->andReturnSelf();
        Mail::shouldReceive('send')->andReturnUsing(function ($mailable) use (&$jumlahLampiran) {
            if ($mailable instanceof PengajuanKreditMasuk) {
                $jumlahLampiran[] = count($mailable->attachments());

                if (count($mailable->attachments()) > 0) {
                    throw new RuntimeException('Lampiran melebihi batas server email.');
                }
            }
        });

        app(PengajuanKreditNotifier::class)->kirimEmail($pengajuan->fresh());

        // Percobaan pertama dengan 4 lampiran, lalu ulang tanpa lampiran
        $this->assertSame([4, 0], $jumlahLampiran);
        $this->assertNotNull($pengajuan->fresh()->email_terkirim_at);
    }

    public function test_produk_umkm_wajib_mengisi_data_usaha(): void
    {
        $response = $this->from('/pengajuan-kredit')->post('/pengajuan-kredit', $this->isian([
            'jenis_kredit' => 'umkm',
        ]));

        $response->assertRedirect('/pengajuan-kredit');
        $response->assertSessionHasErrors(['nama_usaha', 'lokasi_usaha', 'file_upload']);
        $this->assertDatabaseCount('pengajuan_kredits', 0);
    }

    public function test_produk_umkm_dengan_data_usaha_dapat_dikirim(): void
    {
        $this->post('/pengajuan-kredit', $this->isian([
            'jenis_kredit' => 'umkm',
            'nama_usaha' => 'Warung Budi',
            'lokasi_usaha' => 'Jl. Pasar No. 2',
            'file_upload' => UploadedFile::fake()->image('sku.jpg', 20, 20),
        ]))->assertRedirect();

        $pengajuan = PengajuanKredit::first();

        $this->assertSame('Warung Budi', $pengajuan->data['nama_usaha']);
        $this->assertArrayHasKey('file_upload', $pengajuan->berkas);
    }

    public function test_validasi_dasar_pengajuan_kredit(): void
    {
        $response = $this->from('/pengajuan-kredit')->post('/pengajuan-kredit', $this->isian([
            'email' => 'bukan-email',
            'nik' => '123',
            'nominal_kredit' => '0',
            'no_hp' => '123',
            'ktp' => '',
            'persetujuan' => null,
        ]));

        $response->assertRedirect('/pengajuan-kredit');
        $response->assertSessionHasErrors([
            'email', 'nik', 'nominal_kredit', 'no_hp', 'ktp', 'persetujuan',
        ]);
        $this->assertDatabaseCount('pengajuan_kredits', 0);
    }

    public function test_berkas_pengajuan_hanya_bisa_diunduh_admin(): void
    {
        $this->post('/pengajuan-kredit', $this->isian());

        $pengajuan = PengajuanKredit::first();

        $this->get(route('pengajuan-kredit.berkas', [$pengajuan, 'ktp']))
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('pengajuan-kredit.berkas', [$pengajuan, 'ktp']))
            ->assertOk();
    }

    public function test_admin_dapat_melihat_daftar_dan_detail_pengajuan(): void
    {
        config(['app.env' => 'local']);

        $this->post('/pengajuan-kredit', $this->isian());

        $pengajuan = PengajuanKredit::first();

        $this->actingAs(User::factory()->create())
            ->get('/admin/pengajuan-kredits')
            ->assertOk()
            ->assertSee($pengajuan->kode)
            ->assertSee('Budi Santoso');

        $this->actingAs(User::factory()->create())
            ->get('/admin/pengajuan-kredits/'.$pengajuan->id.'/edit')
            ->assertOk()
            ->assertSee($pengajuan->kode)
            ->assertSee('Tindak lanjut')
            ->assertSee('Dinas Contoh');
    }
}
