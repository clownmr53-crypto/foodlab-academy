<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('lms.index') }}" class="text-sm text-emerald-700">← Formation</a>
            <h2 class="font-semibold text-xl">{{ $module->title }}</h2>
        </div>
    </x-slot>
    <div class="max-w-3xl mx-auto py-8 px-4 space-y-3">
        <p class="text-slate-600 mb-4">{{ $module->description }}</p>
        @foreach($module->lessons as $lesson)
            <a href="{{ route('lms.lesson', [$module, $lesson]) }}" class="flex justify-between items-center bg-white border rounded-xl px-4 py-3 hover:border-emerald-400">
                <span>{{ $lesson->order }}. {{ $lesson->title }}</span>
                <span class="text-sm text-slate-500">
                    @if($completed->contains($lesson->id))Terminée @else {{ $lesson->duration }} min @endif
                </span>
            </a>
        @endforeach
    </div>
</x-app-layout>
