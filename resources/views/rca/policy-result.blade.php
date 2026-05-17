<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrokerRCA - Poliță generată</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#00236f",
                        surface: "#f8f9ff",
                        "surface-container": "#e5eeff",
                        "outline-variant": "#c5c5d3",
                        "on-surface": "#0b1c30",
                        "on-surface-variant": "#444651",
                        success: "#006c49",
                    },
                    fontFamily: {
                        sans: ["Inter", "sans-serif"],
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-surface text-on-surface font-sans min-h-screen">

<header class="bg-white border-b border-outline-variant h-16 px-6 md:px-10 flex items-center justify-between sticky top-0 z-40">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center">
            <span class="material-symbols-outlined">shield</span>
        </div>
        <div>
            <p class="text-lg font-bold text-primary leading-none">BrokerRCA</p>
            <p class="text-xs text-on-surface-variant">Poliță generată</p>
        </div>
    </div>

    <a href="{{ route('rca.offer.create') }}" class="px-4 py-2 rounded-lg border border-outline-variant text-sm hover:bg-surface-container transition">
        Ofertă nouă
    </a>
</header>

<main class="max-w-4xl mx-auto px-4 md:px-10 py-8">

    @if(isset($result['data']['policies'][0]['policyId']))
        @php
            $policy = $result['data']['policies'][0];
        @endphp

        <section class="bg-white border border-outline-variant rounded-2xl p-8 shadow-sm">
            <div class="flex items-start gap-5">
                <div class="w-14 h-14 rounded-2xl bg-success text-white flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl">check_circle</span>
                </div>

                <div class="flex-1">
                    <p class="text-sm font-semibold text-success">Status HTTP: {{ $status }}</p>
                    <h1 class="text-3xl font-bold mt-1">Polița RCA a fost generată</h1>
                    <p class="text-on-surface-variant mt-2">
                        Oferta a fost transformată cu succes în poliță.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                <div class="rounded-xl bg-surface p-5">
                    <p class="text-sm text-on-surface-variant">Policy ID</p>
                    <p class="text-xl font-bold mt-1">{{ $policy['policyId'] }}</p>
                </div>

                <div class="rounded-xl bg-surface p-5">
                    <p class="text-sm text-on-surface-variant">Asigurator</p>
                    <p class="text-xl font-bold mt-1">
                        {{ $policy['provider']['organization']['businessName'] ?? '-' }}
                    </p>
                </div>

                <div class="rounded-xl bg-surface p-5">
                    <p class="text-sm text-on-surface-variant">Serie</p>
                    <p class="text-xl font-bold mt-1">{{ $policy['series'] ?? '-' }}</p>
                </div>

                <div class="rounded-xl bg-surface p-5">
                    <p class="text-sm text-on-surface-variant">Număr</p>
                    <p class="text-xl font-bold mt-1">{{ $policy['number'] ?? '-' }}</p>
                </div>

                <div class="rounded-xl bg-surface p-5">
                    <p class="text-sm text-on-surface-variant">Start</p>
                    <p class="text-xl font-bold mt-1">{{ $policy['startDate'] ?? '-' }}</p>
                </div>

                <div class="rounded-xl bg-surface p-5">
                    <p class="text-sm text-on-surface-variant">End</p>
                    <p class="text-xl font-bold mt-1">{{ $policy['endDate'] ?? '-' }}</p>
                </div>

                <div class="rounded-xl bg-surface p-5 md:col-span-2">
                    <p class="text-sm text-on-surface-variant">Preț poliță</p>
                    <p class="text-3xl font-bold text-primary mt-1">
                        {{ $policy['premiumAmount'] ?? '-' }}
                        <span class="text-base">{{ $policy['currency'] ?? 'RON' }}</span>
                    </p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 mt-8 pt-6 border-t border-outline-variant">
                <a href="{{ route('rca.policy.download', $policy['policyId']) }}"
                   class="flex-1 text-center px-5 py-3 rounded-xl bg-primary text-white font-semibold hover:bg-primary/90 transition">
                    Descarcă PDF poliță
                </a>

                <a href="{{ route('rca.offer.create') }}"
                   class="flex-1 text-center px-5 py-3 rounded-xl border border-outline-variant text-primary font-semibold hover:bg-surface-container transition">
                    Creează o ofertă nouă
                </a>
            </div>
        </section>
    @else
        <section class="bg-white border border-outline-variant rounded-2xl p-8 shadow-sm">
            <div class="flex items-start gap-5">
                <div class="w-14 h-14 rounded-2xl bg-red-100 text-red-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl">error</span>
                </div>

                <div>
                    <p class="text-sm font-semibold text-red-700">Status HTTP: {{ $status }}</p>
                    <h1 class="text-3xl font-bold mt-1">Transformarea a eșuat</h1>
                    <p class="text-on-surface-variant mt-2">
                        API-ul nu a returnat un policyId valid.
                    </p>
                </div>
            </div>

            <pre class="mt-6 overflow-auto text-xs bg-surface rounded-xl p-4">{{ json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

            <a href="{{ route('rca.offer.create') }}"
               class="inline-flex mt-6 items-center gap-2 text-primary font-semibold hover:underline">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Înapoi la formular
            </a>
        </section>
    @endif

</main>

</body>
</html>