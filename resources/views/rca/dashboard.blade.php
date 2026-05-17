<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>BrokerRCA - Dashboard</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>

<body class="bg-[#f8f9ff] min-h-screen font-sans text-[#0b1c30]">

<header class="bg-white border-b h-16 px-8 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-[#00236f] text-white flex items-center justify-center">
            <span class="material-symbols-outlined">shield</span>
        </div>
        <div>
            <p class="font-bold text-[#00236f]">BrokerRCA</p>
            <p class="text-xs text-gray-500">Dashboard</p>
        </div>
    </div>

    <form method="POST" action="{{ route('rca.logout') }}">
        @csrf
        <button class="px-4 py-2 border rounded-lg text-sm">Logout</button>
    </form>
</header>

<main class="max-w-5xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold mb-2">Dashboard RCA</h1>
    <p class="text-gray-600 mb-8">Alege rapid ce vrei să faci în aplicație.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('rca.offer.create') }}" class="bg-white border rounded-2xl p-6 shadow-sm hover:shadow-md transition">
            <span class="material-symbols-outlined text-4xl text-[#00236f]">request_quote</span>
            <h2 class="text-xl font-bold mt-4">Creează ofertă RCA</h2>
            <p class="text-sm text-gray-600 mt-2">Completezi datele și primești automat ofertele disponibile.</p>
        </a>

        <div class="bg-white border rounded-2xl p-6 shadow-sm">
            <span class="material-symbols-outlined text-4xl text-[#00236f]">mail</span>
            <h2 class="text-xl font-bold mt-4">Email automat</h2>
            <p class="text-sm text-gray-600 mt-2">Cea mai bună ofertă se trimite automat pe email cu PDF atașat.</p>
        </div>

        <div class="bg-white border rounded-2xl p-6 shadow-sm">
            <span class="material-symbols-outlined text-4xl text-[#00236f]">database</span>
            <h2 class="text-xl font-bold mt-4">Salvare date</h2>
            <p class="text-sm text-gray-600 mt-2">Clientul, cererea și răspunsurile API sunt salvate în baza de date.</p>
        </div>
    </div>
</main>

</body>
</html>