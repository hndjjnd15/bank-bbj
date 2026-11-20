<?php

namespace Database\Seeders;

use App\Models\VideoYoutube;
use Illuminate\Database\Seeder;

class VideoYoutubeSeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            ['judul' => 'Ayok Menabung Bank BBJ', 'id' => 'C-33DUFHPUY'],
            ['judul' => 'Kenali Produk Deposito BBJ', 'id' => 'wQINU3mo4MI'],
            ['judul' => 'Success Story UMKM Baitul Qurro', 'id' => 'LiEVavTX5Io'],
            ['judul' => 'Kenali Investasi', 'id' => 'e-vpWLwvTUE'],
            ['judul' => 'Success Story Durian Mang Asep', 'id' => 'LMuQEnGuWhs'],
        ];

        foreach ($videos as $urutan => $video) {
            VideoYoutube::firstOrCreate(
                ['url' => 'https://www.youtube.com/watch?v='.$video['id']],
                [
                    'judul' => $video['judul'],
                    'urutan' => $urutan + 1,
                    'diterbitkan' => true,
                ],
            );
        }
    }
}
