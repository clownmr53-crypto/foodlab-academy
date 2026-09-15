<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Modules</h2></x-slot>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-4">
        @include('admin._nav')
        <a href="{{ route('admin.modules.create') }}" class="inline-block rounded-lg bg-emerald-600 text-white px-3 py-2 text-sm">Nouveau module</a>
        <div class="bg-white border rounded-xl divide-y">
            @foreach($modules as $module)
                <div class="p-4 flex justify-between items-center gap-3">
                    <div>
                        <p class="font-medium">{{ $module->order }}. {{ $module->title }} @if($module->is_premium_only)<span class="text-xs text-amber-700">Premium</span>@endif</p>
                        <p class="text-xs text-slate-500">{{ $module->lessons_count }} leçons</p>
                    </div>
                    <div class="flex gap-2 text-sm">
                        <a href="{{ route('admin.modules.lessons.index', $module) }}" class="text-emerald-700">Leçons</a>
                        <a href="{{ route('admin.modules.edit', $module) }}">Modifier</a>
                        <form method="POST" action="{{ route('admin.modules.destroy', $module) }}" onsubmit="return confirm('Supprimer ?')">@csrf @method('DELETE')<button class="text-red-600">Suppr.</button></form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
