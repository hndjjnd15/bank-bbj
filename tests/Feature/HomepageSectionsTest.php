<?php

namespace Tests\Feature;

use App\Filament\Resources\BannerBerandas\Pages\CreateBannerBeranda;
use App\Filament\Resources\PopupPromos\Pages\CreatePopupPromo;
use App\Filament\Resources\VideoYoutubes\Pages\CreateVideoYoutube;
use App\Models\BannerBeranda;
use App\Models\Berita;
use App\Models\Lelang;
use App\Models\PopupPromo;
use App\Models\ProdukBank;
use App\Models\SukuBungaDeposito;
use App\Models\User;
use App\Models\VideoYoutube;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class HomepageSectionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_banner_uses_a_portrait_image_on_mobile_and_configured_image_on_desktop(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('media="(max-width: 640px)"', false)
            ->assertSee('images/potret/potret1.png', false)
            ->assertSee('images/web-benner-umkm.webp', false);
    }

    public function test_homepage_uses_the_configured_banner_when_banner_table_is_missing(): void
    {
        Schema::drop('banner_berandas');

        $this->get('/')
            ->assertOk()
            ->assertSee('Mitra keuangan masyarakat dan pelaku usaha')
            ->assertSee('images/web-benner-umkm.webp', false);
    }

    public function test_homepage_displays_active_admin_banners_in_order_with_carousel_controls(): void
    {
        Storage::fake('public');

        BannerBeranda::create([
            'judul' => 'Banner Pertama',
            'teks' => 'Teks banner pertama.',
            'alt' => 'Gambar banner pertama.',
            'gambar_desktop' => 'banner-beranda/desktop-1.webp',
            'gambar_mobile' => 'banner-beranda/mobile-1.webp',
            'durasi_detik' => 5,
            'urutan' => 1,
            'aktif' => true,
        ]);

        BannerBeranda::create([
            'judul' => 'Banner Kedua',
            'teks' => 'Teks banner kedua.',
            'alt' => 'Gambar banner kedua.',
            'gambar_desktop' => 'banner-beranda/desktop-2.webp',
            'durasi_detik' => 8,
            'urutan' => 2,
            'aktif' => true,
        ]);

        BannerBeranda::create([
            'judul' => 'Banner Draft',
            'alt' => 'Gambar banner draft.',
            'gambar_desktop' => 'banner-beranda/draft.webp',
            'durasi_detik' => 6,
            'urutan' => 3,
            'aktif' => false,
        ]);

        $response = $this->get('/')
            ->assertOk()
            ->assertSee('Banner Pertama')
            ->assertSee('Banner Kedua')
            ->assertDontSee('Banner Draft')
            ->assertSee('data-banner-next', false)
            ->assertDontSee('data-banner-toggle', false)
            ->assertSee('data-banner-duration="5"', false)
            ->assertSee('data-banner-duration="8"', false)
            ->assertSee('banner-beranda/mobile-1.webp', false);

        $this->assertLessThan(
            strpos($response->getContent(), 'Banner Kedua'),
            strpos($response->getContent(), 'Banner Pertama'),
        );
    }

    public function test_admin_can_open_banner_beranda_management(): void
    {
        config(['app.env' => 'local']);

        $this->actingAs(User::factory()->create())
            ->get('/admin/banner-beranda')
            ->assertOk()
            ->assertSee('Banner Beranda');
    }

    public function test_admin_can_create_banner_slides_with_duration_and_responsive_images(): void
    {
        config(['app.env' => 'local']);
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateBannerBeranda::class)
            ->fillForm([
                'judul' => 'Banner Baru',
                'teks' => 'Informasi promosi.',
                'alt' => 'Gambar promosi.',
                'gambar_desktop' => UploadedFile::fake()->image('desktop.webp'),
                'gambar_mobile' => UploadedFile::fake()->image('mobile.png'),
                'durasi_detik' => 8,
                'urutan' => 1,
                'aktif' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $banner = BannerBeranda::query()->firstOrFail();

        $this->assertSame(8, $banner->durasi_detik);
        $this->assertTrue($banner->aktif);
        Storage::disk('public')->assertExists($banner->gambar_desktop);
        Storage::disk('public')->assertExists($banner->gambar_mobile);
    }

    public function test_homepage_renders_product_rates_carousels_video_and_default_faq_sections(): void
    {
        foreach (['tabungan', 'deposito', 'kredit'] as $jenis) {
            ProdukBank::create([
                'jenis' => $jenis,
                'judul' => 'Produk '.ucfirst($jenis),
                'slug' => 'produk-'.$jenis,
                'tagline' => 'Informasi '.$jenis,
                'diterbitkan' => true,
            ]);

        }

        SukuBungaDeposito::create([
            'produk_bank_id' => ProdukBank::where('jenis', 'deposito')->firstOrFail()->id,
            'tenor_bulan' => 6,
            'bunga' => 4.25,
        ]);

        Lelang::create([
            'judul' => 'Jaminan Contoh',
            'slug' => 'jaminan-contoh',
            'tipe_aset' => 'Rumah Tinggal',
            'sertifikat' => 'SHM',
            'status' => 'lelang',
            'alamat' => 'Jalan Contoh',
            'kota' => 'Bogor',
            'harga_limit' => 500000000,
            'pic_nama' => 'Marketing',
            'pic_whatsapp' => '081234567890',
            'deskripsi' => 'Informasi jaminan.',
        ]);

        Berita::create([
            'judul' => 'Kegiatan Contoh',
            'slug' => 'kegiatan-contoh',
            'isi' => 'Informasi kegiatan.',
            'tanggal' => today(),
        ]);

        VideoYoutube::create([
            'url' => 'https://youtu.be/M7lc1UVf-VE',
            'urutan' => 1,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Produk dan layanan')
            ->assertSee('Produk Tabungan')
            ->assertSee('Produk Deposito')
            ->assertSee('Produk Kredit')
            ->assertSee('Suku Bunga Produk dan Deposito')
            ->assertSee('Suku Bunga (% / Thn)')
            ->assertSee('Jaminan: LPS')
            ->assertSee('4,25%')
            ->assertSee('Jaminan Contoh')
            ->assertSee('Channel YouTube')
            ->assertSee('youtube-nocookie.com/embed/M7lc1UVf-VE')
            ->assertSee('Kegiatan Contoh')
            ->assertSee('Apa itu BPR?');
    }

    public function test_homepage_shows_a_default_promo_popup_linking_to_the_credit_form(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('data-promo-popup', false)
            ->assertSee('images/pop-up.webp', false)
            ->assertSeeInOrder(['class="promo-popup__tautan"', 'href="/pengajuan-kredit"'], false)
            ->assertSee('Tutup iklan promosi')
            ->assertSee('Lihat penawaran sekarang');

        // Pop-up promosi hanya tampil di beranda.
        $this->get('/faq')->assertOk()->assertDontSee('data-promo-popup', false);
    }

    public function test_homepage_uses_the_popup_promo_managed_in_admin(): void
    {
        PopupPromo::create([
            'judul' => 'Promo Deposito',
            'gambar' => 'popup-promo/promo-deposito.webp',
            'alt' => 'Promo deposito Bank BBJ.',
            'tautan' => '/deposito',
            'urutan' => 1,
            'aktif' => true,
        ]);

        PopupPromo::create([
            'judul' => 'Promo Kedua',
            'gambar' => 'popup-promo/promo-kedua.webp',
            'alt' => 'Promo kedua yang urutannya lebih bawah.',
            'tautan' => '/kredit',
            'urutan' => 2,
            'aktif' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('popup-promo/promo-deposito.webp', false)
            ->assertSeeInOrder(['class="promo-popup__tautan"', 'href="/deposito"'], false)
            ->assertSee('Promo deposito Bank BBJ.')
            ->assertDontSee('promo-kedua.webp', false)
            ->assertDontSee('images/pop-up.webp', false);
    }

    public function test_homepage_hides_the_promo_popup_when_all_entries_are_inactive(): void
    {
        PopupPromo::create([
            'judul' => 'Promo Nonaktif',
            'gambar' => 'popup-promo/nonaktif.webp',
            'alt' => 'Promo yang disembunyikan.',
            'tautan' => '/deposito',
            'urutan' => 1,
            'aktif' => false,
        ]);

        $this->get('/')->assertOk()->assertDontSee('data-promo-popup', false);
    }

    public function test_popup_promo_custom_link_overrides_the_selected_destination(): void
    {
        PopupPromo::create([
            'judul' => 'Promo Tabungan',
            'gambar' => 'popup-promo/promo-tabungan.webp',
            'alt' => 'Promo tabungan Bank BBJ.',
            'tautan' => '/kredit',
            'tautan_kustom' => '/tabungan/tas',
            'urutan' => 1,
            'aktif' => true,
        ]);

        $this->get('/')->assertOk()->assertSee('href="/tabungan/tas"', false);
    }

    public function test_admin_can_open_promo_popup_management(): void
    {
        config(['app.env' => 'local']);

        $this->actingAs(User::factory()->create())
            ->get('/admin/popup-promo')
            ->assertOk()
            ->assertSee('Pop-up Promo')
            ->assertSee('/admin/popup-promo/create', false);
    }

    public function test_admin_can_create_a_promo_popup_with_uploaded_image_and_link(): void
    {
        config(['app.env' => 'local']);
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        Livewire::test(CreatePopupPromo::class)
            ->fillForm([
                'judul' => 'Promo Tabungan',
                'gambar' => UploadedFile::fake()->image('promo-tabungan.jpg', 1600, 900),
                'alt' => 'Promo tabungan Bank BBJ.',
                'tautan' => '/tabungan',
                'urutan' => 1,
                'aktif' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $promo = PopupPromo::query()->firstOrFail();

        $this->assertSame('/tabungan', $promo->tujuan_url);
        $this->assertTrue($promo->aktif);
        Storage::disk('public')->assertExists($promo->gambar);

        $this->get('/')
            ->assertOk()
            ->assertSee(Storage::disk('public')->url($promo->gambar), false)
            ->assertSee('Promo tabungan Bank BBJ.');
    }

    public function test_homepage_youtube_section_uses_one_player_and_a_playlist(): void
    {
        VideoYoutube::create([
            'judul' => 'Profil Bank BBJ',
            'url' => 'https://youtu.be/M7lc1UVf-VE',
            'urutan' => 1,
        ]);

        VideoYoutube::create([
            'judul' => 'Layanan Nasabah',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'urutan' => 2,
        ]);

        $response = $this->get('/')
            ->assertOk()
            ->assertSee('data-video-utama', false)
            ->assertSee('data-video-frame', false)
            ->assertSee('data-video-item', false)
            ->assertSee('data-video-play', false)
            ->assertSee('Profil Bank BBJ')
            ->assertSee('Layanan Nasabah')
            ->assertSee('Tonton di YouTube')
            ->assertSee('href="https://youtu.be/M7lc1UVf-VE"', false)
            ->assertSee('i.ytimg.com/vi/M7lc1UVf-VE/mqdefault.jpg', false)
            ->assertSee('i.ytimg.com/vi/dQw4w9WgXcQ/mqdefault.jpg', false)
            ->assertSee('class="beranda-video__item is-aktif"', false);

        // Hanya pemutar utama yang memakai iframe; sisanya kartu daftar putar.
        $this->assertSame(1, substr_count($response->getContent(), '<iframe'));
    }

    public function test_youtube_links_are_restricted_to_valid_youtube_video_urls(): void
    {
        $this->assertSame('M7lc1UVf-VE', VideoYoutube::idDariUrl('https://www.youtube.com/watch?v=M7lc1UVf-VE'));
        $this->assertSame('M7lc1UVf-VE', VideoYoutube::idDariUrl('https://youtu.be/M7lc1UVf-VE'));
        $this->assertNull(VideoYoutube::idDariUrl('https://example.com/watch?v=M7lc1UVf-VE'));
        $this->assertNull(VideoYoutube::idDariUrl('not-a-url'));
    }

    public function test_admin_can_add_a_youtube_link_without_extra_video_fields(): void
    {
        config(['app.env' => 'local']);
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateVideoYoutube::class)
            ->fillForm(['url' => 'https://example.com/video'])
            ->call('create')
            ->assertHasFormErrors(['url']);

        Livewire::test(CreateVideoYoutube::class)
            ->fillForm(['url' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('video_youtubes', [
            'url' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE',
            'diterbitkan' => true,
        ]);
    }

    public function test_admin_can_add_a_title_to_a_youtube_video(): void
    {
        config(['app.env' => 'local']);
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateVideoYoutube::class)
            ->fillForm([
                'judul' => 'Profil Bank BBJ',
                'url' => 'https://youtu.be/M7lc1UVf-VE',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('video_youtubes', [
            'judul' => 'Profil Bank BBJ',
            'url' => 'https://youtu.be/M7lc1UVf-VE',
        ]);
    }

    public function test_admin_cannot_add_more_than_five_youtube_videos(): void
    {
        config(['app.env' => 'local']);
        $this->actingAs(User::factory()->create());

        foreach (range(1, 5) as $urutan) {
            VideoYoutube::create([
                'url' => 'https://youtu.be/'.sprintf('M7lc1UVf-%02d', $urutan),
                'urutan' => $urutan,
            ]);
        }

        Livewire::test(CreateVideoYoutube::class)
            ->fillForm(['url' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE'])
            ->call('create');

        $this->assertDatabaseCount('video_youtubes', 5);
    }
}
