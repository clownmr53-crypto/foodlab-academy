<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Progress;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, CertificateService $certificates): View
    {
        $user = $request->user();
        $modules = Module::query()->where('is_published', true)->with('lessons')->orderBy('order')->get();
        $completedLessonIds = Progress::query()
            ->where('user_id', $user->id)
            ->where('completed', true)
            ->pluck('lesson_id');

        $totalLessons = $modules->sum(fn ($m) => $m->lessons->where('is_published', true)->count());
        $done = $completedLessonIds->count();

        if ($user->isPremium() || $user->isAdmin()) {
            $certificates->issueIfEligible($user);
            $user->load('certificate');
        }

        return view('dashboard', [
            'user' => $user,
            'modules' => $modules,
            'completedLessonIds' => $completedLessonIds,
            'progressPercent' => $totalLessons > 0 ? (int) round(($done / $totalLessons) * 100) : 0,
        ]);
    }
}
