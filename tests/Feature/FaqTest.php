<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\ProdukBank;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_only_shows_faqs_assigned_to_homepage(): void
    {
        Faq::create([
            'halaman' => 'beranda',
            'tanya' => 'FAQ beranda',
            'jawab' => 'Jawaban beranda',
            'diterbitkan' => true,
        ]);
        Faq::create([
            'halaman' => 'deposito',
            'tanya' => 'FAQ deposito',
            'jawab' => 'Jawaban deposito',
            'diterbitkan' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('FAQ beranda')
            ->assertDontSee('FAQ deposito');
    }

    public function test_product_faq_page_and_faq_index_use_faq_entries(): void
    {
        ProdukBank::create([
            'jenis' => 'deposito',
            'judul' => 'Deposito',
            'slug' => 'deposito',
            'diterbitkan' => true,
        ]);
        Faq::create([
            'halaman' => 'deposito',
            'tanya' => 'Cara membuka deposito?',
            'jawab' => 'Hubungi kantor cabang.',
            'diterbitkan' => true,
        ]);

        $this->get('/deposito')
            ->assertOk()
            ->assertSee('Cara membuka deposito?');

        $this->get('/faq')
            ->assertOk()
            ->assertSee('Produk Deposito')
            ->assertSee('Cara membuka deposito?');
    }

    public function test_faq_index_lists_a_question_only_once_when_it_is_placed_twice(): void
    {
        foreach (['beranda', 'deposito'] as $halaman) {
            Faq::create([
                'halaman' => $halaman,
                'tanya' => 'Pertanyaan ganda',
                'jawab' => 'Jawaban sama.',
                'diterbitkan' => true,
            ]);
        }

        $html = $this->get('/faq')->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, 'Pertanyaan ganda'));
        $this->assertSame(1, substr_count(strip_tags($html), 'dari 1 kategori'));
    }
}
