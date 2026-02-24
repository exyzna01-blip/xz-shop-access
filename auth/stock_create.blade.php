@extends('layouts.app')

@section('content')
  <div class="bg-white border rounded p-5">
    <div class="flex items-center justify-between">
      <h1 class="text-xl font-semibold">Drop New Stock</h1>
      <a class="text-sm underline" href="{{ route('owner.dashboard') }}">Back</a>
    </div>

    <form method="post" action="{{ route('owner.stocks.store') }}" class="grid md:grid-cols-2 gap-4 mt-4">
      @csrf

      <div>
        <label class="block text-sm font-medium">Service</label>
        <select name="service" class="mt-1 w-full border rounded px-3 py-2">
          @foreach($services as $v)
            <option value="{{ $v }}">{{ $v }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium">Duration</label>
        <select name="duration" class="mt-1 w-full border rounded px-3 py-2">
          @foreach($durations as $v)
            <option value="{{ $v }}">{{ $v }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium">Category</label>
        <select name="category" class="mt-1 w-full border rounded px-3 py-2">
          @foreach($categories as $v)
            <option value="{{ $v }}">{{ $v }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium">Devices</label>
        <input name="devices" class="mt-1 w-full border rounded px-3 py-2" placeholder="e.g. 10-11 devices" />
      </div>

      <div>
        <label class="block text-sm font-medium">Email</label>
        <input name="email" class="mt-1 w-full border rounded px-3 py-2" />
      </div>

      <div>
        <label class="block text-sm font-medium">Password</label>
        <input name="password" class="mt-1 w-full border rounded px-3 py-2" />
      </div>

      <div>
        <label class="block text-sm font-medium">Label/Type</label>
        <select name="label" class="mt-1 w-full border rounded px-3 py-2">
          <option value="">(optional)</option>
          @foreach($labels as $v)
            <option value="{{ $v }}">{{ $v }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium">Capital Cost (₱)</label>
        <input name="capital_cost" type="number" step="0.01" min="0" class="mt-1 w-full border rounded px-3 py-2" value="0" />
      </div>

      <div class="md:col-span-2">
        <button class="px-4 py-2 rounded bg-slate-900 text-white">Drop & Notify Admins</button>
      </div>
    </form>
  </div>
@endsection
