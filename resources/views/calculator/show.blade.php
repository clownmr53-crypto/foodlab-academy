<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">{{ $session->title }}</h2></x-slot>
    <div class="max-w-3xl mx-auto py-8 px-4">
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
            <div class="flex gap-3 pt-2">
                <a href="{{ route('calculator.pdf', $session) }}" class="rounded-lg bg-slate-800 text-white px-4 py-2 text-sm">PDF</a>
                <a href="{{ route('calculator.excel', $session) }}" class="rounded-lg bg-emerald-600 text-white px-4 py-2 text-sm">Excel</a>
                <a href="{{ route('calculator.index') }}" class="text-sm text-slate-600 self-center">Nouveau calcul</a>
            </div>
        </div>
    </div>
</x-app-layout>
