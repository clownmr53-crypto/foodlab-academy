<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Demandes coaching</h2></x-slot>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-4">
        @include('admin._nav')
        <div class="bg-white border rounded-xl divide-y">
            @forelse($requests as $req)
                <div class="p-4 space-y-2">
                    <div class="flex justify-between gap-3">
                        <div>
                            <p class="font-medium">{{ $req->name }} &lt;{{ $req->email }}&gt;</p>
                            <p class="text-xs text-slate-500">{{ $req->created_at->format('d/m/Y H:i') }} · {{ $req->preferred_slot }} · {{ $req->phone }}</p>
                            @if($req->message)<p class="text-sm mt-1">{{ $req->message }}</p>@endif
                        </div>
                        <form method="POST" action="{{ route('admin.coaching.destroy', $req) }}">@csrf @method('DELETE')<button class="text-sm text-red-600">Suppr.</button></form>
                    </div>
                    <form method="POST" action="{{ route('admin.coaching.status', $req) }}" class="flex gap-2 items-center text-sm">
                        @csrf @method('PATCH')
                        <select name="status" class="rounded-md border-stone-300 text-sm">
                            @foreach(['pending','contacted','done','cancelled'] as $st)
                                <option value="{{ $st }}" @selected($req->status === $st)>{{ $st }}</option>
                            @endforeach
                        </select>
                        <button class="text-emerald-700">OK</button>
                    </form>
                </div>
            @empty
                <p class="p-4 text-slate-500 text-sm">Aucune demande.</p>
            @endforelse
        </div>
        <div>{{ $requests->links() }}</div>
    </div>
</x-app-layout>
