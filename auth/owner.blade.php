@extends('layouts.app')

@section('content')
  <div class="grid md:grid-cols-4 gap-4 mb-4">
    <div class="bg-white border rounded p-4">
      <div class="text-sm text-slate-600">Total Sales</div>
      <div class="text-2xl font-semibold">₱{{ number_format($totalSales, 2) }}</div>
    </div>
    <div class="bg-white border rounded p-4">
      <div class="text-sm text-slate-600">Total Capital</div>
      <div class="text-2xl font-semibold">₱{{ number_format($totalCapital, 2) }}</div>
    </div>
    <div class="bg-white border rounded p-4">
      <div class="text-sm text-slate-600">Total Revenue</div>
      <div class="text-2xl font-semibold">₱{{ number_format($totalRevenue, 2) }}</div>
    </div>
    <div class="bg-white border rounded p-4">
      <div class="text-sm text-slate-600">Reports</div>
      <a class="underline" href="{{ route('reports.weekly_salary') }}">Weekly Salary</a>
    </div>
  </div>

  <div class="flex flex-wrap gap-3 mb-4">
    <a class="px-3 py-2 rounded bg-slate-900 text-white text-sm" href="{{ route('owner.stocks.create') }}">+ Drop New Stock</a>
    <a class="px-3 py-2 rounded bg-white border text-sm" href="{{ route('owner.approvals.queue') }}">Approved Section (Queue)</a>
    <a class="px-3 py-2 rounded bg-white border text-sm" href="{{ route('owner.catalog') }}">Price Catalog</a>
  </div>

  <div class="bg-white border rounded p-4">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold">Restock / All Stock</h2>
      <span class="text-sm text-slate-500">Edit capital + status</span>
    </div>

    <div class="overflow-auto mt-3">
      <table class="min-w-full text-sm">
        <thead class="text-left text-slate-600">
          <tr>
            <th class="py-2 pr-3">Status</th>
            <th class="py-2 pr-3">Service</th>
            <th class="py-2 pr-3">Duration</th>
            <th class="py-2 pr-3">Category</th>
            <th class="py-2 pr-3">Devices</th>
            <th class="py-2 pr-3">Email</th>
            <th class="py-2 pr-3">Capital</th>
            <th class="py-2 pr-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($stocks as $s)
            <tr class="border-t">
              <td class="py-2 pr-3"><span class="px-2 py-0.5 rounded text-xs bg-slate-100">{{ $s->status }}</span></td>
              <td class="py-2 pr-3">{{ $s->service }}</td>
              <td class="py-2 pr-3">{{ $s->duration }}</td>
              <td class="py-2 pr-3">{{ $s->category }}</td>
              <td class="py-2 pr-3">{{ $s->devices }}</td>
              <td class="py-2 pr-3">{{ $s->email }}</td>
              <td class="py-2 pr-3">₱{{ number_format($s->capital_cost, 2) }}</td>
              <td class="py-2 pr-3">
                <div class="flex gap-2">
                  <a class="text-xs underline" href="{{ route('owner.stocks.edit', $s) }}">Edit</a>
                  <form method="post" action="{{ route('owner.stocks.destroy', $s) }}" onsubmit="return confirm('Delete this stock?')">
                    @csrf
                    @method('delete')
                    <button class="text-xs underline text-red-600">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
