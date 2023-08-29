## Zadania rekrutacyjne web24.com.pl

Utwórz REST API przy użyciu frameworka Laravel / Symfony. Celem aplikacji jest umożliwienie przesłania przez użytkownika informacji odnośnie firmy(nazwa, NIP, adres, miasto, kod pocztowy) oraz jej pracowników(imię, nazwisko, email, numer telefonu(opcjonalne)) - wszystkie pola są obowiązkowe poza tym które jest oznaczone jako opcjonalne. Uzupełnij endpointy do pełnego CRUDa dla powyższych dwóch. Zapisz dane w bazie danych. PS. Stosuj znane Ci dobre praktyki wytwarzania oprogramowania oraz korzystaj z repozytorium kodu.

## Od developera

Realizując zadanie zadanie uznałem, że budując rest Api, fajnie by było mieć autoryzację dlatego dodałem dodatkowy endpoint do logowania, do którego przesyłamy login i hasło a on zwraca nam token, którego używamy dla pozostałych endpointów do autoryzacji.

Dodatkowo w opisie zadania jest CRUD (create, read, update, delete) ale aby pokazać możliwości REST API i to, że znam pozostałe metody dodałem metody patch i put oraz list.

W zadaniu nie ma również nic o swagger ale w dzisiejszych czasach aby dobrze pokazać jak działa REST API bardzo dobrze jest go dodać - co też zrobiłem.

Mając to wszystko ostatnim dodatkowym element aby dobrze zaprezentować to zrobiłem było dodanie UnitTestow oraz napisane Seeder’ów do bazy danych tak aby REST API miało co zwracać

## Zadanie instrukcja uruchomienia

Tworzymy .env na bazie .env.example

Uruchamiamy komendy:
 - php artisan optimize
 - php artisan migrate
 - php artisan db:seed --class=UserSeeder
 - php artisan db:seed --class=DataSeeder
 - php artisan l5-swagger:generate   

Aby wejść w swaggera używamy linku api/documentation
