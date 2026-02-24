@extends('layouts.app')

@section('content')
  <div class="max-w-md mx-auto bg-white rounded border p-5">
    <h1 class="text-xl font-semibold mb-4">Login</h1>
    <form method="post" action="{{ route('login.post') }}" class="space-y-3">
      @csrf
      <div>
        <label class="block text-sm font-medium">Username</label>
        <input name="username" value="{{ old('username') }}" class="mt-1 w-full border rounded px-3 py-2" autocomplete="username" />
      </div>
      <div>
        <label class="block text-sm font-medium">Password</label>
        <input type="password" name="password" class="mt-1 w-full border rounded px-3 py-2" autocomplete="current-password" />
      </div>
      <button class="w-full bg-slate-900 text-white rounded px-4 py-2">Sign in</button>
    </form>

    <div class="mt-4 text-xs text-slate-500">
      OWNER: ownereli / silverdawn<br/>
      ADMINS: admin_cherry, admin_mir, admin_sica / xzshop123
    </div>
  </div>
@endsection
