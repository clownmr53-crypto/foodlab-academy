<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ModuleController extends Controller
{
    public function index(): View
    {
        $modules = Module::query()->withCount('lessons')->orderBy('order')->get();

        return view('admin.modules.index', compact('modules'));
    }

    public function create(): View
    {
        return view('admin.modules.form', ['module' => new Module]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        Module::create($data);

        return redirect()->route('admin.modules.index')->with('status', 'Module créé.');
    }

    public function edit(Module $module): View
    {
        return view('admin.modules.form', compact('module'));
    }

    public function update(Request $request, Module $module): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $module->update($data);

        return redirect()->route('admin.modules.index')->with('status', 'Module mis à jour.');
    }

    public function destroy(Module $module): RedirectResponse
    {
        $module->delete();

        return back()->with('status', 'Module supprimé.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deliverable' => ['nullable', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:1'],
            'is_premium_only' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
        ]);
        $data['is_premium_only'] = $request->boolean('is_premium_only');
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
