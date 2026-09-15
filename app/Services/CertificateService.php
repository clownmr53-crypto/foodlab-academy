<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Progress;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificateService
{
    public function hasCompletedPremiumPath(User $user): bool
    {
        if (! $user->isPremium() && ! $user->isAdmin()) {
            return false;
        }

        $lessonIds = Lesson::query()
            ->whereHas('module', fn ($q) => $q->where('is_published', true))
            ->where('is_published', true)
            ->pluck('id');

        if ($lessonIds->isEmpty()) {
            return false;
        }

        $completed = Progress::query()
            ->where('user_id', $user->id)
            ->where('completed', true)
            ->whereIn('lesson_id', $lessonIds)
            ->count();

        return $completed >= $lessonIds->count();
    }

    public function issueIfEligible(User $user): ?Certificate
    {
        if ($user->certificate) {
            return $user->certificate;
        }

        if (! $this->hasCompletedPremiumPath($user)) {
            return null;
        }

        $code = strtoupper(Str::random(4).'-'.Str::random(4).'-'.Str::random(4));
        $verifyUrl = url('/verify-certificate?code='.$code);

        $certificate = Certificate::create([
            'user_id' => $user->id,
            'code' => $code,
            'qr_payload' => $verifyUrl,
            'issued_at' => now(),
        ]);

        $qrSvg = QrCode::format('svg')->size(180)->generate($verifyUrl);
        $pdf = Pdf::loadView('certificates.pdf', [
            'certificate' => $certificate,
            'user' => $user,
            'qrSvg' => $qrSvg,
            'issuer' => config('foodlab.certificate.issuer'),
            'title' => config('foodlab.certificate.title'),
        ]);

        $path = 'certificates/'.$code.'.pdf';
        Storage::disk('local')->put($path, $pdf->output());
        $certificate->update(['pdf_path' => $path]);

        return $certificate->fresh();
    }

    public function findByCode(string $code): ?Certificate
    {
        return Certificate::query()->with('user')->where('code', $code)->first();
    }
}
