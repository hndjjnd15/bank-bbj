<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(['email' => 'sobatcare@bprbogorjabar.co.id'], [
            'name' => 'sobatcare',
            'password' => bcrypt('admin123'),
        ]);

        $this->call([
            BeritaSeeder::class,
            DokumenPpidSeeder::class,
            KantorCabangSeeder::class,
            KreditSeeder::class,
            LaporanSeeder::class,
            LelangSeeder::class,
            LowonganSeeder::class,
            PengaturanSitusSeeder::class,
            ProdukDepositoSeeder::class,
            ProdukTabunganSeeder::class,
            ProfilSeeder::class,
            ProgramCsrSeeder::class,
            VideoYoutubeSeeder::class,
        ]);
    }
}
