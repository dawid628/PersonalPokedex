# Pokemon API - Recruitment Task
API do zarządzania Pokemonami z integracją z PokeAPI, systemem banowania oraz możliwością dodawania własnych Pokemonów.

---

## Wymagania

- Docker Desktop
- WSL2 (dla Windows)
- Git

---

## Instalacja

### 1. Sklonuj repozytorium
```bash
git clone https://github.com/dawid628/PersonalPokedex.git
cd PersonalPokedex # upewnij się, ze katalog z repo ma taką nazwę
```

### 2. Skopiuj plik konfiguracyjny
```bash
cp .env.example .env
```

### 3. Edytuj `.env`
```env
POKEAPI_URL=https://pokeapi.co/api/v2/
X_SUPER_SECRET_KEY=secret
```

### 4. Zainstaluj zależności

**Linux/macOS/WSL2:**
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

**Windows (PowerShell):**
```powershell
docker run --rm `
    -v "${PWD}:/var/www/html" `
    -w /var/www/html `
    laravelsail/php82-composer:latest `
    composer install --ignore-platform-reqs
```

### 5. Uruchom kontenery
```bash
./vendor/bin/sail up -d
```

### 6. Wygeneruj klucz w kontenerze docker aplikacji
```bash
php artisan key:generate
```

### 7. Uruchom migracje w kontenerze
```bash
php artisan migrate
```

### 8. Gotowe!

Aplikacja dostępna pod: `http://localhost`

---

## Dokumentacja API (Swagger)

Interaktywna dokumentacja dostępna pod adresem:

**http://localhost/api/documentation**

### Wygenerowanie dokumentacji (opcjonalnie)
```bash
# w kontenerze docker
php artisan l5-swagger:generate
```

---

## Przydatne komendy
```bash
# Uruchom kontenery
./vendor/bin/sail up -d

# Zatrzymaj kontenery
./vendor/bin/sail down

# Logi
./vendor/bin/sail logs

# Shell
./vendor/bin/sail shell

# Artisan
./vendor/bin/sail artisan route:list

# Composer
./vendor/bin/sail composer install

# MySQL
./vendor/bin/sail mysql
```

---

## Endpointy API

### Publiczne

#### **POST** `/api/info` - Informacje o pokemonach

Pobiera szczegóły pokemonów z PokeAPI i własnych. Wyklucza zakazane.

**Request:**
```json
{
  "pokemons": ["pikachu", "charizard", "mypokemon"]
}
```

**Response:**
```json
{
  "success": true,
  "total_requested": 3,
  "total_found": 3,
  "total_banned": 0,
  "total_not_found": 0,
  "data": {
    "found": [
      {
        "id": 25,
        "name": "pikachu",
        "height": 4,
        "weight": 60,
        "types": ["electric"],
        "is_custom": false
      },
      {
        "id": 1,
        "name": "mypokemon",
        "is_custom": true
      }
    ],
    "banned": [],
    "not_found": []
  }
}
```

**cURL:**
```bash
curl -X POST http://localhost/api/info \
  -H "Content-Type: application/json" \
  -d '{"pokemons": ["pikachu", "charizard"]}'
```

---

#### **GET** `/api/pokemons` - Lista własnych pokemonów

Zwraca wszystkie własne pokemony.

**Response:**
```json
{
  "success": true,
  "total": 2,
  "data": [
    {
      "id": 1,
      "name": "mypokemon",
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    }
  ]
}
```

**cURL:**
```bash
curl http://localhost/api/pokemons
```

---

### Zabezpieczone (wymagany w headerze `X-SUPER-SECRET-KEY`)

#### **GET** `/api/banned` - Lista zakazanych

**cURL:**
```bash
curl http://localhost/api/banned \
  -H "X-SUPER-SECRET-KEY: secret"
```

---

#### **POST** `/api/banned` - Zabanuj pokemona

**Request:**
```json
{
  "name": "mewtwo"
}
```

**cURL:**
```bash
curl -X POST http://localhost/api/banned \
  -H "X-SUPER-SECRET-KEY: secret" \
  -H "Content-Type: application/json" \
  -d '{"name": "mewtwo"}'
```

---

#### **GET** `/api/banned/{name}` - Sprawdź czy zakazany

**cURL:**
```bash
curl http://localhost/api/banned/mewtwo \
  -H "X-SUPER-SECRET-KEY: secret"
```

---

#### **DELETE** `/api/banned/{name}` - Odbanuj pokemona

**cURL:**
```bash
curl -X DELETE http://localhost/api/banned/mewtwo \
  -H "X-SUPER-SECRET-KEY: secret"
```

---

#### **POST** `/api/pokemons` - Dodaj własnego pokemona

Nazwa musi być unikalna (nie może istnieć w PokeAPI ani lokalnie).

**Request:**
```json
{
  "name": "mypokemon"
}
```

**cURL:**
```bash
curl -X POST http://localhost/api/pokemons \
  -H "X-SUPER-SECRET-KEY: secret" \
  -H "Content-Type: application/json" \
  -d '{"name": "mypokemon"}'
```

---

#### **GET** `/api/pokemons/{name}` - Pobierz własnego pokemona

**cURL:**
```bash
curl http://localhost/api/pokemons/mypokemon \
  -H "X-SUPER-SECRET-KEY: secret"
```

---

#### **DELETE** `/api/pokemons/{name}` - Usuń własnego pokemona

**cURL:**
```bash
curl -X DELETE http://localhost/api/pokemons/mypokemon \
  -H "X-SUPER-SECRET-KEY: secret"
```

---

## Przykładowe scenariusze

### Scenariusz 1: Podstawowe operacje
```bash
# Dodaj pokemona
curl -X POST http://localhost/api/pokemons \
  -H "X-SUPER-SECRET-KEY: secret" \
  -H "Content-Type: application/json" \
  -d '{"name": "superfajnypokemon"}'

# Lista
curl http://localhost/api/pokemons

# Pobierz info
curl -X POST http://localhost/api/info \
  -H "Content-Type: application/json" \
  -d '{"pokemons": ["pikachu", "superfajnypokemon"]}'
```

### Scenariusz 2: Banowanie
```bash
# Zabanuj
curl -X POST http://localhost/api/banned \
  -H "X-SUPER-SECRET-KEY: secret" \
  -H "Content-Type: application/json" \
  -d '{"name": "pikachu"}'

# Sprawdź (nie pojawi się w wynikach)
curl -X POST http://localhost/api/info \
  -H "Content-Type: application/json" \
  -d '{"pokemons": ["pikachu", "charizard"]}'

# Odbanuj
curl -X DELETE http://localhost/api/banned/pikachu \
  -H "X-SUPER-SECRET-KEY: secret"
```

---

## Architektura
**Wykorzystane wzorce:** Repository Pattern, Service Layer

---

## Kilka informacji

- **Zakazane pokemony** nie są zwracane w `/api/info`
- **Własne pokemony** mają flagę `is_custom: true`
- **Pokemony z PokeAPI** mają flagę `is_custom: false`
- **Nazwy** mogą zawierać tylko: małe litery, cyfry, myślniki
- **Nie można dodać** pokemona o nazwie istniejącej w PokeAPI

---
## Licencja

MIT License
