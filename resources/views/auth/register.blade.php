<x-guest-layout>
    <div class="text-center mb-4">
        <h1 class="h4 fw-bold mb-1">Rejestracja kursanta</h1>
        <p class="text-muted small mb-0">Załóż konto w systemie {{ config('app.name', 'OSK L-auto') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Imię <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus maxlength="50" autocomplete="given-name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mt-3 mt-md-0">
                <label for="surname" class="form-label">Nazwisko <span class="text-danger">*</span></label>
                <input type="text" id="surname" name="surname" class="form-control @error('surname') is-invalid @enderror" value="{{ old('surname') }}" required maxlength="50" autocomplete="family-name">
                @error('surname')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Adres e-mail <span class="text-danger">*</span></label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required maxlength="255" autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Numer telefonu <span class="text-danger">*</span></label>
            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required maxlength="15" pattern="[0-9+\- ]+" title="Dozwolone są tylko cyfry, spacje, myślniki i znak plus." autocomplete="tel">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Hasło <span class="text-danger">*</span></label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="8" autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Potwierdź hasło <span class="text-danger">*</span></label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required minlength="8" autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">Zarejestruj się</button>

        <div class="text-center small text-muted">
            Masz już konto?
            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Zaloguj się</a>
        </div>
    </form>
</x-guest-layout>
