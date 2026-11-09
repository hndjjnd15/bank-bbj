<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    public const JENIS = [
        'deposito' => 'Deposito',
        'tabungan' => 'Tabungan',
        'kredit' => 'Kredit',
    ];

    protected $fillable = [
        'jenis', 'nama', 'slug', 'tagline', 'deskripsi', 'fitur', 'syarat', 'ketentuan',
        'nominal_minimal', 'bunga_berlaku_sejak', 'suku_bunga', 'pajak_persen', 'batas_bebas_pajak', 'catatan',
        'brosur_gambar', 'brosur_pdf', 'faq', 'wa_marketing', 'diterbitkan',
    ];

    protected $casts = [
        'suku_bunga' => 'array',
        'brosur_gambar' => 'array',
        'faq' => 'array',
        'bunga_berlaku_sejak' => 'date',
        'pajak_persen' => 'float',
        'diterbitkan' => 'boolean',
    ];

    // ---------- teks berbaris -> daftar poin ----------
    public static function poin(?string $teks): array
    {
        return collect(preg_split('/\R/', (string) $teks))
            ->map(fn ($b) => trim(preg_replace('/^\s*[-*\x{2022}]+\s*/u', '', $b)))
            ->filter()
            ->values()
            ->all();
    }

    public function getFiturPoinAttribute(): array { return self::poin($this->fitur); }
    public function getSyaratPoinAttribute(): array { return self::poin($this->syarat); }
    public function getKetentuanPoinAttribute(): array { return self::poin($this->ketentuan); }
    public function getCatatanPoinAttribute(): array { return self::poin($this->catatan); }

    // ---------- brosur ----------
    private static function url(string $path): string
    {
        return (str_starts_with($path, '/') || str_starts_with($path, 'http')) ? $path : asset('storage/' . $path);
    }

    public function getBrosurGambarUrlsAttribute(): array
    {
        return collect($this->brosur_gambar ?? [])->filter()->map(fn ($p) => self::url($p))->values()->all();
    }

    public function getBrosurPdfUrlAttribute(): ?string
    {
        return $this->brosur_pdf ? self::url($this->brosur_pdf) : null;
    }

    // ---------- WhatsApp ----------
    public function getWaUrlAttribute(): ?string
    {
        $nomor = preg_replace('/\D+/', '', (string) $this->wa_marketing);
        if ($nomor === '') {
            return null;
        }
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        } elseif (str_starts_with($nomor, '8')) {
            $nomor = '62' . $nomor;
        }

        return 'https://wa.me/' . $nomor . '?text=' . rawurlencode('Halo, saya ingin bertanya tentang ' . $this->nama . '.');
    }

    // ---------- suku bunga ----------
    // Baris suku bunga yang valid, dengan angka sudah dibulatkan ke tipe yang benar
    public function getBarisBungaAttribute(): array
    {
        return collect($this->suku_bunga ?? [])
            ->filter(fn ($r) => isset($r['tenor_bulan'], $r['nominal_min'], $r['bunga']) && $r['tenor_bulan'] !== '' && $r['bunga'] !== '')
            ->map(fn ($r) => [
                'tenor' => (int) $r['tenor_bulan'],
                'min' => (int) $r['nominal_min'],
                'maks' => isset($r['nominal_maks']) && $r['nominal_maks'] !== '' ? (int) $r['nominal_maks'] : null,
                'bunga' => (float) $r['bunga'],
            ])
            ->values()
            ->all();
    }

    // "Rp 5 Juta", "Rp 1 Miliar", "Rp 2,5 Juta"
    public static function rupiahSingkat(int $n): string
    {
        if ($n >= 1_000_000_000) {
            return 'Rp ' . rtrim(rtrim(number_format($n / 1_000_000_000, 2, ',', '.'), '0'), ',') . ' Miliar';
        }
        if ($n >= 1_000_000) {
            return 'Rp ' . rtrim(rtrim(number_format($n / 1_000_000, 2, ',', '.'), '0'), ',') . ' Juta';
        }

        return 'Rp ' . number_format($n, 0, ',', '.');
    }

    public static function persen(float $n): string
    {
        return number_format($n, 2, ',', '.') . '%';
    }

    // Tabel suku bunga siap tampil: baris = tenor, kolom = rentang nominal
    // ['tenors' => [1, 3, ...], 'tiers' => [['min' =>, 'maks' =>, 'label' =>], ...], 'sel' => [tenor][indeksTier] => bunga]
    public function getTabelBungaAttribute(): array
    {
        $baris = collect($this->baris_bunga);

        $tiers = $baris
            ->map(fn ($r) => ['min' => $r['min'], 'maks' => $r['maks']])
            ->unique(fn ($t) => $t['min'] . '-' . ($t['maks'] ?? 'x'))
            ->sortBy('min')
            ->values()
            ->map(function ($t) {
                $t['label'] = $t['maks'] === null
                    ? 'Mulai ' . self::rupiahSingkat($t['min'])
                    : self::rupiahSingkat($t['min']) . ' s.d. < ' . self::rupiahSingkat($t['maks']);

                return $t;
            })
            ->all();

        $tenors = $baris->pluck('tenor')->unique()->sort()->values()->all();

        $sel = [];
        foreach ($baris as $r) {
            foreach ($tiers as $i => $t) {
                if ($t['min'] === $r['min'] && $t['maks'] === $r['maks']) {
                    $sel[$r['tenor']][$i] = $r['bunga'];
                }
            }
        }

        return compact('tenors', 'tiers', 'sel');
    }

    // Ringkasan untuk chip di bagian atas halaman (dihitung dari data, bukan diketik manual)
    public function getBungaTertinggiAttribute(): ?float
    {
        $b = collect($this->baris_bunga)->max('bunga');

        return $b !== null ? (float) $b : null;
    }

    public function getNominalMulaiAttribute(): ?int
    {
        if ($this->nominal_minimal) {
            return (int) $this->nominal_minimal;
        }
        $m = collect($this->baris_bunga)->min('min');

        return $m !== null ? (int) $m : null;
    }

    // Data untuk kalkulator (dikirim ke JavaScript)
    public function getDataKalkulatorAttribute(): array
    {
        return [
            'rates' => $this->baris_bunga,
            'tenors' => $this->tabel_bunga['tenors'],
            'pajak' => $this->pajak_persen ?? 0,
            'batasPajak' => (int) ($this->batas_bebas_pajak ?? 0),
            'minimal' => $this->nominal_mulai ?? 0,
        ];
    }
}
