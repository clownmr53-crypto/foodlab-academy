<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ $session->title }}</h2></x-slot>
    <div class="max-w-3xl mx-auto py-8 px-4 space-y-6">
        @php $r = $session->results; @endphp
        <div class="bg-white border rounded-xl p-6 space-y-4">
            <p class="text-lg font-semibold">{{ $r['product_name'] ?? '' }}</p>
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b"><th class="py-2">Ingrédient</th><th>Qté</th><th>Coût u.</th><th>Total</th></tr></thead>
                <tbody>
                @foreach($r['ingredient_lines'] ?? [] as $line)
                    <tr class="border-b border-stone-100">
                        <td class="py-2">{{ $line['name'] }}</td>
                        <td>{{ $line['quantity'] }}</td>
                        <td>{{ number_format($line['unit_cost'], 2, ',', ' ') }}</td>
                        <td>{{ number_format($line['total'], 2, ',', ' ') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <dl class="grid grid-cols-2 gap-2 text-sm">
                <div>Main d'œuvre <strong class="float-right">{{ number_format($r['labor_total'], 2, ',', ' ') }}</strong></div>
                <div>Frais généraux <strong class="float-right">{{ number_format($r['overhead'], 2, ',', ' ') }}</strong></div>
                <div>Emballage <strong class="float-right">{{ number_format($r['packaging'], 2, ',', ' ') }}</strong></div>
                <div>Coût total <strong class="float-right">{{ number_format($r['total_cost'], 2, ',', ' ') }}</strong></div>
                <div>Coût unitaire <strong class="float-right text-emerald-700">{{ number_format($r['unit_cost'], 2, ',', ' ') }}</strong></div>
                <div>Prix suggéré ({{ $r['margin_percent'] }}%) <strong class="float-right">{{ number_format($r['suggested_price'], 2, ',', ' ') }}</strong></div>
            </dl>

            @if(isset($r['break_even_units']))
                <div class="rounded-lg bg-stone-50 border p-4 text-sm space-y-1">
                    <p class="font-semibold">Seuil de rentabilité</p>
                    <p>Coûts fixes : <strong>{{ number_format($r['fixed_costs'] ?? 0, 2, ',', ' ') }}</strong></p>
                    <p>Coût variable / unité : <strong>{{ number_format($r['variable_unit_cost'] ?? 0, 2, ',', ' ') }}</strong></p>
                    <p>Prix de vente : <strong>{{ number_format($r['selling_price'] ?? 0, 2, ',', ' ') }}</strong></p>
                    <p>Marge de contribution : <strong>{{ number_format($r['contribution_margin'] ?? 0, 2, ',', ' ') }}</strong></p>
                    @if($r['break_even_units'] !== null)
                        <p class="text-emerald-800">Seuil : <strong>{{ $r['break_even_units'] }}</strong> unité(s) (CA ≈ {{ number_format($r['break_even_revenue'] ?? 0, 2, ',', ' ') }})</p>
                    @else
                        <p class="text-amber-800">Marge de contribution ≤ 0 — impossible d’atteindre le seuil à ce prix.</p>
                    @endif
                </div>
            @endif

            @if(($r['market_price'] ?? null) !== null)
                <div class="rounded-lg bg-sky-50 border border-sky-100 p-4 text-sm space-y-1">
                    <p class="font-semibold">Comparaison prix marché</p>
                    <p>Prix marché : <strong>{{ number_format($r['market_price'], 2, ',', ' ') }}</strong></p>
                    <p>Écart vs coût unitaire : <strong class="{{ ($r['market_delta_vs_cost'] ?? 0) >= 0 ? 'text-emerald-700' : 'text-red-600' }}">{{ number_format($r['market_delta_vs_cost'], 2, ',', ' ') }}</strong></p>
                    <p>Écart vs prix suggéré : <strong class="{{ ($r['market_delta_vs_suggested'] ?? 0) >= 0 ? 'text-emerald-700' : 'text-red-600' }}">{{ number_format($r['market_delta_vs_suggested'], 2, ',', ' ') }}</strong></p>
                </div>
            @endif

            <div class="pt-2">
                <canvas id="costChart" height="160"></canvas>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('calculator.pdf', $session) }}" class="rounded-lg bg-slate-800 text-white px-4 py-2 text-sm">PDF</a>
                <a href="{{ route('calculator.excel', $session) }}" class="rounded-lg bg-emerald-600 text-white px-4 py-2 text-sm">Excel</a>
                <a href="{{ route('calculator.index') }}" class="text-sm text-slate-600 self-center">Nouveau calcul</a>
            </div>
        </div>
    </div>

    @push('head')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @endpush
    @push('scripts')
        <script>
            (function () {
                var breakdown = @json($r['chart_breakdown'] ?? ['labels' => [], 'values' => []]);
                var ctx = document.getElementById('costChart');
                if (!ctx || typeof Chart === 'undefined') return;
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: breakdown.labels || [],
                        datasets: [{
                            data: breakdown.values || [],
                            backgroundColor: ['#059669', '#0ea5e9', '#f59e0b', '#8b5cf6']
                        }]
                    },
                    options: {
                        plugins: {
                            legend: { position: 'bottom' },
                            title: { display: true, text: 'Répartition des coûts' }
                        }
                    }
                });
            })();
        </script>
    @endpush
</x-app-layout>
