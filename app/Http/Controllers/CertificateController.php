<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
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
        $service->issueIfEligible($user);

        $certificates = $user->certificates()->orderByDesc('issued_at')->get();

        return view('certificates.mine', compact('certificates', 'user'));
    }

    public function download(Request $request): StreamedResponse
    {
        $type = $request->query('type', Certificate::TYPE_PREMIUM);
        abort_unless(in_array($type, [Certificate::TYPE_PREMIUM, Certificate::TYPE_STARTER], true), 404);

        $certificate = $request->user()->certificates()->where('type', $type)->first();
        abort_unless($certificate && $certificate->pdf_path, 404);

        $filename = $type === Certificate::TYPE_STARTER
            ? 'certificat-participation-starter.pdf'
            : 'certificat-premium-foodlab.pdf';

        return Storage::disk('local')->download($certificate->pdf_path, $filename);
    }

    public function verify(Request $request, CertificateService $service): View
    {
        $code = trim((string) $request->query('code', ''));
        $certificate = $code !== '' ? $service->findByCode($code) : null;

        return view('certificates.verify', compact('code', 'certificate'));
    }
}
