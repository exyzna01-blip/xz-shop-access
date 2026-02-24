<?php

namespace App\Http\Controllers;

use App\Models\SaleTransaction;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function queue()
    {
        $items = SaleTransaction::query()
            ->where('status','PENDING_APPROVAL')
            ->with(['stockItem','receipts'])
            ->orderBy('created_at')
            ->get();

        return view('owner.approval_queue', compact('items'));
    }

    public function approve(Request $request, SaleTransaction $tx)
    {
        abort_unless($request->user()->role === 'OWNER', 403);
        if ($tx->status !== 'PENDING_APPROVAL') {
            return back()->withErrors(['approve' => 'Transaction not in approval queue.']);
        }

        DB::transaction(function () use ($request, $tx) {
            $tx->update([
                'status' => 'APPROVED_SOLD',
                'owner_review_status' => 'APPROVED',
                'approved_at' => now(),
            ]);

            $tx->stockItem()->update(['status' => 'SOLD']);

            AuditLog::create([
                'actor_user_id' => $request->user()->id,
                'action' => 'SALE_APPROVED',
                'entity_type' => 'SaleTransaction',
                'entity_id' => $tx->id,
                'meta_json' => ['stock_id'=>$tx->stock_item_id],
                'created_at' => now(),
            ]);
        });

        return back()->with('ok','Approved as SOLD.');
    }

    public function refund(Request $request, SaleTransaction $tx)
    {
        abort_unless($request->user()->role === 'OWNER', 403);

        if (!in_array($tx->status, ['PENDING_APPROVAL','APPROVED_SOLD'], true)) {
            return back()->withErrors(['refund' => 'Transaction cannot be refunded in its current status.']);
        }

        DB::transaction(function () use ($request, $tx) {
            $tx->update([
                'status' => 'APPROVED_REFUNDED',
                'owner_review_status' => 'REFUNDED',
                'approved_at' => now(),
                'owner_notes' => $request->input('owner_notes'),
            ]);

            $tx->stockItem()->update(['status' => 'REFUNDED']);

            AuditLog::create([
                'actor_user_id' => $request->user()->id,
                'action' => 'SALE_REFUNDED',
                'entity_type' => 'SaleTransaction',
                'entity_id' => $tx->id,
                'meta_json' => ['stock_id'=>$tx->stock_item_id],
                'created_at' => now(),
            ]);
        });

        return back()->with('ok','Marked as REFUNDED.');
    }
}
