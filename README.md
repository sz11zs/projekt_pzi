# 🥕 Piljarnica kod Šime

**🇭🇷 Hrvatski** · [🇬🇧 English](#-english)

Web aplikacija za naručivanje voća, povrća i domaćih proizvoda iz lokalnih trgovina. Sučelje je prilagođeno **starijim korisnicima**: krupna slova, jasni gumbi, malo koraka do narudžbe i jednostavno plaćanje karticom ili gotovinom pri dostavi.

🎨 **Figma prototip:** [otvori prototip u Figmi](https://www.figma.com/proto/5yd4YceS1B5pVoKjeogiRq/PILJARNICA?node-id=0-1)

## 🎬 Video prikaz

Kratki video pokazuje kako aplikacija radi: od odabira proizvoda i košarice do narudžbe i administratorskog dijela.

(https://github.com/user-attachments/assets/f679a8c1-0402-411b-b9ce-95197ef7e9e6)

## ✨ Mogućnosti

- pregled proizvoda po kategorijama (voće, povrće, gotovi proizvodi), količine u kg ili komadima
- košarica, adresa dostave i plaćanje karticom ili gotovinom
- registracija i prijava korisnika (token autentikacija)
- admin dashboard: proizvodi, kategorije, narudžbe, kupci, najprodavaniji proizvodi i najbolji kupci
- superadmin može dodavati nove administratore

## 🛠️ Tehnologije

| Dio | Tehnologije |
|---|---|
| **Frontend** | Vue 3 (Composition API, TypeScript), Vuetify 3, Pinia, Vue Router, Vite 7 |
| **Backend** | PHP 8.2+, Laravel 12, Laravel Sanctum (API tokeni) |
| **Baza podataka** | MySQL / MariaDB (npr. preko XAMPP-a); SQLite je podržan za brzo lokalno testiranje |

```
projekt_pzi/
├── backend/    # Laravel REST API (http://127.0.0.1:8000/api)
└── frontend/   # Vue + Vuetify SPA (http://localhost:3000)
```

## 🚀 Pokretanje

### Preduvjeti

- PHP 8.2+ i [Composer](https://getcomposer.org/)
- Node.js 22+ i npm
- MySQL/MariaDB (npr. [XAMPP](https://www.apachefriends.org/))

### 1. Backend

```bash
cd backend
composer install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
```

U `backend/.env` postavite podatke za bazu (vlastitu lozinku nemojte nikad commitati):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=piljarnica
DB_USERNAME=root
DB_PASSWORD=
```

Kreirajte bazu `piljarnica` (npr. u phpMyAdminu), zatim:

Za prvog administratora (superadmina) u `backend/.env` upišite i:

```env
SEED_SUPERADMIN_NAME=ime
SEED_SUPERADMIN_EMAIL=admin@primjer.hr
SEED_SUPERADMIN_PASSWORD=jaka-lozinka
```

Zatim:

```bash
php artisan migrate --seed    # tablice + uloge, kategorije, načini plaćanja (+ superadmin)
php artisan serve             # API radi na http://127.0.0.1:8000
```

Seeder samo dodaje retke koji nedostaju i nikad ne mijenja postojeće, pa se `php artisan db:seed` može sigurno pokrenuti i na bazi s podacima. Načini plaćanja: 1 = pouzećem, 2 = kartica.

### 2. Frontend

```bash
cd frontend
npm install
cp .env.example .env          # Windows: copy .env.example .env
npm run dev                   # http://localhost:3000
```

Adresa backenda postavlja se u `frontend/.env` varijablom `VITE_API_URL` (zadano `http://127.0.0.1:8000/api`).

Administratorska prijava nalazi se na `/adminlogin`.

---

## 🇬🇧 English

A web app for ordering fruit, vegetables and homemade products from local shops. The interface is designed for **older users**: large text, clear buttons, few steps to place an order, and simple payment by card or cash on delivery.

🎨 **Figma prototype:** [open the prototype in Figma](https://www.figma.com/proto/5yd4YceS1B5pVoKjeogiRq/PILJARNICA?node-id=0-1)

### 🎬 Video demo

A short video showing how the app works: browsing products, the cart, placing an order and the admin dashboard.

▶️ See the [video in the Croatian section above](#-video-prikaz).

### ✨ Features

- browse products by category (fruit, vegetables, ready-made products), sold by kg or by piece
- cart, delivery address, card or cash payment
- user registration and login (token authentication)
- admin dashboard: products, categories, orders, customers, best sellers and top customers
- a super admin can create new administrators

### 🛠️ Tech stack

| Part | Technologies |
|---|---|
| **Frontend** | Vue 3 (Composition API, TypeScript), Vuetify 3, Pinia, Vue Router, Vite 7 |
| **Backend** | PHP 8.2+, Laravel 12, Laravel Sanctum (API tokens) |
| **Database** | MySQL / MariaDB (e.g. via XAMPP); SQLite also works for quick local testing |

### 🚀 Getting started

**Prerequisites:** PHP 8.2+, Composer, Node.js 22+, npm, MySQL/MariaDB.

**Backend**

```bash
cd backend
composer install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
# set DB_CONNECTION=mysql and DB_* values in .env (never commit .env)
# optional first super admin: SEED_SUPERADMIN_NAME / _EMAIL / _PASSWORD in .env
php artisan migrate --seed    # tables + roles, categories, payment methods (+ super admin)
php artisan serve             # API at http://127.0.0.1:8000
```

**Frontend**

```bash
cd frontend
npm install
cp .env.example .env          # Windows: copy .env.example .env
npm run dev                   # http://localhost:3000
```

The backend address is set with `VITE_API_URL` in `frontend/.env` (default `http://127.0.0.1:8000/api`).

The admin login is at `/adminlogin`.
