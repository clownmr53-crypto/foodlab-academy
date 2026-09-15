<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function index(Module $module): View
    {
        $module->load(['lessons' => fn ($q) => $q->orderBy('order')]);

        return view('admin.lessons.index', compact('module'));
    }

    public function create(Module $module): View
    {
        return view('admin.lessons.form', ['module' => $module, 'lesson' => new Lesson]);
    }

    public function store(Request $request, Module $module): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['module_id'] = $module->id;
        $data['video_provider'] = $data['video_provider'] ?: config('foodlab.video.default_provider');
        Lesson::create($data);

        return redirect()->route('admin.modules.lessons.index', $module)->with('status', 'Leçon créée.');
    }

    public function edit(Module $module, Lesson $lesson): View
    {
        abort_unless($lesson->module_id === $module->id, 404);

        return view('admin.lessons.form', compact('module', 'lesson'));
    }

    public function update(Request $request, Module $module, Lesson $lesson): RedirectResponse
    {
        abort_unless($lesson->module_id === $module->id, 404);
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $lesson->update($data);

        return redirect()->route('admin.modules.lessons.index', $module)->with('status', 'Leçon mise à jour.');
    }

    public function destroy(Module $module, Lesson $lesson): RedirectResponse
    {
        abort_unless($lesson->module_id === $module->id, 404);
        $lesson->delete();

        return back()->with('status', 'Leçon supprimée.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:1'],
            'video_provider' => ['nullable', 'in:bunny,vimeo'],
            'video_id' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'is_premium_only' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
        ]);
        $data['is_premium_only'] = $request->boolean('is_premium_only');
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
