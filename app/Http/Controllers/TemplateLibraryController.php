<?php

namespace App\Http\Controllers;

use App\Models\ResourceTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class TemplateLibraryController extends Controller
{
    public function index(): View
    {
        $templates = ResourceTemplate::query()
            ->where('is_published', true)
            ->orderBy('order')
            ->orderBy('title')
            ->get();

        return view('templates.index', compact('templates'));
    }

    public function download(ResourceTemplate $template): StreamedResponse|RedirectResponse
    {
        abort_unless($template->is_published, 404);

        if (filled($template->external_url)) {
            return redirect()->away($template->external_url);
        }

        abort_unless(filled($template->file_path) && Storage::disk('public')->exists($template->file_path), 404);

        return Storage::disk('public')->download(
            $template->file_path,
            basename($template->file_path)
        );
    }
}
