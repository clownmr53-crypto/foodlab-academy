<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Choisir un plan</h2></x-slot>
    <div class="max-w-4xl mx-auto py-10 px-4 grid md:grid-cols-2 gap-6">
        @foreach($plans as $key => $plan)
            <div class="bg-white border rounded-2xl p-6 {{ $key === 'premium' ? 'border-emerald-500' : '' }}">
                <h3 class="text-xl font-bold">{{ $plan['name'] }}</h3>
                <p class="text-3xl font-bold text-emerald-700 my-3">{{ number_format($plan['price'], 0, ',', ' ') }} {{ $plan['currency'] }}</p>
                <p class="text-sm text-slate-600 mb-4">Accès modules {{ implode(', ', $plan['modules_access']) }}</p>
                @if(auth()->user()->plan === $key)
                    <p class="text-emerald-700 font-medium">Plan actuel</p>
                @elseif(auth()->user()->plan === 'premium' && $key === 'starter')
                    <p class="text-slate-500 text-sm">Inclus dans Premium</p>
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
                        <button class="w-full rounded-lg bg-emerald-600 text-white py-2 font-semibold">
                            {{ auth()->user()->plan === 'starter' && $key === 'premium' ? 'Upgrader vers Premium' : 'Souscrire' }}
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</x-app-layout>
