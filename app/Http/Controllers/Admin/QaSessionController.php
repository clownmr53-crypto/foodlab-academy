<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QaSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QaSessionController extends Controller
{
    public function index(): View
    {
        return view('admin.qa.index', [
            'sessions' => QaSession::orderByDesc('session_at')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.qa.form', ['session' => new QaSession]);
    }

    public function store(Request $request): RedirectResponse
    {
        QaSession::create($this->validated($request));

        return redirect()->route('admin.qa.index')->with('status', 'Session Q&R créée.');
    }

    public function edit(QaSession $qa): View
    {
        return view('admin.qa.form', ['session' => $qa]);
    }

    public function update(Request $request, QaSession $qa): RedirectResponse
    {
        $qa->update($this->validated($request));

        return redirect()->route('admin.qa.index')->with('status', 'Session Q&R mise à jour.');
    }

    public function destroy(QaSession $qa): RedirectResponse
    {
        $qa->delete();

        return back()->with('status', 'Session supprimée.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'session_at' => ['required', 'date'],
            'visio_link' => ['nullable', 'url', 'max:500'],
            'replay_url' => ['nullable', 'url', 'max:500'],
            'is_published' => ['sometimes', 'boolean'],
        ]);
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
