<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>RCA Auth Test</title>
</head>
<body>
    <h1>Obține token RCA</h1>

    @if ($errors->any())
        <div style="color:red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('rca.auth.authenticate') }}">
        @csrf

        <label>Account</label>
        <input type="text" name="account" value="{{ old('account') }}">

        <br><br>

        <label>Password</label>
        <input type="password" name="password">

        <br><br>

        <button type="submit">Obține token</button>
    </form>

    @isset($status)
        <hr>

        <h2>Rezultat auth</h2>
        <p>Status HTTP: {{ $status }}</p>
        <p>Successful: {{ $successful ? 'DA' : 'NU' }}</p>

        @if ($token)
            <h3>Token primit:</h3>
            <textarea rows="8" style="width:100%;">{{ $token }}</textarea>

            <br><br>

            <form method="GET" action="{{ route('rca.offer.create') }}">
    <input type="hidden" name="api_token" value="{{ $token }}">
    <button type="submit">Mergi la formular ofertă</button>
</form>
        @else
            <h3>Răspuns API:</h3>
            <pre>{{ json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        @endif
    @endisset
</body>
</html>