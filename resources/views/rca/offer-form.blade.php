<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrokerRCA - Ofertă Nouă</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#2563eb",
                        dark: "#0f172a",
                        surface: "#f3f5fb",
                        muted: "#64748b",
                    },
                    fontFamily: {
                        sans: ["Inter", "sans-serif"],
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-surface text-dark font-sans min-h-screen">

<header class="bg-white border-b border-slate-200 px-6 md:px-10 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4 sticky top-0 z-40">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center shadow-md">
            <span class="material-symbols-outlined">shield</span>
        </div>

        <div>
            <p class="text-2xl font-extrabold text-slate-900 leading-none">BrokerRCA</p>
            <p class="text-sm text-slate-500 mt-1">Generator oferte comparative</p>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('rca.dashboard') }}" class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-sm font-semibold hover:bg-slate-50 transition">
            Dashboard
        </a>

        <a href="{{ route('rca.chatbot') }}" class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-sm font-semibold hover:bg-slate-50 transition">
            Chatbot RCA
        </a>

        <form method="POST" action="{{ route('rca.logout') }}">
            @csrf
            <button class="px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">
                Logout
            </button>
        </form>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 md:px-10 py-8">

    <section class="mb-8 rounded-3xl bg-gradient-to-br from-slate-900 to-blue-900 text-white p-8 shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-4xl font-extrabold">
                    Ofertă Nouă RCA
                </h1>

                <p class="text-blue-100 mt-3 max-w-2xl">
                    Completează formularul clasic pentru a genera automat ofertele disponibile de la asiguratori.
                </p>
            </div>

            <a href="{{ route('rca.chatbot') }}" class="inline-flex items-center justify-center gap-2 bg-white text-slate-900 px-5 py-3 rounded-2xl font-bold hover:bg-blue-50 transition">
                <span class="material-symbols-outlined">chat</span>
                Completează cu chatbot
            </a>
        </div>
    </section>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-3 font-bold">1</div>
            <p class="font-bold">Poliță</p>
            <p class="text-sm text-slate-500">Durată și comision</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-3 font-bold">2</div>
            <p class="font-bold">Asigurat</p>
            <p class="text-sm text-slate-500">Date personale</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-3 font-bold">3</div>
            <p class="font-bold">Vehicul</p>
            <p class="text-sm text-slate-500">VIN, CIV, motor</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center mb-3 font-bold">4</div>
            <p class="font-bold">Oferte</p>
            <p class="text-sm text-slate-500">Comparare automată</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-red-700">
            <p class="font-bold mb-2">Verifică următoarele câmpuri:</p>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('rca.offer.store') }}" class="space-y-6">
        @csrf

        <section class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-extrabold">Asiguratori</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Cererea va fi trimisă automat către asiguratorii configurați.
                    </p>
                </div>

                <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-4 py-2 text-xs font-bold text-blue-700">
                    <span class="material-symbols-outlined text-sm">bolt</span>
                    Auto-comparare
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach(($insurers ?? []) as $key => $insurer)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <p class="font-bold text-sm">{{ $insurer['label'] ?? $key }}</p>
                        <p class="text-xs text-slate-500">{{ $key }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <h2 class="text-2xl font-extrabold mb-6">Detalii Poliță</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div>
                    <label class="block text-sm font-bold mb-1">Data început poliță</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="date" name="start_date" value="{{ old('start_date', '2026-04-28') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Durată poliță</label>
                    <select class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" name="term_time">
                        <option value="1" {{ old('term_time', 6) == 1 ? 'selected' : '' }}>1 lună</option>
                        <option value="6" {{ old('term_time', 6) == 6 ? 'selected' : '' }}>6 luni</option>
                        <option value="12" {{ old('term_time', 6) == 12 ? 'selected' : '' }}>12 luni</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Număr rate</label>
                    <select class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" name="installment_count">
                        <option value="1" {{ old('installment_count', 1) == 1 ? 'selected' : '' }}>1 rată</option>
                        <option value="2" {{ old('installment_count') == 2 ? 'selected' : '' }}>2 rate</option>
                        <option value="4" {{ old('installment_count') == 4 ? 'selected' : '' }}>4 rate</option>
                        <option value="12" {{ old('installment_count') == 12 ? 'selected' : '' }}>12 rate</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Limită comision</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="number" name="commission_percent_limit" value="{{ old('commission_percent_limit', 12) }}">
                </div>
            </div>
        </section>

        <section class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <h2 class="text-2xl font-extrabold mb-6">Date asigurat</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-bold mb-1">Nume</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="last_name" value="{{ old('last_name', 'Pop') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Prenume</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="first_name" value="{{ old('first_name', 'Vasile') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">CNP</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="tax_id" value="{{ old('tax_id', '1960701400013') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Data nașterii</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="date" name="birthdate" value="{{ old('birthdate', '1996-07-01') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Gen</label>
                    <select class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" name="gender">
                        <option value="m" {{ old('gender', 'm') == 'm' ? 'selected' : '' }}>Masculin</option>
                        <option value="f" {{ old('gender') == 'f' ? 'selected' : '' }}>Feminin</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Email</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="email" name="email" value="{{ old('email', 'email@email.ro') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Telefon</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="mobile_number" value="{{ old('mobile_number', '0744444444') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Tip act</label>
                    <select class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" name="id_type">
                        <option value="CI" {{ old('id_type', 'CI') == 'CI' ? 'selected' : '' }}>CI</option>
                        <option value="PASSPORT" {{ old('id_type') == 'PASSPORT' ? 'selected' : '' }}>Pașaport</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Serie și număr act</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="id_number" value="{{ old('id_number', 'CJ123456') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Emitent act</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="id_issue_authority" value="{{ old('id_issue_authority', 'SPCLEP Cluj') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Dată emitere act</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="date" name="id_issue_date" value="{{ old('id_issue_date', '2022-11-24') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Dată emitere permis</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="date" name="driving_license_issue_date" value="{{ old('driving_license_issue_date', '2016-06-15') }}">
                </div>
            </div>
        </section>

        <section class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <h2 class="text-2xl font-extrabold mb-6">Adresă</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div>
                    <label class="block text-sm font-bold mb-1">Țară</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="country" value="{{ old('country', 'RO') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Județ</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="county" value="{{ old('county', 'CJ') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Oraș</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="city" value="{{ old('city', 'Cluj-Napoca') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Cod oraș / SIRUTA</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="number" name="city_code" value="{{ old('city_code', 54984) }}">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold mb-1">Stradă</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="street" value="{{ old('street', 'Principala') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Număr</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="house_number" value="{{ old('house_number', '19-21') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Cod poștal</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="postcode" value="{{ old('postcode', '400356') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Bloc</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="building" value="{{ old('building', 'A1') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Scară</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="staircase" value="{{ old('staircase', '1') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Apartament</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="apartment" value="{{ old('apartment', '12') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Etaj</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="floor" value="{{ old('floor', '3') }}">
                </div>
            </div>
        </section>

        <section class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <h2 class="text-2xl font-extrabold mb-6">Date vehicul</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div>
                    <label class="block text-sm font-bold mb-1">Număr înmatriculare</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="license_plate" value="{{ old('license_plate', 'CJ01ABC') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Tip înmatriculare</label>
                    <select class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" name="registration_type">
                        <option value="registered" {{ old('registration_type', 'registered') == 'registered' ? 'selected' : '' }}>Înmatriculat</option>
                        <option value="recorded" {{ old('registration_type') == 'recorded' ? 'selected' : '' }}>Înregistrat</option>
                        <option value="temporaryRegistered" {{ old('registration_type') == 'temporaryRegistered' ? 'selected' : '' }}>Înmatriculat temporar</option>
                        <option value="temporaryRecorded" {{ old('registration_type') == 'temporaryRecorded' ? 'selected' : '' }}>Înregistrat temporar</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold mb-1">VIN</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="vin" value="{{ old('vin', 'WAUZZZ8K1AA000025') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Tip vehicul</label>
                    <select class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" name="vehicle_type">
                        <option value="M1" {{ old('vehicle_type', 'M1') == 'M1' ? 'selected' : '' }}>M1</option>
                        <option value="M1G" {{ old('vehicle_type') == 'M1G' ? 'selected' : '' }}>M1G</option>
                        <option value="N1" {{ old('vehicle_type') == 'N1' ? 'selected' : '' }}>N1</option>
                        <option value="N1G" {{ old('vehicle_type') == 'N1G' ? 'selected' : '' }}>N1G</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Marcă</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="brand" value="{{ old('brand', 'Audi') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Model</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="model" value="{{ old('model', 'A4') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">An fabricație</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="number" name="year_of_construction" value="{{ old('year_of_construction', 2018) }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Capacitate cilindrică</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="number" name="engine_displacement" value="{{ old('engine_displacement', 1968) }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Putere motor kW</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="number" name="engine_power" value="{{ old('engine_power', 110) }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Masă totală</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="number" name="total_weight" value="{{ old('total_weight', 2050) }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Locuri</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="number" name="seats" value="{{ old('seats', 5) }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Combustibil</label>
                    <select class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" name="fuel_type">
                        <option value="diesel" {{ old('fuel_type', 'diesel') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="petrol" {{ old('fuel_type') == 'petrol' ? 'selected' : '' }}>Benzină</option>
                        <option value="hybrid" {{ old('fuel_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        <option value="electric" {{ old('fuel_type') == 'electric' ? 'selected' : '' }}>Electric</option>
                        <option value="lpg" {{ old('fuel_type') == 'lpg' ? 'selected' : '' }}>GPL</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Prima înmatriculare</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="date" name="first_registration" value="{{ old('first_registration', '2018-05-10') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Utilizare</label>
                    <select class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" name="usage_type">
                        <option value="personal" {{ old('usage_type', 'personal') == 'personal' ? 'selected' : '' }}>Personal</option>
                        <option value="taxi" {{ old('usage_type') == 'taxi' ? 'selected' : '' }}>Taxi</option>
                        <option value="carRental" {{ old('usage_type') == 'carRental' ? 'selected' : '' }}>Rent a car</option>
                        <option value="drivingSchool" {{ old('usage_type') == 'drivingSchool' ? 'selected' : '' }}>Școală auto</option>
                        <option value="courier" {{ old('usage_type') == 'courier' ? 'selected' : '' }}>Curierat</option>
                        <option value="cargoTransportation" {{ old('usage_type') == 'cargoTransportation' ? 'selected' : '' }}>Transport marfă</option>
                        <option value="passengerTransportation" {{ old('usage_type') == 'passengerTransportation' ? 'selected' : '' }}>Transport persoane</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">CIV</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="text" name="vehicle_identification" value="{{ old('vehicle_identification', 'G122737') }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Kilometraj</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="number" name="current_mileage" value="{{ old('current_mileage', 120000) }}">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1">Expirare ITP</label>
                    <input class="w-full rounded-xl border-slate-300 focus:border-primary focus:ring-primary/20" type="date" name="expiration_date_pti" value="{{ old('expiration_date_pti', '2026-06-16') }}">
                </div>
            </div>
        </section>

        <div class="sticky bottom-0 bg-white/90 backdrop-blur border border-slate-200 rounded-3xl p-5 flex flex-col md:flex-row items-center justify-between gap-4 shadow-lg">
            <div>
                <p class="font-extrabold text-lg">Gata de ofertare</p>
                <p class="text-sm text-slate-500">Se vor afișa automat toate ofertele disponibile.</p>
            </div>

            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <a href="{{ route('rca.dashboard') }}" class="w-full md:w-auto px-6 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 font-bold hover:bg-slate-50 transition text-center">
                    Înapoi
                </a>

                <button type="submit" class="w-full md:w-auto px-8 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    Cere oferte
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </button>
            </div>
        </div>
    </form>
</main>

</body>
</html>