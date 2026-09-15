<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Sessions Q&R</h2></x-slot>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-4">
        @include('admin._nav')
        <a href="{{ route('admin.qa.create') }}" class="inline-block rounded-lg bg-emerald-600 text-white px-3 py-2 text-sm">Nouvelle session</a>
        <div class="bg-white border rounded-xl divide-y">
            @forelse($sessions as $session)
                <div class="p-4 flex justify-between gap-3">
                    <div>
                        <p class="font-medium">{{ $session->title }}</p>
                        <p class="text-xs text-slate-500">{{ $session->session_at->format('d/m/Y H:i') }} · {{ $session->is_published ? 'Publié' : 'Brouillon' }}</p>
                    </div>
                    <div class="flex gap-2 text-sm">
                        <a href="{{ route('admin.qa.edit', $session) }}">Modifier</a>
                        <form method="POST" action="{{ route('admin.qa.destroy', $session) }}">@csrf @method('DELETE')<button class="text-red-600">Suppr.</button></form>
                    </div>
                </div>
            @empty
                <p class="p-4 text-slate-500 text-sm">Aucune session.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
