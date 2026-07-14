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
        Schema::table('events', function (Blueprint $table) {
            $table->string('organizer_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->date('registration_start')->nullable();
            $table->date('registration_end')->nullable();
            $table->integer('max_seats')->nullable();
            $table->string('banner_image_path')->nullable();
            $table->string('agenda_pdf_path')->nullable();
            $table->foreignId('assigned_admin_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['assigned_admin_id']);
            $table->dropColumn([
                'organizer_name', 'contact_number', 'email', 'start_time', 'end_time',
                'registration_start', 'registration_end', 'max_seats', 
                'banner_image_path', 'agenda_pdf_path', 'assigned_admin_id'
            ]);
        });
    }
};
