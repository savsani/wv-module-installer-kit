@if (session()->has('impersonator_id') && Route::has('admin.impersonate.stop'))
    <div role="status" class="flex items-center justify-center gap-3 bg-amber-500 px-4 py-2.5 text-center text-sm font-medium text-white">
        <span>
            You're viewing as <strong>{{ auth()->user()->name }}</strong>.
        </span>
        <form method="POST" action="{{ route('admin.impersonate.stop') }}">
            @csrf
            <button type="submit" class="underline hover:no-underline">
                Return to your account
            </button>
        </form>
    </div>
@endif
