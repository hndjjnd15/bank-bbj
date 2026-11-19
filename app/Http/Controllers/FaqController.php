<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class FaqController extends Controller
{
    // Halaman FAQ menampilkan seluruh FAQ yang diterbitkan, dikelompokkan menurut penempatan.
    public function index()
    {
        $daftar = Faq::query()
            ->terbit()
            ->get()
            // Satu pertanyaan boleh dipasang di beberapa halaman (mis. beranda dan deposito).
            // Tampilkan sekali saja: utamakan halaman produk sebagai kategorinya, bukan "beranda".
            ->sortBy(fn (Faq $item): int => $item->halaman === 'beranda' ? 1 : 0)
            ->unique(fn (Faq $item): string => $item->tanya."\u{1F}".$item->jawab)
            ->groupBy('halaman')
            ->map(fn ($faq, string $halaman) => [
                'slug' => $halaman,
                'judul' => Faq::HALAMAN[$halaman] ?? $halaman,
                'faq' => $faq,
            ])
            ->values();

        $total = $daftar->sum(fn (array $grup) => $grup['faq']->count());

        return view('faq.index', compact('daftar', 'total'));
    }
}
