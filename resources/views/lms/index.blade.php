<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Formation</h2></x-slot>
    <div class="max-w-5xl mx-auto py-8 px-4 space-y-4">
        @foreach($modules as $module)
            @php $locked = ! $user->canAccessModule($module); @endphp
            <div class="bg-white border rounded-xl p-5 {{ $locked ? 'opacity-70' : '' }}">
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <h3 class="font-semibold text-lg">Module {{ $module->order }} — {{ $module->title }}
                            @if($module->is_premium_only)<span class="text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded">Premium</span>@endif
                        </h3>
                        <p class="text-sm text-slate-600 mt-1">{{ $module->description }}</p>
                        <ul class="mt-3 text-sm space-y-1">
                            @foreach($module->lessons as $lesson)
                                <li class="flex items-center gap-2">
                                    @if($completed->contains($lesson->id))
                                        <span class="text-emerald-600">✓</span>
                                    @else
                                        <span class="text-slate-300">○</span>
                                    @endif
                                    {{ $lesson->title }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @if($locked)
                        <a href="{{ route('payments.plans') }}" class="text-sm whitespace-nowrap text-amber-700 font-medium">Débloquer Premium</a>
                    @else
                        <a href="{{ route('lms.module', $module) }}" class="rounded-lg bg-emerald-600 text-white px-3 py-2 text-sm">Ouvrir</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
