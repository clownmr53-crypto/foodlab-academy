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

    /**
     * Modules 1–3 (Starter path) completed.
     */
    public function hasCompletedStarterPath(User $user): bool
    {
        if (! $user->hasPlan('starter') && ! $user->isAdmin()) {
            return false;
        }

        $starterOrders = config('foodlab.plans.starter.modules_access', [1, 2, 3]);

        $lessonIds = Lesson::query()
            ->whereHas('module', function ($q) use ($starterOrders) {
                $q->where('is_published', true)
                    ->whereIn('order', $starterOrders)
                    ->where('is_premium_only', false);
            })
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
        $this->issueStarterIfEligible($user);

        return $this->issuePremiumIfEligible($user);
    }

    public function issuePremiumIfEligible(User $user): ?Certificate
    {
        $existing = $user->premiumCertificate;
        if ($existing) {
            return $existing;
        }

        if (! $this->hasCompletedPremiumPath($user)) {
            return null;
        }

        return $this->createCertificate(
            $user,
            Certificate::TYPE_PREMIUM,
            config('foodlab.certificate.title'),
            'pour avoir complété le parcours Premium FoodLab Academy.'
        );
    }

    public function issueStarterIfEligible(User $user): ?Certificate
    {
        $existing = $user->starterCertificate;
        if ($existing) {
            return $existing;
        }

        if (! $this->hasCompletedStarterPath($user)) {
            return null;
        }

        return $this->createCertificate(
            $user,
            Certificate::TYPE_STARTER,
            config('foodlab.certificate.starter_title', 'Certificat de participation Starter'),
            'pour avoir complété les modules 1 à 3 (parcours Starter).'
        );
    }

    protected function createCertificate(User $user, string $type, string $title, string $subtitle): Certificate
    {
        $code = strtoupper(Str::random(4).'-'.Str::random(4).'-'.Str::random(4));
        $verifyUrl = url('/verify-certificate?code='.$code);

        $certificate = Certificate::create([
            'user_id' => $user->id,
            'type' => $type,
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
            'title' => $title,
            'subtitle' => $subtitle,
        ]);

        $path = 'certificates/'.$type.'-'.$code.'.pdf';
        Storage::disk('local')->put($path, $pdf->output());
        $certificate->update(['pdf_path' => $path]);

        return $certificate->fresh();
    }

    public function findByCode(string $code): ?Certificate
    {
        return Certificate::query()->with('user')->where('code', $code)->first();
    }
}
