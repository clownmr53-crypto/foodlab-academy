<?php

namespace App\Http\Controllers;

use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificateController extends Controller
{
    public function mine(Request $request, CertificateService $service): View
    {
        $user = $request->user();
        $certificate = $service->issueIfEligible($user) ?? $user->certificate;

        return view('certificates.mine', compact('certificate', 'user'));
    }

    public function download(Request $request): StreamedResponse
    {
        $certificate = $request->user()->certificate;
        abort_unless($certificate && $certificate->pdf_path, 404);

        return Storage::disk('local')->download($certificate->pdf_path, 'certificat-foodlab.pdf');
    }

    public function verify(Request $request, CertificateService $service): View
    {
        $code = trim((string) $request->query('code', ''));
        $certificate = $code !== '' ? $service->findByCode($code) : null;

        return view('certificates.verify', compact('code', 'certificate'));
    }
}
