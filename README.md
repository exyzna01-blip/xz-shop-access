# xz shop access 

This package contains **migrations, seeders, controllers, RBAC middleware, and Blade UI skeleton** for the “xz shop access” app.

## Requirements
- PHP 8.2+
- Composer
- MySQL 8 / MariaDB 10.6+
- A fresh Laravel 11 install

## Install (fresh)
```bash
composer create-project laravel/laravel xz-shop-access
cd xz-shop-access
```

## Apply this skeleton
Copy/merge the folders from this zip into your Laravel project root:
- `app/`
- `config/`
- `database/`
- `resources/views/`
- `routes/web.php`
- (and follow the `bootstrap/app.php` snippet below)

## Register RBAC middleware alias (Laravel 11)
Open `bootstrap/app.php` and add the `role` alias:

```php
->withMiddleware(function (Illuminate\Foundation\Configuration\Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\EnsureRole::class,
    ]);
})
```

## Configure `.env`
Set database credentials, then run:
```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Default accounts
- OWNER: `ownereli` / `silverdawn`
- ADMINS: `admin_cherry`, `admin_mir`, `admin_sica` / `xzshop123`

## Receipts
- Stored on the **public disk**: `storage/app/public/receipts/{tx_id}/...`
- Validation:
  - 1–5 images
  - jpg/jpeg/png/webp
  - total size per transaction ≤ 700MB
