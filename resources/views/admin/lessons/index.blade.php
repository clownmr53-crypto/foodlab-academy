<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Leçons — {{ $module->title }}</h2></x-slot>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-4">
        @include('admin._nav')
        <a href="{{ route('admin.modules.lessons.create', $module) }}" class="inline-block rounded-lg bg-emerald-600 text-white px-3 py-2 text-sm">Nouvelle leçon</a>
        <div class="bg-white border rounded-xl divide-y">
            @foreach($module->lessons as $lesson)
                <div class="p-4 flex justify-between">
                    <p>{{ $lesson->order }}. {{ $lesson->title }}</p>
                    <div class="flex gap-2 text-sm">
                        <a href="{{ route('admin.modules.lessons.edit', [$module, $lesson]) }}">Modifier</a>
                        <form method="POST" action="{{ route('admin.modules.lessons.destroy', [$module, $lesson]) }}">@csrf @method('DELETE')<button class="text-red-600">Suppr.</button></form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
