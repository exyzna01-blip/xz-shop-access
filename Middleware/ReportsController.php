<?php

namespace App\Http\Controllers;

use App\Models\SaleTransaction;
use App\Services\SalaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportsController extends Controller
{
    private function weekStartFromInput(?string $dateStr): string
    {
        $d = $dateStr ? Carbon::parse($dateStr) : now();
        $monday = $d->copy()->startOfDay()->subDays(($d->dayOfWeekIso - 1));
        return $monday->toDateString();
    }

    public function weeklySalary(Request $request)
    {
        $user = $request->user();
        $weekStart = $this->weekStartFromInput($request->query('week_start'));

        $q = SaleTransaction::query()
            ->where('weekly_bucket', $weekStart)
            ->where('status', 'APPROVED_SOLD')
            ->with('stockItem');

        if ($user->role === 'ADMIN') {
            $q->where('admin_id', $user->id);
        }

        $txs = $q->orderBy('approved_at')->get();

        $rows = [];
        $byAdmin = [];
        $totalSalary = 0.0;

        foreach ($txs as $tx) {
            $calc = SalaryService::salaryForTx($tx);
            $rows[] = [
                'approved_at' => optional($tx->approved_at)->format('Y-m-d H:i'),
                'admin' => $tx->admin_username,
                'service' => $tx->service,
                'duration' => $tx->duration,
                'category' => $tx->category,
                'devices' => $tx->devices,
                'price' => (float)$tx->price,
                'capital' => (float)$tx->capital_cost,
                'profit' => $calc['profit'],
                'percent' => $calc['percent'],
                'salary' => $calc['salary'],
            ];
            $byAdmin[$tx->admin_username] = ($byAdmin[$tx->admin_username] ?? 0) + $calc['salary'];
            $totalSalary += $calc['salary'];
        }

        $totalSales = (float)$txs->sum('price');
        $totalCapital = (float)$txs->sum('capital_cost');
        $totalRevenue = $totalSales - $totalCapital;

        return view('reports.weekly_salary', compact(
            'weekStart','rows','byAdmin','totalSales','totalCapital','totalRevenue','totalSalary'
        ));
    }
}
