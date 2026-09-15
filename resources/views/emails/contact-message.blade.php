<x-mail::message>
# Nouveau message de contact

**De :** {{ $contactMessage->name }} ({{ $contactMessage->email }})

**Sujet :** {{ $contactMessage->subject ?: '—' }}

{{ $contactMessage->message }}
</x-mail::message>
