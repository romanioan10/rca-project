<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrokerRCA - Oferte disponibile</title>

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
            <p class="text-xs text-on-surface-variant">Rezultate ofertare</p>
        </div>
    </div>

    <a href="{{ route('rca.offer.create') }}" class="px-4 py-2 rounded-lg border border-outline-variant text-sm hover:bg-surface-container transition">
        Ofertă nouă
    </a>
</header>

<main class="max-w-6xl mx-auto px-4 md:px-10 py-8">

    <div class="mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Oferte RCA disponibile</h1>
            <p class="text-on-surface-variant mt-2">
                Ofertele sunt sortate crescător după preț. Poți descărca PDF-ul sau transforma oferta în poliță.
            </p>
        </div>

        @if(!empty($offers))
            <div class="rounded-2xl bg-white border border-outline-variant px-5 py-4 shadow-sm">
                <p class="text-sm text-on-surface-variant">Total oferte</p>
                <p class="text-2xl font-bold text-primary">{{ count($offers) }}</p>
            </div>
        @endif
    </div>

    @if(!empty($offers))
        @php
            $bestOffer = $offers[0] ?? null;
        @endphp

        @if($bestOffer)
            <section class="mb-8 rounded-2xl border border-green-200 bg-green-50 p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-success text-white flex items-center justify-center">
                        <span class="material-symbols-outlined">workspace_premium</span>
                    </div>

                    <div class="flex-1">
                        <p class="text-sm font-semibold text-success">Cea mai bună ofertă</p>
                        <h2 class="text-2xl font-bold mt-1">
                            {{ $bestOffer['provider_label'] ?? '-' }} —
                            {{ $bestOffer['premiumAmount'] ?? '-' }} {{ $bestOffer['currency'] ?? 'RON' }}
                        </h2>
                        <p class="text-sm text-on-surface-variant mt-1">
                            Valabilitate: {{ $bestOffer['startDate'] ?? '-' }} — {{ $bestOffer['endDate'] ?? '-' }}
                        </p>
                    </div>
                </div>
            </section>
        @endif

        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($offers as $index => $offer)
                <article class="bg-white border border-outline-variant rounded-2xl p-6 shadow-sm flex flex-col gap-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-xl font-bold">{{ $offer['provider_label'] ?? '-' }}</h3>

                                @if($index === 0)
                                    <span class="text-xs rounded-full bg-green-100 text-green-700 px-2 py-1 font-semibold">
                                        Recomandată
                                    </span>
                                @endif
                            </div>

                            <p class="text-sm text-on-surface-variant mt-1">
                                Cod ofertă: {{ $offer['providerOfferCode'] ?? '-' }}
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="text-sm text-on-surface-variant">Preț</p>
                            <p class="text-2xl font-bold text-primary">
                                {{ $offer['premiumAmount'] ?? '-' }}
                                <span class="text-sm">{{ $offer['currency'] ?? 'RON' }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="rounded-xl bg-surface p-4">
                            <p class="text-on-surface-variant">Offer ID</p>
                            <p class="font-semibold mt-1">{{ $offer['offerId'] ?? '-' }}</p>
                        </div>

                        <div class="rounded-xl bg-surface p-4">
                            <p class="text-on-surface-variant">Bonus Malus</p>
                            <p class="font-semibold mt-1">{{ $offer['bonusMalusClass'] ?? '-' }}</p>
                        </div>

                        <div class="rounded-xl bg-surface p-4">
                            <p class="text-on-surface-variant">Start</p>
                            <p class="font-semibold mt-1">{{ $offer['startDate'] ?? '-' }}</p>
                        </div>

                        <div class="rounded-xl bg-surface p-4">
                            <p class="text-on-surface-variant">End</p>
                            <p class="font-semibold mt-1">{{ $offer['endDate'] ?? '-' }}</p>
                        </div>
                    </div>

                    @if(!empty($offer['greenCardExclusions']))
                        <div class="rounded-xl bg-orange-50 border border-orange-100 p-4 text-sm">
                            <p class="font-semibold text-orange-800">Excluderi Carte Verde</p>
                            <p class="text-orange-700 mt-1">{{ $offer['greenCardExclusions'] }}</p>
                        </div>
                    @endif

                    @if(!empty($offer['notes']))
                        <details class="rounded-xl bg-surface border border-outline-variant p-4 text-sm">
                            <summary class="font-semibold cursor-pointer">Observații ofertă</summary>
                            <p class="text-on-surface-variant whitespace-pre-line mt-2">{{ $offer['notes'] }}</p>
                        </details>
                    @endif

                    @if(isset($offer['offerId']))
                        <div class="flex flex-col gap-3 pt-4 border-t border-outline-variant">
                            <a href="{{ route('rca.offer.download', $offer['offerId']) }}"
                               class="w-full text-center px-4 py-3 rounded-xl border border-primary text-primary font-semibold hover:bg-surface-container transition">
                                Descarcă PDF ofertă
                            </a>

                            <form method="POST" action="{{ route('rca.offer.policy', $offer['offerId']) }}" class="space-y-3">
                                @csrf

                                <input type="hidden" name="premium_amount" value="{{ $offer['premiumAmount'] ?? 0 }}">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Metodă plată</label>
                                        <select name="payment_method" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary/20">
                                            <option value="broker receipt">Broker receipt</option>
                                            <option value="payment order">Payment order</option>
                                            <option value="broker payment order">Broker payment order</option>
                                            <option value="pos">POS</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-1">Decontare directă</label>
                                        <select name="include_direct_compensation" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary/20">
                                            <option value="0">Nu</option>
                                            <option value="1">Da</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit"
                                        class="w-full px-4 py-3 rounded-xl bg-primary text-white font-semibold hover:bg-primary/90 transition flex items-center justify-center gap-2">
                                    Transformă în poliță
                                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                                </button>
                            </form>
                        </div>
                    @endif
                </article>
            @endforeach
        </section>
    @else
        <section class="bg-white border border-outline-variant rounded-2xl p-8 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-100 text-red-700 flex items-center justify-center">
                    <span class="material-symbols-outlined">error</span>
                </div>

                <div>
                    <h2 class="text-xl font-bold">Nu s-au găsit oferte disponibile</h2>
                    <p class="text-on-surface-variant mt-1">
                        Verifică datele introduse sau configurația asiguratorilor.
                    </p>
                </div>
            </div>
        </section>
    @endif

    <details class="mt-8 bg-white border border-outline-variant rounded-2xl p-6 shadow-sm">
        <summary class="font-semibold cursor-pointer">Răspunsuri tehnice asiguratori</summary>
        <pre class="mt-4 overflow-auto text-xs bg-surface rounded-xl p-4">{{ json_encode($responses ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </details>

    <div class="mt-8">
        <a href="{{ route('rca.offer.create') }}" class="inline-flex items-center gap-2 text-primary font-semibold hover:underline">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Înapoi la formular
        </a>
    </div>
</main>

</body>
</html>