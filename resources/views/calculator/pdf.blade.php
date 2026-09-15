<!DOCTYPE html>
<html lang="fr"><head><meta charset="utf-8"><title>Coût de revient</title>
<style>body{font-family: DejaVu Sans, sans-serif;font-size:12px;color:#222} h1{font-size:18px} table{width:100%;border-collapse:collapse;margin-top:12px} th,td{border:1px solid #ccc;padding:6px;text-align:left}</style>
</head><body>
@php $r = $session->results; @endphp
<h1>FoodLab — Coût de revient</h1>
<p><strong>{{ $r['product_name'] ?? $session->title }}</strong> — {{ $session->created_at->format('d/m/Y') }}</p>
<table>
<thead><tr><th>Ingrédient</th><th>Quantité</th><th>Coût unitaire</th><th>Total</th></tr></thead>
<tbody>
@foreach($r['ingredient_lines'] ?? [] as $line)
<tr><td>{{ $line['name'] }}</td><td>{{ $line['quantity'] }}</td><td>{{ number_format($line['unit_cost'], 2, ',', ' ') }}</td><td>{{ number_format($line['total'], 2, ',', ' ') }}</td></tr>
@endforeach
</tbody>
</table>
<p>Main d'œuvre : {{ number_format($r['labor_total'], 2, ',', ' ') }}</p>
<p>Frais généraux : {{ number_format($r['overhead'], 2, ',', ' ') }}</p>
<p>Emballage : {{ number_format($r['packaging'], 2, ',', ' ') }}</p>
<p><strong>Coût total : {{ number_format($r['total_cost'], 2, ',', ' ') }}</strong></p>
<p><strong>Coût unitaire : {{ number_format($r['unit_cost'], 2, ',', ' ') }}</strong> ({{ $r['yield_units'] }} portions)</p>
<p>Prix suggéré (marge {{ $r['margin_percent'] }}%) : {{ number_format($r['suggested_price'], 2, ',', ' ') }}</p>
</body></html>
