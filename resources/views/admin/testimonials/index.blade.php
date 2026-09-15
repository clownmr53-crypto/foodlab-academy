<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Témoignages</h2></x-slot>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-4">
        @include('admin._nav')
        <a href="{{ route('admin.testimonials.create') }}" class="inline-block rounded-lg bg-emerald-600 text-white px-3 py-2 text-sm">Nouveau</a>
        <div class="bg-white border rounded-xl divide-y">
            @foreach($testimonials as $t)
                <div class="p-4 flex justify-between gap-3">
                    <div><p class="font-medium">{{ $t->author_name }}</p><p class="text-sm text-slate-600">{{ Str::limit($t->content, 80) }}</p></div>
                    <div class="flex gap-2 text-sm">
                        <a href="{{ route('admin.testimonials.edit', $t) }}">Modifier</a>
                        <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}">@csrf @method('DELETE')<button class="text-red-600">Suppr.</button></form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
