<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        // Membuka file CSV
        $csvPath = base_path('database/dataset_berita.csv');
        
        // Memastikan file ada sebelum diproses
        if (!file_exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan di folder database!");
            return;
        }

        $csvFile = fopen($csvPath, "r");
        $count = 1;
        
        // Membaca baris demi baris
        while (($line = fgets($csvFile)) !== false) {
            // Abaikan baris yang kosong
            if (trim($line) == '') continue;

            // Masukkan data ke PostgreSQL
            Article::create([
                'title'   => 'Berita Ke-' . $count, // Judul dibuat otomatis
                'content' => trim($line) // Seluruh baris teks dimasukkan ke konten
            ]);
            
            $count++;
        }
        
        fclose($csvFile);
    }
}