# Aplikacja TO-DO list

Zadanie rekrutacyjne

## Instalacja

1. Composer

```
composer install
```

2. W głównym katalogu projektu utworzyć plik konfiguracyjny ```.env```, zawierający zmienne środowiskowe

```
cp .env.lexamle .env
```

2. Docker - uruchomienie kontenerów poszczególnych usług

```
./vendor/bin/sail up
```

3. JavaScript & CSS

```
npm install
npm run build
```

4. Migracje i fikstury (Eloquent ORM)

```
sail artisan migrate --seed
```

lub

```
php artisan migrate --seed
```

5. Konfiguracja połączenia z Google Calendar API

Ustawić wartość zmiennej środowiskowej ```GOOGLE_CALENDAR_ID``` w pliku ```.env```

W katalogu ```storage/app/google-calendar``` utworzyć plik ```service-account-credentials.json``` zawierający klucz uwierzytelniający.
