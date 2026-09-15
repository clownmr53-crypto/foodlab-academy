<?php

namespace Tests\Unit;

use App\Models\Lesson;
use App\Models\Module;
use App\Models\Progress;
use App\Models\User;
use App\Services\CertificateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CertificateServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_issues_certificate_when_premium_path_complete(): void
    {
        $user = User::factory()->premium()->create();
        $module = Module::create([
            'title' => 'Mod',
            'slug' => 'mod',
            'order' => 1,
            'is_premium_only' => false,
            'is_published' => true,
        ]);
        $lesson = Lesson::create([
            'module_id' => $module->id,
            'title' => 'L1',
            'slug' => 'l1',
            'order' => 1,
            'is_published' => true,
        ]);
        Progress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'completed' => true,
            'completed_at' => now(),
            'percent' => 100,
        ]);

        $cert = app(CertificateService::class)->issueIfEligible($user);

        $this->assertNotNull($cert);
        $this->assertNotEmpty($cert->code);
        $this->assertNotNull($cert->pdf_path);
    }

    #[Test]
    public function it_does_not_issue_for_incomplete_path(): void
    {
        $user = User::factory()->premium()->create();
        Module::create([
            'title' => 'Mod',
            'slug' => 'mod2',
            'order' => 1,
            'is_published' => true,
        ]);
        Lesson::create([
            'module_id' => 1,
            'title' => 'L1',
            'slug' => 'l1b',
            'order' => 1,
            'is_published' => true,
        ]);

        $this->assertNull(app(CertificateService::class)->issueIfEligible($user));
    }
}
