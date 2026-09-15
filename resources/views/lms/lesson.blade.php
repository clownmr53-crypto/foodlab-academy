<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('lms.module', $module) }}" class="text-sm text-emerald-700">← {{ $module->title }}</a>
            <h2 class="font-semibold text-xl">{{ $lesson->title }}</h2>
        </div>
    </x-slot>
    <div class="max-w-4xl mx-auto py-8 px-4 space-y-6">
        {!! $embed !!}
        <div class="bg-white border rounded-xl p-6 prose max-w-none">
            {!! $lesson->content !!}
        </div>
        @if(!($progress?->completed))
            <form method="POST" action="{{ route('lms.lesson.complete', [$module, $lesson]) }}">
                @csrf
                <x-primary-button>Marquer comme terminée</x-primary-button>
            </form>
        @else
            <p class="text-emerald-700 font-medium">✓ Leçon terminée le {{ $progress->completed_at?->format('d/m/Y') }}</p>
        @endif
    </div>
</x-app-layout>
