<x-mail::message>
# Reçu de paiement

Bonjour {{ $payment->user->name }},

Votre paiement FoodLab Academy a été confirmé.

- **Plan :** {{ ucfirst($payment->plan) }}
- **Montant :** {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}
- **Reçu n° :** {{ $payment->receipt_number }}
- **Date :** {{ optional($payment->paid_at)->format('d/m/Y H:i') }}

<x-mail::button :url="route('dashboard')">
Accéder à mon espace
</x-mail::button>

Merci,<br>
{{ config('app.name') }}
</x-mail::message>
