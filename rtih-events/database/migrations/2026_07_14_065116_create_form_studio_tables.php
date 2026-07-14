<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fs_forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('draft'); // draft, published, archived
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fs_form_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('fs_forms')->cascadeOnDelete();
            $table->integer('version_number');
            $table->boolean('is_published')->default(false);
            $table->json('schema')->nullable(); // Denormalized snapshot for fast rendering
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('fs_form_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id')->constrained('fs_form_versions')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('fs_form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('fs_form_sections')->cascadeOnDelete();
            $table->string('type'); // text, textarea, select, radio, checkbox, date, file
            $table->string('label');
            $table->string('name'); // unique identifier for the field in the form
            $table->text('help_text')->nullable();
            $table->boolean('is_required')->default(false);
            $table->json('validation_rules')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('fs_form_field_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_id')->constrained('fs_form_fields')->cascadeOnDelete();
            $table->string('label');
            $table->string('value');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('fs_form_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_id')->constrained('fs_form_fields')->cascadeOnDelete(); // the field that is conditionally shown/hidden
            $table->string('action'); // show, hide
            $table->string('operator'); // equals, not_equals, contains, gt, lt
            $table->foreignId('target_field_id')->constrained('fs_form_fields')->cascadeOnDelete(); // the field being evaluated
            $table->string('target_value');
            $table->timestamps();
        });

        Schema::create('fs_form_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_version_id')->constrained('fs_form_versions')->cascadeOnDelete();
            $table->nullableMorphs('respondent'); // user_id or visitor_id etc.
            $table->string('status')->default('partial'); // partial, complete
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fs_form_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('fs_form_responses')->cascadeOnDelete();
            $table->foreignId('field_id')->constrained('fs_form_fields')->cascadeOnDelete();
            $table->json('value'); // JSON to support multiple choice arrays, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fs_form_answers');
        Schema::dropIfExists('fs_form_responses');
        Schema::dropIfExists('fs_form_conditions');
        Schema::dropIfExists('fs_form_field_options');
        Schema::dropIfExists('fs_form_fields');
        Schema::dropIfExists('fs_form_sections');
        Schema::dropIfExists('fs_form_versions');
        Schema::dropIfExists('fs_forms');
    }
};
