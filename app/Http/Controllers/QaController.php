<?php

namespace App\Http\Controllers;

use App\Models\QaSession;
use Illuminate\View\View;

class QaController extends Controller
{
    public function index(): View
    {
        $upcoming = QaSession::query()
            ->where('is_published', true)
            ->where('session_at', '>=', now()->subDay())
            ->orderBy('session_at')
            ->get();

        $past = QaSession::query()
            ->where('is_published', true)
            ->where('session_at', '<', now()->subDay())
            ->orderByDesc('session_at')
            ->limit(20)
            ->get();

        return view('qa.index', compact('upcoming', 'past'));
    }
}
