<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeritaSearch - Hasil Pencarian</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>

        *{
            font-family:'Plus Jakarta Sans',sans-serif;
        }

        body{
            background:
            radial-gradient(circle at top left,#06b6d420,transparent 30%),
            radial-gradient(circle at bottom right,#2563eb20,transparent 40%),
            #020617;
            overflow-x:hidden;
            transition:.3s;
        }

        .glass{
            background:rgba(255,255,255,.05);
            backdrop-filter:blur(20px);
            border:1px solid rgba(255,255,255,.08);
        }

        .result-card{
            transition:.4s ease;
        }

        .result-card:hover{
            transform:translateY(-8px) scale(1.01);
            border-color:#22d3ee;
            box-shadow:0 20px 50px rgba(34,211,238,.15);
        }

        .glow{
            position:fixed;
            border-radius:999px;
            filter:blur(150px);
            z-index:-1;
        }

        .glow1{
            width:500px;
            height:500px;
            background:#06b6d4;
            top:-100px;
            left:-100px;
            opacity:.12;
        }

        .glow2{
            width:600px;
            height:600px;
            background:#2563eb;
            right:-150px;
            bottom:-150px;
            opacity:.12;
        }

        @keyframes fadeUp{
            from{
                opacity:0;
                transform:translateY(40px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        .animate-card{
            animation:fadeUp .7s ease forwards;
        }

        .search-input:focus{
            box-shadow:0 0 0 4px rgba(34,211,238,.25);
        }

        .light{
            background:#f8fafc !important;
            color:#0f172a !important;
        }

        .light .glass{
            background:white;
            color:#0f172a;
            border:1px solid #e2e8f0;
        }

        .light .text-slate-300{
            color:#475569 !important;
        }

        .light .text-slate-400{
            color:#64748b !important;
        }

    </style>

</head>

<body class="min-h-screen text-white">

<div class="glow glow1"></div>
<div class="glow glow2"></div>

<div class="max-w-7xl mx-auto px-6 py-10">

    <!-- HEADER -->

    <div class="glass rounded-[40px] p-10 mb-10 relative">

        <button
            id="themeToggle"
            class="absolute right-8 top-8 glass px-4 py-3 rounded-xl hover:scale-105 transition">

            🌙

        </button>

        <h1 class="text-center text-6xl font-extrabold mb-4">

            <a href="/"
                class="bg-gradient-to-r from-cyan-400 via-blue-500 to-cyan-300 bg-clip-text text-transparent">

                BeritaSearch

            </a>

        </h1>

        <p class="text-center text-slate-400 text-xl mb-5">
            Sistem Temu Kembali Informasi Berita Menggunakan Metode TF-IDF
        </p>

        <div class="flex justify-center gap-3 mb-8 flex-wrap">

            <span class="px-4 py-2 rounded-full bg-cyan-500/20 border border-cyan-500/30 text-cyan-300">
                🔍 Information Retrieval
            </span>

            <span class="px-4 py-2 rounded-full bg-blue-500/20 border border-blue-500/30 text-blue-300">
                🧠 TF-IDF
            </span>

            <span class="px-4 py-2 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300">
                🗄 PostgreSQL
            </span>

            <span class="px-4 py-2 rounded-full bg-purple-500/20 border border-purple-500/30 text-purple-300">
                ⚡ Laravel
            </span>

        </div>

        <form action="{{ route('search') }}" method="GET">

            <div class="flex flex-col md:flex-row gap-4">

                <input
                    type="text"
                    name="q"
                    value="{{ $query ?? '' }}"
                    placeholder="Masukkan kata kunci berita..."
                    class="search-input flex-1 px-8 py-5 rounded-3xl text-slate-800 text-lg outline-none">

                <button
                    id="searchBtn"
                    type="submit"
                    class="px-10 py-5 rounded-3xl bg-gradient-to-r from-blue-600 to-cyan-500 font-bold text-lg hover:scale-105 transition">

                    🔍 Cari

                </button>

            </div>

        </form>

    </div>

    @if(isset($results))

    <!-- DASHBOARD -->

    <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-5 mb-10">

        <div class="glass rounded-3xl p-6">

            <div class="text-4xl">🔍</div>

            <p class="text-slate-400 mt-3">
                Keyword
            </p>

            <h3 class="font-bold text-xl mt-2">
                {{ $query }}
            </h3>

        </div>

        <div class="glass rounded-3xl p-6">

            <div class="text-4xl">📄</div>

            <p class="text-slate-400 mt-3">
                Total Hasil
            </p>

            <h3
                class="font-bold text-xl mt-2 counter"
                data-target="{{ count($results) }}">
                0
            </h3>

        </div>

        <div class="glass rounded-3xl p-6">

            <div class="text-4xl">🧠</div>

            <p class="text-slate-400 mt-3">
                Metode
            </p>

            <h3 class="font-bold text-xl mt-2">
                TF-IDF
            </h3>

        </div>

        <div class="glass rounded-3xl p-6">

            <div class="text-4xl">🏆</div>

            <p class="text-slate-400 mt-3">
                Top Score
            </p>

            <h3 class="font-bold text-xl mt-2">
                {{ count($results) ? number_format($results[0]->score,4) : 0 }}
            </h3>

        </div>

        <div class="glass rounded-3xl p-6">

            <div class="text-4xl">⚡</div>

            <p class="text-slate-400 mt-3">
                Waktu Pencarian
            </p>

            <h3 class="font-bold text-xl mt-2">
                {{ number_format($executionTime ?? 0,2) }} ms
            </h3>

        </div>

    </div>
        <!-- CHART TF-IDF -->

    <div class="glass rounded-[35px] p-8 mb-10">

        <div class="flex items-center justify-between mb-6">

            <h2 class="text-2xl font-bold">
                📊 Visualisasi Skor TF-IDF
            </h2>

            <span class="px-4 py-2 rounded-xl bg-cyan-500/20 text-cyan-300 text-sm">
                Top 10 Dokumen
            </span>

        </div>

        <canvas id="tfidfChart" height="100"></canvas>

    </div>

    <!-- HASIL PENCARIAN -->

    <div class="space-y-8">

        @foreach($results as $index => $item)

        <div class="glass rounded-[35px] p-8 result-card animate-card">

            <div class="flex flex-col lg:flex-row justify-between gap-8">

                <div class="flex-1">

                    @if($index == 0)

                    <span class="inline-flex items-center gap-2 bg-yellow-500/20 border border-yellow-500/30 text-yellow-300 px-5 py-2 rounded-full">
                        🥇 Ranking #1
                    </span>

                    @elseif($index == 1)

                    <span class="inline-flex items-center gap-2 bg-slate-500/20 border border-slate-500/30 text-slate-200 px-5 py-2 rounded-full">
                        🥈 Ranking #2
                    </span>

                    @elseif($index == 2)

                    <span class="inline-flex items-center gap-2 bg-orange-500/20 border border-orange-500/30 text-orange-300 px-5 py-2 rounded-full">
                        🥉 Ranking #3
                    </span>

                    @else

                    <span class="inline-flex items-center gap-2 bg-cyan-500/20 border border-cyan-500/30 text-cyan-300 px-5 py-2 rounded-full">
                        🏅 Ranking #{{ $index+1 }}
                    </span>

                    @endif

                    <h2 class="text-3xl font-bold mt-5 leading-tight">
                        {{ $item->title }}
                    </h2>

                    <p class="text-slate-300 mt-5 leading-relaxed text-justify">
                        {{ Str::limit($item->content,350) }}
                    </p>

                </div>

                <div class="flex flex-col items-center justify-center">

                    <div class="relative">

                        <div class="w-32 h-32 rounded-full border-[8px] border-cyan-400 flex items-center justify-center">

                            <div class="text-center">

                                <div class="text-3xl font-bold">

                                    {{ count($results) ? number_format(($item->score / $results[0]->score) * 100,0) : 0 }}

                                </div>

                                <div class="text-sm text-slate-400">
                                    %
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="mt-4 text-cyan-300 font-semibold">
                        Relevansi
                    </div>

                </div>

            </div>

            <!-- SCORE -->

            <div class="mt-8">

                <div class="flex justify-between mb-2 text-sm">

                    <span>
                        TF-IDF Score
                    </span>

                    <span>
                        {{ number_format($item->score,4) }}
                    </span>

                </div>

                <div class="h-3 bg-slate-800 rounded-full overflow-hidden">

                    <div
                        class="h-full bg-gradient-to-r from-cyan-400 via-blue-500 to-cyan-400"
                        style="width: {{ count($results) ? min(($item->score / $results[0]->score) * 100,100) : 0 }}%">
                    </div>

                </div>

            </div>

        </div>

        @endforeach

    </div>

    @if(count($results) == 0)

    <div class="glass rounded-[35px] p-12 text-center">

        <div class="text-7xl mb-6">
            😔
        </div>

        <h2 class="text-3xl font-bold mb-3">
            Berita Tidak Ditemukan
        </h2>

        <p class="text-slate-400">
            Coba gunakan kata kunci yang berbeda atau lebih spesifik.
        </p>

    </div>

    @endif

    @endif

    <!-- FOOTER -->

    <div class="text-center mt-16 text-slate-500">

        <p>
            © {{ date('Y') }} BeritaSearch —
            Information Retrieval menggunakan TF-IDF
        </p>

    </div>

</div>

<script>

    /* DARK MODE */

    const btn = document.getElementById('themeToggle');

    btn.addEventListener('click',()=>{

        document.body.classList.toggle('light');

        btn.innerHTML =
        document.body.classList.contains('light')
        ? '☀️'
        : '🌙';

    });

    /* LOADING SEARCH */

    document.querySelector('form').addEventListener('submit',()=>{

        const btnSearch =
        document.getElementById('searchBtn');

        btnSearch.innerHTML =
        '⏳ Mencari...';

        btnSearch.disabled = true;

    });

    /* COUNTER */

    document.querySelectorAll('.counter')
    .forEach(counter=>{

        const target =
        parseInt(counter.dataset.target);

        let current = 0;

        const increment =
        Math.ceil(target/50);

        const interval =
        setInterval(()=>{

            current += increment;

            if(current >= target){

                current = target;

                clearInterval(interval);

            }

            counter.innerText =
            current;

        },20);

    });

    /* CHART TF-IDF */

    @if(isset($results) && count($results) > 0)

    const ctx =
    document.getElementById('tfidfChart');

    if(ctx){

        new Chart(ctx,{

            type:'bar',

            data:{

                labels:[

                    @foreach(array_slice($results,0,10) as $index => $item)

                    'Top {{ $index+1 }}',

                    @endforeach

                ],

                datasets:[{

                    label:'TF-IDF Score',

                    data:[

                        @foreach(array_slice($results,0,10) as $item)

                        {{ $item->score }},

                        @endforeach

                    ],

                    backgroundColor:
                    'rgba(34,211,238,0.7)',

                    borderRadius:12

                }]

            },

            options:{

                responsive:true,

                plugins:{

                    legend:{

                        labels:{

                            color:'#ffffff'

                        }

                    }

                },

                scales:{

                    x:{

                        ticks:{

                            color:'#ffffff'

                        }

                    },

                    y:{

                        ticks:{

                            color:'#ffffff'

                        }

                    }

                }

            }

        });

    }

    @endif

</script>

</body>
</html>