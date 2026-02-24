<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use App\Models\SaleTransaction;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function admin()
    {
        $user = request()->user();

        $stocks = StockItem::query()
            ->whereIn('status', ['AVAILABLE','RESERVED','SOLD_PENDING'])
            ->orderByDesc('created_at')
            ->limit(150)
            ->get();

        $pending = SaleTransaction::query()
            ->where('admin_id', $user->id)
            ->whereIn('status', ['RESERVED_PENDING','PENDING_APPROVAL'])
            ->with('stockItem')
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        $sold = SaleTransaction::query()
            ->where('admin_id', $user->id)
            ->where('status', 'APPROVED_SOLD')
            ->with('stockItem')
            ->orderByDesc('approved_at')
            ->limit(100)
            ->get();

        $refunded = SaleTransaction::query()
            ->where('admin_id', $user->id)
            ->where('status', 'APPROVED_REFUNDED')
            ->with('stockItem')
            ->orderByDesc('approved_at')
            ->limit(100)
            ->get();

        $notifications = Notification::query()
            ->where('to_user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        return view('dashboards.admin', compact('stocks','pending','sold','refunded','notifications'));
    }

    public function owner()
    {
        $stocks = StockItem::query()->orderByDesc('created_at')->limit(250)->get();

        $queue = SaleTransaction::query()
            ->where('status', 'PENDING_APPROVAL')
            ->with(['stockItem','receipts'])
            ->orderBy('created_at')
            ->limit(120)
            ->get();

        $approvedSold = SaleTransaction::query()->where('status','APPROVED_SOLD')->get();
        $totalSales = (float)$approvedSold->sum('price');
        $totalCapital = (float)$approvedSold->sum('capital_cost');
        $totalRevenue = $totalSales - $totalCapital;

        return view('dashboards.owner', compact('stocks','queue','totalSales','totalCapital','totalRevenue'));
    }
}
