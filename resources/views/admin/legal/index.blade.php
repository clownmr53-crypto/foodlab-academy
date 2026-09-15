<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Pages légales</h2></x-slot>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-4">
        @include('admin._nav')
        <div class="bg-white border rounded-xl divide-y">
            @foreach($pages as $page)
                <div class="p-4 flex justify-between">
                    <p class="font-medium">{{ $page->title }} <span class="text-xs text-slate-400">/{{ $page->slug }}</span></p>
                    <a href="{{ route('admin.legal.edit', $page) }}" class="text-sm text-emerald-700">Modifier</a>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
