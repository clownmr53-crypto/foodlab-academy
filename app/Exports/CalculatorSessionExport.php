<?php

namespace App\Exports;

use App\Models\CalculatorSession;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CalculatorSessionExport implements FromCollection, WithHeadings
{
    public function __construct(public CalculatorSession $session) {}

    public function collection(): Enumerable
    {
        $rows = collect();
        $results = $this->session->results;
        foreach ($results['ingredient_lines'] ?? [] as $line) {
            $rows->push([
                'Type' => 'Ingrédient',
                'Libellé' => $line['name'] ?? '',
                'Quantité' => $line['quantity'] ?? '',
                'Coût unitaire' => $line['unit_cost'] ?? '',
                'Total' => $line['total'] ?? '',
            ]);
        }

        $rows->push(['Type' => 'Main d\'œuvre', 'Libellé' => '', 'Quantité' => '', 'Coût unitaire' => '', 'Total' => $results['labor_total'] ?? 0]);
        $rows->push(['Type' => 'Frais généraux', 'Libellé' => '', 'Quantité' => '', 'Coût unitaire' => '', 'Total' => $results['overhead'] ?? 0]);
        $rows->push(['Type' => 'Emballage', 'Libellé' => '', 'Quantité' => '', 'Coût unitaire' => '', 'Total' => $results['packaging'] ?? 0]);
        $rows->push(['Type' => 'Coût total', 'Libellé' => '', 'Quantité' => '', 'Coût unitaire' => '', 'Total' => $results['total_cost'] ?? 0]);
        $rows->push(['Type' => 'Coût unitaire', 'Libellé' => '', 'Quantité' => $results['yield_units'] ?? 1, 'Coût unitaire' => '', 'Total' => $results['unit_cost'] ?? 0]);
        $rows->push(['Type' => 'Prix suggéré', 'Libellé' => 'Marge '.$results['margin_percent'].'%', 'Quantité' => '', 'Coût unitaire' => '', 'Total' => $results['suggested_price'] ?? 0]);

        return $rows;
    }

    public function headings(): array
    {
        return ['Type', 'Libellé', 'Quantité', 'Coût unitaire', 'Total'];
    }
}
