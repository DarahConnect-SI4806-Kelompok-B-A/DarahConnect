<?php

use App\Enums\BloodRequestStatus;
use App\Enums\UrgencyLevel;
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
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_code', 32)->unique();

            $table->foreignId('requester_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('facility_id')
                ->constrained('facilities')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Patient & Clinical Data
            $table->string('patient_name', 120);
            $table->unsignedTinyInteger('patient_age')->nullable();
            $table->enum('patient_gender', ['L', 'P'])->nullable();
            $table->string('patient_relationship', 60)->default('Keluarga Pasien');
            $table->string('medical_record_number', 50)->nullable();
            $table->string('ward_room', 100)->nullable();
            $table->string('clinical_diagnosis', 255)->nullable();
            $table->decimal('hemoglobin_level', 4, 1)->nullable();

            // Transfusion Specification
            $table->enum('blood_type', ['A', 'B', 'AB', 'O']);
            $table->enum('rhesus', ['+', '-']);
            $table->enum('component_type', ['WB', 'PRC', 'TC', 'FFP'])->default('PRC');
            $table->unsignedTinyInteger('bags_needed')->default(1);
            $table->unsignedTinyInteger('bags_fulfilled')->default(0);
            $table->string('urgency_level', 20)->default(UrgencyLevel::URGENT->value);
            $table->dateTime('needed_at')->nullable();
            $table->text('additional_notes')->nullable();

            // Doctor Referral Verification Document
            $table->string('doctor_name', 120)->nullable();
            $table->string('referral_letter_path', 255)->nullable();
            $table->string('referral_letter_original_name', 255)->nullable();
            $table->string('referral_letter_mime', 100)->nullable();
            $table->unsignedInteger('referral_letter_size')->nullable();

            // Lifecycle & Verification Tracking
            $table->string('status', 32)->default(BloodRequestStatus::DRAFT->value);
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Cancellation Audit
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();

            $table->index(['requester_id', 'status']);
            $table->index(['status', 'urgency_level']);
            $table->index(['blood_type', 'rhesus', 'component_type']);
            $table->index(['facility_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
