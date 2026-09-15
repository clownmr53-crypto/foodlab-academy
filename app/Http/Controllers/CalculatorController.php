<?php

namespace App\Http\Controllers;

use App\Exports\CalculatorSessionExport;
use App\Models\CalculatorSession;
use App\Services\CostCalculatorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CalculatorController extends Controller
{
    public function index(Request $request): View
    {
        $sessions = CalculatorSession::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->take(20)
            ->get();

        return view('calculator.index', compact('sessions'));
    }

    public function store(Request $request, CostCalculatorService $calculator): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'product_name' => ['required', 'string', 'max:255'],
            'ingredients' => ['required', 'array', 'min:1'],
            'ingredients.*.name' => ['nullable', 'string', 'max:255'],
            'ingredients.*.quantity' => ['required', 'numeric', 'min:0'],
            'ingredients.*.unit_cost' => ['required', 'numeric', 'min:0'],
            'labor_hours' => ['nullable', 'numeric', 'min:0'],
            'labor_rate' => ['nullable', 'numeric', 'min:0'],
            'overhead' => ['nullable', 'numeric', 'min:0'],
            'packaging' => ['nullable', 'numeric', 'min:0'],
            'yield_units' => ['required', 'numeric', 'min:1'],
            'margin_percent' => ['nullable', 'numeric', 'min:0', 'max:500'],
            'market_price' => ['nullable', 'numeric', 'min:0'],
            'fixed_costs' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $results = $calculator->calculate($data);

        $session = CalculatorSession::create([
            'user_id' => $request->user()->id,
            'title' => $data['title'] ?: $data['product_name'],
            'inputs' => $data,
            'results' => $results,
        ]);

        return redirect()->route('calculator.show', $session)->with('status', 'Calcul enregistré.');
    }

    public function show(Request $request, CalculatorSession $calculator): View
    {
        abort_unless($calculator->user_id === $request->user()->id || $request->user()->isAdmin(), 403);

        return view('calculator.show', ['session' => $calculator]);
    }

    public function pdf(Request $request, CalculatorSession $calculator)
    {
        abort_unless($calculator->user_id === $request->user()->id || $request->user()->isAdmin(), 403);
        $pdf = Pdf::loadView('calculator.pdf', ['session' => $calculator]);

        return $pdf->download('cout-de-revient-'.$calculator->id.'.pdf');
    }

    public function excel(Request $request, CalculatorSession $calculator): BinaryFileResponse
    {
        abort_unless($calculator->user_id === $request->user()->id || $request->user()->isAdmin(), 403);

        return Excel::download(new CalculatorSessionExport($calculator), 'cout-de-revient-'.$calculator->id.'.xlsx');
    }
}
