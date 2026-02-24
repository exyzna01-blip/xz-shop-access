@extends('layouts.app')

@section('content')
  <div class="bg-white border rounded p-5">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
      <div>
        <h1 class="text-xl font-semibold">Weekly Salary Report</h1>
        <div class="text-sm text-slate-600">Week start (Monday): <span class="font-semibold">{{ $weekStart }}</span></div>
      </div>

      <form method="get" action="{{ route('reports.weekly_salary') }}" class="flex items-center gap-2">
        <input type="date" name="week_start" class="border rounded px-2 py-1 text-sm" value="{{ request('week_start', $weekStart) }}" />
        <button class="px-3 py-1 rounded bg-slate-900 text-white text-sm">Go</button>
        @if(auth()->user()->role === 'OWNER')
          <a class="text-sm underline" href="{{ route('owner.dashboard') }}">Owner Dashboard</a>
        @else
          <a class="text-sm underline" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
        @endif
      </form>
    </div>

    <div class="grid md:grid-cols-4 gap-4 mt-4">
      <div class="border rounded p-3">
        <div class="text-xs text-slate-600">Total Sales</div>
        <div class="text-lg font-semibold">₱{{ number_format($totalSales, 2) }}</div>
      </div>
      <div class="border rounded p-3">
        <div class="text-xs text-slate-600">Total Capital</div>
        <div class="text-lg font-semibold">₱{{ number_format($totalCapital, 2) }}</div>
      </div>
      <div class="border rounded p-3">
        <div class="text-xs text-slate-600">Total Revenue</div>
        <div class="text-lg font-semibold">₱{{ number_format($totalRevenue, 2) }}</div>
      </div>
      <div class="border rounded p-3">
        <div class="text-xs text-slate-600">Total Weekly Salary</div>
        <div class="text-lg font-semibold">₱{{ number_format($totalSalary, 2) }}</div>
      </div>
    </div>

    @if(auth()->user()->role === 'OWNER')
      <div class="mt-4">
        <div class="font-semibold mb-2">Salary by Admin</div>
        <div class="grid md:grid-cols-3 gap-3">
          @forelse($byAdmin as $admin => $amt)
            <div class="border rounded p-3">
              <div class="text-sm text-slate-600">{{ $admin }}</div>
              <div class="text-lg font-semibold">₱{{ number_format($amt, 2) }}</div>
            </div>
          @empty
            <div class="text-slate-500">No approved sales this week.</div>
          @endforelse
        </div>
      </div>
    @endif

    <div class="mt-5 overflow-auto">
      <table class="min-w-full text-sm">
        <thead class="text-left text-slate-600">
          <tr>
            <th class="py-2 pr-3">Approved At</th>
            @if(auth()->user()->role === 'OWNER')
              <th class="py-2 pr-3">Admin</th>
            @endif
            <th class="py-2 pr-3">Service</th>
            <th class="py-2 pr-3">Duration</th>
            <th class="py-2 pr-3">Category</th>
            <th class="py-2 pr-3">Devices</th>
            <th class="py-2 pr-3">Price</th>
            <th class="py-2 pr-3">Capital</th>
            <th class="py-2 pr-3">Profit</th>
            <th class="py-2 pr-3">%</th>
            <th class="py-2 pr-3">Salary</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $r)
            <tr class="border-t">
              <td class="py-2 pr-3">{{ $r['approved_at'] }}</td>
              @if(auth()->user()->role === 'OWNER')
                <td class="py-2 pr-3">{{ $r['admin'] }}</td>
              @endif
              <td class="py-2 pr-3">{{ $r['service'] }}</td>
              <td class="py-2 pr-3">{{ $r['duration'] }}</td>
              <td class="py-2 pr-3">{{ $r['category'] }}</td>
              <td class="py-2 pr-3">{{ $r['devices'] }}</td>
              <td class="py-2 pr-3">₱{{ number_format($r['price'], 2) }}</td>
              <td class="py-2 pr-3">₱{{ number_format($r['capital'], 2) }}</td>
              <td class="py-2 pr-3">₱{{ number_format($r['profit'], 2) }}</td>
              <td class="py-2 pr-3">{{ (int)round($r['percent'] * 100) }}%</td>
              <td class="py-2 pr-3 font-semibold">₱{{ number_format($r['salary'], 2) }}</td>
            </tr>
          @empty
            <tr class="border-t">
              <td class="py-3 text-slate-500" colspan="12">No APPROVED_SOLD transactions in this week.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
