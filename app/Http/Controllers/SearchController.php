<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class SearchController extends Controller
{
    /**
     * Halaman Awal
     */
    public function index()
    {
        return view('search_page');
    }

    /**
     * Proses Pencarian TF-IDF
     */
    public function search(Request $request)
    {
        // Mulai hitung waktu pencarian
        $start = microtime(true);

        $query = trim($request->input('q'));

        if (empty($query)) {
            return redirect('/');
        }

        // Ambil semua artikel
        $articles = Article::all();

        $N = $articles->count();

        if ($N == 0) {

            return view('search_page', [
                'results' => [],
                'query' => $query,
                'executionTime' => 0
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | PREPROCESSING QUERY
        |--------------------------------------------------------------------------
        */

        $cleanQuery = strtolower(
            preg_replace('/[^a-zA-Z0-9\s]/', '', $query)
        );

        $keywords = array_filter(
            explode(' ', $cleanQuery)
        );

        if (empty($keywords)) {

            return view('search_page', [
                'results' => [],
                'query' => $query,
                'executionTime' => 0
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | DOCUMENT FREQUENCY (DF)
        |--------------------------------------------------------------------------
        */

        $df = [];

        foreach ($keywords as $keyword) {

            $df[$keyword] = 0;

            foreach ($articles as $article) {

                $content = strtolower($article->content);

                if (str_contains($content, $keyword)) {
                    $df[$keyword]++;
                }

            }

        }

        /*
        |--------------------------------------------------------------------------
        | TF-IDF CALCULATION
        |--------------------------------------------------------------------------
        */

        $results = [];

        foreach ($articles as $article) {

            $score = 0;

            $content = strtolower($article->content);

            foreach ($keywords as $keyword) {

                // TF (Term Frequency)
                $tf = substr_count(
                    $content,
                    $keyword
                );

                // IDF (Inverse Document Frequency)
                $idf = ($df[$keyword] > 0)
                    ? log10($N / $df[$keyword])
                    : 0;

                // TF-IDF
                $score += ($tf * $idf);

            }

            if ($score > 0) {

                $article->score = $score;

                $results[] = $article;

            }

        }
                /*
        |--------------------------------------------------------------------------
        | HAPUS DUPLIKAT BERDASARKAN ISI BERITA
        |--------------------------------------------------------------------------
        */

        $results = collect($results)
            ->unique('content')
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | SORTING BERDASARKAN SCORE TF-IDF
        |--------------------------------------------------------------------------
        */

        usort($results, function ($a, $b) {

            return $b->score <=> $a->score;

        });

        /*
        |--------------------------------------------------------------------------
        | STATISTIK DASHBOARD
        |--------------------------------------------------------------------------
        */

        $totalDocuments = $N;

        $totalKeywords = count($keywords);

        $foundDocuments = count($results);

        $topScore = count($results)
            ? $results[0]->score
            : 0;

        /*
        |--------------------------------------------------------------------------
        | EXECUTION TIME (MS)
        |--------------------------------------------------------------------------
        */

        $executionTime =
            (microtime(true) - $start) * 1000;

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('search_page', [

            'results' => $results,

            'query' => $query,

            'executionTime' => $executionTime,

            'totalDocuments' => $totalDocuments,

            'totalKeywords' => $totalKeywords,

            'foundDocuments' => $foundDocuments,

            'topScore' => $topScore

        ]);
    }
}