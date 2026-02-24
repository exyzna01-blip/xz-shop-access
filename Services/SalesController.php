<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use App\Models\SaleTransaction;
use App\Models\SaleReceipt;
use App\Models\PriceCatalogItem;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    private function matchPrice(string $service, string $duration, string $category, string $devices): float
    {
        $q = PriceCatalogItem::query()
            ->where('active', 1)
            ->where('service', $service)
            ->where('category', $category)
            ->where('devices', $devices);

        $exact = (clone $q)->where('duration', $duration)->first();
        if ($exact) return (float)$exact->price;

        $fallback = (clone $q)->whereNull('duration')->first();
        if ($fallback) return (float)$fallback->price;

        return 0.0;
    }

    private function weekStartDate(): string
    {
        $now = now();
        $monday = $now->copy()->startOfDay()->subDays(($now->dayOfWeekIso - 1));
        return $monday->toDateString();
    }

    public function reserve(Request $request, StockItem $stock)
    {
        $user = $request->user();
        abort_unless($user->role === 'ADMIN', 403);

        if ($stock->status !== 'AVAILABLE') {
            return back()->withErrors(['reserve' => 'Stock is not available.']);
        }

        DB::transaction(function () use ($user, $stock) {
            $price = $this->matchPrice($stock->service, $stock->duration, $stock->category, $stock->devices);

            $tx = SaleTransaction::create([
                'stock_item_id' => $stock->id,
                'admin_id' => $user->id,
                'admin_username' => $user->username,
                'service' => $stock->service,
                'duration' => $stock->duration,
                'category' => $stock->category,
                'devices' => $stock->devices,
                'price' => $price,
                'capital_cost' => (float)$stock->capital_cost,
                'status' => 'RESERVED_PENDING',
                'owner_review_status' => 'NEEDS_REVIEW',
                'owner_notes' => null,
                'weekly_bucket' => $this->weekStartDate(),
                'approved_at' => null,
            ]);

            $stock->update(['status' => 'RESERVED']);

            AuditLog::create([
                'actor_user_id' => $user->id,
                'action' => 'STOCK_RESERVED',
                'entity_type' => 'SaleTransaction',
                'entity_id' => $tx->id,
                'meta_json' => ['stock_id'=>$stock->id],
                'created_at' => now(),
            ]);
        });

        return back()->with('ok','Reserved. It will appear in Pending.');
    }

    public function sold(Request $request, StockItem $stock)
    {
        $user = $request->user();
        abort_unless($user->role === 'ADMIN', 403);

        $request->validate([
            'receipts' => ['required','array','min:1','max:'.config('xzshop.receipt.max_images')],
            'receipts.*' => ['file','mimes:jpg,jpeg,png,webp'],
        ], [
            'receipts.required' => 'Receipt is required to mark as SOLD.',
            'receipts.*.mimes' => 'Only JPG, PNG, or WEBP images are allowed.',
        ]);

        $files = $request->file('receipts', []);
        $total = 0;
        foreach ($files as $f) $total += $f->getSize();
        if ($total > config('xzshop.receipt.total_limit_bytes')) {
            return back()->withErrors(['receipts' => 'Total receipt upload exceeds 700MB. Please compress or upload fewer images.']);
        }

        if (!in_array($stock->status, ['AVAILABLE','RESERVED'], true)) {
            return back()->withErrors(['sold' => 'Stock cannot be sold in its current status.']);
        }

        DB::transaction(function () use ($user, $stock, $files) {
            $price = $this->matchPrice($stock->service, $stock->duration, $stock->category, $stock->devices);

            $tx = SaleTransaction::create([
                'stock_item_id' => $stock->id,
                'admin_id' => $user->id,
                'admin_username' => $user->username,
                'service' => $stock->service,
                'duration' => $stock->duration,
                'category' => $stock->category,
                'devices' => $stock->devices,
                'price' => $price,
                'capital_cost' => (float)$stock->capital_cost,
                'status' => 'PENDING_APPROVAL',
                'owner_review_status' => 'NEEDS_REVIEW',
                'owner_notes' => null,
                'weekly_bucket' => $this->weekStartDate(),
                'approved_at' => null,
            ]);

            $dir = "receipts/{$tx->id}";
            foreach ($files as $file) {
                $stored = $file->store($dir, 'public');
                SaleReceipt::create([
                    'sale_transaction_id' => $tx->id,
                    'path' => $stored,
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                    'size_bytes' => $file->getSize(),
                    'created_at' => now(),
                ]);
            }

            $stock->update(['status' => 'SOLD_PENDING']);

            AuditLog::create([
                'actor_user_id' => $user->id,
                'action' => 'SALE_SUBMITTED',
                'entity_type' => 'SaleTransaction',
                'entity_id' => $tx->id,
                'meta_json' => ['stock_id'=>$stock->id,'receipt_count'=>count($files)],
                'created_at' => now(),
            ]);
        });

        return back()->with('ok','Submitted for owner approval.');
    }
}
