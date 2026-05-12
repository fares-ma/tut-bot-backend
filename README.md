# TUT-BOT Backend (Laravel API)

REST API backend for the TUT-BOT graduation project.

This backend serves data to the React frontend and replaces static `mockData.ts` with real API endpoints.

## Tech Stack

- Laravel 13 (PHP 8.3+)
- SQLite (default for local development)
- JSON REST API

## API Endpoints

Base URL: `http://localhost:8000/api`

- `GET /landmarks`
- `GET /landmarks/{id}`
- `GET /reviews`
- `GET /reviews/{id}`
- `GET /stories`
- `GET /stories/{id}`

## Data Models

Implemented models and migrations:

- `Landmark`
- `Review`
- `TravelStory`
- `Badge`

All models include `fillable` fields.

## Seed Data

Egyptian tourism demo data is included in:

- `LandmarkSeeder`
- `ReviewSeeder`
- `TravelStorySeeder`
- `BadgeSeeder`

All seeders are called from `DatabaseSeeder`.

## First-Time Setup (Codespaces or Local)

1. Install dependencies

```bash
composer install
```

2. Create env file

```bash
cp .env.example .env
```

3. Generate app key

```bash
php artisan key:generate
```

4. Ensure SQLite file exists

```bash
touch database/database.sqlite
```

5. Run migration + seed

```bash
php artisan migrate:fresh --seed
```

6. Start server

```bash
php artisan serve
```

Backend will run on `http://127.0.0.1:8000` (or `http://localhost:8000`).

## CORS Configuration

CORS is configurable via `.env`:

```env
CORS_ALLOWED_ORIGINS=http://localhost:5173,http://127.0.0.1:5173,https://tut-bot-f.vercel.app
```

If your frontend runs on another domain/port, append it to this comma-separated list.

After editing env values:

```bash
php artisan config:clear
```

## Frontend Integration (React)

In frontend `.env` (Vite):

```env
VITE_API_URL=http://localhost:8000/api
```

Example API client:

```ts
import axios from 'axios';

export const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
});

export const fetchLandmarks = async () => (await api.get('/landmarks')).data;
export const fetchReviews = async () => (await api.get('/reviews')).data;
export const fetchStories = async () => (await api.get('/stories')).data;
```

## Team Handover (For a Teammate Running Local)

A teammate can run this backend by doing only:

```bash
git clone <repo-url>
cd tut-bot-backend
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan serve
```

Then configure frontend:

```env
VITE_API_URL=http://localhost:8000/api
```

## Quick Checks

List API routes:

```bash
php artisan route:list --path=api
```

Run tests:

```bash
php artisan test
```
