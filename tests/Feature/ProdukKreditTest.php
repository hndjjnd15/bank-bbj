<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\ProdukBank;
use App\Support\HtmlDeskripsiProduk;
use Database\Seeders\KreditSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProdukKreditTest extends TestCase
{
    use RefreshDatabase;

    public function test_kredit_index_lists_published_credit_products(): void
    {
        $this->produkKredit(['judul' => 'Kredit UMKM', 'slug' => 'kredit-umkm']);
        $this->produkKredit(['judul' => 'Kredit ASN', 'slug' => 'kredit-asn', 'diterbitkan' => false]);

        $this->get('/kredit')
            ->assertOk()
            ->assertSee('Kredit UMKM')
            ->assertDontSee('Kredit ASN')
            ->assertSee('/kredit/kredit-umkm')
            ->assertDontSee('download', false);
    }

    public function test_kredit_detail_shows_configured_calculator_brochure_and_contact(): void
    {
        $produk = $this->produkKredit([
            'judul' => 'Kredit UMKM',
            'slug' => 'kredit-umkm',
            'deskripsi' => '<p><strong>Fasilitas kredit</strong> untuk usaha kecil.</p><ul><li>Informasi persyaratan.</li></ul>',
            'flyer' => ['/images/flyer-umkm.webp'],
            'brosur_pdf' => 'produk/brosur-umkm.pdf',
            'simulasi_aktif' => true,
            'suku_bunga_kredit' => 12,
            'biaya_admin_persen' => 1.5,
            'kontak_whatsapp' => '08123456789',
        ]);
        Faq::create([
            'halaman' => 'kredit',
            'produk_bank_id' => $produk->id,
            'tanya' => 'Siapa yang dapat mengajukan?',
            'jawab' => 'Hubungi kantor cabang untuk informasi persyaratan.',
            'diterbitkan' => true,
        ]);
        $this->produkKredit(['judul' => 'Kredit Pegawai', 'slug' => 'kredit-pegawai']);

        $this->get('/kredit/'.$produk->slug)
            ->assertOk()
            ->assertSee('<strong>Fasilitas kredit</strong>', false)
            ->assertSee('<ul><li>Informasi persyaratan.</li></ul>', false)
            ->assertSee('data-kalkulator-kredit', false)
            ->assertSee('12,00% p.a.')
            ->assertSee('data-biaya-admin-persen="1.50"', false)
            ->assertSee('data-nominal-maks="300000000"', false)
            ->assertSee('data-kredit-tenor="60"', false)
            ->assertSee('data-kredit-pencairan', false)
            ->assertSee('3Bln')
            ->assertSee('1,50%', false)
            ->assertSee('https://wa.me/628123456789')
            ->assertSee('Siapa yang dapat mengajukan?')
            ->assertSee('Produk kredit lainnya')
            ->assertSee('/images/flyer-umkm.webp')
            ->assertSee('brosur-umkm.pdf')
            ->assertDontSee('Keunggulan')
            ->assertDontSee('Setoran minimal');
    }

    public function test_kredit_detail_omits_calculator_when_disabled(): void
    {
        $produk = $this->produkKredit([
            'judul' => 'Kredit MOU',
            'slug' => 'kredit-mou',
            'simulasi_aktif' => false,
        ]);

        $this->get('/kredit/'.$produk->slug)
            ->assertOk()
            ->assertDontSee('data-kalkulator-kredit', false)
            ->assertDontSee('Simulasi Cicilan')
            ->assertSee('FAQ Kredit')
            ->assertSee('Belum ada FAQ kredit yang ditambahkan.');
    }

    public function test_kredit_faqs_are_scoped_to_their_products(): void
    {
        $umkm = $this->produkKredit(['judul' => 'Kredit UMKM', 'slug' => 'kredit-umkm']);
        $pns = $this->produkKredit(['judul' => 'Kredit PNS', 'slug' => 'kredit-pns']);

        Faq::create([
            'halaman' => 'kredit',
            'produk_bank_id' => $umkm->id,
            'tanya' => 'FAQ khusus UMKM',
            'jawab' => 'Jawaban UMKM.',
            'diterbitkan' => true,
        ]);
        Faq::create([
            'halaman' => 'kredit',
            'produk_bank_id' => $pns->id,
            'tanya' => 'FAQ khusus PNS',
            'jawab' => 'Jawaban PNS.',
            'diterbitkan' => true,
        ]);
        Faq::create([
            'halaman' => 'kredit',
            'tanya' => 'FAQ umum lama',
            'jawab' => 'Jawaban umum.',
            'diterbitkan' => true,
        ]);

        $this->get('/kredit/kredit-umkm')
            ->assertOk()
            ->assertSee('FAQ khusus UMKM')
            ->assertDontSee('FAQ umum lama')
            ->assertDontSee('FAQ khusus PNS');

        $this->get('/kredit/kredit-pns')
            ->assertOk()
            ->assertSee('FAQ khusus PNS')
            ->assertDontSee('FAQ umum lama')
            ->assertDontSee('FAQ khusus UMKM');
    }

    public function test_kredit_seeder_adds_the_six_requested_products_without_overwriting_edits(): void
    {
        $this->seed(KreditSeeder::class);

        $this->assertSame(6, ProdukBank::query()->where('jenis', 'kredit')->count());

        ProdukBank::query()->where('slug', 'kredit-umkm')->update(['judul' => 'Nama hasil edit admin']);
        $this->seed(KreditSeeder::class);

        $this->assertSame(6, ProdukBank::query()->where('jenis', 'kredit')->count());
        $this->assertSame('Nama hasil edit admin', ProdukBank::query()->where('slug', 'kredit-umkm')->value('judul'));
    }

    public function test_rich_description_strips_unsafe_markup_and_attributes(): void
    {
        $html = HtmlDeskripsiProduk::bersihkan('<p onclick="alert(1)"><strong>Aman</strong><script>alert(2)</script></p>');

        $this->assertSame('<p><strong>Aman</strong></p>', $html);
    }

    private function produkKredit(array $overrides = []): ProdukBank
    {
        return ProdukBank::create(array_merge([
            'jenis' => 'kredit',
            'judul' => 'Produk Kredit',
            'slug' => 'produk-kredit',
            'diterbitkan' => true,
            'simulasi_aktif' => false,
            'biaya_admin_persen' => 0,
        ], $overrides));
    }
}
