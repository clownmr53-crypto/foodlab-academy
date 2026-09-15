<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Templates</h2></x-slot>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-4">
        @include('admin._nav')
        <a href="{{ route('admin.templates.create') }}" class="inline-block rounded-lg bg-emerald-600 text-white px-3 py-2 text-sm">Nouveau template</a>
        <div class="bg-white border rounded-xl divide-y">
            @forelse($templates as $template)
                <div class="p-4 flex justify-between gap-3">
                    <div>
                        <p class="font-medium">{{ $template->title }}</p>
                        <p class="text-xs text-slate-500">{{ $template->file_path ? 'Fichier' : ($template->external_url ? 'URL externe' : 'Sans fichier') }}</p>
                    </div>
                    <div class="flex gap-2 text-sm">
                        <a href="{{ route('admin.templates.edit', $template) }}">Modifier</a>
                        <form method="POST" action="{{ route('admin.templates.destroy', $template) }}">@csrf @method('DELETE')<button class="text-red-600">Suppr.</button></form>
                    </div>
                </div>
            @empty
                <p class="p-4 text-slate-500 text-sm">Aucun template.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
