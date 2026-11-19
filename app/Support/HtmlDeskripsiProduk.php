<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

class HtmlDeskripsiProduk
{
    private const TAG_YANG_DIIZINKAN = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's',
        'h2', 'h3', 'h4', 'small', 'ul', 'ol', 'li', 'blockquote',
    ];

    private const TAG_YANG_DIHAPUS = [
        'script', 'style', 'iframe', 'object', 'embed', 'svg', 'math',
    ];

    public static function bersihkan(?string $html): string
    {
        if (blank($html)) {
            return '';
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $sebelumnya = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<?xml encoding="UTF-8"><div id="deskripsi-produk">'.$html.'</div>',
            LIBXML_HTML_NODEFDTD | LIBXML_NONET,
        );
        libxml_clear_errors();
        libxml_use_internal_errors($sebelumnya);

        $wadah = $document->getElementById('deskripsi-produk');

        return $wadah ? self::isiAman($wadah) : e($html);
    }

    /**
     * Ubah deskripsi hasil editor kaya (HTML) menjadi teks biasa.
     * Dipakai saat jenis produk diubah dari kredit ke tabungan/deposito,
     * yang halamannya menampilkan deskripsi sebagai teks, bukan HTML.
     */
    public static function teksBiasa(?string $html): string
    {
        if (blank($html)) {
            return '';
        }

        $teks = (string) preg_replace('#<br\s*/?>#i', "\n", $html);
        $teks = (string) preg_replace('#<li[^>]*>#i', '- ', $teks);
        $teks = (string) preg_replace('#</li>#i', "\n", $teks);
        $teks = (string) preg_replace('#</(p|div|h[1-6]|blockquote|ul|ol)>#i', "\n\n", $teks);
        $teks = html_entity_decode(strip_tags($teks), ENT_QUOTES, 'UTF-8');

        return trim((string) preg_replace("/\n{3,}/", "\n\n", $teks));
    }

    private static function isiAman(DOMNode $node): string
    {
        $hasil = '';

        foreach ($node->childNodes as $anak) {
            if ($anak instanceof DOMText) {
                $hasil .= htmlspecialchars($anak->nodeValue ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

                continue;
            }

            if (! $anak instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($anak->tagName);

            if (in_array($tag, self::TAG_YANG_DIHAPUS, true)) {
                continue;
            }

            $isi = self::isiAman($anak);

            if (! in_array($tag, self::TAG_YANG_DIIZINKAN, true)) {
                $hasil .= $isi;

                continue;
            }

            $hasil .= $tag === 'br' ? '<br>' : '<'.$tag.'>'.$isi.'</'.$tag.'>';
        }

        return $hasil;
    }
}
