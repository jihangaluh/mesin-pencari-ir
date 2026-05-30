<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesin Pencari Berita IR</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 p-8">

    <div class="max-w-3xl mx-auto mt-10">
        <h1 class="text-4xl font-bold text-center text-blue-600 mb-8">
            <a href="/">BeritaSearch.</a>
        </h1>
        
        <form action="{{ route('search') }}" method="GET" class="flex justify-center mb-10 shadow-lg rounded-full">
            <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Ketikkan topik berita..." 
                   class="w-full px-6 py-4 border border-gray-200 rounded-l-full focus:outline-none focus:border-blue-400 text-lg">
            <button type="submit" class="bg-blue-600 text-white px-8 py-4 rounded-r-full hover:bg-blue-700 transition font-semibold text-lg">
                Cari
            </button>
        </form>

        @if(isset($results))
            <p class="text-gray-500 mb-6 border-b pb-2">
                Menemukan {{ count($results) }} hasil pencarian untuk "<strong>{{ $query }}</strong>"
            </p>
            
            <div class="space-y-6">
                @foreach($results as $item)
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                        <h2 class="text-xl font-semibold text-blue-700 mb-2">{{ $item->title }}</h2>
                        
                        <p class="text-gray-700 text-sm mb-4 leading-relaxed">
                            {{ Str::limit($item->content, 250) }}
                        </p>
                        
                        <div class="inline-block bg-blue-50 text-blue-800 text-xs font-mono py-1 px-3 rounded-md">
                            Skor TF-IDF: {{ number_format($item->score, 4) }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if(count($results) == 0)
                <div class="text-center text-gray-500 mt-10">
                    <p class="text-xl">Opps! Berita tidak ditemukan.</p>
                    <p class="text-sm mt-2">Coba gunakan kata kunci lain yang lebih umum.</p>
                </div>
            @endif
        @endif
    </div>

</body>
</html>