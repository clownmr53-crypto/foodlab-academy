<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800">Bonjour, {{ $user->name }}</h2>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 space-y-6">
        <div class="grid md:grid-cols-3 gap-4">
            <div class="rounded-xl bg-white border p-5">
                <p class="text-sm text-slate-500">Plan</p>
                <p class="text-2xl font-bold text-emerald-700">{{ $user->plan ? ucfirst($user->plan) : 'Aucun' }}</p>
                @unless($user->hasPlan('starter'))
                    <a href="{{ route('payments.plans') }}" class="text-sm text-emerald-700 underline">Choisir un plan</a>
                @endunless
            </div>
            <div class="rounded-xl bg-white border p-5">
                <p class="text-sm text-slate-500">Progression</p>
                <p class="text-2xl font-bold">{{ $progressPercent }}%</p>
            </div>
            <div class="rounded-xl bg-white border p-5">
                <p class="text-sm text-slate-500">Certificats</p>
                @if($user->certificates()->exists())
                    <a href="{{ route('certificates.mine') }}" class="text-emerald-700 font-semibold underline">Voir mes certificats</a>
                @else
                    <p class="text-slate-600 text-sm">Starter (modules 1–3) ou Premium (parcours complet).</p>
                @endif
            </div>
        </div>

        <div class="rounded-xl bg-white border p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Modules</h3>
                @if($user->hasPlan('starter') || $user->isAdmin())
                    <a href="{{ route('lms.index') }}" class="text-sm text-emerald-700">Ouvrir la formation →</a>
                @endif
            </div>
            <div class="space-y-3">
                @foreach($modules as $module)
                    @php $locked = ! $user->canAccessModule($module); @endphp
                    <div class="flex items-center justify-between rounded-lg border px-4 py-3 {{ $locked ? 'bg-stone-50 opacity-75' : '' }}">
                        <div>
                            <p class="font-medium">{{ $module->order }}. {{ $module->title }}
                                @if($module->is_premium_only)<span class="ml-2 text-xs rounded bg-amber-100 text-amber-800 px-2 py-0.5">Premium</span>@endif
                            </p>
                            <p class="text-xs text-slate-500">{{ $module->lessons->count() }} leçons</p>
                        </div>
                        @if($locked)
                            <span class="text-sm text-slate-500">🔒 Verrouillé</span>
                        @else
                            <a class="text-sm text-emerald-700 font-medium" href="{{ route('lms.module', $module) }}">Continuer</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
