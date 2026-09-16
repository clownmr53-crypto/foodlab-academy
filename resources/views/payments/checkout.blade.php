<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Paiement</h2></x-slot>
    <div class="max-w-lg mx-auto py-10 px-4">
        <div class="bg-white border rounded-xl p-6 space-y-4">
            <p>Plan : <strong>{{ ucfirst($payment->plan) }}</strong></p>
            <p>Montant : <strong>{{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}</strong></p>
            <p>Fournisseur : <strong>{{ $payment->provider }}</strong></p>
            @isset($message)<p class="text-amber-700 text-sm">{{ $message }}</p>@endisset

            @if(($mmProvider ?? $payment->provider) === 'fedapay')
                <div class="rounded-lg bg-emerald-50 border border-emerald-100 p-3 text-sm text-emerald-900">
                    <p class="font-medium mb-1">Mobile Money via FedaPay</p>
                    <p class="text-emerald-800/80">Méthodes supportées : MTN Money · Orange Money · Moov Money · Wave</p>
                </div>
            @endif

            @if(!empty($simulate))
                <p class="text-sm text-slate-600">Mode simulation sandbox : cliquez pour activer le plan sans paiement réel.</p>
                <form method="POST" action="{{ route('payments.simulate', $payment) }}">
                    @csrf
                    <x-primary-button>Simuler le paiement réussi</x-primary-button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
