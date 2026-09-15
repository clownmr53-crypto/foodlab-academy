<?php

namespace App\Http\Controllers;

use App\Models\CoachingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoachingController extends Controller
{
    public function index(Request $request): View
    {
        $calendlyUrl = config('foodlab.calendly_url');

        return view('coaching.index', [
            'calendlyUrl' => $calendlyUrl,
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (filled(config('foodlab.calendly_url'))) {
            return redirect()->route('coaching.index')
                ->with('status', 'Utilisez le widget Calendly pour réserver.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'preferred_slot' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

        CoachingRequest::create([
            ...$data,
            'user_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        return back()->with('status', 'Demande de coaching envoyée. Nous vous recontacterons.');
    }
}
