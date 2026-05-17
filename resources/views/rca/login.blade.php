<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrokerRCA - Login</title>

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
                    },
                    fontFamily: {
                        sans: ["Inter", "sans-serif"],
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-surface min-h-screen font-sans text-on-surface overflow-hidden">

<div class="min-h-screen grid lg:grid-cols-2">

    <div class="hidden lg:flex flex-col justify-between bg-primary text-white p-12 relative overflow-hidden">

        <div class="absolute inset-0 opacity-10">
            <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full border border-white"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 rounded-full border border-white"></div>
        </div>

        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl">shield</span>
                </div>

                <div>
                    <h1 class="text-3xl font-bold">BrokerRCA</h1>
                    <p class="text-blue-100 text-sm">Platformă comparare oferte RCA</p>
                </div>
            </div>
        </div>

        <div class="relative z-10 max-w-md">
            <h2 class="text-5xl font-bold leading-tight">
                Ofertare RCA rapidă și modernă
            </h2>

            <p class="mt-6 text-blue-100 text-lg leading-relaxed">
                Compară automat ofertele tuturor asiguratorilor, descarcă PDF-uri și transformă instant ofertele în polițe RCA.
            </p>

            <div class="mt-10 space-y-4">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined">bolt</span>
                    <p>Generare rapidă oferte</p>
                </div>

                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined">mail</span>
                    <p>Trimitere email automată</p>
                </div>

                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined">description</span>
                    <p>PDF ofertă și poliță</p>
                </div>
            </div>
        </div>

        <div class="relative z-10 text-sm text-blue-100">
            © {{ date('Y') }} BrokerRCA
        </div>
    </div>

    <div class="flex items-center justify-center p-6 md:p-12">

        <div class="w-full max-w-md">

            <div class="lg:hidden flex items-center justify-center gap-3 mb-10">
                <div class="w-12 h-12 rounded-2xl bg-primary text-white flex items-center justify-center">
                    <span class="material-symbols-outlined">shield</span>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-primary">BrokerRCA</h1>
                    <p class="text-sm text-on-surface-variant">Platformă RCA</p>
                </div>
            </div>

            <div class="bg-white border border-outline-variant rounded-3xl shadow-sm p-8">

                <div class="mb-8">
                    <h2 class="text-3xl font-bold">Autentificare</h2>
                    <p class="text-on-surface-variant mt-2">
                        Introdu datele pentru a obține tokenul API.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('rca.login.submit') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Account
                        </label>

                        <input
                            type="text"
                            name="account"
                            value="{{ old('account', 'test') }}"
                            class="w-full rounded-xl border-outline-variant focus:border-primary focus:ring-primary/20"
                            placeholder="Introdu contul"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            value="test"
                            class="w-full rounded-xl border-outline-variant focus:border-primary focus:ring-primary/20"
                            placeholder="Introdu parola"
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-primary hover:bg-primary/90 transition text-white font-semibold py-3 rounded-xl flex items-center justify-center gap-2"
                    >
                        Login și obține token

                        <span class="material-symbols-outlined text-lg">
                            arrow_forward
                        </span>
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-outline-variant">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-on-surface-variant">Environment</span>
                        <span class="px-3 py-1 rounded-full bg-surface-container text-primary font-medium">
                            QA / Testing
                        </span>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>