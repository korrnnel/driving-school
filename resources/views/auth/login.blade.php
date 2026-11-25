<x-guest-layout>
    <div class="text-center mb-4">
        <h1 class="h4 fw-bold mb-1">Logowanie</h1>
        <p class="text-muted small mb-0">Zaloguj się do swojego konta w {{ config('app.name', 'OSK L-auto') }}</p>
    </div>

    @if (request()->boolean('enroll'))
        <div class="alert alert-info small mb-3" role="alert">
            Zaloguj się lub załóż konto kursanta, aby móc zapisać się na wybrany kurs.
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success small mb-3" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Adres e-mail <span class="text-danger">*</span></label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Hasło <span class="text-danger">*</span></label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label small" for="remember_me">Zapamiętaj mnie</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">Zaloguj się</button>

        <div class="text-center small text-muted">
            Nie masz jeszcze konta?
            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Zarejestruj się</a>
        </div>
    </form>
</x-guest-layout>
