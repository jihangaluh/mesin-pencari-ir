<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class SearchController extends Controller
{
    // 1. Menampilkan halaman pencarian awal
    public function index()
    {
        return view('search_page');
    }

    // 2. Otak Algoritma Information Retrieval (TF-IDF)
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        // Jika user tidak mengetik apa-apa, kembalikan ke halaman awal
        if (!$query) {
            return redirect('/');
        }

        // Ambil semua 499 data berita dari PostgreSQL
        $articles = Article::all();
        $N = $articles->count(); // Total dokumen (N)

        // Text Preprocessing: Ubah kata kunci jadi huruf kecil & bersihkan simbol
        $cleanQuery = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $query));
        $keywords = array_filter(explode(' ', $cleanQuery));

        if (empty($keywords)) {
            return view('search_page', ['results' => [], 'query' => $query]);
        }

        // Tahap 1: Menghitung DF (Document Frequency)
        // Berapa banyak dokumen yang mengandung kata kunci tersebut?
        $df = [];
        foreach ($keywords as $keyword) {
            $df[$keyword] = 0;
            foreach ($articles as $article) {
                if (str_contains(strtolower($article->content), $keyword)) {
                    $df[$keyword]++;
                }
            }
        }

        // Tahap 2: Menghitung TF-IDF dan Total Skor per Dokumen
        $results = [];
        foreach ($articles as $article) {
            $score = 0;
            $contentLower = strtolower($article->content);

            foreach ($keywords as $keyword) {
                // TF (Term Frequency): Berapa kali kata kunci muncul di 1 dokumen
                $tf = substr_count($contentLower, $keyword);
                
                // IDF (Inverse Document Frequency): Semakin jarang kata itu di dokumen lain, semakin penting maknanya
                $idf = ($df[$keyword] > 0) ? log10($N / $df[$keyword]) : 0;
                
                // Total Skor Relevansi (W) = TF x IDF
                $score += ($tf * $idf);
            }

            // Jika dokumen memiliki skor lebih dari 0, masukkan ke daftar hasil
            if ($score > 0) {
                $article->score = $score; 
                $results[] = $article;
            }
        }

        // Tahap 3: Ranking (Urutkan dari skor relevansi paling tinggi ke rendah)
        usort($results, function($a, $b) {
            return $b->score <=> $a->score;
        });

        // Kirim hasil pencarian ke tampilan web
        return view('search_page', ['results' => $results, 'query' => $query]);
    }
}