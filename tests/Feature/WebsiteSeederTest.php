<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_loads_reference_site_content_without_media_and_is_repeatable(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('beritas', 3);
        $this->assertDatabaseCount('kantor_cabangs', 4);
        $this->assertDatabaseCount('lelangs', 21);
        $this->assertDatabaseCount('produk_banks', 12);
        $this->assertDatabaseCount('video_youtubes', 5);

        $this->assertDatabaseHas('beritas', [
            'slug' => 'kerja-sama-bank-bjb-cabang-cibinong',
            'foto' => null,
        ]);
        $this->assertDatabaseHas('produk_banks', [
            'slug' => 'tamasya',
            'foto_utama' => null,
            'flyer' => null,
            'brosur_pdf' => null,
        ]);
        $this->assertDatabaseHas('lelangs', [
            'slug' => 'kantor-bbj',
            'foto_utama' => null,
            'galeri' => null,
        ]);
        $this->assertDatabaseHas('kantor_cabangs', [
            'slug' => 'kantor-pusat-parungpanjang',
            'foto_utama' => null,
            'galeri' => null,
        ]);

        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('beritas', 3);
        $this->assertDatabaseCount('kantor_cabangs', 4);
        $this->assertDatabaseCount('lelangs', 21);
        $this->assertDatabaseCount('produk_banks', 12);
        $this->assertDatabaseCount('video_youtubes', 5);
    }
}
