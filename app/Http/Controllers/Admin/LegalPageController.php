<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalPageController extends Controller
{
    public function index(): View
    {
        return view('admin.legal.index', ['pages' => LegalPage::orderBy('slug')->get()]);
    }

    public function edit(LegalPage $legal): View
    {
        return view('admin.legal.form', ['page' => $legal]);
    }

    public function update(Request $request, LegalPage $legal): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);
        $legal->update($data);

        return redirect()->route('admin.legal.index')->with('status', 'Page légale mise à jour.');
    }
}
