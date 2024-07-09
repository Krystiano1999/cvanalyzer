Instrukcje uruchomienia aplikacji

1. Uruchomienie zagnieżdżonego Vue w widokach Laravel

Aby uruchomić aplikację Vue zagnieżdżoną w widokach Laravel, wykonaj poniższe kroki:

a. Przejdź do folderu `apka`:
cd apka

b. Zainstaluj zależności NPM:
npm install

c. Uruchom serwer deweloperski Vue:
npm run dev

d. Uruchom serwer Laravel (jeśli nie jest już uruchomiony):
php artisan serve


2. Uruchomienie bazy danych MySQL

Baza danych MySQL znajduje się w folderze `baza`. Aby ją uruchomić, możesz użyć XAMPP lub innego menedżera serwera MySQL.

a. Uruchom XAMPP i włącz moduł MySQL:
  1. Otwórz panel kontrolny XAMPP.
  2. Kliknij "Start" przy module MySQL.

b. Skonfiguruj bazę danych w Laravel:
Uzupełnij konfiguracje bazy danych w pliku `.env` w głównym folderze aplikacji Laravel aby była zgodna z konfiguracją MySQL uruchomioną w XAMPP. Przykład konfiguracji:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=twoja_baza_danych
DB_USERNAME=twoj_uzytkownik
DB_PASSWORD=twoje_haslo


3. Uruchomienie FastAPI

Aplikacja FastAPI znajduje się w folderze `model`. Plik, który uruchamia aplikację nazywa się `analyze.py`.

a. Przejdź do folderu `model`:
cd model


b. Zainstaluj wymagane zależności:
Zainstaluj wymagane biblioteki, korzystając z pliku `requirements.txt`:
pip install -r requirements.txt

c. Uruchom aplikację FastAPI za pomocą `uvicorn`:
uvicorn analyze:app --host 0.0.0.0 --port 8888 --reload

