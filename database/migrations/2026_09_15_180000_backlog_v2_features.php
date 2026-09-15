<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('type')->default('premium')->after('user_id');
            $table->unique(['user_id', 'type']);
        });

        Schema::create('forum_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('forum_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
        });

        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_thread_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('coaching_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message')->nullable();
            $table->string('preferred_slot')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('qa_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('session_at');
            $table->string('visio_link')->nullable();
            $table->string('replay_url')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('resource_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_templates');
        Schema::dropIfExists('qa_sessions');
        Schema::dropIfExists('coaching_requests');
        Schema::dropIfExists('forum_posts');
        Schema::dropIfExists('forum_threads');
        Schema::dropIfExists('forum_categories');

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'type']);
            $table->dropColumn('type');
        });
    }
};
