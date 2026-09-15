<?php

namespace Tests\Unit;

use App\Services\CostCalculatorService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CostCalculatorServiceTest extends TestCase
{
    #[Test]
    public function it_calculates_unit_cost_and_suggested_price(): void
    {
        $service = new CostCalculatorService;
        $result = $service->calculate([
            'product_name' => 'Attiéké bol',
            'ingredients' => [
                ['name' => 'Manioc', 'quantity' => 2, 'unit_cost' => 500],
                ['name' => 'Huile', 'quantity' => 0.1, 'unit_cost' => 2000],
            ],
            'labor_hours' => 1,
            'labor_rate' => 1000,
            'overhead' => 200,
            'packaging' => 100,
            'yield_units' => 10,
            'margin_percent' => 50,
        ]);

        $this->assertSame(1200.0, $result['ingredients_total']);
        $this->assertSame(1000.0, $result['labor_total']);
        $this->assertSame(2500.0, $result['total_cost']);
        $this->assertSame(250.0, $result['unit_cost']);
        $this->assertSame(375.0, $result['suggested_price']);
        $this->assertSame(125.0, $result['unit_margin']);
    }

    #[Test]
    public function it_supports_margin_on_price_and_fixed_amount(): void
    {
        $service = new CostCalculatorService;
        $base = [
            'ingredients' => [['name' => 'X', 'quantity' => 1, 'unit_cost' => 1000]],
            'labor_hours' => 0,
            'labor_rate' => 0,
            'overhead' => 0,
            'packaging' => 0,
            'yield_units' => 1,
        ];

        $onPrice = $service->calculate($base + [
            'margin_mode' => 'margin_on_price',
            'margin_value' => 20,
        ]);
        $this->assertSame(1250.0, $onPrice['suggested_price']);

        $fixed = $service->calculate($base + [
            'margin_mode' => 'fixed_amount',
            'margin_value' => 300,
        ]);
        $this->assertSame(1300.0, $fixed['suggested_price']);
    }

    #[Test]
    public function it_applies_yield_loss_to_ingredients(): void
    {
        $service = new CostCalculatorService;
        $result = $service->calculate([
            'ingredients' => [['name' => 'Farine', 'quantity' => 1, 'unit_cost' => 1000]],
            'labor_hours' => 0,
            'labor_rate' => 0,
            'overhead' => 0,
            'packaging' => 0,
            'yield_units' => 1,
            'apply_yield_loss' => true,
            'yield_loss_percent' => 20,
            'margin_mode' => 'markup_on_cost',
            'margin_value' => 0,
        ]);

        $this->assertSame(1250.0, $result['ingredients_total']);
        $this->assertSame(1250.0, $result['unit_cost']);
    }
}
