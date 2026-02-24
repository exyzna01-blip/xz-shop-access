@extends('layouts.app')

@section('content')
  <div class="bg-white border rounded p-5">
    <div class="flex items-center justify-between">
      <h1 class="text-xl font-semibold">Price Catalog Manager</h1>
      <a class="text-sm underline" href="{{ route('owner.dashboard') }}">Back</a>
    </div>

    <form method="post" action="{{ route('owner.catalog.store') }}" class="grid md:grid-cols-6 gap-2 mt-4">
      @csrf
      <input name="service" class="border rounded px-2 py-2 text-sm" placeholder="service" required />
      <input name="duration" class="border rounded px-2 py-2 text-sm" placeholder="duration (optional)" />
      <input name="category" class="border rounded px-2 py-2 text-sm" placeholder="category" required />
      <input name="devices" class="border rounded px-2 py-2 text-sm" placeholder="devices" />
      <input name="price" type="number" step="0.01" min="0" class="border rounded px-2 py-2 text-sm" placeholder="price" required />
      <button class="rounded bg-slate-900 text-white text-sm">Add</button>
    </form>

    <div class="overflow-auto mt-4">
      <table class="min-w-full text-sm">
        <thead class="text-left text-slate-600">
          <tr>
            <th class="py-2 pr-3">Service</th>
            <th class="py-2 pr-3">Duration</th>
            <th class="py-2 pr-3">Category</th>
            <th class="py-2 pr-3">Devices</th>
            <th class="py-2 pr-3">Price</th>
            <th class="py-2 pr-3">Active</th>
            <th class="py-2 pr-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($items as $it)
            <tr class="border-t align-top">
              <td class="py-2 pr-3">{{ $it->service }}</td>
              <td class="py-2 pr-3">{{ $it->duration ?? '—' }}</td>
              <td class="py-2 pr-3">{{ $it->category }}</td>
              <td class="py-2 pr-3">{{ $it->devices }}</td>
              <td class="py-2 pr-3">₱{{ number_format($it->price, 2) }}</td>
              <td class="py-2 pr-3">
                <span class="px-2 py-0.5 rounded text-xs {{ $it->active ? 'bg-emerald-50 border border-emerald-100' : 'bg-slate-100' }}">
                  {{ $it->active ? 'active' : 'inactive' }}
                </span>
              </td>
              <td class="py-2 pr-3">
                <details class="text-xs">
                  <summary class="cursor-pointer underline">Edit</summary>
                  <form method="post" action="{{ route('owner.catalog.update', $it) }}" class="grid grid-cols-2 gap-2 mt-2">
                    @csrf
                    @method('put')
                    <input name="service" class="border rounded px-2 py-1" value="{{ $it->service }}" required />
                    <input name="duration" class="border rounded px-2 py-1" value="{{ $it->duration }}" placeholder="duration (optional)" />
                    <input name="category" class="border rounded px-2 py-1" value="{{ $it->category }}" required />
                    <input name="devices" class="border rounded px-2 py-1" value="{{ $it->devices }}" />
                    <input name="price" type="number" step="0.01" min="0" class="border rounded px-2 py-1" value="{{ $it->price }}" required />
                    <label class="flex items-center gap-2 text-xs">
                      <input type="checkbox" name="active" value="1" {{ $it->active ? 'checked' : '' }} />
                      Active
                    </label>
                    <div class="col-span-2 flex gap-2">
                      <button class="px-3 py-1 rounded bg-slate-900 text-white">Save</button>
                      <form method="post" action="{{ route('owner.catalog.destroy', $it) }}" onsubmit="return confirm('Delete this catalog item?')">
                        @csrf
                        @method('delete')
                        <button class="px-3 py-1 rounded bg-red-600 text-white">Delete</button>
                      </form>
                    </div>
                  </form>
                </details>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
