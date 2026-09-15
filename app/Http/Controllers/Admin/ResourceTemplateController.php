<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResourceTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ResourceTemplateController extends Controller
{
    public function index(): View
    {
        return view('admin.templates.index', [
            'templates' => ResourceTemplate::orderBy('order')->orderBy('title')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.templates.form', ['template' => new ResourceTemplate]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('templates', 'public');
        }
        ResourceTemplate::create($data);

        return redirect()->route('admin.templates.index')->with('status', 'Template créé.');
    }

    public function edit(ResourceTemplate $template): View
    {
        return view('admin.templates.form', compact('template'));
    }

    public function update(Request $request, ResourceTemplate $template): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('file')) {
            if ($template->file_path) {
                Storage::disk('public')->delete($template->file_path);
            }
            $data['file_path'] = $request->file('file')->store('templates', 'public');
        }
        $template->update($data);

        return redirect()->route('admin.templates.index')->with('status', 'Template mis à jour.');
    }

    public function destroy(ResourceTemplate $template): RedirectResponse
    {
        if ($template->file_path) {
            Storage::disk('public')->delete($template->file_path);
        }
        $template->delete();

        return back()->with('status', 'Template supprimé.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'external_url' => ['nullable', 'url', 'max:500'],
            'file' => ['nullable', 'file', 'max:10240'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['sometimes', 'boolean'],
        ]);
        $data['is_published'] = $request->boolean('is_published');
        $data['order'] = $data['order'] ?? 0;
        unset($data['file']);

        return $data;
    }
}
