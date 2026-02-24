@extends('layouts.app')

@section('content')
  <div class="bg-white border rounded p-5">
    <div class="flex items-center justify-between">
      <h1 class="text-xl font-semibold">Approved Section — Approval Queue</h1>
      <a class="text-sm underline" href="{{ route('owner.dashboard') }}">Back</a>
    </div>

    <div class="mt-4 space-y-4">
      @forelse($items as $tx)
        <div class="border rounded p-4">
          <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2">
            <div>
              <div class="font-semibold">Admin: {{ $tx->admin_username }}</div>
              <div class="text-sm text-slate-700">
                {{ $tx->service }} • {{ $tx->duration }} • {{ $tx->category }} • {{ $tx->devices }}
              </div>
              <div class="text-sm">
                Price: <span class="font-semibold">₱{{ number_format($tx->price, 2) }}</span>
                • Capital: <span class="font-semibold">₱{{ number_format($tx->capital_cost, 2) }}</span>
                • Profit est: <span class="font-semibold">₱{{ number_format($tx->price - $tx->capital_cost, 2) }}</span>
              </div>
              <div class="text-xs text-slate-500 mt-1">Submitted: {{ $tx->created_at->format('Y-m-d H:i') }}</div>
            </div>

            <div class="flex gap-2">
              <form method="post" action="{{ route('owner.approvals.approve', $tx) }}">
                @csrf
                <button class="px-3 py-2 rounded bg-emerald-600 text-white text-sm">APPROVED</button>
              </form>

              <form method="post" action="{{ route('owner.approvals.refund', $tx) }}">
                @csrf
                <input name="owner_notes" class="border rounded px-2 py-1 text-sm" placeholder="Refund note (optional)" />
                <button class="px-3 py-2 rounded bg-red-600 text-white text-sm">REFUNDED</button>
              </form>
            </div>
          </div>

          <div class="mt-3">
            <div class="text-sm font-semibold mb-2">Receipts</div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
              @foreach($tx->receipts as $r)
                <a href="{{ asset('storage/'.$r->path) }}" target="_blank" class="block border rounded overflow-hidden">
                  <img src="{{ asset('storage/'.$r->path) }}" class="w-full h-24 object-cover" alt="receipt" />
                </a>
              @endforeach
            </div>
            <div class="text-xs text-slate-500 mt-2">If images do not show, run <code>php artisan storage:link</code>.</div>
          </div>
        </div>
      @empty
        <div class="text-slate-500">No pending approvals.</div>
      @endforelse
    </div>
  </div>
@endsection
