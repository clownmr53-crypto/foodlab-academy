<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Choisir un plan</h2></x-slot>
    <div class="max-w-4xl mx-auto py-10 px-4 space-y-8">
        <div class="text-center">
            <h3 class="text-2xl font-bold mb-2">Starter gratuit pour toujours, ou Premium complet</h3>
            <p class="text-slate-600 text-sm">Garantie {{ (int) config('foodlab.guarantee_days', 14) }} jours satisfait ou remboursé sur Premium. Paiement sécurisé.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            @foreach($plans as $key => $plan)
                @php
                    $label = $plan['currency_label'] ?? 'FCFA';
                    $isFree = (int) $plan['price'] <= 0;
                @endphp
                <div class="bg-white border rounded-2xl p-6 {{ $key === 'premium' ? 'border-emerald-500 ring-2 ring-emerald-100' : 'border-stone-200' }}">
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ $isFree ? 'Gratuit pour toujours' : 'Programme Premium' }}</p>
                    <h3 class="text-xl font-bold mt-1">{{ $plan['name'] }}</h3>
                    <p class="text-3xl font-bold text-emerald-700 my-3">
                        @if($isFree)
                            0 {{ $label }} <span class="text-base font-medium text-slate-500">/ pour toujours</span>
                        @else
                            {{ number_format($plan['price'], 0, ',', ' ') }} {{ $label }}
                        @endif
                    </p>
                    <ul class="space-y-2 text-sm mb-5">
                        @foreach(($plan['features'] ?? []) as $feature)
                            <li class="flex gap-2 {{ ($feature['included'] ?? true) ? 'text-slate-700' : 'text-slate-400 line-through' }}">
                                <span>{{ ($feature['included'] ?? true) ? '✓' : '✗' }}</span>
                                <span>{{ $feature['text'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <p class="text-xs text-slate-500 mb-4">Accès modules {{ implode(', ', $plan['modules_access']) }}</p>

                    @if(auth()->user()->plan === $key)
                        <p class="text-emerald-700 font-medium">Plan actuel</p>
                    @elseif(auth()->user()->plan === 'premium' && $key === 'starter')
                        <p class="text-slate-500 text-sm">Inclus dans Premium</p>
                    @elseif($isFree)
                        <form method="POST" action="{{ route('payments.checkout') }}">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $key }}">
                            <input type="hidden" name="provider" value="fake">
                            <button class="w-full rounded-lg bg-emerald-600 text-white py-2.5 font-semibold">Activer Starter gratuitement</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('payments.checkout') }}" class="space-y-3">
                            @csrf
                            <input type="hidden" name="plan" value="{{ $key }}">
                            <label class="block text-sm">Moyen de paiement
                                <select name="provider" class="mt-1 w-full rounded-md border-stone-300">
                                    <option value="fake">Simulation (sandbox local)</option>
                                    <option value="stripe">Carte (Stripe)</option>
                                    <option value="{{ $mmProvider }}">Mobile Money ({{ ucfirst($mmProvider) }})</option>
                                </select>
                            </label>
                            <button class="w-full rounded-lg bg-emerald-600 text-white py-2.5 font-semibold">
                                {{ auth()->user()->plan === 'starter' && $key === 'premium' ? 'Upgrader vers Premium' : 'Souscrire Premium' }}
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="rounded-xl bg-stone-50 border border-stone-200 p-5 text-sm text-slate-600">
            <p class="font-semibold text-slate-800 mb-2">Moyens de paiement acceptés</p>
            <p>{{ implode(' · ', config('foodlab.payment_methods_labels', ['Orange Money', 'MTN Money', 'Moov Money', 'Wave', 'Stripe'])) }}</p>
            <p class="mt-3">🔒 Garantie {{ (int) config('foodlab.guarantee_days', 14) }} jours satisfait ou remboursé — sans condition sur le Premium.</p>
        </div>
    </div>
</x-app-layout>
