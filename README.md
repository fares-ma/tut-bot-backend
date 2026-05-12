# TUT-BOT Backend (Laravel API)

REST API backend for the TUT-BOT graduation project.

This backend serves data to the React frontend and replaces static `mockData.ts` with real API endpoints.

## Tech Stack

- Laravel 13 (PHP 8.3+)
- SQLite (default for local development)
- JSON REST API

## API Endpoints

Base URL: `http://localhost:8000/api`

### Landmarks
- `GET /landmarks` — List all landmarks (paginated, default 15 per page)
  - Query params: `?per_page=20&search=luxor` (search by name or region)
- `GET /landmarks/{id}` — Get landmark by ID
- `POST /landmarks` — Create landmark (admin only, validation required)
- `PUT /landmarks/{id}` — Update landmark (admin only, validation required)
- `DELETE /landmarks/{id}` — Delete landmark (admin only)

### Reviews
- `GET /reviews` — List all reviews (paginated)
  - Query params: `?per_page=20`
- `GET /reviews/{id}` — Get review by ID
- `POST /reviews` — Create review (validation required)
- `PUT /reviews/{id}` — Update review (validation required)
- `DELETE /reviews/{id}` — Delete review

### Travel Stories
- `GET /stories` — List all stories (paginated)
  - Query params: `?per_page=20`
- `GET /stories/{id}` — Get story by ID
- `POST /stories` — Create story (validation required)
- `PUT /stories/{id}` — Update story (validation required)
- `DELETE /stories/{id}` — Delete story

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

## Deployment (Free Hosting on Railway)

### Setup on Railway

Railway.app provides free tier suitable for this project. Follow these steps:

1. **Create Railway account**: https://railway.app
2. **Connect GitHub**: Link your repo (`fares-ma/tut-bot-backend`) to Railway
3. **Set environment variables** in Railway dashboard:
   - `APP_KEY` — Generate locally: `php artisan key:generate` then copy the key
   - `APP_ENV` — Set to `production`
   - `APP_DEBUG` — Set to `false`
   - `APP_URL` — Will be provided by Railway (e.g., `https://tut-bot-backend-prod-xxxx.railway.app`)
   - `CORS_ALLOWED_ORIGINS` — Add your frontend URL (e.g., frontend Vercel URL)
   - `DB_CONNECTION` — Set to `pgsql` (Railway provides PostgreSQL by default)
   - Database credentials will be auto-provided by Railway

4. **Deploy**: Railway auto-deploys when you push to `main` branch

### After Deployment

Once deployed, you'll get a production URL like:
```
https://tut-bot-backend-prod-xxxx.railway.app/api
```

Update frontend `.env` to use this URL:
```env
VITE_API_URL=https://tut-bot-backend-prod-xxxx.railway.app/api
```

## Quick Checks

List API routes:

```bash
php artisan route:list --path=api
```

Test endpoint (before running frontend):

```bash
curl http://localhost:8000/api/landmarks
curl http://localhost:8000/api/landmarks/1
curl "http://localhost:8000/api/landmarks?search=giza"
```

## Error Handling & Validation

All POST/PUT endpoints validate input:

- **422 Unprocessable Entity** — Validation failed (includes error details in response)
- **404 Not Found** — Resource not found
- **500 Internal Server Error** — Server error (check logs)

Example validation error response:

```json
{
  "errors": {
    "name": ["The name field is required."],
    "rating": ["The rating must be between 0 and 5."]
  }
}
```

Run tests:

```bash
php artisan test
```
