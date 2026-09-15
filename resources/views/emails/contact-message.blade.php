<x-mail::message>
# Nouveau message de contact

**De :** {{ $contactMessage->name }} ({{ $contactMessage->email }})

**WhatsApp :** {{ $contactMessage->whatsapp ?: '—' }}

**Pays :** {{ $contactMessage->country ?: '—' }}

**Sujet :** {{ $contactMessage->subject ?: '—' }}

{{ $contactMessage->message }}
</x-mail::message>
