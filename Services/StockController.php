<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use App\Models\User;
use App\Models\Notification;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class StockController extends Controller
{
    public function create()
    {
        return view('owner.stock_create', [
            'services' => config('xzshop.services'),
            'durations' => config('xzshop.durations'),
            'labels' => config('xzshop.labels'),
            'categories' => config('xzshop.categories'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'service' => ['required','string'],
            'duration' => ['required','string'],
            'category' => ['required','string'],
            'devices' => ['nullable','string'],
            'email' => ['required','string'],
            'password' => ['required','string'],
            'label' => ['nullable','string'],
            'capital_cost' => ['nullable','numeric','min:0'],
        ]);

        $stock = StockItem::create([
            'service' => $request->service,
            'duration' => $request->duration,
            'category' => $request->category,
            'devices' => $request->devices ?? '',
            'email' => $request->email,
            'password' => Crypt::encryptString($request->password),
            'label' => $request->label ?? '',
            'capital_cost' => $request->capital_cost ?? 0,
            'status' => 'AVAILABLE',
            'created_by' => $request->user()->id,
        ]);

        AuditLog::create([
            'actor_user_id' => $request->user()->id,
            'action' => 'STOCK_CREATED',
            'entity_type' => 'StockItem',
            'entity_id' => $stock->id,
            'meta_json' => ['service'=>$stock->service,'duration'=>$stock->duration],
            'created_at' => now(),
        ]);

        $admins = User::query()->where('role','ADMIN')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'to_role' => 'ADMIN',
                'to_user_id' => $admin->id,
                'message' => "New drop: {$stock->service} • {$stock->duration} • {$stock->category} • {$stock->devices}",
                'type' => 'NEW_DROP',
                'read_at' => null,
                'created_at' => now(),
            ]);
        }

        return redirect()->route('owner.dashboard')->with('ok', 'Stock dropped and admins notified.');
    }

    public function edit(StockItem $stock)
    {
        return view('owner.stock_edit', [
            'stock' => $stock,
            'services' => config('xzshop.services'),
            'durations' => config('xzshop.durations'),
            'labels' => config('xzshop.labels'),
            'categories' => config('xzshop.categories'),
        ]);
    }

    public function update(Request $request, StockItem $stock)
    {
        $request->validate([
            'service' => ['required','string'],
            'duration' => ['required','string'],
            'category' => ['required','string'],
            'devices' => ['nullable','string'],
            'email' => ['required','string'],
            'password' => ['required','string'],
            'label' => ['nullable','string'],
            'capital_cost' => ['nullable','numeric','min:0'],
            'status' => ['required','string'],
        ]);

        $stock->update([
            'service' => $request->service,
            'duration' => $request->duration,
            'category' => $request->category,
            'devices' => $request->devices ?? '',
            'email' => $request->email,
            'password' => Crypt::encryptString($request->password),
            'label' => $request->label ?? '',
            'capital_cost' => $request->capital_cost ?? 0,
            'status' => $request->status,
        ]);

        AuditLog::create([
            'actor_user_id' => $request->user()->id,
            'action' => 'STOCK_UPDATED',
            'entity_type' => 'StockItem',
            'entity_id' => $stock->id,
            'meta_json' => ['status'=>$stock->status],
            'created_at' => now(),
        ]);

        return redirect()->route('owner.dashboard')->with('ok', 'Stock updated.');
    }

    public function destroy(Request $request, StockItem $stock)
    {
        $id = $stock->id;
        $stock->delete();

        AuditLog::create([
            'actor_user_id' => $request->user()->id,
            'action' => 'STOCK_DELETED',
            'entity_type' => 'StockItem',
            'entity_id' => $id,
            'meta_json' => null,
            'created_at' => now(),
        ]);

        return redirect()->route('owner.dashboard')->with('ok', 'Stock deleted.');
    }
}
