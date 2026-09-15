<?php

namespace App\Services;

class CostCalculatorService
{
    /**
     * Calcule le coût de revient, seuil de rentabilité et écart marché.
     *
     * @param  array<string, mixed>  $inputs
     * @return array<string, mixed>
     */
    public function calculate(array $inputs): array
    {
        $ingredients = $inputs['ingredients'] ?? [];
        $ingredientLines = [];
        $ingredientsTotal = 0.0;

        foreach ($ingredients as $line) {
            $qty = (float) ($line['quantity'] ?? 0);
            $unitCost = (float) ($line['unit_cost'] ?? 0);
            $lineTotal = round($qty * $unitCost, 2);
            $ingredientsTotal += $lineTotal;
            $ingredientLines[] = [
                'name' => (string) ($line['name'] ?? 'Ingrédient'),
                'quantity' => $qty,
                'unit_cost' => $unitCost,
                'total' => $lineTotal,
            ];
        }

        $applyYieldLoss = filter_var($inputs['apply_yield_loss'] ?? false, FILTER_VALIDATE_BOOL);
        $yieldLossPercent = max(0.0, min(99.0, (float) ($inputs['yield_loss_percent'] ?? 0)));
        if ($applyYieldLoss && $yieldLossPercent > 0) {
            $factor = 1 / (1 - ($yieldLossPercent / 100));
            $ingredientsTotal = round($ingredientsTotal * $factor, 2);
            foreach ($ingredientLines as &$line) {
                $line['total'] = round($line['total'] * $factor, 2);
            }
            unset($line);
        }

        $laborHours = (float) ($inputs['labor_hours'] ?? 0);
        $laborRate = (float) ($inputs['labor_rate'] ?? 0);
        $laborTotal = round($laborHours * $laborRate, 2);
        $overhead = round((float) ($inputs['overhead'] ?? 0), 2);
        $packaging = round((float) ($inputs['packaging'] ?? 0), 2);
        $yieldUnits = max(1.0, (float) ($inputs['yield_units'] ?? 1));

        $marginMode = (string) ($inputs['margin_mode'] ?? 'markup_on_cost');
        if (! in_array($marginMode, ['markup_on_cost', 'margin_on_price', 'fixed_amount'], true)) {
            $marginMode = 'markup_on_cost';
        }
        $marginValue = (float) ($inputs['margin_value'] ?? ($inputs['margin_percent'] ?? 30));
        $marginPercent = $marginMode === 'fixed_amount' ? 0.0 : $marginValue;

        $totalCost = round($ingredientsTotal + $laborTotal + $overhead + $packaging, 2);
        $unitCost = round($totalCost / $yieldUnits, 2);

        $suggestedPrice = match ($marginMode) {
            'margin_on_price' => $marginValue < 100
                ? round($unitCost / (1 - ($marginValue / 100)), 2)
                : round($unitCost * 2, 2),
            'fixed_amount' => round($unitCost + $marginValue, 2),
            default => round($unitCost * (1 + ($marginValue / 100)), 2),
        };

        $unitMargin = round($suggestedPrice - $unitCost, 2);
        if ($marginMode !== 'fixed_amount' && $suggestedPrice > 0) {
            $marginPercent = $marginMode === 'margin_on_price'
                ? $marginValue
                : round(($unitMargin / $unitCost) * 100, 2);
        } elseif ($suggestedPrice > 0) {
            $marginPercent = round(($unitMargin / $suggestedPrice) * 100, 2);
        }

        $variableTotal = round($ingredientsTotal + $laborTotal + $packaging, 2);
        $variableUnitCost = round($variableTotal / $yieldUnits, 2);

        $fixedCosts = isset($inputs['fixed_costs']) && $inputs['fixed_costs'] !== null && $inputs['fixed_costs'] !== ''
            ? round((float) $inputs['fixed_costs'], 2)
            : $overhead;

        $sellingPrice = isset($inputs['selling_price']) && $inputs['selling_price'] !== null && $inputs['selling_price'] !== ''
            ? round((float) $inputs['selling_price'], 2)
            : $suggestedPrice;

        $contributionMargin = round($sellingPrice - $variableUnitCost, 2);
        $breakEvenUnits = null;
        $breakEvenRevenue = null;
        if ($contributionMargin > 0) {
            $breakEvenUnits = (int) ceil($fixedCosts / $contributionMargin);
            $breakEvenRevenue = round($breakEvenUnits * $sellingPrice, 2);
        }

        $marketPrice = null;
        $marketDeltaVsCost = null;
        $marketDeltaVsSuggested = null;
        if (isset($inputs['market_price']) && $inputs['market_price'] !== null && $inputs['market_price'] !== '') {
            $marketPrice = round((float) $inputs['market_price'], 2);
            $marketDeltaVsCost = round($marketPrice - $unitCost, 2);
            $marketDeltaVsSuggested = round($marketPrice - $suggestedPrice, 2);
        }

        $chartBreakdown = [
            'labels' => ['Ingrédients', 'Main d\'œuvre', 'Frais généraux', 'Emballage'],
            'values' => [
                round($ingredientsTotal, 2),
                $laborTotal,
                $overhead,
                $packaging,
            ],
        ];

        return [
            'product_name' => (string) ($inputs['product_name'] ?? 'Produit'),
            'sector' => (string) ($inputs['sector'] ?? ''),
            'maturity_level' => (string) ($inputs['maturity_level'] ?? ''),
            'currency' => (string) ($inputs['currency'] ?? 'FCFA'),
            'unit_label' => (string) ($inputs['unit_label'] ?? 'unité'),
            'apply_yield_loss' => $applyYieldLoss,
            'yield_loss_percent' => $yieldLossPercent,
            'margin_mode' => $marginMode,
            'margin_value' => $marginValue,
            'ingredient_lines' => $ingredientLines,
            'ingredients_total' => round($ingredientsTotal, 2),
            'labor_total' => $laborTotal,
            'overhead' => $overhead,
            'packaging' => $packaging,
            'total_cost' => $totalCost,
            'yield_units' => $yieldUnits,
            'unit_cost' => $unitCost,
            'margin_percent' => $marginPercent,
            'suggested_price' => $suggestedPrice,
            'unit_margin' => $unitMargin,
            'fixed_costs' => $fixedCosts,
            'variable_unit_cost' => $variableUnitCost,
            'selling_price' => $sellingPrice,
            'contribution_margin' => $contributionMargin,
            'break_even_units' => $breakEvenUnits,
            'break_even_revenue' => $breakEvenRevenue,
            'market_price' => $marketPrice,
            'market_delta_vs_cost' => $marketDeltaVsCost,
            'market_delta_vs_suggested' => $marketDeltaVsSuggested,
            'chart_breakdown' => $chartBreakdown,
        ];
    }
}
