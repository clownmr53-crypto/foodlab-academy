<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Paiement</h2></x-slot>
    <div class="max-w-lg mx-auto py-10 px-4">
        <div class="bg-white border rounded-xl p-6 text-center space-y-3">
            @if($payment->status === 'paid')
                <p class="text-emerald-700 text-xl font-bold">Paiement confirmé</p>
                <p>Reçu n° {{ $payment->receipt_number }}</p>
                <p>Plan activé : <strong>{{ ucfirst($payment->plan) }}</strong></p>
                <a href="{{ route('lms.index') }}" class="inline-block mt-2 rounded-lg bg-emerald-600 text-white px-4 py-2">Accéder à la formation</a>
            @else
                <p class="text-amber-700">Paiement en attente de confirmation.</p>
                <a href="{{ route('payments.plans') }}" class="text-emerald-700 underline">Retour aux plans</a>
            @endif
        </div>
    </div>
</x-app-layout>
