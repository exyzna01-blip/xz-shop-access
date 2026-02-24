@extends('layouts.app')

@php
  use Illuminate\Support\Facades\Crypt;
@endphp

@section('content')
  <div class="flex flex-col md:flex-row md:items-start gap-4">
    <div class="flex-1">
      <div class="bg-white border rounded p-4 mb-4">
        <div class="flex items-center justify-between">
          <h2 class="font-semibold">Restock</h2>
          <a class="text-sm underline" href="{{ route('reports.weekly_salary') }}">Weekly Salary Report</a>
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
                <th class="py-2 pr-3">Password</th>
                <th class="py-2 pr-3">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($stocks as $s)
                <tr class="border-t">
                  <td class="py-2 pr-3"><span class="px-2 py-0.5 rounded text-xs bg-slate-100">{{ $s->status }}</span></td>
                  <td class="py-2 pr-3">{{ $s->service }}</td>
                  <td class="py-2 pr-3">{{ $s->duration }}</td>
                  <td class="py-2 pr-3"><span class="px-2 py-0.5 rounded text-xs bg-indigo-50 border border-indigo-100">{{ $s->category }}</span></td>
                  <td class="py-2 pr-3">{{ $s->devices }}</td>
                  <td class="py-2 pr-3">{{ $s->email }}</td>
                  <td class="py-2 pr-3">
                    @php
                      $pw = '';
                      try { $pw = Crypt::decryptString($s->password); } catch (\Throwable $e) { $pw = '[encrypted]'; }
                    @endphp
                    <span class="font-mono">{{ $pw }}</span>
                  </td>
                  <td class="py-2 pr-3">
                    <div class="flex flex-wrap gap-2">
                      @if($s->status === 'AVAILABLE')
                        <form method="post" action="{{ route('admin.stocks.reserve', $s) }}">
                          @csrf
                          <button class="px-3 py-1 rounded bg-amber-500 text-white text-xs">RESERVED</button>
                        </form>
                      @endif

                      @if(in_array($s->status, ['AVAILABLE','RESERVED'], true))
                        <button class="px-3 py-1 rounded bg-emerald-600 text-white text-xs" onclick="openSoldModal({{ $s->id }})">SOLD</button>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div class="grid md:grid-cols-3 gap-4">
        <div class="bg-white border rounded p-4">
          <h3 class="font-semibold mb-2">Pending</h3>
          <div class="text-sm text-slate-600 mb-2">Reserved + waiting approval</div>
          <ul class="text-sm space-y-1">
            @forelse($pending as $p)
              <li class="border rounded p-2">
                <div class="flex justify-between">
                  <span class="font-semibold">{{ $p->status }}</span>
                  <span class="text-xs text-slate-500">{{ $p->created_at->format('Y-m-d') }}</span>
                </div>
                <div>{{ $p->service }} • {{ $p->duration }} • {{ $p->category }} • {{ $p->devices }}</div>
              </li>
            @empty
              <li class="text-slate-500">No pending.</li>
            @endforelse
          </ul>
        </div>

        <div class="bg-white border rounded p-4">
          <h3 class="font-semibold mb-2">Sold (Approved)</h3>
          <ul class="text-sm space-y-1">
            @forelse($sold as $tx)
              <li class="border rounded p-2">
                <div class="flex justify-between">
                  <span class="font-semibold">₱{{ number_format($tx->price, 2) }}</span>
                  <span class="text-xs text-slate-500">{{ optional($tx->approved_at)->format('Y-m-d') }}</span>
                </div>
                <div>{{ $tx->service }} • {{ $tx->duration }}</div>
              </li>
            @empty
              <li class="text-slate-500">No sold yet.</li>
            @endforelse
          </ul>
        </div>

        <div class="bg-white border rounded p-4">
          <h3 class="font-semibold mb-2">Refunded (Approved)</h3>
          <ul class="text-sm space-y-1">
            @forelse($refunded as $tx)
              <li class="border rounded p-2">
                <div class="flex justify-between">
                  <span class="font-semibold">{{ $tx->service }}</span>
                  <span class="text-xs text-slate-500">{{ optional($tx->approved_at)->format('Y-m-d') }}</span>
                </div>
                <div class="text-slate-600">Refunded</div>
              </li>
            @empty
              <li class="text-slate-500">No refunded.</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>

    <aside class="w-full md:w-80">
      <div class="bg-white border rounded p-4">
        <div class="flex items-center justify-between">
          <h3 class="font-semibold">Notifications</h3>
          <span class="text-xs text-slate-500">New drops</span>
        </div>
        <ul class="mt-3 space-y-2 text-sm">
          @forelse($notifications as $n)
            <li class="border rounded p-2 {{ $n->read_at ? 'bg-white' : 'bg-blue-50' }}">
              <div class="text-slate-800">{{ $n->message }}</div>
              <div class="flex items-center justify-between mt-1">
                <span class="text-xs text-slate-500">{{ $n->created_at->format('Y-m-d H:i') }}</span>
                @if(!$n->read_at)
                  <form method="post" action="{{ route('admin.notifications.read', $n) }}">
                    @csrf
                    <button class="text-xs underline">Mark read</button>
                  </form>
                @endif
              </div>
            </li>
          @empty
            <li class="text-slate-500">No notifications.</li>
          @endforelse
        </ul>
      </div>
    </aside>
  </div>

  <!-- SOLD modal -->
  <div id="soldModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4">
    <div class="bg-white rounded border w-full max-w-lg p-4">
      <div class="flex justify-between items-center">
        <h3 class="font-semibold">Mark as SOLD (Receipt required)</h3>
        <button class="text-sm underline" onclick="closeSoldModal()">Close</button>
      </div>

      <form id="soldForm" method="post" enctype="multipart/form-data" class="mt-3 space-y-3">
        @csrf
        <div>
          <label class="block text-sm font-medium">Upload receipts (1–5 images, total ≤ 700MB)</label>
          <input id="receiptsInput" type="file" name="receipts[]" multiple accept=".jpg,.jpeg,.png,.webp" class="mt-1 w-full" />
          <div id="sizeHint" class="text-xs text-slate-600 mt-1">Total: 0 MB</div>
        </div>

        <button id="submitSold" class="px-4 py-2 rounded bg-emerald-600 text-white disabled:opacity-50" disabled>
          Submit for approval
        </button>

        <div class="text-xs text-slate-500">
          Clear errors are shown above (too large / unsupported type). If upload fails, retry on stronger connection or compress images.
        </div>
      </form>
    </div>
  </div>

  <script>
    const modal = document.getElementById('soldModal');
    const form = document.getElementById('soldForm');
    const input = document.getElementById('receiptsInput');
    const sizeHint = document.getElementById('sizeHint');
    const submitBtn = document.getElementById('submitSold');

    function openSoldModal(stockId) {
      form.action = `/admin/stocks/${stockId}/sold`;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      input.value = '';
      sizeHint.textContent = 'Total: 0 MB';
      submitBtn.disabled = true;
    }

    function closeSoldModal() {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    input.addEventListener('change', () => {
      const files = Array.from(input.files || []);
      const maxFiles = {{ config('xzshop.receipt.max_images') }};
      const limit = {{ config('xzshop.receipt.total_limit_bytes') }};

      if (files.length < 1) {
        submitBtn.disabled = true;
        sizeHint.textContent = 'Total: 0 MB';
        return;
      }
      if (files.length > maxFiles) {
        submitBtn.disabled = true;
        sizeHint.textContent = `Too many files. Max ${maxFiles}.`;
        return;
      }

      const total = files.reduce((acc,f) => acc + (f.size || 0), 0);
      const mb = (total / (1024*1024)).toFixed(1);
      sizeHint.textContent = `Total: ${mb} MB`;
      submitBtn.disabled = total > limit;
      if (total > limit) sizeHint.textContent += ' — exceeds 700MB limit.';
    });
  </script>
@endsection
