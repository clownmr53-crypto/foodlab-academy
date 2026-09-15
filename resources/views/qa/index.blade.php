<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Q&R mensuelles</h2></x-slot>
    <div class="max-w-3xl mx-auto py-8 px-4 space-y-8">
        <section>
            <h3 class="font-semibold text-lg mb-3">À venir</h3>
            <div class="space-y-3">
                @forelse($upcoming as $session)
                    <div class="bg-white border rounded-xl p-5">
                        <p class="font-medium text-lg">{{ $session->title }}</p>
                        <p class="text-sm text-emerald-700 mt-1">{{ $session->session_at->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</p>
                        @if($session->description)<p class="text-sm text-slate-600 mt-2">{{ $session->description }}</p>@endif
                        @if($session->visio_link)
                            <a href="{{ $session->visio_link }}" target="_blank" rel="noopener" class="inline-block mt-3 text-sm rounded-lg bg-emerald-600 text-white px-3 py-1.5">Rejoindre la visio</a>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucune session à venir.</p>
                @endforelse
            </div>
        </section>
        <section>
            <h3 class="font-semibold text-lg mb-3">Replays</h3>
            <div class="space-y-3">
                @forelse($past as $session)
                    <div class="bg-white border rounded-xl p-5">
                        <p class="font-medium">{{ $session->title }}</p>
                        <p class="text-xs text-slate-500">{{ $session->session_at->format('d/m/Y') }}</p>
                        @if($session->replay_url)
                            <a href="{{ $session->replay_url }}" target="_blank" rel="noopener" class="text-sm text-emerald-700 underline mt-2 inline-block">Voir le replay</a>
                        @else
                            <p class="text-xs text-slate-400 mt-1">Replay bientôt disponible</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun replay pour le moment.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
