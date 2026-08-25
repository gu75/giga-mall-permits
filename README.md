# Giga Mall Permit System — Phase 1

Digitizes the two paper forms:
1. **Work Permit Form** — tenant requests contractor/worker access, approved sequentially by Operations → HSE → Security.
2. **Material Inward/Outward Permit** — tenant declares material movement, approved by Operations, then gate-logged (IN/OUT) by Security.

Both permits generate a downloadable/printable PDF once submitted, matching the original form layout.

## 1. Create a fresh Laravel project

```bash
composer create-project laravel/laravel giga-mall-permits
cd giga-mall-permits
```

## 2. Install Laravel Breeze (auth scaffolding) and DomPDF

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
composer require barryvdh/laravel-dompdf
npm install && npm run build
```

## 3. Copy these files into your project

Copy each folder from this package into the matching path in your new Laravel project,
**overwriting** `routes/web.php` and `app/Models/User.php` (or merge manually if you've
already customized them):

```
database/migrations/*      -> database/migrations/
database/seeders/*         -> database/seeders/
app/Models/*                -> app/Models/
app/Http/Controllers/*      -> app/Http/Controllers/
app/Http/Middleware/*       -> app/Http/Middleware/
routes/web.php               -> routes/web.php  (merge with existing Breeze routes)
resources/views/layouts/*    -> resources/views/layouts/
resources/views/work-permits/*     -> resources/views/work-permits/
resources/views/material-permits/* -> resources/views/material-permits/
```

**Important:** `routes/web.php` here only contains the permit routes. Open your existing
Breeze-generated `routes/web.php` and paste this file's `Route::middleware('auth')->group(...)`
block into it (don't delete Breeze's `/dashboard` and profile routes).

## 4. Register the role middleware

In `bootstrap/app.php` (Laravel 11+), add the middleware alias:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\EnsureUserHasRole::class,
    ]);
})
```

(On Laravel 10 or earlier, register it in `app/Http/Kernel.php` under `$middlewareAliases` instead.)

## 5. Run migrations and seed test users

```bash
php artisan migrate
php artisan db:seed --class=Database\\Seeders\\RoleUsersSeeder
```

This creates one login per role, all with password `password`:

| Role       | Email                     |
|------------|---------------------------|
| Admin      | admin@gigamall.test       |
| Tenant     | tenant@gigamall.test      |
| Operations | operations@gigamall.test  |
| HSE        | hse@gigamall.test         |
| Security   | security@gigamall.test    |

## 6. Serve it

```bash
php artisan serve
```

Log in as `tenant@gigamall.test`, submit a Work Permit or Material Permit, then log in as
`operations@gigamall.test` (then `hse@`, then `security@` for work permits) to walk it through
the approval chain and download the resulting PDF.

## What's included in Phase 1

- Role-based access: tenant / operations / hse / security / admin
- Work Permit: submission with dynamic worker rows, sequential 3-stage approval, rejection with reason, PDF export
- Material Permit: submission with dynamic item rows, Operations approval, Security gate IN/OUT logging, PDF export
- Status badges and a simple dashboard-style list view per role

## Suggested Phase 2 ideas (not built yet)

- Email/SMS notifications on each approval stage
- File/photo attachments (e.g. worker ID photos)
- Multi-shop tenant accounts (one login, multiple outlets)
- Admin panel for managing users/roles
- Reporting/export (e.g. all permits for a date range, CSV export)
- Digital signature capture instead of just approver name/timestamp
- QR code on PDF for gate staff to scan and verify authenticity
