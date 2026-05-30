<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>BeritaSearch AI</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>

*{
    font-family:'Plus Jakarta Sans',sans-serif;
}

body{
    overflow-x:hidden;
}

/* Aurora Background */
.bg-animation{
    background:
    radial-gradient(circle at 20% 20%, rgba(99,102,241,.4), transparent 30%),
    radial-gradient(circle at 80% 30%, rgba(236,72,153,.4), transparent 30%),
    radial-gradient(circle at 50% 80%, rgba(34,197,94,.3), transparent 30%),
    linear-gradient(135deg,#0f172a,#020617);
    background-size:200% 200%;
    animation:bgmove 15s ease infinite;
}

@keyframes bgmove{
    0%{background-position:0% 50%}
    50%{background-position:100% 50%}
    100%{background-position:0% 50%}
}

/* Glass Card */
.glass{
    background:rgba(255,255,255,.08);
    backdrop-filter:blur(30px);
    border:1px solid rgba(255,255,255,.15);
}

/* Floating Glow */
.glow{
    position:absolute;
    border-radius:9999px;
    filter:blur(120px);
    opacity:.5;
    animation:float 8s ease-in-out infinite;
}

@keyframes float{
    0%,100%{
        transform:translateY(0px);
    }
    50%{
        transform:translateY(-30px);
    }
}

/* Card Animation */
.result-card{
    transition:.4s;
}

.result-card:hover{
    transform:translateY(-5px);
    box-shadow:
    0 20px 50px rgba(59,130,246,.25);
}

/* Reveal */
.fadeUp{
    animation:fadeUp .8s ease forwards;
}

@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(30px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

</style>

</head>

<body class="bg-animation min-h-screen flex items-center justify-center px-4 py-10 relative">

<div class="glow bg-blue-500 w-96 h-96 top-0 left-0"></div>
<div class="glow bg-pink-500 w-96 h-96 bottom-0 right-0"></div>

<div class="glass rounded-[40px] p-10 max-w-3xl w-full shadow-2xl fadeUp">

    <!-- Header -->
    <div class="text-center mb-10">

        <h1 class="text-6xl md:text-7xl font-extrabold mb-4">
            <span class="bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-500 bg-clip-text text-transparent">
                BeritaSearch
            </span>
        </h1>

        <p class="text-white/70 text-lg">
            Mesin pencarian berita berbasis TF-IDF & Information Retrieval
        </p>

    </div>

    <!-- Search -->
    <form action="{{ route('search') }}" method="GET">

        <div class="relative">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="absolute left-6 top-1/2 -translate-y-1/2 w-6 h-6 text-slate-400"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>

            </svg>

            <input
                type="text"
                name="q"
                value="{{ $query ?? '' }}"
                placeholder="Cari berita..."
                class="w-full pl-16 pr-36 py-5 rounded-full bg-white text-slate-800 text-lg font-semibold outline-none shadow-xl">

            <button
                type="submit"
                class="absolute right-2 top-2 bottom-2 px-8 rounded-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold hover:scale-105 transition">

                Cari

            </button>

        </div>

    </form>

    <!-- Result -->
    @if(isset($results))

    <div class="mt-10 space-y-5">

        <h2 class="text-white font-bold text-xl">
            Hasil Pencarian
        </h2>

        @foreach($results as $item)

        <div class="result-card bg-white rounded-3xl p-6">

            <div class="flex justify-between items-start gap-4">

                <div>

                    <h3 class="text-xl font-bold text-slate-800">
                        {{ $item->title }}
                    </h3>

                    <p class="text-slate-500 mt-2">
                        Dokumen relevan berdasarkan perhitungan TF-IDF.
                    </p>

                </div>

                <div class="bg-gradient-to-r from-blue-500 to-purple-500 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg">
                    {{ number_format($item->score,4) }}
                </div>

            </div>

        </div>

        @endforeach

    </div>

    @endif

</div>

</body>
</html>