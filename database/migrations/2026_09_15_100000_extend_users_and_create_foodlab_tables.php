<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('country')->nullable()->after('password');
            $table->string('sector')->nullable()->after('country');
            $table->string('level')->nullable()->after('sector');
            $table->string('role')->default('student')->after('level');
            $table->string('plan')->nullable()->after('role');
            $table->timestamp('plan_activated_at')->nullable()->after('plan');
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('order')->default(1);
            $table->boolean('is_premium_only')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('content')->nullable();
            $table->unsignedInteger('order')->default(1);
            $table->string('video_provider')->nullable();
            $table->string('video_id')->nullable();
            $table->unsignedInteger('duration')->nullable();
            $table->boolean('is_premium_only')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->unique(['module_id', 'slug']);
        });

        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->unsignedTinyInteger('percent')->default(0);
            $table->timestamps();
            $table->unique(['user_id', 'lesson_id']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider');
            $table->string('plan');
            $table->unsignedInteger('amount');
            $table->string('currency', 10)->default('XOF');
            $table->string('status')->default('pending');
            $table->string('external_id')->nullable();
            $table->json('payload')->nullable();
            $table->string('receipt_number')->nullable()->unique();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('calculator_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->json('inputs');
            $table->json('results');
            $table->timestamps();
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('qr_payload');
            $table->string('pdf_path')->nullable();
            $table->timestamp('issued_at');
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->string('author_role')->nullable();
            $table->string('country')->nullable();
            $table->text('content');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('legal_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->longText('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_pages');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('calculator_sessions');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('progress');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('modules');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['country', 'sector', 'level', 'role', 'plan', 'plan_activated_at']);
        });
    }
};
