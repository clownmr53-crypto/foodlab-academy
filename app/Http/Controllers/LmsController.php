<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use App\Models\Progress;
use App\Services\CertificateService;
use App\Services\VideoEmbedService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LmsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $modules = Module::query()->where('is_published', true)->with(['lessons' => fn ($q) => $q->where('is_published', true)])->orderBy('order')->get();
        $completed = Progress::query()->where('user_id', $user->id)->where('completed', true)->pluck('lesson_id');

        return view('lms.index', compact('modules', 'user', 'completed'));
    }

    public function showModule(Request $request, Module $module): View|RedirectResponse
    {
        $user = $request->user();
        if (! $user->canAccessModule($module)) {
            return redirect()->route('payments.plans')
                ->with('error', 'Le module « '.$module->title.' » est réservé au plan Premium.');
        }

        $module->load(['lessons' => fn ($q) => $q->where('is_published', true)->orderBy('order')]);
        $completed = Progress::query()->where('user_id', $user->id)->where('completed', true)->pluck('lesson_id');

        return view('lms.module', compact('module', 'user', 'completed'));
    }

    public function showLesson(Request $request, Module $module, Lesson $lesson, VideoEmbedService $video): View|RedirectResponse
    {
        abort_unless($lesson->module_id === $module->id, 404);
        $user = $request->user();

        if (! $user->canAccessModule($module)) {
            return redirect()->route('payments.plans')
                ->with('error', 'Cette leçon est réservée au plan Premium.');
        }

        $embed = $video->iframeHtml($lesson->video_provider ?: config('foodlab.video.default_provider'), $lesson->video_id);
        $progress = Progress::query()->where('user_id', $user->id)->where('lesson_id', $lesson->id)->first();

        return view('lms.lesson', compact('module', 'lesson', 'embed', 'progress', 'user'));
    }

    public function completeLesson(Request $request, Module $module, Lesson $lesson, CertificateService $certificates): RedirectResponse
    {
        abort_unless($lesson->module_id === $module->id, 404);
        $user = $request->user();
        abort_unless($user->canAccessModule($module), 403);

        Progress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['completed' => true, 'completed_at' => now(), 'percent' => 100]
        );

        $certificates->issueIfEligible($user);

        return back()->with('status', 'Leçon marquée comme terminée.');
    }
}
