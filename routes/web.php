<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\PriceCatalogController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\NotificationController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'admin'])->middleware('role:ADMIN')->name('admin.dashboard');
    Route::get('/owner', [DashboardController::class, 'owner'])->middleware('role:OWNER')->name('owner.dashboard');

    // Owner stock
    Route::get('/owner/stocks/create', [StockController::class, 'create'])->middleware('role:OWNER')->name('owner.stocks.create');
    Route::post('/owner/stocks', [StockController::class, 'store'])->middleware('role:OWNER')->name('owner.stocks.store');
    Route::get('/owner/stocks/{stock}/edit', [StockController::class, 'edit'])->middleware('role:OWNER')->name('owner.stocks.edit');
    Route::put('/owner/stocks/{stock}', [StockController::class, 'update'])->middleware('role:OWNER')->name('owner.stocks.update');
    Route::delete('/owner/stocks/{stock}', [StockController::class, 'destroy'])->middleware('role:OWNER')->name('owner.stocks.destroy');

    // Admin actions
    Route::post('/admin/stocks/{stock}/reserve', [SalesController::class, 'reserve'])->middleware('role:ADMIN')->name('admin.stocks.reserve');
    Route::post('/admin/stocks/{stock}/sold', [SalesController::class, 'sold'])->middleware('role:ADMIN')->name('admin.stocks.sold');

    // Owner approvals
    Route::get('/owner/approvals', [ApprovalController::class, 'queue'])->middleware('role:OWNER')->name('owner.approvals.queue');
    Route::post('/owner/approvals/{tx}/approve', [ApprovalController::class, 'approve'])->middleware('role:OWNER')->name('owner.approvals.approve');
    Route::post('/owner/approvals/{tx}/refund', [ApprovalController::class, 'refund'])->middleware('role:OWNER')->name('owner.approvals.refund');

    // Catalog
    Route::get('/owner/catalog', [PriceCatalogController::class, 'index'])->middleware('role:OWNER')->name('owner.catalog');
    Route::post('/owner/catalog', [PriceCatalogController::class, 'store'])->middleware('role:OWNER')->name('owner.catalog.store');
    Route::put('/owner/catalog/{item}', [PriceCatalogController::class, 'update'])->middleware('role:OWNER')->name('owner.catalog.update');
    Route::delete('/owner/catalog/{item}', [PriceCatalogController::class, 'destroy'])->middleware('role:OWNER')->name('owner.catalog.destroy');

    // Reports
    Route::get('/reports/weekly-salary', [ReportsController::class, 'weeklySalary'])->name('reports.weekly_salary');

    // Notifications
    Route::post('/admin/notifications/{notification}/read', [NotificationController::class, 'markRead'])->middleware('role:ADMIN')->name('admin.notifications.read');
});
