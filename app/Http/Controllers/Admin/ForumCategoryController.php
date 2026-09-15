<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ForumCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.forum.categories', [
            'categories' => ForumCategory::withCount('threads')->orderBy('order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        ForumCategory::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']).'-'.Str::random(4),
            'description' => $data['description'] ?? null,
            'order' => $data['order'] ?? 0,
            'is_published' => true,
        ]);

        return back()->with('status', 'Catégorie créée.');
    }

    public function destroy(ForumCategory $category): RedirectResponse
    {
        $category->delete();

        return back()->with('status', 'Catégorie supprimée.');
    }
}
