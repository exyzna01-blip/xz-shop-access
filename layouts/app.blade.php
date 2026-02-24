<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('xzshop.app_name') }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900">
  <nav class="bg-white border-b">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
      <div class="font-semibold text-lg">{{ config('xzshop.app_name') }}</div>
      <div class="flex items-center gap-3">
        @auth
          <span class="text-sm px-2 py-1 rounded bg-slate-100">{{ auth()->user()->role }}: {{ auth()->user()->username }}</span>
          <form method="post" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm px-3 py-1 rounded bg-slate-900 text-white">Logout</button>
          </form>
        @endauth
      </div>
    </div>
  </nav>

  <main class="max-w-6xl mx-auto px-4 py-6">
    @if(session('ok'))
      <div class="mb-4 p-3 rounded bg-green-50 border border-green-200 text-green-800">{{ session('ok') }}</div>
    @endif

    @if($errors->any())
      <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-800">
        <div class="font-semibold mb-1">Please fix:</div>
        <ul class="list-disc pl-5 text-sm">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @yield('content')
  </main>

  <footer class="border-t bg-white">
    <div class="max-w-6xl mx-auto px-4 py-5 text-sm text-slate-600">
      <div class="font-semibold">Shop Info</div>
      <div>owner: @xzelise on telegram</div>
      <div>main ch: @xoxoelisee on telegram</div>
      <div class="mt-2">
        <div class="font-semibold">Weekly Salary Rule</div>
        <div>Profit = Sales - Capital. Salary = Profit × Percentage. Only APPROVED_SOLD counts. REFUNDED is excluded/deducted.</div>
      </div>
    </div>
  </footer>
</body>
</html>
