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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('form_id')->constrained('forms')->cascadeOnDelete();
            $table->string('applicant_name');
            $table->string('applicant_email')->index();
            $table->json('data');
            $table->string('status')->default('submitted');
            $table->timestamps();

            $table->index(['event_id', 'applicant_email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
