<?php

namespace App\Http\Controllers;

use App\Models\BannerBeranda;
use App\Models\Berita;
use App\Models\Faq;
use App\Models\Lelang;
use App\Models\PopupPromo;
use App\Models\ProdukBank;
use App\Models\SukuBungaDeposito;
use App\Models\VideoYoutube;
use Illuminate\Support\Facades\Schema;

class BerandaController extends Controller
{
    public function index()
    {
        $produkAktif = ProdukBank::query()
            ->where('diterbitkan', true)
            ->orderBy('id')
            ->get()
            ->groupBy('jenis');

        $layanan = collect(config('beranda.layanan'))
            ->map(function (array $pengaturan, string $jenis) use ($produkAktif): array {
                $produk = $produkAktif->get($jenis)?->first();

                return [
                    ...$pengaturan,
                    'produk' => $produk,
                    'judul' => $produk?->judul ?? $pengaturan['nama'],
                    'tagline' => $produk?->tagline ?: $pengaturan['deskripsi'],
                    'gambar' => $produk?->foto_url ?: asset($pengaturan['gambar']),
                ];
            });

        $sukuBunga = SukuBungaDeposito::query()
            ->whereHas('produk', fn ($query) => $query
                ->whereIn('jenis', ['deposito', 'tabungan'])
                ->where('diterbitkan', true))
            ->with('produk')
            ->orderBy('produk_bank_id')
            ->orderBy('tenor_bulan')
            ->get();

        $lelang = Lelang::query()
            ->where('status', '!=', 'terjual')
            ->latest()
            ->take(8)
            ->get();

        $berita = Berita::query()
            ->orderByDesc('tanggal')
            ->take(8)
            ->get();

        $videoYoutube = VideoYoutube::query()
            ->tayang()
            ->take(5)
            ->get()
            ->filter(fn (VideoYoutube $video): bool => $video->embed_url !== null)
            ->values();

        $faqAdmin = Faq::query()
            ->terbit()
            ->where('halaman', 'beranda')
            ->get()
            ->map(fn (Faq $item): array => [
                'tanya' => $item->tanya,
                'jawab' => $item->jawab,
            ]);

        $faq = collect(config('beranda.faq'))
            ->merge($faqAdmin)
            ->unique('tanya')
            ->take(7)
            ->values();

        $bannerSlides = collect();
        if (Schema::hasTable('banner_berandas')) {
            $bannerSlides = BannerBeranda::query()
                ->tayang()
                ->get()
                ->map(fn (BannerBeranda $banner): array => [
                    'judul' => $banner->judul,
                    'teks' => $banner->teks,
                    'alt' => $banner->alt,
                    'gambar' => $banner->gambar_desktop_url,
                    'gambar_mobile' => $banner->gambar_mobile_url,
                    'durasi_detik' => $banner->durasi_detik,
                ]);
        }

        if ($bannerSlides->isEmpty()) {
            $banner = config('beranda.banner');
            $bannerSlides = collect([[
                ...$banner,
                'gambar' => asset($banner['gambar']),
                'gambar_mobile' => asset('images/potret/potret1.png'),
                'durasi_detik' => 6,
            ]]);
        }

        // Pop-up promo: satu pop-up aktif dengan urutan terkecil.
        // Jika tabel belum dipakai sama sekali, tampilkan gambar bawaan lama.
        $popupPromo = null;
        $popupDefault = true;

        if (Schema::hasTable('popup_promos')) {
            $popupPromo = PopupPromo::query()->tayang()->first();
            $popupDefault = ! PopupPromo::query()->exists();
        }

        return view('beranda', [
            'bannerSlides' => $bannerSlides,
            'layanan' => $layanan,
            'sukuBunga' => $sukuBunga,
            'lelang' => $lelang,
            'videoYoutube' => $videoYoutube,
            'berita' => $berita,
            'faq' => $faq,
            'popupPromo' => $popupPromo,
            'popupDefault' => $popupDefault,
        ]);
    }
}
