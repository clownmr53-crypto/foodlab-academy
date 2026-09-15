<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoachingRequestController extends Controller
{
    public function index(): View
    {
        return view('admin.coaching.index', [
            'requests' => CoachingRequest::with('user')->latest()->paginate(30),
        ]);
    }

    public function updateStatus(Request $request, CoachingRequest $coaching): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,contacted,done,cancelled'],
        ]);
        $coaching->update($data);

        return back()->with('status', 'Statut mis à jour.');
    }

    public function destroy(CoachingRequest $coaching): RedirectResponse
    {
        $coaching->delete();

        return back()->with('status', 'Demande supprimée.');
    }
}
