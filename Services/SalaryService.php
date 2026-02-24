<?php

namespace App\Services;

use App\Models\SaleTransaction;

class SalaryService
{
    public static function percentFor(string $adminUsername, float $profit): float
    {
        $profit = max(0.0, $profit);

        if ($adminUsername === 'admin_cherry') {
            if ($profit <= 499) return 0.25;
            if ($profit <= 999) return 0.35;
            if ($profit <= 1499) return 0.40;
            return 0.45;
        }

        if (in_array($adminUsername, ['admin_mir','admin_sica'], true)) {
            if ($profit <= 499) return 0.20;
            if ($profit <= 999) return 0.30;
            if ($profit <= 1499) return 0.35;
            return 0.40;
        }

        return 0.0;
    }

    public static function salaryForTx(SaleTransaction $tx): array
    {
        $profit = (float)$tx->price - (float)$tx->capital_cost;
        $pct = self::percentFor($tx->admin_username, $profit);
        $salary = $profit * $pct;

        return ['profit'=>$profit,'percent'=>$pct,'salary'=>$salary];
    }
}
